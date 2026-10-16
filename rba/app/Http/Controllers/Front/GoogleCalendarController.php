<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleCalendarController extends Controller
{
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Redirect user to Google OAuth consent screen
     */
    public function redirect()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $authUrl = $this->calendarService->getAuthUrl();
        return redirect($authUrl);
    }

    /**
     * Handle the OAuth callback from Google
     */
    public function callback(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $code = $request->get('code');

        if (!$code) {
            return redirect()->route('calendar')
                ->with('error', 'Authorization failed. Please try again.');
        }

        try {
            $this->calendarService->handleCallback($code, Auth::id());

            return redirect()->route('calendar')
                ->with('success', 'Google Calendar connected successfully!');
        } catch (Exception $e) {
            Log::error('Google Calendar OAuth callback error: ' . $e->getMessage());

            return redirect()->route('calendar')
                ->with('error', 'Failed to connect Google Calendar. Please try again.');
        }
    }

    /**
     * Add a due date to Google Calendar
     */
    public function addToCalendar(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'course_id' => 'required|string',
            'course_name' => 'required|string',
            'due_date' => 'required|date',
            'course_url' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Check if calendar is connected
        if (!$this->calendarService->isConnected($userId)) {
            return response()->json([
                'success' => false,
                'error' => 'Google Calendar not connected',
                'auth_url' => route('google.calendar.redirect'),
            ], 403);
        }

        $result = $this->calendarService->createDueDateEvent(
            $userId,
            $validated['course_id'],
            $validated['course_name'],
            \Carbon\Carbon::parse($validated['due_date']),
            $validated['course_url'] ?? null
        );

        if (!$result || !$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to create calendar event',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event added to Google Calendar',
            'event_link' => $result['event_link'],
        ]);
    }

    /**
     * Remove event from Google Calendar
     */
    public function removeFromCalendar(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'event_id' => 'required|string',
        ]);

        $success = $this->calendarService->deleteEvent(
            Auth::id(),
            $validated['event_id']
        );

        if (!$success) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to remove event from calendar',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event removed from Google Calendar',
        ]);
    }

    /**
     * Disconnect Google Calendar
     */
    public function disconnect()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->calendarService->disconnect(Auth::id());

        return redirect()->route('calendar')
            ->with('success', 'Google Calendar disconnected successfully.');
    }

    /**
     * Get connection status
     */
    public function status()
    {
        if (!Auth::check()) {
            return response()->json(['connected' => false]);
        }

        return response()->json([
            'connected' => $this->calendarService->isConnected(Auth::id()),
        ]);
    }
}
