<?php

namespace Database\Seeders;

use App\Enums\ReqDocs;
use App\Enums\Role;
use App\Models\RequiredIdeDoc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequiredDocumentsSeeder extends Seeder
{
    
    public function run(): void
    {
                // وثائق خاصة بالمحامي
                RequiredIdeDoc::create([
                    'req_doc' => ReqDocs::LICENSE_LW->value ,
                    'for' => Role::LAWYER->value ,
                ]);
        
                // وثائق خاصة بالقاضي
                RequiredIdeDoc::create([
                    'req_doc' => ReqDocs::APPOINTMENT_JD->value ,
                    'for' =>Role::JUDGE->value ,
                ]);
        
                // وثائق مشتركة
                RequiredIdeDoc::create([
                    'req_doc' => ReqDocs::IDCARD->value ,
                    'for' => Role::LAWYER->value ,
                ]);
        
                RequiredIdeDoc::create([
                    'req_doc' => ReqDocs::IDCARD->value ,
                    'for' => Role::JUDGE->value ,
                ]);
    }
}
