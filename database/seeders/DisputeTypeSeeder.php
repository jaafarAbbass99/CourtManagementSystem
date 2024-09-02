<?php

namespace Database\Seeders;

use App\Models\DisputeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisputeTypeSeeder extends Seeder
{

    public function run(): void
    {
        DisputeType::create(['name' => 'عقدية']);
        DisputeType::create(['name' => 'أسرية']);
        DisputeType::create(['name' => 'عقارية']);
        DisputeType::create(['name' => 'تجارية']);
    }
}
