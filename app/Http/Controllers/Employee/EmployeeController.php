<?php

namespace App\Http\Controllers\Employee;

use App\Enums\Status;
use App\Enums\StatusCaseInSection;
use App\Enums\TypeCourt;
use App\Enums\TypeOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\InterestEmployeeRequest;
use App\Http\Requests\ShowInterestRequest;
use App\Http\Resources\Employee\InterestEmployeeResource;
use App\Http\Resources\Lawyer\DecisionOrderResource;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\Court;
use App\Models\CourtType;
use App\Models\DecisionOrder;
use App\Models\Dewan;
use App\Models\DewanOrder;
use App\Models\Interest;
use App\Models\JudgeSection;
use App\Models\order;
use App\Models\PowerOfAttorney;
use App\Models\Win;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function showInterestInMyCourt(){
        try{
            $user = Auth::user()->user;
            $dewan = Dewan::where('user_id',$user->id)->first();
            
            $interests = Interest::whereHas('case.now',function ($q)use($dewan){
                    $q->where('court_type_id',$dewan->court_type_id);
                })
                ->with(['user','case'])
                ->get();
            
            $result = InterestEmployeeResource::collection($interests);
            return $this->sendResponse($result,'كل الاهتمامات');
            
        }catch(Exception $e){
            return $this->sendError('خطأ في ارجاع النتيجة ' .$e->getMessage());
        }
    }

    public function showInterestInMyCourtByName(ShowInterestRequest $request){
        try{
            $user = Auth::user()->user;
            $dewan = Dewan::where('user_id',$user->id)->first();
            
            $interests = Interest::whereHas('case.now',function ($q)use($dewan){
                    $q->where('court_type_id',$dewan->court_type_id);
                })
                ->whereIf('first_name' , $request->first_name)
                ->whereIf('last_name' , $request->last_name)
                ->with(['user','case'])
                ->get();
            
            $result = InterestEmployeeResource::collection($interests);
            return $this->sendResponse($result,'كل الاهتمامات');
            
        }catch(Exception $e){
            return $this->sendError('خطأ في ارجاع النتيجة ' .$e->getMessage());
        }
    }

// makeInteresterAdmin
    public function makeInteresterAdmin(InterestEmployeeRequest $request){
        try{
            $interest = Interest::where('id',$request->interest_id)->first();

            $check_admin = Interest::where('case_id',$interest->case_id)
                            ->where('is_admin',true)->exists();

            if($check_admin)
                return $this->sendError('لا يمكن اعطاء الصلاحية لاكثر من شخص ');

            $interest->is_admin = true ;
            $result = $interest->save();
            
            if($result)
                return $this->sendOkResponse('تم وضعك كمسؤول');
        }catch(Exception $e){
            return $this->sendError('خطأ في تنفيذ امر ' .$e->getMessage());
        }
    }

    public function cancelInteresterAdmin(InterestEmployeeRequest $request){
        try{
            $result = Interest::where('id',$request->interest_id)
            ->update(['is_admin'=>false]);
            if($result)
                return $this->sendOkResponse('تم الغائك مسؤول');
        }catch(Exception $e){
            return $this->sendError('خطأ في تنفيذ امر ' .$e->getMessage());
        }
    }
