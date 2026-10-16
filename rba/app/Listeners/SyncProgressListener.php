<?php

namespace App\Listeners;

use App\Events\CourseProgressSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SyncProgressListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    #[ListensTo(CourseProgressSync::class)]
    public function handle(CourseProgressSync $event): void
    {
        foreach ($event->courseIds as $courseId) {
            app(\Front\UserLicenseController::class)->getSingleProgress($event->email, $courseId);
        }
    }
        
}
