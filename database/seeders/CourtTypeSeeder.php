<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\CourtType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dam_ = Court::where('name','محكمة مدنية')
                    ->where('province','دمشق')->first()->id;

        $alp_ = Court::where('name','محكمة مدنية')
                    ->where('province','حلب')->first()->id;
                    
        CourtType::create(['type' => 'بدائية', 'type_form' => 'ST', 'city' => 'المزة' ,'rank'=>1,'court_id'=>$dam_ ]);
        CourtType::create(['type' => 'استئناف', 'type_form' => 'CU', 'city' => 'المزة' ,'rank'=>2, 'court_id'=>$dam_ ]);
        CourtType::create(['type' => 'نقض', 'type_form' => 'NG', 'city' => 'المزة' ,'rank'=>3, 'court_id'=>$dam_ ]);
        CourtType::create(['type' => 'بدائية', 'type_form' => 'ST', 'city' => 'الحمدانية' ,'rank'=>1 , 'court_id'=>$alp_]);
        CourtType::create(['type' => 'استئناف', 'type_form' => 'CU', 'city' => 'الحمدانية' ,'rank'=>2, 'court_id'=>$alp_ ]);
        CourtType::create(['type' => 'تنفيذ', 'type_form' => 'Em', 'city' => 'المزة' ,'rank'=>4 , 'court_id'=>$dam_]);
    }
}
