<?php

namespace Tests\Feature;

use A17\Twill\Models\Media;
use App\Http\Requests\Twill\EventRequest;
use App\Http\Requests\Twill\HomeSlideRequest;
use App\Http\Requests\Twill\PageRequest;
use App\Http\Requests\Twill\ServiceRequest;
use App\Mail\SiteSubmissionReceived;
use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\HomeSlide;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Service;
use App\Repositories\GalleryItemRepository;
use App\Repositories\HomeSlideRepository;
use App\Repositories\ServiceRepository;
use Database\Seeders\HomeSlidesSeeder;
use Database\Seeders\PageContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TeamBuildingPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_pages_load(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Bizetu Hub')
            ->assertSee('Build. Belong.')
            ->assertSee('images/bizetu-hub-logo.png');
        $this->get(route('services'))->assertOk();
        $this->get(route('events'))->assertOk();
        $this->get(route('industries'))->assertOk();
        $this->get(route('gallery'))->assertOk();
        $this->get(route('packages'))->assertOk();
        $this->get(route('testimonials'))->assertOk();
        $this->get(route('faqs'))->assertOk();
        $this->get(route('privacy'))->assertOk();
        $this->get(route('terms'))->assertOk();
        $this->get(route('insights'))->assertOk();
        $this->get(route('book'))->assertOk();
        $this->get(route('contact'))->assertOk();
    }

    public function test_published_home_slides_appear_in_order_and_unpublished_slides_are_hidden(): void
    {
        HomeSlide::create([
            'title' => 'First homepage message',
            'description' => 'First slide copy.',
            'published' => true,
        ]);
        HomeSlide::create([
            'title' => 'Second homepage message',
            'description' => 'Second slide copy.',
            'published' => true,
        ]);
        HomeSlide::create([
            'title' => 'Unpublished homepage message',
            'position' => 0,
            'published' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['First homepage message', 'Second homepage message'])
            ->assertDontSee('Unpublished homepage message')
            ->assertSee('Show slide 2')
            ->assertSee('data-slide-next', false);
    }

    public function test_home_banner_keeps_the_reference_heading_and_compact_layout(): void
    {
        HomeSlide::create([
            'title' => 'Build. Belong. Become.',
            'description' => 'Bizetu Hub helps organisations build stronger teams and professionals grow stronger careers.',
            'button_label' => 'Start a conversation',
            'button_url' => '/book',
            'published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Build. Belong.')
            ->assertSee('Become.')
            ->assertSee('Start a conversation')
            ->assertSee('min-h-[500px]', false)
            ->assertSee('Explore what we do');
    }

    public function test_home_slide_repository_persists_attached_banner_images(): void
    {
        $slide = HomeSlide::create([
            'title' => 'Image persistence test',
            'published' => true,
        ]);
        $media = Media::create([
            'uuid' => 'banner-test/banner.jpg',
            'filename' => 'banner.jpg',
            'alt_text' => 'Banner',
            'width' => 1200,
            'height' => 600,
        ]);

        app(HomeSlideRepository::class)->afterSaveHandleMedias($slide, [
            'medias' => [
                'background' => [
                    [
                        'id' => $media->id,
                        'metadatas' => ['custom' => []],
                    ],
                ],
            ],
        ]);

        $slide->refresh();

        $this->assertTrue($slide->hasImage('background'));
        $this->assertSame($media->id, $slide->medias->first()->id);
    }

    public function test_gallery_item_repository_persists_attached_images(): void
    {
        $galleryItem = GalleryItem::create([
            'title' => 'Image persistence test',
            'alt_text' => 'Banner image',
            'published' => true,
        ]);
        $media = Media::create([
            'uuid' => 'gallery-test/gallery.jpg',
            'filename' => 'gallery.jpg',
            'alt_text' => 'Gallery',
            'width' => 1200,
            'height' => 600,
        ]);

        app(GalleryItemRepository::class)->afterSaveHandleMedias($galleryItem, [
            'medias' => [
                'image' => [
                    [
                        'id' => $media->id,
                        'metadatas' => ['custom' => []],
                    ],
                ],
            ],
        ]);

        $galleryItem->refresh();

        $this->assertTrue($galleryItem->hasImage('image'));
        $this->assertSame($media->id, $galleryItem->medias->first()->id);
    }

    public function test_default_home_slides_can_be_seeded_more_than_once(): void
    {
        $this->seed(HomeSlidesSeeder::class);
        $this->seed(HomeSlidesSeeder::class);

        $this->assertDatabaseCount('home_slides', 3);
    }

    public function test_standard_site_pages_are_available_in_the_page_editor(): void
    {
        $this->seed(PageContentSeeder::class);
        $this->seed(PageContentSeeder::class);

        $this->assertDatabaseCount('pages', count(Page::PAGE_KEYS));
        $this->assertDatabaseHas('pages', [
            'page_key' => 'home',
            'published' => true,
        ]);
        $this->assertDatabaseHas('pages', [
            'page_key' => 'contact',
            'published' => true,
        ]);
        $this->get('/pages/about')->assertNotFound();
    }

    public function test_homepage_content_blocks_replace_the_default_sections_without_hiding_the_slides(): void
    {
        $this->seed(PageContentSeeder::class);
        $page = Page::where('page_key', 'home')->firstOrFail();
        $page->blocks()->create([
            'type' => 'content_section',
            'position' => 0,
            'editor_name' => 'default',
            'content' => [
                'eyebrow' => ['en' => 'Homepage section'],
                'title' => ['en' => 'Editable homepage heading'],
                'text' => ['en' => '<p>Editable homepage copy.</p>'],
            ],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Editable homepage heading')
            ->assertSee('Editable homepage copy.')
            ->assertSee('data-home-slide')
            ->assertDontSee('People-first experiences for stronger teams.');
    }

    public function test_fixed_page_blocks_render_instead_of_the_static_page_copy(): void
    {
        $this->seed(PageContentSeeder::class);
        $page = Page::where('page_key', 'about')->firstOrFail();
        $page->blocks()->create([
            'type' => 'content_section',
            'position' => 0,
            'editor_name' => 'default',
            'content' => [
                'eyebrow' => ['en' => 'Our story'],
                'title' => ['en' => 'Admin-managed about section'],
                'text' => ['en' => '<p>Updated about-page copy.</p>'],
            ],
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Admin-managed about section')
            ->assertSee('Updated about-page copy.')
            ->assertDontSee('People are at the heart of every organisation.');
    }

    public function test_block_based_service_page_keeps_services_managed_in_the_services_module(): void
    {
        $this->seed(PageContentSeeder::class);
        $page = Page::where('page_key', 'services')->firstOrFail();
        $page->blocks()->create([
            'type' => 'content_section',
            'position' => 0,
            'editor_name' => 'default',
            'content' => [
                'title' => ['en' => 'Our programmes'],
            ],
        ]);
        Service::create([
            'title' => 'Admin-managed service',
            'slug' => 'admin-managed-service',
            'excerpt' => 'This remains connected to the Services module.',
            'published' => true,
        ]);

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('Our programmes')
            ->assertSee('Admin-managed service')
            ->assertSee('This remains connected to the Services module.');
    }

    public function test_services_page_uses_programme_cards_and_service_detail_view(): void
    {
        Service::create([
            'title' => 'Team connection',
            'slug' => 'team-connection',
            'excerpt' => 'Build trust and a stronger sense of belonging.',
            'description' => 'A practical experience for teams.',
            'badge' => 'Popular',
            'duration' => 'Half-day',
            'group_size' => '10–40 people',
            'highlights' => '<ul><li>Build trust through shared challenges</li><li>Take practical habits back to work</li></ul>',
            'published' => true,
        ]);

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('services-experience', false)
            ->assertSee('service-glass-card', false)
            ->assertSee('data-services-view="overview"', false)
            ->assertSee('data-service-detail-template="team-connection"', false)
            ->assertSee('service-proposal-dialog', false)
            ->assertSee('Popular')
            ->assertSee('Half-day')
            ->assertSee('10–40 people')
            ->assertSee(route('services.show', 'team-connection'), false);

        $this->get(route('services.show', 'team-connection'))
            ->assertOk()
            ->assertSee('service-detail-panel', false)
            ->assertSee('Team connection')
            ->assertSee('Programme highlights')
            ->assertSee('Build trust through shared challenges')
            ->assertSee('Request this programme')
            ->assertSee(route('book', ['service_id' => $service->id]), false);
    }

    public function test_service_repository_persists_attached_programme_images(): void
    {
        $service = Service::create([
            'title' => 'Image persistence test',
            'slug' => 'image-persistence-test',
            'excerpt' => 'Service image.',
            'published' => true,
        ]);
        $media = Media::create([
            'uuid' => 'service-test/service.jpg',
            'filename' => 'service.jpg',
            'alt_text' => 'A team outdoors',
            'width' => 1200,
            'height' => 900,
        ]);

        app(ServiceRepository::class)->afterSaveHandleMedias($service, [
            'medias' => [
                'cover' => [
                    [
                        'id' => $media->id,
                        'metadatas' => ['custom' => []],
                    ],
                ],
            ],
        ]);
        $service->refresh();

        $this->assertTrue($service->hasImage('cover'));
        $this->assertSame($media->id, $service->medias->first()->id);
    }

    public function test_nested_card_blocks_render_and_contact_blocks_preserve_the_enquiry_form(): void
    {
        $this->seed(PageContentSeeder::class);
        $aboutPage = Page::where('page_key', 'about')->firstOrFail();
        $cardGrid = $aboutPage->blocks()->create([
            'type' => 'card_grid',
            'position' => 0,
            'editor_name' => 'default',
            'content' => [
                'title' => ['en' => 'Our values'],
            ],
        ]);
        $cardGrid->children()->create([
            'type' => 'page_card',
            'position' => 0,
            'content' => [
                'title' => ['en' => 'Listen first'],
                'text' => ['en' => '<p>Understand the team context.</p>'],
            ],
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Our values')
            ->assertSee('Listen first')
            ->assertSee('Understand the team context.');

        $contactPage = Page::where('page_key', 'contact')->firstOrFail();
        $contactPage->blocks()->create([
            'type' => 'content_section',
            'position' => 0,
            'editor_name' => 'default',
            'content' => [
                'title' => ['en' => 'Start a conversation'],
                'text' => ['en' => '<p>Write to the team.</p>'],
            ],
        ]);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Start a conversation')
            ->assertSee(route('contact.store'), false)
            ->assertSee('name="message"', false);
    }

    public function test_twill_media_urls_include_the_upload_directory(): void
    {
        $url = Storage::disk('twill_media_library')->url('slide.jpg');

        $this->assertStringEndsWith('/storage/uploads/slide.jpg', $url);
    }

    public function test_home_slide_buttons_reject_unsafe_url_schemes(): void
    {
        $request = HomeSlideRequest::create('/admin/homeSlides', 'POST', [
            'title' => 'New slide',
            'button_label' => 'Click me',
            'button_url' => 'javascript:alert(1)',
        ]);
        $validator = Validator::make($request->all(), $request->rulesForCreate());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('button_url'));
    }

    public function test_page_builder_buttons_reject_unsafe_url_schemes(): void
    {
        $request = PageRequest::create('/admin/pages', 'POST', [
            'title' => 'Safe links',
            'blocks' => [
                [
                    'type' => 'call_to_action',
                    'content' => ['button_url' => 'javascript:alert(1)'],
                ],
                [
                    'type' => 'card_grid',
                    'blocks' => [
                        [
                            'type' => 'page_card',
                            'content' => ['link_url' => 'javascript:alert(1)'],
                        ],
                    ],
                ],
            ],
        ]);
        $validator = Validator::make($request->all(), $request->rulesForCreate());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('blocks.0.content.button_url'));
        $this->assertTrue($validator->errors()->has('blocks.1.blocks.0.content.link_url'));
    }

    public function test_event_draft_can_be_created_from_the_title_only_quick_create_form(): void
    {
        $request = EventRequest::create('/admin/events', 'POST', [
            'title' => 'Hiking',
            'slug' => 'hiking',
            'published' => false,
        ]);
        $validator = Validator::make($request->all(), $request->rulesForCreate());

        $this->assertTrue($validator->passes(), $validator->errors()->toJson());

        $event = Event::create([
            'title' => 'Hiking',
            'slug' => 'hiking',
            'published' => false,
        ]);

        $this->assertFalse($event->canPublish);
        $this->assertDatabaseHas('events', [
            'title' => 'Hiking',
            'published' => false,
            'description' => null,
            'venue' => null,
            'starts_at' => null,
        ]);
    }

    public function test_event_cannot_be_published_without_required_event_details(): void
    {
        $request = EventRequest::create('/admin/events', 'POST', [
            'title' => 'Hiking',
            'slug' => 'hiking',
            'published' => true,
        ]);
        $validator = Validator::make($request->all(), $request->rulesForCreate());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('description'));
        $this->assertTrue($validator->errors()->has('venue'));
        $this->assertTrue($validator->errors()->has('starts_at'));
    }

    public function test_service_draft_can_be_created_from_the_title_only_quick_create_form(): void
    {
        $request = ServiceRequest::create('/admin/services', 'POST', [
            'title' => 'HR Services',
            'slug' => 'hr-services',
            'published' => false,
        ]);
        $validator = Validator::make($request->all(), $request->rulesForCreate());

        $this->assertTrue($validator->passes(), $validator->errors()->toJson());

        $service = Service::create([
            'title' => 'HR Services',
            'slug' => 'hr-services',
            'published' => false,
        ]);

        $this->assertFalse($service->canPublish);
        $this->assertDatabaseHas('services', [
            'title' => 'HR Services',
            'published' => false,
            'excerpt' => null,
        ]);
    }

    public function test_service_cannot_be_published_without_an_excerpt(): void
    {
        $request = ServiceRequest::create('/admin/services', 'POST', [
            'title' => 'HR Services',
            'slug' => 'hr-services',
            'published' => true,
        ]);
        $validator = Validator::make($request->all(), $request->rulesForCreate());

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('excerpt'));
    }

    public function test_published_menu_items_control_header_and_footer_navigation(): void
    {
        MenuItem::create([
            'title' => 'Custom programme',
            'url' => '/custom-programme',
            'location' => 'header',
            'position' => 1,
            'open_new_tab' => true,
            'published' => true,
        ]);
        MenuItem::create([
            'title' => 'Draft link',
            'url' => '/draft',
            'location' => 'header',
            'position' => 2,
            'published' => false,
        ]);
        MenuItem::create([
            'title' => 'Custom policy',
            'url' => '/custom-policy',
            'location' => 'footer',
            'published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('href="/custom-programme"', false)
            ->assertSee('target="_blank" rel="noopener noreferrer"', false)
            ->assertSee('href="/custom-policy"', false)
            ->assertDontSee('href="/draft"', false);
    }

    public function test_booking_request_is_saved_as_new_and_is_not_confirmed(): void
    {
        Mail::fake();

        $service = Service::create([
            'title' => 'Team connection',
            'slug' => 'team-connection',
            'excerpt' => 'Build trust and connection.',
            'published' => true,
        ]);

        $response = $this->post(route('book.store'), [
            'organisation' => 'Example Company',
            'name' => 'Alex Example',
            'email' => 'alex@example.com',
            'phone' => '+254700000000',
            'service_id' => $service->id,
            'preferred_date' => now()->addDays(10)->toDateString(),
            'participants' => 12,
            'venue_preference' => 'Nairobi',
            'requirements' => 'Accessible venue required.',
        ]);

        $response->assertRedirect(route('book'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'organisation' => 'Example Company',
            'service_id' => $service->id,
            'status' => 'new',
            'published' => true,
        ]);
        $this->assertSame('new', Booking::firstOrFail()->status);
        Mail::assertQueued(SiteSubmissionReceived::class, fn ($mail) => $mail->submissionType === 'booking');
    }

    public function test_booking_request_can_register_for_a_public_event(): void
    {
        $event = Event::create([
            'title' => 'Collaboration Lab',
            'slug' => 'collaboration-lab',
            'description' => 'A practical collaboration workshop.',
            'venue' => 'Nairobi',
            'starts_at' => now()->addDays(20),
            'published' => true,
        ]);

        $this->post(route('book.store'), [
            'organisation' => 'Example Company',
            'name' => 'Alex Example',
            'email' => 'alex@example.com',
            'phone' => '+254700000000',
            'event_id' => $event->id,
            'preferred_date' => now()->addDays(20)->toDateString(),
            'participants' => 4,
        ])->assertRedirect(route('book'));

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'service_id' => null,
            'status' => 'new',
        ]);
    }

    public function test_booking_request_cannot_select_a_service_and_event_together(): void
    {
        $service = Service::create([
            'title' => 'Team connection',
            'slug' => 'team-connection-exclusive',
            'excerpt' => 'Build trust and connection.',
            'published' => true,
        ]);
        $event = Event::create([
            'title' => 'Collaboration Lab',
            'slug' => 'collaboration-lab-exclusive',
            'description' => 'A practical collaboration workshop.',
            'venue' => 'Nairobi',
            'starts_at' => now()->addDays(20),
            'published' => true,
        ]);

        $this->from(route('book'))
            ->post(route('book.store'), [
                'organisation' => 'Example Company',
                'name' => 'Alex Example',
                'email' => 'alex@example.com',
                'phone' => '+254700000000',
                'service_id' => $service->id,
                'event_id' => $event->id,
                'preferred_date' => $event->starts_at->toDateString(),
                'participants' => 4,
            ])
            ->assertRedirect(route('book'))
            ->assertSessionHasErrors(['service_id', 'event_id']);
    }

    public function test_event_booking_enforces_event_date_and_capacity(): void
    {
        $event = Event::create([
            'title' => 'Limited Collaboration Lab',
            'slug' => 'limited-collaboration-lab',
            'description' => 'A limited collaboration workshop.',
            'venue' => 'Nairobi',
            'starts_at' => now()->addDays(20),
            'capacity' => 5,
            'published' => true,
        ]);

        $base = [
            'organisation' => 'Example Company',
            'name' => 'Alex Example',
            'email' => 'alex@example.com',
            'phone' => '+254700000000',
            'event_id' => $event->id,
            'preferred_date' => $event->starts_at->toDateString(),
        ];

        $this->post(route('book.store'), $base + ['participants' => 4])
            ->assertRedirect(route('book'));

        $this->from(route('book'))
            ->post(route('book.store'), $base + ['participants' => 2])
            ->assertRedirect(route('book'))
            ->assertSessionHasErrors('participants');

        $this->from(route('book'))
            ->post(route('book.store'), $base + [
                'preferred_date' => $event->starts_at->copy()->addDay()->toDateString(),
                'participants' => 1,
            ])
            ->assertRedirect(route('book'))
            ->assertSessionHasErrors(['preferred_date', 'participants']);
    }

    public function test_past_booking_dates_and_unpublished_services_are_rejected(): void
    {
        $service = Service::create([
            'title' => 'Unpublished session',
            'slug' => 'unpublished-session',
            'excerpt' => 'Not public.',
            'published' => false,
        ]);

        $this->from(route('book'))
            ->post(route('book.store'), [
                'organisation' => 'Example Company',
                'name' => 'Alex Example',
                'email' => 'alex@example.com',
                'phone' => '+254700000000',
                'service_id' => $service->id,
                'preferred_date' => now()->subDay()->toDateString(),
                'participants' => 4,
            ])
            ->assertRedirect(route('book'))
            ->assertSessionHasErrors(['service_id', 'preferred_date']);

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_contact_enquiry_is_saved_for_admin_follow_up(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), [
            'name' => 'Alex Example',
            'email' => 'alex@example.com',
            'organisation' => 'Example Company',
            'phone' => '+254700000000',
            'message' => 'Please send a proposal.',
        ])->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('enquiries', [
            'name' => 'Alex Example',
            'status' => 'new',
        ]);
        $this->assertSame('new', Enquiry::firstOrFail()->status);
        Mail::assertQueued(SiteSubmissionReceived::class, fn ($mail) => $mail->submissionType === 'enquiry');
    }
}
