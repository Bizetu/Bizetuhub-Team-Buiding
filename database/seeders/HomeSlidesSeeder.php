<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use Illuminate\Database\Seeder;

class HomeSlidesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'title' => 'Build. Belong. Become.',
                'eyebrow' => 'Nairobi · Kenya',
                'description' => 'Bizetu Hub helps organisations build stronger teams and professionals grow stronger careers.',
                'button_label' => 'Start a conversation',
                'button_url' => '/book',
            ],
            [
                'title' => 'Stronger teams start with people.',
                'eyebrow' => 'Team building · HR advisory',
                'description' => 'Create the conditions for trust, clearer collaboration and more confident people leaders.',
                'button_label' => 'Explore our services',
                'button_url' => '/services',
            ],
            [
                'title' => 'Make your next step count.',
                'eyebrow' => 'Events · Career support',
                'description' => 'From purposeful corporate experiences to practical career guidance, we help people move forward.',
                'button_label' => 'Talk to our team',
                'button_url' => '/contact',
            ],
        ] as $slide) {
            HomeSlide::firstOrCreate(
                ['title' => $slide['title']],
                $slide + ['published' => true]
            );
        }
    }
}
