<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Create the page records used by the CMS without populating editable blocks.
     * Blocks are managed in the admin and must not reappear after deletion when
     * a seeder is run again.
     */
    public function run(): void
    {
        foreach (array_keys(Page::PAGE_KEYS) as $position => $key) {
            $title = Page::PAGE_KEYS[$key];

            Page::firstOrCreate(
                ['page_key' => $key],
                [
                    'title' => $title,
                    'header_title' => $title,
                    'position' => $position,
                    'published' => true,
                ]
            );
        }
    }
}
