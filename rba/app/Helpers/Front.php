<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use App\Models\PlatformSetting;
use App\Models\Menu;

if (!function_exists('companyLogo')) {
    function companyLogo()
    {
        $logo = cache()->remember('platform_settings', 3600, function () {
            return PlatformSetting::where('id', 1)->first();
        });

        $logoImage = $logo->image('logo') ?? '/favicon.png';

        return $logoImage;
    }
}

if (!function_exists('mainMenu')) {
    function mainMenu()
    {
        $menus = Menu::where('menu_type_id', 1)
            ->published()
            ->orderBy('position', 'asc')
            ->get();

        return $menus;
    }
}

if (!function_exists('mainMenuMobile')) {
    function mainMenuMobile()
    {
        $menus = Menu::where('menu_type_id', 1)
            ->published()
            ->orderBy('position', 'asc')
            ->get();

        return $menus;
    }
}

if (!function_exists('footerMenu')) {
    function footerMenu()
    {
        $menus = Menu::where('menu_type_id', 2)->published()->orderBy('position', 'asc')->get();

        return $menus;
    }
}

if (!function_exists('footerMenuGuest')) {
    function footerMenuGuest()
    {
        $menus = Menu::published()->where('menu_type_id', 2)->whereIn('key', ['terms-conditions', 'privacy-policy'])->orderBy('position', 'asc')->get();

        return $menus;
    }
}

if (!function_exists('secondaryMenu')) {
    function secondaryMenu()
    {
        $menus = Menu::where('menu_type_id', 4)
            ->published()
            ->orderBy('position', 'asc')
            ->get();

        return $menus;
    }
}

if (!function_exists('mobileMenu')) {
    function mobileMenu()
    {
        $menus = Menu::where('menu_type_id', 4)
            ->orWhere('menu_type_id', 1)
            ->published()
            ->orderBy('position', 'asc')
            ->get();

        return $menus;
    }
}

if (!function_exists('truncateString')) {
    function truncateString($str, $len)
    {
        $tail = max(0, $len - 10);
        $trunk = substr($str, 0, $tail);
        $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($str, $tail, $len - $tail))));

        return $trunk;
    }
}

if (!function_exists('paginator')) {
    function paginator($items, $perPage, $path, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $options = ['path' => '/' . $path];

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

if (!function_exists('defaultBanner')) {
    function defaultBanner($image, $default = 'images/defaults/banner.webp')
    {
        // Handle empty or placeholder images
        if (!$image || str_starts_with($image, 'data:image/gif;base64')) {
            return asset($default);
        }

        // If it's a full URL, extract the path
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            // Extract path after /storage/
            if (preg_match('#/storage/(.+)$#', $image, $matches)) {
                $path = $matches[1];
                if (Storage::disk('public')->exists($path)) {
                    return $image;
                }
            } else {
                // URL doesn't match storage pattern, assume it's valid
                return $image;
            }
            return asset($default);
        }

        // Handle relative storage paths
        if (!Storage::disk('public')->exists($image)) {
            return asset($default);
        }

        return Storage::disk('public')->url($image);
    }
}

if (!function_exists('learnNavigateVideo')) {
    function learnNavigateVideo()
    {
        $setting = PlatformSetting::first();

        if ($setting && $setting->file('learn_navigate')) {
            return $setting->file('learn_navigate', 'default');
        }

        return null;
    }
}

if (!function_exists('split_title')) {
    function split_title(?string $title, int $maxWords = 12): array
    {
        if (!$title) {
            return ['first' => '', 'middle' => '', 'last2' => ''];
        }

        $words = preg_split('/\s+/', trim($title));
        $words = array_slice($words, 0, $maxWords);
        $count = count($words);

        if ($count === 1) {
            $first = $words[0];
            $middle = '';
            $last2 = '';
        } elseif ($count === 2) {
            $first = $words[0];
            $middle = '';
            $last2 = $words[1];
        } else {
            $first = $words[0];
            $middle = $count > 3 ? implode(' ', array_slice($words, 1, $count - 3)) : '';
            $last2 = implode(' ', array_slice($words, -2));
        }

        return ['first' => $first, 'middle' => $middle, 'last2' => $last2];
    }
}
