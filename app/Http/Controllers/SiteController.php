<?php

namespace App\Http\Controllers;

use App\Mail\SiteSubmissionReceived;
use App\Models\Booking;
use App\Models\ContactInterest;
use App\Models\Enquiry;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\HomeSlide;
use App\Models\Insight;
use App\Models\Page;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $homePage = Page::where('page_key', 'home')
            ->where('published', true)
            ->with('blocks')
            ->first();

        return view('site.home', [
            'homePage' => $homePage,
            'homeSlides' => HomeSlide::where('published', true)
                ->with(['medias', 'files'])
                ->orderBy('position')
                ->orderBy('id')
                ->get(),
            'services' => Service::where('published', true)
                ->with('medias')
                ->orderByDesc('featured')
                ->orderBy('position')
                ->orderBy('id')
                ->take(3)
                ->get(),
            'events' => Event::where('published', true)
                ->where('starts_at', '>=', now())
                ->with('medias')
                ->orderBy('starts_at')
                ->take(3)
                ->get(),
            'testimonials' => Testimonial::where('published', true)
                ->orderByDesc('featured')
                ->orderByDesc('id')
                ->take(3)
                ->get(),
        ]);
    }

    public function services(): View
    {
        return view('site.services', [
            'services' => Service::where('published', true)
                ->with('medias')
                ->orderByDesc('featured')
                ->orderBy('position')
                ->orderBy('id')
                ->get(),
            'pageContent' => Page::where('page_key', 'services')
                ->where('published', true)
                ->with('blocks')
                ->first(),
        ]);
    }

    public function showService(Service $service): View
    {
        abort_unless($service->published, 404);
        $service->load('medias');

        return view('site.service', [
            'service' => $service,
            'services' => Service::where('published', true)
                ->with('medias')
                ->orderByDesc('featured')
                ->orderBy('position')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function events(): View
    {
        $data = [
            'upcomingEvents' => Event::where('published', true)
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->get(),
            'pastEvents' => Event::where('published', true)
                ->where('starts_at', '<', now())
                ->orderByDesc('starts_at')
                ->get(),
        ];

        return $this->pageView('events', 'site.events', $data);
    }

    public function industries(): View
    {
        return $this->pageView('industries', 'site.industries');
    }

    public function gallery(): View
    {
        $data = [
            'galleryItems' => GalleryItem::where('published', true)->orderBy('position')->orderByDesc('created_at')->paginate(12),
        ];

        return $this->pageView('gallery', 'site.gallery', $data);
    }

    public function packages(): View
    {
        return $this->pageView('packages', 'site.packages');
    }

    public function testimonials(): View
    {
        $data = [
            'testimonials' => Testimonial::where('published', true)->orderByDesc('featured')->orderByDesc('id')->paginate(9),
        ];

        return $this->pageView('testimonials', 'site.testimonials', $data);
    }

    public function faqs(): View
    {
        return $this->pageView('faqs', 'site.faqs');
    }

    public function privacy(): View
    {
        return $this->pageView('privacy', 'site.privacy');
    }

    public function terms(): View
    {
        return $this->pageView('terms', 'site.terms');
    }

    public function showEvent(Event $event): View
    {
        abort_unless($event->published, 404);

        return view('site.event', compact('event'));
    }

    public function insights(): View
    {
        $data = [
            'insights' => Insight::published()->orderByDesc('created_at')->paginate(9),
        ];

        return $this->pageView('insights', 'site.insights', $data);
    }

    public function showInsight(Insight $insight): View
    {
        abort_unless($insight->published, 404);

        return view('site.insight', compact('insight'));
    }

    public function page(string $slug): View
    {
        $page = Page::published()->whereNull('page_key')->forSlug($slug)->firstOrFail();

        return view('site.page', compact('page'));
    }

    public function about(): View
    {
        $page = Page::where('page_key', 'about')
            ->where('published', true)
            ->with('blocks')
            ->first();

        if ($page) {
            // Keep the purpose statements above the Our Story content section,
            // even when the block was added later in the page editor.
            $page->setRelation(
                'blocks',
                $page->blocks->sortBy(fn ($block): int => $block->type === 'mission_vision_belief' ? 0 : 1)->values()
            );
        }

        return view('site.about', ['pageContent' => $page]);
    }

    public function contact(): View
    {
        return $this->pageView('contact', 'site.contact', [
            'contactInterests' => ContactInterest::where('published', true)
                ->orderBy('position')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function book(): View
    {
        $data = [
            'services' => Service::where('published', true)->orderBy('title')->get(),
            'events' => Event::where('published', true)
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->get(),
        ];

        return $this->pageView('book', 'site.book', $data);
    }

    private function pageView(string $pageKey, string $fallbackView, array $data = []): View
    {
        $page = Page::where('page_key', $pageKey)
            ->where('published', true)
            ->with('blocks')
            ->first();

        if ($page !== null && $page->blocks->isNotEmpty() && ! in_array($pageKey, ['about', 'contact', 'book'], true)) {
            return view('site.page', [
                'page' => $page,
                'pageData' => $data,
            ]);
        }

        return view($fallbackView, $data + ['pageContent' => $page]);
    }

    public function storeEnquiry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'interest' => ['required', 'string', 'max:120', Rule::exists('contact_interests', 'title')->where('published', true)],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $enquiry = Enquiry::create($data + [
            'title' => 'Enquiry from '.$data['name'],
            'status' => 'new',
        ]);

        Mail::to('info@bizetuhub.com')->send(new SiteSubmissionReceived('enquiry', $enquiry->id, $data));

        return back()->with('success', 'Thanks — your enquiry has been received.');
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'organisation' => ['required', 'string', 'max:160'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'service_id' => [
                'nullable',
                'required_without:event_id',
                'prohibited_with:event_id',
                'integer',
                Rule::exists('services', 'id')->where('published', true),
            ],
            'event_id' => [
                'nullable',
                'required_without:service_id',
                'prohibited_with:service_id',
                'integer',
                Rule::exists('events', 'id')
                    ->where('published', true)
                    ->where('starts_at', '>=', now()),
            ],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'participants' => ['required', 'integer', 'min:2', 'max:10000'],
            'venue_preference' => ['nullable', 'string', 'max:200'],
            'requirements' => ['nullable', 'string', 'max:3000'],
        ]);

        if ($data['event_id'] ?? null) {
            $event = Event::findOrFail($data['event_id']);

            if ($event->starts_at?->toDateString() !== $data['preferred_date']) {
                throw ValidationException::withMessages([
                    'preferred_date' => 'The preferred date must match the selected event date.',
                ]);
            }

            if ($event->capacity !== null) {
                $reservedParticipants = Booking::where('event_id', $event->id)
                    ->whereNotIn('status', ['cancelled'])
                    ->sum('participants');

                if ($reservedParticipants + $data['participants'] > $event->capacity) {
                    throw ValidationException::withMessages([
                        'participants' => 'This event does not have enough remaining capacity for that group size.',
                    ]);
                }
            }
        }

        if (isset($data['service_id'])) {
            $data['service'] = Service::findOrFail($data['service_id'])->title;
        }

        $booking = Booking::create($data + [
            'title' => 'Booking request - '.$data['organisation'],
            'status' => 'new',
            'published' => true,
        ]);

        $this->notifySiteTeam('booking', $data, $booking->id);

        return redirect()->route('book')->with('success', 'Your request has been received. It is not confirmed until our team approves it.');
    }

    private function notifySiteTeam(string $type, array $details, int $submissionId): void
    {
        $recipient = config('services.team_building.notification_email');

        if (! is_string($recipient) || filter_var($recipient, FILTER_VALIDATE_EMAIL) === false) {
            Log::warning('A website submission was saved, but no valid SITE_NOTIFICATION_EMAIL is configured.', [
                'submission_type' => $type,
                'submission_id' => $submissionId,
            ]);

            return;
        }

        Mail::to($recipient)->send(new SiteSubmissionReceived($type, $submissionId, $details));
    }
}
