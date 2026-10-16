<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PageRepository;
use App\Models\Evaluation;
use App\Models\UserEvaluation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserEvaluationController extends Controller
{
    const PAGE_COURSE_EVALUATIONS = 'course-evaluation';

    public function show(string $id)
    {
        $key = self::PAGE_COURSE_EVALUATIONS;
        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        $sections = Evaluation::where('course_id', $id)->first();
        if (!$sections) {
            abort(404);
        }
        $evaluation = UserEvaluation::where('user_id', Auth::user()->id)->where('course_id', $id)->first();

        return view('site.pages.course-evaluation', [
            'sections' => $sections,
            'evaluation' => $evaluation,
            'id' => $id,
            'pageItem' => $itemPage,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer',
        ]);

        $inputs = $request->except(['_token', 'course_id']);

        $evaluationData = [
            'published' => 1,
            'course_id' => $request->input('course_id'),
            'username' => Auth::user()->username ?? Auth::user()->name,
            'user_id' => Auth::id(),
            'company_id' => Auth::user()->company_id ?? null
        ];

        $questionNumber = 1;
        foreach ($inputs as $question => $answer) {
            if ($questionNumber <= 10) {
                $evaluationData["question{$questionNumber}"] = $question;
                $evaluationData["answer{$questionNumber}"] = $answer;
                $questionNumber++;
            }
        }

        $userEvaluation = UserEvaluation::updateOrCreate($evaluationData);

        return redirect()->route('profile.courses')->with('success', 'Evaluation submitted successfully.');
    }
}
