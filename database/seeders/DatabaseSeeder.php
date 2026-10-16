<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\MenuItem;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            [
                'title' => 'Team connection',
                'excerpt' => 'Build trust, openness and a stronger sense of belonging.',
                'description' => 'A facilitated experience that helps teams listen, collaborate and leave with practical habits for better connection.',
                'icon' => '◌',
            ],
            [
                'title' => 'Leadership in practice',
                'excerpt' => 'Turn leadership principles into everyday behaviours.',
                'description' => 'A focused programme for managers and emerging leaders navigating change, complexity and accountability.',
                'icon' => '↗',
            ],
            [
                'title' => 'Creative problem solving',
                'excerpt' => 'Give your people a playful way to tackle real challenges.',
                'description' => 'Teams use creative prompts and structured reflection to see familiar problems differently and move forward together.',
                'icon' => '✦',
            ],
        ] as $service) {
            Service::updateOrCreate(
                ['slug' => Str::slug($service['title'])],
                $service + ['featured' => true, 'published' => true]
            );
        }

        Event::updateOrCreate(
            ['slug' => 'the-collaboration-lab'],
            [
                'title' => 'The Collaboration Lab',
                'description' => 'A practical half-day for teams ready to improve how they work together.',
                'venue' => 'Nairobi · The Foundry',
                'starts_at' => now()->addDays(18)->setTime(9, 0),
                'ends_at' => now()->addDays(18)->setTime(13, 0),
                'capacity' => 40,
                'published' => true,
            ]
        );

        $this->call(HomeSlidesSeeder::class);

        foreach ([
            ['title' => 'Services', 'url' => '/services', 'location' => 'header'],
            ['title' => 'Events', 'url' => '/events', 'location' => 'header'],
            ['title' => 'Industries', 'url' => '/industries', 'location' => 'header'],
            ['title' => 'Insights', 'url' => '/insights', 'location' => 'header'],
            ['title' => 'Gallery', 'url' => '/gallery', 'location' => 'header'],
            ['title' => 'About', 'url' => '/about', 'location' => 'header'],
            ['title' => 'Contact', 'url' => '/contact', 'location' => 'header'],
            ['title' => 'Packages', 'url' => '/packages', 'location' => 'footer'],
            ['title' => 'Testimonials', 'url' => '/testimonials', 'location' => 'footer'],
            ['title' => 'FAQs', 'url' => '/faqs', 'location' => 'footer'],
            ['title' => 'Privacy', 'url' => '/privacy', 'location' => 'footer'],
            ['title' => 'Terms', 'url' => '/terms', 'location' => 'footer'],
        ] as $position => $menuItem) {
            MenuItem::updateOrCreate(
                ['url' => $menuItem['url'], 'location' => $menuItem['location']],
                $menuItem + [
                    'menu_type' => $menuItem['location'] === 'footer' ? 'footer' : 'main',
                    'position' => $position,
                    'published' => true,
                ]
            );
        }
    }
}
