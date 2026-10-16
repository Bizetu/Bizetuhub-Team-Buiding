<?php

namespace App\Http\Controllers\Front;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Course;
use App\Models\Page;
use App\Models\Faq;

class PageController extends Controller
{
    protected $pageRepository;
    protected $homeKey;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->homeKey = PageRepository::PAGE_HOMEPAGE;
    }


    public function home()
    {
        if(Auth::check()){
            $key = 'home';
            $itemPage = app(PageRepository::class)->getPage($key);
            if (!$itemPage) {
                abort(404);
            }
            $featuredModules = Course::published()->with('medias')->get();
            return view('site.pages.home', [
                'pageItem' => $itemPage,
                'featuredModules' => $featuredModules
            ]);

        } else {
            $key = 'home_guest';
            $itemPage = app(PageRepository::class)->getPage($key);
            if (!$itemPage) {
                abort(404);
            }
            return view('site.pages.home_guest', [
                'pageItem' => $itemPage,
            ]);
        }
    }

    public function courses()
    {
        $key = 'courses';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.courses', [
            'pageItem' => $itemPage,
        ]);
    }
 
    public function pages(string $slug, PageRepository $pageRepository): View
    {
        $itemPage = $pageRepository->forSlug($slug);
 
        if (!$itemPage) {
            abort(404);
        }
 
        return view('site.pages.' . $itemPage, ['pageItem' => $itemPage]);
    }

    public function esgGuidelines()
    {
        $key = 'esg-guidelines';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.esg-guidelines', [
            'pageItem' => $itemPage,
        ]);
    }

    public function esgPillars()
    {
        $key = 'pillars';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.pillars', [
            'pageItem' => $itemPage,
        ]);
    }

    public function caseStudies()
    {
        $key = 'case-studies';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.case-studies', [
            'pageItem' => $itemPage,
        ]);
    }

    public function privacyPolicy()
    {
        $key = 'privacy-policy';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.privacy-policy', [
            'pageItem' => $itemPage,
        ]);
    }

    public function termsConditions()
    {
        $key = 'terms-conditions';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.terms-conditions', [
            'pageItem' => $itemPage,
        ]);
    }

    public function faqs()
    {
        $key = 'faqs';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        $faqs = Faq::with('category')->published()->orderBy('position')->get();
        $groups = $faqs->groupBy('category.title');

        return view('site.pages.faqs', [
            'pageItem' => $itemPage,
            'faqs' => $faqs,
            'groups' => $groups,
        ]);
    }

    public function events()
    {
        $key = 'events';
        $itemPage = app(PageRepository::class)->getPage($key);
        if (!$itemPage) {
            abort(404);
        }
        return view('site.pages.resources.events', [
            'pageItem' => $itemPage,
        ]);
    }


    public function research(Request $request)
    {
        $key = 'research';
        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        $blocks = $itemPage->blocks;
        $items = collect();

        foreach ($blocks as $block) {
            if ($block->type === 'resource_research' && !empty($block->children)) {
                $items = $items->merge($block->children);
            }
        }

        $items = $items->filter(function ($item) {
            return $item->content['publish_status'] ?? false;
        });

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $items = $items->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->content['title'] ?? ''), $search);
            });
        }

        $items = $items->sortByDesc(function ($item) {
            return $item->content['date_published'] ?? null;
        })->values();

        $perPage = 12;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedItems,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('site.pages.resources.research', [
            'pageItem' => $itemPage,
            'items' => $paginated,
            'search' => $request->search,
        ]);
    }

    public function externalLinks()
    {
        $key = 'external-links';
        $itemPage = app(PageRepository::class)->getPage($key);
        if (!$itemPage) {
            abort(404);
        }
        return view('site.pages.resources.external-links', [
            'pageItem' => $itemPage,
        ]);
    }

    public function videos()
    {
        $key = 'videos';
        $itemPage = app(PageRepository::class)->getPage($key);
        if (!$itemPage) {
            abort(404);
        }
        return view('site.pages.resources.videos', [
            'pageItem' => $itemPage,
        ]);
    }

    public function about()
    {
        $key = 'about';
        $itemPage = app(PageRepository::class)->getPage($key);
        if (!$itemPage) {
            abort(404);
        }
        return view('site.pages.about', [
            'pageItem' => $itemPage,
        ]);
    }

    public function greenGrowth()
    {
        $key = 'green-growth';
        $itemPage = app(PageRepository::class)->getPage($key);
        if (!$itemPage) {
            abort(404);
        }
        return view('site.pages.green-growth', [
            'pageItem' => $itemPage,
        ]);
    }
}
