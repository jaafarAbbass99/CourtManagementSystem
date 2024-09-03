<?php

namespace App\Services\Lawyer;

use App\Models\LawyerCourt;
use Exception;
use Illuminate\Support\Facades\Auth;

class LawyerService
{
    public function joinCourt($userId, $courtId, $assignedDate = null)
    {
        $data = LawyerCourt::create([
            'user_id' => $userId,
            'court_id' => $courtId,
            'assigned_date' => $assignedDate,
        ]);

        return $data ;
    }

    public function getMyCourts()
    {
        return LawyerCourt::where('user_id',Auth::user()->user->id)
                ->with('court')
                ->get();
    }
}