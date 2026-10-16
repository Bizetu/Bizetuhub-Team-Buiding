<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Models\CaseStudy;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function __construct()
    {
        $this->caseStudiesKey = PageRepository::PAGE_CASESTUDIES;
    }

    public function caseStudies(){
        
        $itemPage =  app(PageRepository::class)->getPage($this->caseStudiesKey);

        $cases = CaseStudy::published()->paginate(4);

        return view('site.pages.caseStudies.' . $this->caseStudiesKey, [
            'pageItem' => $itemPage,
            'casestudies'=> $cases,
        ]);
    }

    public function singleCaseStudy(Request $request, $id)
    {
    
        $itemPage =  app(PageRepository::class)->getPage($this->caseStudiesKey);
        $casestudy = CaseStudy::where('id', $id)->first();

        $casestudies = CaseStudy::where('id','!=', $id)->get();
            return view('site.pages.caseStudies.case-study', [
                'pageItem' => $itemPage,
                'caseStudy'=> $casestudy,
            ]);
    }
}
