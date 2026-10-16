<?php

namespace App\Providers;

use A17\Twill\Facades\TwillAppSettings;
use A17\Twill\Services\Settings\SettingsGroup;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TwillAppSettings::registerSettingsGroup(
            SettingsGroup::make()->name('site')->label('Site settings')
        );

        View::composer(['layouts.site', 'site.*'], function ($view): void {
            TwillAppSettings::getGroupForName('site')->boot();

            $settings = static fn (string $key): ?string => TwillAppSettings::get('site.contact.'.$key)
                ?: null;

            $appearance = TwillAppSettings::getGroupDataForSectionAndName('site', 'appearance');
            $themeMode = $appearance->input('theme_mode') === 'light' ? 'light' : 'dark';
            $color = static function (string $key, string $fallback) use ($appearance): string {
                $value = $appearance->input($key);

                return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1
                    ? $value
                    : $fallback;
            };

            $pageBackground = null;
            $pageBackgroundColor = null;
            $viewData = $view->getData();
            foreach (['page', 'pageContent', 'homePage'] as $pageVariable) {
                if (($viewData[$pageVariable] ?? null) instanceof Page) {
                    $page = $viewData[$pageVariable];
                    $pageBackground = $page->image('page_background', 'default', [], true);
                    $candidateColor = $page->page_background_color;
                    if (is_string($candidateColor) && preg_match('/^#[0-9a-fA-F]{6}$/', $candidateColor) === 1) {
                        $pageBackgroundColor = $candidateColor;
                    }
                    break;
                }
            }

            if (! $pageBackground || ! $pageBackgroundColor) {
                $route = Route::current();
                $routeName = $route?->getName();
                $pageKey = match (true) {
                    $routeName === 'home' => 'home',
                    $routeName === 'services' || $routeName === 'services.show' => 'services',
                    $routeName === 'events' || $routeName === 'events.show' => 'events',
                    $routeName === 'industries' => 'industries',
                    $routeName === 'gallery' => 'gallery',
                    $routeName === 'packages' => 'packages',
                    $routeName === 'testimonials' => 'testimonials',
                    $routeName === 'faqs' => 'faqs',
                    $routeName === 'privacy' => 'privacy',
                    $routeName === 'terms' => 'terms',
                    $routeName === 'insights' || $routeName === 'insights.show' => 'insights',
                    $routeName === 'about' => 'about',
                    $routeName === 'contact' || $routeName === 'contact.store' => 'contact',
                    $routeName === 'book' || $routeName === 'book.store' => 'book',
                    default => null,
                };

                $backgroundPage = null;
                if (in_array($routeName, ['pages.root', 'pages.show'], true)) {
                    $slug = (string) $route?->parameter('slug');
                    $backgroundPage = Page::published()
                        ->whereNull('page_key')
                        ->forSlug($slug)
                        ->with('medias')
                        ->first();
                } elseif ($pageKey) {
                    $backgroundPage = Page::published()
                        ->where('page_key', $pageKey)
                        ->with('medias')
                        ->first();
                }

                if ($backgroundPage) {
                    $pageBackground ??= $backgroundPage->image('page_background', 'default', [], true);
                    $candidateColor = $backgroundPage->page_background_color;
                    if (is_string($candidateColor) && preg_match('/^#[0-9a-fA-F]{6}$/', $candidateColor) === 1) {
                        $pageBackgroundColor = $candidateColor;
                    }
                }
            }

            $view->with('siteSettings', [
                'name' => $settings('site_name') ?: config('app.name'),
                'tag_line' => $appearance->input('tag_line') ?: null,
                'logo' => $appearance->image('logo', 'default', [], true),
                'email' => $settings('contact_email') ?: config('mail.from.address'),
                'phone' => $settings('contact_phone'),
                'office' => $settings('office_location'),
                'working_hours' => $settings('working_hours'),
                'instagram' => $settings('instagram_url'),
                'linkedin' => $settings('linkedin_url'),
                'background_image' => $pageBackground
                    ?: ($pageBackgroundColor ? null : $appearance->image('background', 'default', [], true)),
                'page_background_image' => $pageBackground,
                'page_background_color' => $pageBackgroundColor,
                'theme_mode' => $themeMode,
                'background_overlay' => max(0, min(90, (int) ($appearance->input('background_overlay') ?? ($themeMode === 'dark' ? 68 : 0)))) / 100,
                'background_color' => $color('background_color', $themeMode === 'dark' ? '#06180f' : '#fbfaf5'),
                'text_color' => $color('text_color', $themeMode === 'dark' ? '#f1f5f9' : '#334155'),
                'heading_color' => $color('heading_color', $themeMode === 'dark' ? '#f8fafc' : '#0a1f1a'),
                'primary_color' => $color('primary_color', '#facc15'),
                'secondary_color' => $color('secondary_color', '#a7f3d0'),
                'third_color' => $color('third_color', '#fde68a'),
                'menu_color' => $color('menu_color', $themeMode === 'dark' ? '#e2e8f0' : '#17352a'),
                'menu_color_active' => $color('menu_color_active', $themeMode === 'dark' ? '#facc15' : '#047857'),
                'header_background_color' => $color('header_background_color', $themeMode === 'dark' ? '#03110c' : '#ffffff'),
                'footer_color' => $color('footer_color', $themeMode === 'dark' ? '#03110c' : '#17352a'),
                'enroll_color' => $color('enroll_color', '#facc15'),
                'progress_color' => $color('progress_color', '#258054'),
                'retake_color' => $color('retake_color', '#e6b52c'),
                'completed_color' => $color('completed_color', '#258054'),
            ]);

            $view->with('siteMenus', MenuItem::where('published', true)
                ->orderBy('position')
                ->orderBy('id')
                ->get()
                ->groupBy(fn (MenuItem $item): string => $item->menu_type === 'footer' || $item->location === 'footer'
                    ? 'footer'
                    : 'main'));
        });
    }
}
