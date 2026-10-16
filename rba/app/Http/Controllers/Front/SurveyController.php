<?php

namespace App\Http\Controllers\Front;

use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\UserSurvey;
use App\Models\Survey;
use Carbon\Carbon;

class SurveyController extends Controller
{
    public function getActiveSurvey(): ?Survey
    {
        $now = Carbon::now();
        // $now = Carbon::parse('2025-10-06'); // simulation date for the first 7 days of Q4
        $userCompanyId = Auth::user()->company_id;
        $userDepartmentId = Auth::user()->department_id;
        $userBranchId = Auth::user()->branch_id;
        $userJobRoleId = Auth::user()->job_role_id;

        $currentQuarter = $this->getCurrentQuarter($now);
        $quarterStart = $this->getQuarterStartDate($currentQuarter['year'], $currentQuarter['quarter']);
        $quarterEnd = $this->getQuarterEndDate($currentQuarter['year'], $currentQuarter['quarter']);

        $activeSurvey = Survey::where(function($query) use ($now, $quarterStart, $quarterEnd) {
                // Surveys with dates: use Twill's publishedInListings scope
                $query->where(function($q) use ($quarterStart, $quarterEnd) {
                    $q->whereNotNull('publish_start_date')
                    ->whereNotNull('publish_end_date')
                    ->publishedInListings();
                })
                // Surveys without dates: active for first 7 days of every quarter
                ->orWhere(function($q) use ($now, $quarterStart) {
                    $q->whereNull('publish_start_date')
                    ->published()
                    ->whereRaw('? BETWEEN ? AND DATE_ADD(?, INTERVAL 6 DAY)', [
                        $now->toDateString(),
                        $quarterStart->toDateString(),
                        $quarterStart->toDateString()
                    ]);
                });
            })
            ->where(function($q) use ($userCompanyId) {
                $q->whereNull('company_id')
                ->orWhere('company_id', $userCompanyId);
            })
            ->where(function($q) use ($userDepartmentId) {
                $q->whereNull('department_id')
                ->orWhere('department_id', $userDepartmentId);
            })
            ->where(function($q) use ($userBranchId) {
                $q->whereNull('branch_id')
                ->orWhere('branch_id', $userBranchId);
            })
            ->where(function($q) use ($userJobRoleId) {
                $q->whereNull('job_role_id')
                ->orWhere('job_role_id', $userJobRoleId);
            })
            ->orderBy('updated_at', 'desc')
            ->first();

        return $activeSurvey;
    }

    private function getCurrentQuarter(Carbon $date): array
    {
        $month = $date->month;
        $quarter = (int) ceil($month / 3);

        return [
            'year' => $date->year,
            'quarter' => $quarter,
        ];
    }

    private function getQuarterStartDate(int $year, int $quarter): Carbon
    {
        $month = ($quarter - 1) * 3 + 1;
        return Carbon::create($year, $month, 1, 0, 0, 0);
    }

    private function getQuarterEndDate(int $year, int $quarter): Carbon
    {
        $month = $quarter * 3;
        return Carbon::create($year, $month, 1, 0, 0, 0)->endOfMonth();
    }

    private function canUserSeeSurvey($userId, $surveyId): bool
    {
        $now = Carbon::now();
        $currentQuarter = $this->getCurrentQuarter($now);
        $quarterStart = $this->getQuarterStartDate($currentQuarter['year'], $currentQuarter['quarter']);

        // Check if user already submitted this survey in current quarter
        $hasSubmitted = UserSurvey::where('user_id', $userId)
            ->where('survey_id', $surveyId)
            ->where('created_at', '>=', $quarterStart)
            ->exists();

        return !$hasSubmitted;
    }

    public function getSurveyData()
    {
        $user = Auth::user();
        $survey = $this->getActiveSurvey();

        if (!$survey) {
            return response()->json([
                'show_survey' => false,
                'message' => 'No active survey available for this period.'
            ]);
        }

        if (!$this->canUserSeeSurvey($user->id, $survey->id)) {
            return response()->json([
                'show_survey' => false,
                'message' => 'You have already completed this period\'s survey.'
            ]);
        }

        $questions = $this->getSurveyWithQuestions($survey);

        return response()->json([
            'show_survey' => true,
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
                'description' => $survey->description,
            ],
            'questions' => $questions,
            'user_id' => $user->id,
        ]);
    }

    public function getSurveyWithQuestions(Survey $survey): array
    {
        $blocks = $survey->blocks()
            ->where('type', 'survey_repeater')
            ->orderBy('position')
            ->get();

        $questions = [];
        foreach ($blocks as $index => $block) {
            $content = $block->content;
            $questions[] = [
                'number' => $index + 1,
                'question' => $content['survey_question'] ?? '',
                'type' => $content['type'] ?? 'text',
                'show_in_analytics' => $content['show_in_analytics'] ?? 0,
                'analytics_type' => $content['analytics_type'] ?? null,
                'values' => $this->extractValues($content),
            ];
        }

        return $questions;
    }

    private function extractValues(array $content): array
    {
        $values = [];
        for ($i = 1; $i <= 10; $i++) {
            $key = "value_{$i}";
            if (!empty($content[$key])) {
                $values[] = $content[$key];
            }
        }
        return $values;
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'answers' => 'required|array',
        ]);

        $surveyId = $request->input('survey_id');
        $answers = $request->input('answers', []);

        if (!$this->canUserSeeSurvey($user->id, $surveyId)) {
            return response()->json([
                'success' => false,
                'message' => "You have already submitted this period's survey."
            ], 422);
        }

        try {
            $userSurvey = new UserSurvey();
            $userSurvey->survey_id = $surveyId;
            $userSurvey->user_id = $user->id;
            $userSurvey->branch_id = $user->branch_id;
            $userSurvey->company_id = $user->company_id;
            $userSurvey->department_id = $user->department_id;
            $userSurvey->job_role_id = $user->job_role_id;
            $userSurvey->email = $user->email;
            $userSurvey->name = $user->name;
            $userSurvey->published = 1;

            foreach ($answers as $questionNumber => $answerData) {
                if ($questionNumber > 30) continue;

                $questionCol = "question_{$questionNumber}";
                $answerCol = "answer_{$questionNumber}";

                $userSurvey->$questionCol = $answerData['question'] ?? '';

                $answerValue = $answerData['value'] ?? '';
                if (is_array($answerValue)) {
                    $answerValue = implode(', ', $answerValue);
                }

                $userSurvey->$answerCol = $answerValue;
            }

            $userSurvey->save();

            return response()->json([
                'success' => true,
                'message' => 'Thank you for completing the survey!'
            ]);

        } catch (QueryException $e) {
            Log::error('Survey save failed: '.$e->getMessage(), [
                'user_id' => $user->id,
                'survey_id' => $surveyId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit survey. Please try again.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Unexpected error saving survey: '.$e->getMessage(), [
                'user_id' => $user->id,
                'survey_id' => $surveyId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }
}
