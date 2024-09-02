<?php

namespace App\Services\Lawyer;

use App\Models\LawyerCourt;

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
}