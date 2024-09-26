<?php

namespace App\Http\Controllers;

use App\Enums\Party;
use App\Enums\Representing;
use App\Enums\Status;
use App\Http\Requests\AddFilesToCaseRequest;
use App\Http\Requests\CaseJudgeIdRequest;
use App\Http\Requests\Lawyer\DeleteDecisionOrderRequest;
use App\Http\Requests\Lawyer\ShowCasesByStatusRequest;
use App\Http\Requests\Lawyer\ShowDetailsCaseRequest;
use App\Http\Requests\Lawyer\StoreDecisionOrderRequest;
use App\Http\Requests\OpenCaseByLawyerRequest;
use App\Http\Requests\ShowCaseByDetailsRequest;
use App\Http\Requests\StatusCaseCloseOpenRequest;
use App\Http\Requests\User\Add\AddAttorneyOrderRequest;
use App\Http\Requests\User\Add\AddDefenseOrderRequest;
use App\Http\Requests\User\Add\cancelAttorneyOrderRequest;
use App\Http\Requests\User\Add\cancelDefenseOrderRequest;
use App\Http\Requests\User\Add\OkAttorneyOrderRequest;
use App\Http\Requests\User\Add\OkDefenseOrderRequest;
use App\Http\Resources\AttorneyOrderResource;
use App\Http\Resources\AttorneysCaseResource;
use App\Http\Resources\Cases\CasesResources;
use App\Http\Resources\DefenseOrderResource;
use App\Http\Resources\InterestResource;
use App\Http\Resources\Judge\Show\CaseDocResource;
use App\Http\Resources\Judge\Show\SessionsCaseResource;
use App\Http\Resources\Lawyer\AttorneyOrderResource as LawyerAttorneyOrderResource;
use App\Http\Resources\Lawyer\CaseInSectionResource as LawyerCaseInSectionResource;
use App\Http\Resources\Lawyer\DecisionOrderResource;
use App\Http\Resources\Lawyer\DefenseOrderResource as LawyerDefenseOrderResource;
use App\Http\Resources\Lawyer\MyAttorneyResource;
use App\Http\Resources\Lawyer\SessionWithSectionResource;
use App\Http\Resources\Lawyer\ShowDetailsCaseResource;
use App\Http\Resources\OrderAttorneyResource;
use App\Models\AttorneyOrders;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\defenseOrder;
use App\Models\Interest;
use App\Models\JudgeSection;
use App\Models\LawyerCourt;
use App\Models\order;
use App\Models\PowerOfAttorney;
use App\Services\CaseService;
use Exception;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use LDAP\Result;

class CaseController extends Controller
{
    
    protected $caseService;

    public function __construct(CaseService $caseService)
    {
        $this->caseService = $caseService;
    }

    // فتح دعوى جديدة
    public function openCase(OpenCaseByLawyerRequest $request)
    {
        try{
            
            $data = $this->caseService->openCase($request->all());
            $result = SessionWithSectionResource::make($data);
            if($data)
                return $this->sendResponse($result,'تم فتح الدعوى بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في فتح دعوى'
            );
        }
    }

    // فتح دعوى لتوكيل 
    public function openCaseForAttorney(OpenCaseByLawyerRequest $request)
    {
        try{
            $data = $this->caseService->openCaseForAttorney($request->all());
            $result = SessionWithSectionResource::make($data);
            if($data)
                return $this->sendResponse($result,'تم فتح الدعوى بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في فتح دعوى'
            );
        }
    }

    // addFilesToCase
    public function addFilesToCase(AddFilesToCaseRequest $request)
    {
        try{
            $data = $this->caseService->addFilesToCase($request);
            if($data)
                return $this->sendOKResponse('تم اضافة الملفات بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اضافة الملفات'
            );
        }
    }

