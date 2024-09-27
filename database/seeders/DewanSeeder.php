<?php

namespace Database\Seeders;

use App\Models\Dewan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DewanSeeder extends Seeder
{

    public function run(): void
    {
        $users = User::where('first_name' , 'موظف ديوان')->get() ;

        foreach($users as $user){
            if($user->last_name === 'البداية')
                Dewan::create([
                    'name' => 'ديوان البداية',
                    'user_id' => $user->id ,  
                    'court_type_id' => 1, 
                ]);
                // 49|liAC99rFaHKLby7D06825BUO7zQ3WUkwBFoVOD772c12ad08
            if($user->last_name === 'الاستئناف')
                Dewan::create([
                    'name' => 'ديوان الاستئناف',
                    'user_id' => $user->id,  
                    'court_type_id' => 2,  
                ]);
            if($user->last_name === 'النقض')    
                Dewan::create([
                    'name' => 'ديوان النقض',
                    'user_id' => $user->id, 
                    'court_type_id' => 3,  
                ]);
            if($user->last_name === 'التنفيذ')    
                Dewan::create([
                    'name' => 'ديوان التنفيذ',
                    'user_id' => $user->id, 
                    'court_type_id' => 4, 
                ]);
                
        }

    }
}