// 9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999
    // showAllOrder
    public function showAllOrder(){
        try{

            $user = Auth::user()->user;
            $dewan = Dewan::where('user_id',$user->id)->first();
            
            $order = DecisionOrder::whereHas('decision.case' , function ($q)use($dewan){
                $q->where('court_type_id',$dewan->court_type_id);
            })
            ->with('decision.caseDocs')
            ->get();

            return $order ;
            $result = DecisionOrderResource::collection($order);
            return $this->sendResponse($result);

        }catch(Exception $e){
            return $this->sendError('خطأ في تنفيذ امر ' .$e->getMessage());
        }
    }

    // عرض الطلبات المعالجة
    // showProcessedOrder
    public function showProcessedOrder(){
        try{
            $user = Auth::user()->user;
            $dewan = Dewan::where('user_id',$user->id)->first();
            
            $order = DecisionOrder::whereHas('processOrder',function ($q)use($dewan){
                    $q->where('dewan_id', $dewan->id);
                })
                ->with('decision.caseDocs')
                ->get();
            $result = DecisionOrderResource::collection($order);
            return $this->sendResponse($result);

        }catch(Exception $e){
            return $this->sendError('خطأ في تنفيذ امر ' .$e->getMessage());
        }
    }

    public function okOrder($order){
        $order->status_order = Status::APPROVED->value ;
        $order->response_date = Carbon::now()->format('Y-m-d');
        return $order->save();
    }



    public function moveCase($case,$order_type){

        if($order_type == TypeOrder::CU->value )
            $to = TypeCourt::CU->value;
        else if($order_type == TypeOrder::NG->value )
            $to = TypeCourt::NG->value;
        else if($order_type == TypeOrder::EM->value )
            $to = TypeCourt::EM->value;
        else $to = TypeCourt::ST->value;

        try{
            $typeId = CourtType::where('type_form',$to)
            ->whereHas('court' , function ($q)use($case){
                $q->where('id',$case->courtType->court_id);
            })->value('id');
        
            $sectionId  = $this->getSectionByRandom($typeId);
            
            $case->exist_now = $sectionId ;
            $case->court_type_id = $typeId ;
            $move = $case->save();

            if(!$move)
                return $this->sendError('لم يتم تحريك القضية');

            CaseJudge::create([
                        'case_id' => $case->id ,
                        'judge_section_id' => $sectionId ,
                        'status' => StatusCaseInSection::OPEN->value,
                    ]);

            $attorneys = PowerOfAttorney::where('case_id',$case->id)
                        ->get();
            
            foreach($attorneys as $attorney){
                Win::create([
                    'court_type_id' => $typeId ,
                    'attorney_id' =>$attorney->id ,
                    'get' => 'yet',
                ]);
            }
            return $to;
        }catch(Exception $e){
            return throw new Exception('خظأ في تحريك الدعوى');
        }
        
        
    }

    private function getSectionByRandom($typeId){
        $sectionIds = JudgeSection::where('court_type_id',$typeId)->pluck('id');
        
        if ($sectionIds->isNotEmpty())
            return Arr::random($sectionIds->toArray());
        
        return throw new Exception('لا يوجد قضاة في الاقسام') ;
    }


    // MakeOkDecisionOrder
    public function MakeOkDecisionOrder($order_id){
        DB::beginTransaction();
        try{
            
            $order = DecisionOrder::where('id',$order_id)->first();
            
            if(!$order)
            return $this->sendError('لا يوجد طلب بهذا التعريف');
            // الموافقة على الطلب 
            $result = $this->okOrder($order); 

            if(!$result)        
            $this->sendError('لم يتم الموافقة على الطلب');
         

            //نقل القضيةالى محكمة         
            $case =  $order->decision->case;
            

            $to = $this->moveCase($case,$order->type_order->value);  
            DB::commit();
            return $this->sendOkResponse(' تم قبول الطلب وتحويل القضية الى محكمة  '.$to);

        }catch(Exception $e){
            DB::rollBack();
            return $this->sendError('خطأ في تنفيذ امر ' .$e->getMessage());
        }
    }

    // cancelOkDecisionOrder
    public function cancelOkDecisionOrder($order_id)
    {
        try{            
            $order = DecisionOrder::where('id',$order_id)->first();
            if($order->status_order != Status::PENDING)
                return $this->sendError('الطلب معالج لا يمكن الغائه');

            $order->status_order = Status::PENDING ->value;
            $data = $order->save();

            if(!$data)
                return $this->sendError('لم يتم الغاء الطلب');
            return $this->sendOkResponse(' تم الغاء الطلب');
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في تأكيد طلب التوكيل'
            );
        }
    }








}