    // عرض ملفات الدعوى 
    // showFilesCase
    public function showMyFilesCase($case_id)
    {
        try{
            $data = $this->caseService->getFilesCase($case_id,Auth::user()->user->id);
            $result = CaseDocResource::collection($data);
            return $this->sendResponse($result,'ملفاتي للقضية');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار الملفات'
            );
        }
    }

    // اظهار ملفات القاضي او المحامي التي رفعها لقضية ما
    public function showFilesCaseByUser($case_id,$user_id)
    {
        try{
            $data = $this->caseService->getFilesCase($case_id,$user_id);
            $result = CaseDocResource::collection($data);
            return $this->sendResponse($result,'الملفات');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار الملفات'
            );
        }
    }

    

    public function showFilesMyLawyerCase($case_id)
    {
        try{
            $party = Interest::where('case_id',$case_id)
                ->where('user_id',Auth::user()->user->id)
                ->value('party');
            $my_lawyer_id = LawyerCourt::whereHas('cases',function ($q)use($case_id,$party){
                        $q->where('case_id',$case_id)
                        ->where('representing',$party);
                })->value('user_id');

            $data = $this->caseService->getFilesCase($case_id,$my_lawyer_id);

            $result = CaseDocResource::collection($data);

            return $this->sendResponse($result,'الملفات');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار الملفات'
            );
        }
    }


    //عرض ملفات الخصم 
    public function showFilesOppLawyerCase($case_id)
    {
        try{
            $party = Interest::where('case_id',$case_id)
                ->where('user_id',Auth::user()->user->id)
                ->value('party');
            $my_lawyer_id = LawyerCourt::whereHas('cases',function ($q)use($case_id,$party){
                        $q->where('case_id',$case_id)
                        ->where('representing','!=',$party);
                })->value('user_id');

            $data = $this->caseService->getFilesCase($case_id,$my_lawyer_id);

            $result = CaseDocResource::collection($data);

            return $this->sendResponse($result,'الملفات');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار الملفات'
            );
        }
    } 


    // عرض ملفات القاضي لدعوى  مهتم بها 
    public function showFilesJudgeCaseByCourt($case_id,$court_type_id)
    {
        try{
            $judge_id = JudgeSection::where('court_type_id',$court_type_id)
                        ->whereHas('cases',function ($q) use($case_id){
                            $q->where('case_id',$case_id);
                        })->value('user_id');
                
            $data = $this->caseService->getFilesCase($case_id,$judge_id);

            $result = CaseDocResource::collection($data);

            return $this->sendResponse($result,'الملفات');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار الملفات'
            );
        }
    }

    // showDefenseOrdersInterestCase
    // اظهار طلبات الدفاع لدعوى ما 
    public function showDefenseOrdersInterestCase($case_id)
    {
        try{
            $data = $this->caseService->getDefenseOrdersinterestsCase($case_id);
            $result = DefenseOrderResource::collection($data);
            return $this->sendResponse($result,'طلبات الدفاع');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار طلبات الدفاع'
            );
        }
    }

    //showAttorneyOrdersInterestCase
    // لدعوى ما اظهار طلبات التوكيل   
    public function showAttorneyOrdersInterestCase($case_id)
    {
        try{
            $data = $this->caseService->getAttorneyOrdersinterestsCase($case_id);
            $result = DefenseOrderResource::collection($data);
            return $this->sendResponse($result,'طلبات التوكيل');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار طلبات التوكيل'
            );
        }
    }

    //showAttorneyOrders
    // اظهار طلبات التوكيل   
    public function showAttorneyOrders()
    {
        try{
            $data = $this->caseService->getAttorneyOrders(Auth::user()->user->id);
            $result = DefenseOrderResource::collection($data);
            return $this->sendResponse($result,'طلبات التوكيل');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اظهار طلبات التوكيل'
            );
        }
    }
    

    
    // addDefenseOrder
    //اضافة طلب دفاع  
    public function addDefenseOrder(AddDefenseOrderRequest $request)
    {
        try{
            if(Gate::denies('isAbleAttorneys',$request->case_id))
                return $this->sendError('ليس لديك صلاحية توكيل');

            if(Gate::denies('isIRepresentForCase',[$request->case_id,Party::PARTY_TWO]))
                return $this->sendError('لا تمثل الطرف الثاني');

            $data = $this->caseService->addDefenseOrder($request->case_id,$request->lawyer_id);
            if($data)
                return $this->sendOKResponse('تم اضافة الطلب بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اضافة طلب الدفاع'
            );
        }
    }


    // ddAttorneyOrder
    // اضافة طلب توكيل للمحامي
    public function addAttorneyOrder(AddAttorneyOrderRequest $request)
    {
        try{
            $data = $this->caseService->addAttorneyOrder(Auth::user()->user->id,$request->lawyer_id,$request->court_id);
            if($data)
                return $this->sendOKResponse('تم اضافة الطلب بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اضافة طلب التوكيل'
            );
        }
    }
    
    // oKAttorneyOrder
    // تأكيد طلب دفاع لاستلامها المحامي
    public function oKDefenseOrder(OkDefenseOrderRequest $request)
    {
        try{
            $order = order::where('id',$request->order_id)
                ->with('orderable')
                ->first();
            if($order->status_order->value != Status::APPROVED->value)
                return $this->sendError('الطلب '.__('status.'.$order->status_order->value));

            $interest = Interest::whereHas('defenseOrders',function ($q)use($order){
                $q->where('interest_id',$order->orderable->interest_id);
            })->first();
            
            if($interest->party = 2)
                $rep = Representing::PARTY_TWO->value;
            else
                $rep = Representing::PARTY_ONE->value;

            $data = $this->caseService->oKAttorneyOrder([
                'lawyerCourt_id' => $order->lawyer_court_id,
                'case_id' => $interest->case_id,
                'representing' => $rep ,
                'order_id' => $order->id
            ]);

            if($data)
                return $this->sendOKResponse('تم تأكيد الطلب بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في تأكيد طلب الدفاع'
            );
        }
    }

    // oKAttorneyOrder
    // تأكيد طلب توكيل لاستلامها المحامي
    public function oKAttorneyOrder(OkAttorneyOrderRequest $request)
    {
        try{
            $order = order::where('id',$request->order_id)
                ->first();
            if($order->status_order->value != Status::APPROVED->value)
                return $this->sendError('الطلب '.__('status.'.$order->status_order->value));    
            
            $data = $this->caseService->oKAttorneyOrder([
                'lawyerCourt_id' => $order->lawyer_court_id,
                'representing' => Representing::PARTY_ONE->value,
                'order_id' => $order->id,
            ]);

            if($data)
                return $this->sendOKResponse('تم تأكيد الطلب بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في تأكيد طلب التوكيل'
            );
        }
    }


    // cancelDefenseOrder
    //الغاء طلب الدفاع
    public function cancelDefenseOrder(cancelDefenseOrderRequest $request)
    {
        try{
            $data =$this->caseService->cancelDefenseOrder($request->order_id);
            if(!$data)
                return $this->sendError('لم يتم الغاء الطلب');
            return $this->sendOkResponse(' يتم الغاء الطلب');
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في تأكيد طلب الدفاع'
            );
        }
    }

    // cancelAttorneyOrder
    // الغاء طلب التوكيل
    public function cancelAttorneyOrder(cancelAttorneyOrderRequest $request)
    {
        try{            
            $data =$this->caseService->cancelAttorneyOrder(Auth::user()->user->id,$request->order_id);
            if(!$data)
                return $this->sendError('لم يتم الغاء الطلب');
            return $this->sendOkResponse(' تم الغاء الطلب');
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في تأكيد طلب التوكيل'
            );
        }
    }


    // عرض الدعاوى التابعة له في محكمة محددة
    public function showCasesInCourt($court_id)
    {
        $cases = $this->caseService->getCasesInCourt($court_id);

        $result =  CasesResources::collection($cases);
        
        return $this->sendResponse($result);
    }

    // عرض الدعاوى التابعة له في كل المحاكم
    public function showAllCases()
    {
        $cases = $this->caseService->getAllCases(auth()->user()->user->id);
        
        $result =  CasesResources::collection($cases);
        return $this->sendResponse($result,'جميع الدعاوى في كل محاكمي');
    }

    // showMyReceivedAttorney
    // اظهار كل توكيلاتي المستلمة
    public function showMyReceivedAttorney()
    {
        $attorneys = $this->caseService->getAllAttorney(auth()->user()->user->id);
        $result =  MyAttorneyResource::collection($attorneys);
        return $this->sendResponse($result,'جميع توكيلاتي في كل محاكمي');
    }

    // اظهار كل توكيلاتي المستلمة في محكمة ما
    public function showMyReceivedAttorneyInCourt($court_id)
    {
        $attorneys = $this->caseService->getAllAttorneyInCourt(auth()->user()->user->id,$court_id);
        $result =  MyAttorneyResource::collection($attorneys);
        return $this->sendResponse($result,'جميع توكيلاتي في محكمة');
    }

    // عرض دعوى معينة حسب اسم المدعي والعام والمحكمة
    public function showCaseByDetails(ShowCaseByDetailsRequest $request)
    {
        $case = $this->caseService->getCaseByDetails($request->party_one, $request->court_id, $request->year);
        if (!$case) {
            return response()->json(['message' => 'لم يتم العثور على الدعوى.'], 404);
        }
        $result =  CasesResources::collection($case);

        return $this->sendResponse($result);
    }

    // عرض دعوى حسب رقمها
    public function showCaseByNumber($number_case)
    {
        $case = $this->caseService->getCaseByNumber($number_case);
        
        if (!$case) {
            return response()->json(['message' => 'لم يتم العثور على الدعوى.'], 404);
        }
        $result =  CasesResources::make($case);

        return $this->sendResponse($result , 'الدعوى');
    }

    public function showCasesInCourtByStatus(ShowCasesByStatusRequest $request)
    {
        try{

            $data = $this->caseService->getCasesInCourtByStatus($request->only('court_id','type_court','status'));

            if($data->isEmpty())
                return $this->sendOkResponse('لايوجد نتائج لعرضها');

            $result = CasesResources::collection($data);
            return $this->sendResponse($result,'كل الدعاوى');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showCountCasesInCourtByStatus(ShowCasesByStatusRequest $request)
    {
        try{
            $data = $this->caseService->getCountCasesInCourtByStatus($request->only('my_court_id','type_court','status'));

            return $this->sendResponse(['count'=>$data],'عدد الدعاوى');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // اظهار القضية في اي قسم وعند اي محامي والجلسات
    public function showDetailsCase(ShowDetailsCaseRequest $request){
        try{
            $data = $this->caseService->getDetailsCase($request->only('case_id','court_type_id'));

            if(!$data)
                return $this->sendOkResponse('لايوجد نتائج لعرضها');
            // return $data ;
            $result = ShowDetailsCaseResource::make($data);
            return $this->sendResponse($result,'تفاصيل  الدعوى  ');
            
            $result = LawyerCaseInSectionResource::make($data);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    public function showCasesCloseOrOpenWithDetails($status_case){
        try{

            $data = $this->caseService->getCasesByStatusInSection($status_case, Auth::user()->user->id);

            if($data->isEmpty())
                return $this->sendOkResponse('لايوجد نتائج لعرضها');

            $result = LawyerCaseInSectionResource::collection($data);
            return $this->sendResponse($result,'الدعاوى حسب حالتها ضمن القسم  ');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showSessionsCase($case_id , $case_judge_id )
    {
        try{

            $data = $this->caseService->getSessionsCase($case_id,$case_judge_id);

            $result = SessionsCaseResource::collection($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // عرض التوكيلات مع المحاميين
    // showAttorneysCase
    public function showAttorneysCase($case_id)
    {
        try{

            $data = $this->caseService->getAttorneysCase($case_id);
            $result = AttorneysCaseResource::collection($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // showInterestes
    // عرض الاهتمامات 
    public function showInterestes()
    {
        try{

            $data = $this->caseService->getInterestes(Auth::user()->user->id);
            $result = InterestResource::collection($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    public function getCasesSummary()
    {
        try {
            $data = $this->caseService->getStatisticsCasesByCourtTypeAndStatus(Auth::user()->user->id);

            if ($data->isEmpty()) {
                return $this->sendOkResponse('لايوجد نتائج لعرضها');
            }
            
            return $this->sendResponse($data,'نتائج احصائية ');

            return $cases;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addDecisionOrder(StoreDecisionOrderRequest $request){
        try{

            $this->caseService->addDecisionOrder($request->only('decision_id','type_order'));
            return $this->sendOKResponse('تم اضافة الطلب بنجاح.');
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),': خطأ في اضافة الطلب'
            );
        }
    }

    
    
    // showDecisionOrder
    public function showDecisionOrder($decision_order_id){
        try{

            $data = $this->caseService->getDecisionOrder($decision_order_id);
            if(!$data)
                return $this->sendError('لا يوجد طلب');
            
            $result = DecisionOrderResource::make($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // showAllOrder
    public function showAllOrder(){
        try{

            $data = $this->caseService->getAllOrder(Auth::user()->user->id);
            $result = DecisionOrderResource::collection($data);

            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // deleteOrder
    public function deleteOrder(DeleteDecisionOrderRequest $request){
        try{
            if(Gate::denies('isOrderPending',$request->order_id))
                return $this->sendError('لا يمكن حذف الطلب');

            $data = $this->caseService->deleteOrder(Auth::user()->user->id,$request->order_id);
            if($data)
                return $this->sendOKResponse('تم حذف الطلب بنجاح.');

        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),': خطأ في اضافة الطلب'
            );
        }
    }

     // عرض طلبات التوكيل
    public function showAttOrders(){
        try{
            $orders = order::where('orderable_type',AttorneyOrders::class)
            ->whereHas('lawyerUser',function ($q) {
                $q->where('user_id',Auth::user()->user->id);
            })->with('requester')
            ->get();
            return LawyerAttorneyOrderResource::collection($orders); 
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'الطلبات: '
            );
        }
    }
     // عرض طلبات الدفاع
     public function showDefOrders(){
        try{
            $orders = order::where('orderable_type',defenseOrder::class)
            ->whereHas('lawyerUser',function ($q) {
                $q->where('user_id',Auth::user()->user->id);
            })->with('interest.case')
            ->get();
            return LawyerDefenseOrderResource::collection($orders); 
            return $orders ;
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'الطلبات: '
            );
        }
    }

    // قبول الطلب التوكيل
    public function acceptPowAttOrder($order_id){
        try{
            $order = order::where('id',$order_id)
                    ->where('status_order' , Status::REJECTED->value)
                    ->exists();
            if($order)
                return $this->sendError('الطلب مرفوض');

            $result = order::where('id',$order_id)
                ->update(['status_order'=>Status::APPROVED->value]);
            if(!$result)
                return $this->sendError('لم يتم قبول الطلب');
            
            return $this->sendError('لم تم قبول الطلب');

        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في قبول الطلب '
            );
        }
    }
    public function rejectPowAttOrder($order_id){
        try{
            $order = order::where('id',$order_id)
                    ->where('status_order' , Status::APPROVED->value)
                    ->exists();
            if($order)
                return $this->sendError('الطلب مقبول');

            $result = order::where('id',$order_id)
                ->update(['status_order'=>Status::REJECTED->value]);
            if(!$result)
                return $this->sendError('لم يتم قبول الطلب');
            
            return $this->sendError('لم تم قبول الطلب');

        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في رفض الطلب'
            );
        }
    }


}
