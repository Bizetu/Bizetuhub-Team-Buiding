<?php

namespace App\Services;

use App\Models\CalendarAccessToken;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleCalendarEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoogleCalendarService
{
    private GoogleClient $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google_calendar.client_id'));
        $this->client->setClientSecret(config('services.google_calendar.client_secret'));
        $this->client->setRedirectUri(config('services.google_calendar.redirect_uri'));
        $this->client->setScopes([GoogleCalendar::CALENDAR_EVENTS]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    /**
     * Get the Google OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Exchange authorization code for access token and save to database
     */
    public function handleCallback(string $code, int $userId): void
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new Exception('Error fetching access token: ' . $token['error']);
        }

        CalendarAccessToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'google_access_token' => $token['access_token'],
                'google_refresh_token' => $token['refresh_token'] ?? null,
                'google_token_expires_at' => isset($token['expires_in'])
                    ? Carbon::now()->addSeconds($token['expires_in'])
                    : null,
            ]
        );
    }

    /**
     * Get authenticated Google Calendar service for a user
     */
    private function getCalendarService(int $userId): ?GoogleCalendar
    {
        $tokenRecord = CalendarAccessToken::where('user_id', $userId)->first();

        if (!$tokenRecord || !$tokenRecord->google_access_token) {
            return null;
        }

        // Check if token is expired
        if ($tokenRecord->google_token_expires_at &&
            Carbon::now()->greaterThan($tokenRecord->google_token_expires_at)) {

            if (!$tokenRecord->google_refresh_token) {
                return null; // Need to re-authenticate
            }

            // Refresh the token
            $this->client->setAccessToken([
                'access_token' => $tokenRecord->google_access_token,
                'refresh_token' => $tokenRecord->google_refresh_token,
            ]);

            if ($this->client->isAccessTokenExpired()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($tokenRecord->google_refresh_token);

                if (isset($newToken['error'])) {
                    return null;
                }

                $tokenRecord->update([
                    'google_access_token' => $newToken['access_token'],
                    'google_token_expires_at' => Carbon::now()->addSeconds($newToken['expires_in']),
                ]);
            }
        } else {
            $this->client->setAccessToken($tokenRecord->google_access_token);
        }

        return new GoogleCalendar($this->client);
    }

    /**
     * Check if user has connected their Google Calendar
     */
    public function isConnected(int $userId): bool
    {
        $tokenRecord = CalendarAccessToken::where('user_id', $userId)->first();
        return $tokenRecord && $tokenRecord->google_access_token;
    }

    /**
     * Create a calendar event for a due date
     */
    public function createDueDateEvent(
        int $userId,
        string $courseId,
        string $courseName,
        Carbon $dueDate,
        ?string $courseUrl = null
    ): ?array {
        $service = $this->getCalendarService($userId);

        if (!$service) {
            return null;
        }

        $event = new GoogleCalendarEvent([
            'summary' => "Due: {$courseName}",
            'description' => $courseUrl
                ? "Complete this course by the due date.\n\nCourse link: {$courseUrl}"
                : 'Complete this course by the due date on your eLearning platform.',
            'start' => [
                'date' => $dueDate->format('Y-m-d'),
                'timeZone' => config('app.timezone'),
            ],
            'end' => [
                'date' => $dueDate->addDay()->format('Y-m-d'),
                'timeZone' => config('app.timezone'),
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60], // 1 day before
                    ['method' => 'popup', 'minutes' => 60],       // 1 hour before
                ],
            ],
        ]);

        try {
            $createdEvent = $service->events->insert('primary', $event);

            // Save to database
            CalendarEvent::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'calendar_option' => 'google',
                'event_id' => $createdEvent->getId(),
                'event_link' => $createdEvent->getHtmlLink(),
            ]);

            return [
                'success' => true,
                'event_id' => $createdEvent->getId(),
                'event_link' => $createdEvent->getHtmlLink(),
            ];
        } catch (Exception $e) {
            Log::error('Google Calendar event creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a calendar event
     */
    public function deleteEvent(int $userId, string $eventId): bool
    {
        $service = $this->getCalendarService($userId);

        if (!$service) {
            return false;
        }

        try {
            $service->events->delete('primary', $eventId);

            CalendarEvent::where('user_id', $userId)->where('event_id', $eventId)->delete();

            return true;
        } catch (Exception $e) {
            Log::error('Google Calendar event deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a calendar event
     */
    public function updateEvent(
        int $userId,
        string $eventId,
        array $updates
    ): bool {
        $service = $this->getCalendarService($userId);

        if (!$service) {
            return false;
        }

        try {
            $event = $service->events->get('primary', $eventId);

            if (isset($updates['summary'])) {
                $event->setSummary($updates['summary']);
            }
            if (isset($updates['description'])) {
                $event->setDescription($updates['description']);
            }
            if (isset($updates['start'])) {
                $event->setStart($updates['start']);
            }
            if (isset($updates['end'])) {
                $event->setEnd($updates['end']);
            }

            $service->events->update('primary', $eventId, $event);
            return true;
        } catch (Exception $e) {
            Log::error('Google Calendar event update failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Disconnect Google Calendar for a user
     */
    public function disconnect(int $userId): void
    {
        DB::beginTransaction();

        try {
            CalendarEvent::where('user_id', $userId)->delete();
            CalendarAccessToken::where('user_id', $userId)->delete();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }
}
