<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\EmailForQueuing;
use App\Mail\MessageForQueuingCoursesCompleted;
use App\Mail\MessageForQueuingCoursesYetStarted;
use App\Mail\MessageForQueuingCoursesInProgress;
use App\Mail\MessageForQueuingCoursesCompletedbutFailed;
use App\Mail\MessageForQueuingInProgress;
use App\Mail\MessageForQueuingCompletedbutFailed;
use App\Mail\MessageForQueuingCompleted;
use App\Mail\MessageForQueuingYetStarted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\Course;
use App\Models\DueDate;
use App\Models\CompletionReport;
use App\Models\EmailUnsubscribe;

class SendMessagesEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example usage: php artisan send:messages "Subject here" "Message here"
     */
    protected $signature = 'send:messages';

    protected $emailLimit = 2;

    /**
     * The console command description.
     */
    protected $description = 'Send emails to all users';

    public function handle()
    {

        //select all messages with send date set
        $messages = Message::published()->whereNotNull('send_date')->where('sent_status', '!=', 1)->get();

        $messages = $messages->filter(function ($message) {
        // parse as Africa/Nairobi explicitly
        $sendDate = Carbon::parse($message->send_date, 'Africa/Nairobi');

        // get current local time
        $now = now()->timezone('Africa/Nairobi');

        // var_dump($sendDate->toDateTimeString(), $now->toDateTimeString());

        return $now->greaterThan($sendDate);
        });

        $messages = Message::published()->whereNotNull('send_date')->where('sent_status', '!=', 1)->get();

        //select those 
        // $readyForSend = PlatformMessage::wherein('id', $toRun)->get();
 
        foreach ($messages as $job){
            //Remove duplicates
            $companyMessages     = $job->company_id;
            $jobRoleMessages     = $job->job_role_ids ?? [];
            $departmentMessages  = $job->department_ids ?? [];
            $branchMessages      = $job->branch_ids ?? [];
            $courseMessages      = $job->course_ids ?? [];
            $audienceMessages    = $job->audience_ids ?? [];
            $adminMessages       = $job->target_ids ?? [];
            $courseMessages      = $job->course_ids ?? [];
            $audience = '';
            $isCourseMessage = false;
            $unsubscribed = EmailUnsubscribe::pluck('email');

            $querys = User::query()
                ->with(['jobRole','completions','licenses'])
                ->from('twill_users')
                ->whereNull('twill_users.deleted_at')
                ->where('twill_users.email_counter', '<', $this->emailLimit)->get();

            $query = User::query()
                ->with(['jobRole','completions','licenses'])
                ->from('twill_users')
                ->whereNull('twill_users.deleted_at')
                ->where('twill_users.email_counter', '<', $this->emailLimit)
            ->when($unsubscribed->isNotEmpty(), function ($q) use ($unsubscribed) {
                        $q->whereNotIn('twill_users.email', $unsubscribed);
                    })
            // Company filter
            ->when($companyMessages, function ($q) use ($companyMessages) {
                $q->where('twill_users.company_id', $companyMessages);
            })

            // Grouped OR filters
            ->when(
                !empty($jobRoleMessages) || !empty($adminMessages),
                function ($q) use ($jobRoleMessages, $adminMessages) {
                    $q->where(function ($sub) use ($jobRoleMessages, $adminMessages) {
                        if (!empty($jobRoleMessages)) {
                            $sub->orWhereIn('twill_users.job_role_id', $jobRoleMessages); 
                        }
                        if (!empty($adminMessages)) {
                            $sub->orWhereIn('twill_users.role', $adminMessages); 
                        }
                    });
                }
            )

            // Audience filter
            ->when(!empty($audienceMessages), function ($q) use ($audienceMessages, &$audience, &$courseMessages, &$isCourseMessage) {
                $audience = array_first($audienceMessages);
                if (!empty($courseMessages)) {
                    //Ensure job role has ALL required courses
                    // $q->whereIn('course_id', function ($sub) use ($courseMessages) {
                    //     $sub->select('job_role_id')
                    //         ->from('courses')
                    //         ->whereIn('course_id', $courseMessages);
                    // });

                    $isCourseMessage = true;
                } else {
                    // fallback
                    $q->where('completion_status', $audience);
                }
                $audience = array_first($audienceMessages);
            });


            $subject = $job->subject;
            $title = $job->title;

            $msg = $job->message;
     
            $userd = $query->where('twill_users.email', 'like', '%@farwell-consultants.com%')->where('email', '!=','admin@farwell-consultants.com')->get();
            // $data = [
            //     'sender'  => 'Tower Sacco Academy',
            //     'subject' => $subject,
            //     'name'    => ucfirst($newUser->first_name) . ' ' . ucfirst($newUser->last_name),
            //     'msg'     => $msg,
            //     'title'   =>$title,
            //     'loginUrl' => 'https://academy.towersacco.co.ke',
            // ];
            // Mail::to($newUser->email)->send(new EmailForQueuing($data));
            //      sleep(1);
            // $this->info("Email sent to {$newUser->email}");

            foreach ($userd as $user) {
                $data = [
                    'sender'  => "Rwanda Bankers' Association",
                    'supportTeam'    => 'Farwell Support Team',
                    'subject' => "Successful Certification",
                    'jobRole' => $user->jobRole->title ?? '',
                    'name'    => ucfirst($user->first_name) . ' ' . ucfirst($user->last_name),
                    'recipient_email' =>'erick@farwell-consultants.com',
                    'msg'     => $msg,
                    'title'   =>$title,
                    'loginUrl' => 'https://rba.farwell-consultants.com/',
                ];
                //Handle completion mandatory status
                if($audience &&  $isCourseMessage){
                    $jobCourses = $user->completions()->whereIn('course_id', $courseMessages)->pluck('course_id')->unique()->values();
                    switch ($audience){
                        case 'Completed':
                            $courses = $user->completions()
                                ->whereIn('course_id', $courseMessages)
                                ->where('completion_status', $audience)
                                ->get();

                            if (!empty($courses)) {
                                $courses = $courses->map(function ($course) {
                                    $course->score = round($course->score * 100, 1);
                                    return $course;
                                });
                            }

                            $data['certificateUrl'] = route('profile.courses');
                            $data['totalCount'] = $courses->count();
                            $data['courses'] = $courses;
                            //send only if the courses list is not empty
                            if (!empty($courses)) {
                                Mail::to('jumaaerick@gmail.com')->send(new MessageForQueuingCoursesCompleted($data));
                                $user->increment('email_counter');
                            }
                            dd('hello');
                            break;
                        case 'Completed but Failed':
                            $courses = $user->completions()
                                ->whereIn('course_id', $jobCourses)
                                ->where('completion_status', $audience)
                                ->get();

                            if (!empty($courses)) {
                                $courses->map(function ($course) {
                                    $course->score = round($course->score * 100, 1);
                                    return $course;
                                });
                            }

                            $data['averageScore'] = round($user->average_score_overall*100, 1);
                            $data['totalCount'] = $courses->count();
                            $data['courses'] = $courses;
                            //send only if the courses list is not empty
                            if (!empty($courses)) {
                                Mail::to('jumaaerick@gmail.com')->send(new MessageForQueuingCoursesCompletedbutFailed($data));
                                $user->increment('email_counter');
                            }
                            dd('hello');
                            break;
                        case 'In Progress':
                            $data['totalCount'] = $user->completions->count();
                            $jobCourses = $user->completions()->whereIn('course_id', $courseMessages)->pluck('course_id')->unique()->values();
                            $licenses = $user->licenses()
                                ->whereIn('course_id', $jobCourses)
                                ->with(['course','completion' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }])
                                ->get()
                                ->filter(function ($license) use ($audience) {
                                    $completion = $license->completion;
                                    $status = $completion ? $completion->status : null;;
                                    return $status === $audience || is_null($status);
                                });

                            $data['completedCount'] = $user->completions()->whereIn('course_id', $courseMessages)->count();

                            $data['courses'] = $licenses->pluck('course')->filter()->values()->map(function ($course) {
                                return (object) [
                                    'id'           => $course->id,
                                    'display_name' => $course->display_name,
                                    'short_name' =>$course->short_name
                                ];
                            });

                            $data['progressPercent'] = ($data['totalCount'] > 0)
                                ? round(($data['completedCount'] / $data['totalCount']) * 100, 1)
                                : 0;

                            if (!empty($courses)) {
                                Mail::to('erick@farwell-consultants.com')->send(new MessageForQueuingCoursesInProgress($data));
                                $user->increment('email_counter');
                            }
                            dd('hapa');
                            break;

                        case 'Yet Started':
                            $doneEnrolled = $user->completions()->pluck('course_id')
                                    ->merge($user->licenses()->pluck('course_id'))
                                    ->unique()
                                    ->values();
                            $courses = $user->completions()->whereIn('course_id', $courseMessages)->pluck('course_id')->unique()->values();
                            $notDone = $courses->diff($doneEnrolled)->values();
                            $data['courses'] = Course::published()->orderBy('created_at')->whereIn('id', $notDone)->get(['display_name', 'short_name']);
                            $data['totalCount'] =  $data['courses']->count();
                            if (!empty($courses)) {
                                Mail::to('jumaaerick@gmail.com')->send(new MessageForQueuingCoursesYetStarted($data));
                                $user->increment('email_counter');
                            }
                            dd('hello');
                            break;
                        default:
                            dd('hapas');
                            'Yet Started';
                    }
                }
                else {
                    switch ($audience){
                        case 'Completed':
                            $data['certificateUrl'] = route('profile.certificateOverall', $user->id);
                            $data['totalCount'] = $user->completions->count();
                            $data['averageScore'] = round($user->average_score*100, 1);
                            $data['completedAt'] = $user->has_completion_date;
                            if ($user->average_score >= 0.8) {
                                Mail::to('erick@farwell-consultants.com')->send(new MessageForQueuingCompleted($data));
                                $user->increment('email_counter');
                            }
                            dd('hapad');
                            break;
                        case 'Completed but Failed':
                            $data['averageScore'] = round($user->average_score*100, 1);
                            if ($user->average_score >= 0.2) {
                                Mail::to('erick@farwell-consultants.com')->send(new MessageForQueuingCompletedbutFailed($data));
                                $user->increment('email_counter');
                            }
                            dd('hapad');
                            break;
                        case 'In Progress':
                            $data['totalCount'] = $user->completions->count();
                            $courseIds = $user->completions->pluck('course_id')->unique();

                            $completedCourseIds = $user->completions
                                ->whereIn('course_id', $courseIds)
                                ->where('completion_status', '!=', 'In Progress')
                                ->pluck('course_id')
                                ->unique();

                            $data['completedCount'] = $completedCourseIds->count();

                            $pendingCourseIds = $courseIds->diff($completedCourseIds);

                            $pendingModules = $user->completions
                                ->whereIn('course_id', $pendingCourseIds);

                            $data['pendingModules'] = \App\Models\Course::published()->orderBy('created_at')->whereIn('id', $pendingModules->pluck('course_id'))->get(['short_name', 'display_name', 'course_id']);

                            $data['pendingCount'] = $pendingModules->count();
                            $data['remainingCount'] = $pendingModules->count();
                            $data['progressPercent'] = round(($data['completedCount'] / $data['totalCount'])*100, 1);
                            if ($completedCourseIds) {
                                Mail::to('erick@farwell-consultants.com')->send(new MessageForQueuingInProgress($data));
                                $user->increment('email_counter');
                            }
                            dd('hapa');
                            break;

                        case 'Yet Started':
                            $courses = Course::published()->orderBy('created_at')->get(['display_name', 'short_name']);
                            $data['totalCount'] = $courses->count();
                            // $courseIds = $user->completions->pluck('course_id')->unique();
                            $data['courses'] = $courses;
                            if ($courses) {
                                Mail::to('erick@farwell-consultants.com')->send(new MessageForQueuingYetStarted($data));
                                $user->increment('email_counter');
                            }
                             dd('hapas');
                            break;
                        default:
                            dd('hapas');
                            'Yet Started';
                    }


                }

                // Mail::to('jumaaerick@gmail.com')->send(new EmailForQueuing($data));
                 sleep(1);
                $this->info("Email sent to {'jumaaerick@gmail.com'}");
            }

                if ($job->is_recurrent) {
                    $timestamp = $job->send_date;  
                    $date = Carbon::parse($timestamp);

                    if ($job->recurrent_frequency == 30) {
                        // Treat 30 as monthly recurrence
                        $nextRun = $date->copy()->addMonthNoOverflow();
                    } else {
                        // Strict day-based recurrence
                        $nextRun = $date->copy()->addDays($job->recurrent_frequency);
                    }

                    $job->send_date = $nextRun;
                    $job->next_run = $nextRun; 
                    $job->save();
                } else {
                    $job->sent_status = 1;
                    $job->save();
                }
                $this->info("All emails have been sent.");

        }

              $this->info("All emails have been sent.");

    }
}
