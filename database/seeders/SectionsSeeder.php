<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    public function run(): void
    {
        $courts = Court::all();

        foreach($courts as $court){
            for($i = 1 ; $i < 8 ; $i++){
                Section::create([
                    'court_id' => $court->id,
                    'section_number' => $i,
                    'location' => $court->city
                ]);
            }
        }   
    }
}
