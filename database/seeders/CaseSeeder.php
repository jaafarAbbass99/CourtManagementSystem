<?php

namespace Database\Seeders;

use App\Models\CaseJudge;
use App\Models\Cases;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Cases::create([
        //     'party_one' => 'محمد كمال',
        //     'party_two' => 'فؤاد محمد', 
        //     'subject' => 'sss',
        //     'dispute_type_id' => 1 ,
        //     'case_type_id' => 1,
        // ]);


        // Cases::create([
        //     'party_one' => ' كمال',
        //     'party_two' => ' محمد', 
        //     'subject' => 'sss',
        //     'dispute_type_id' => 1 ,
        //     'case_type_id' => 1,
        // ]);

        
        // Cases::create([
        //     'party_one' => ' كمال',
        //     'party_two' => ' محمد', 
        //     'subject' => 'sss',
        //     'dispute_type_id' => 1 ,
        //     'case_type_id' => 2,
        // ]);


        // CaseJudge::create([
        //     'case_id' => 1 ,
        //     'judge_section_id' => 2 ,
        //     'status' => 'resolved' ,
        // ]);


    }
}
