<?php

namespace App\Observers;

use App\Models\Session;
use Illuminate\Support\Facades\Log;

class SessionObserver
{

    public function created(Session $session): void
    {
        
        $lastSessionNumber  = Session::where('case_judge_id', $session->case_judge_id)
        ->max('session_number');    
            
        $session->session_number = $lastSessionNumber == 0 ? 1 : $lastSessionNumber + 1;
        $session->save();
    }

    /**
     * Handle the Session "updated" event.
     */
    public function updated(Session $session): void
    {
        //
    }

    /**
     * Handle the Session "deleted" event.
     */
    public function deleted(Session $session): void
    {
        //
    }

    /**
     * Handle the Session "restored" event.
     */
    public function restored(Session $session): void
    {
        //
    }

    /**
     * Handle the Session "force deleted" event.
     */
    public function forceDeleted(Session $session): void
    {
        //
    }
}
