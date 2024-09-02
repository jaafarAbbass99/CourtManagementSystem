<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\CourtType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    public function run()
    {

        $courts = [
            ['name' => 'محكمة مدنية', 'province' => 'دمشق'],
            ['name' => 'محكمة جزائية', 'province' => 'دمشق'],
            ['name' => 'محكمة جزائية', 'province' => 'حلب'],
            ['name' => 'محكمة مدنية', 'province' => 'حلب'],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}
