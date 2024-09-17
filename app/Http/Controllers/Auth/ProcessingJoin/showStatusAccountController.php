<?php

namespace App\Http\Controllers\Auth\ProcessingJoin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class showStatusAccountController extends Controller
{
    public function showStatusAccount(){
        try{
            $status = Account::where('id' , Auth::user()->user->id)
                ->value('status');
    
            return $this->sendResponse([
                'status'=> __('status.'.$status)
            ]);

        }catch(Exception $e){
            return $this->sendError('لا يوجد حساب'.$e->getMessage());
        }
    }
}
