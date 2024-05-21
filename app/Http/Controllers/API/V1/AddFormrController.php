<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubsbscribeRequset;
use App\Http\Resources\SsResource;
use App\Http\Resources\SubsecrebResource;
use App\Models\images;
use App\Models\Note;
use App\Models\Payment;
use App\Models\subscriptions;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddFormrController extends Controller
{
    use GeneralTrait;

    // ------------------------------FUNCTION FOR ADD FORM WITH UPLOAD IMAGES----------------------------------
    public function create(SubsbscribeRequset $request)
    {
        try {
            // 1. Create a new subscription
            $user = Auth::user();

            $earthId = $request->input('earth_id');

            if ($user->earths()->where('earth_id', $earthId)->exists()) {
                return $this->buildResponse(null, 'Error', 'لقد اشتركت بالفعل في هذه الأرض', 400);
            }

            $subscription = subscriptions::create([
                'area_id' => $request->input('area_id'), 
                'earth_id' => $earthId,
                'user_id' =>$user->id,
                'name' => $request->input('name'),
                'father_name' => $request->input('father_name'),
                'mother_name' => $request->input('mother_name'),
                'birth' => $request->input('birth'),
                'ID_personal' => $request->input('ID_personal'),
                'address' => $request->input('address'),
                'phone1' => $request->input('phone1'),
                'phone2' => $request->input('phone2')!=""?$request->input('phone2'):"00",
                'email' => $request->input('email'),
                'factory_name' => floatval($request->input('factory_name')),
                'factory_ent' => $request->input('factory_ent'),
                'Industry_name' => $request->input('Industry_name'),
                'ID_classification' => $request->input('ID_classification'),
                'Money' => $request->input('Money'),
                'Num_Worker' => $request->input('Num_Worker'),
                'Value_equipment' => $request->input('Value_equipment'),
                'Num_Year_Worker' => $request->input('Num_Year_Worker'),
                'Num_Exce' => $request->input('Num_Exce'),
                'Q_Water' => $request->input('Q_Water'),
                'Q_Electricity' => $request->input('Q_Electricity'),
                'state_checked' => 0,
                'state_approval' => 0,
                'state_complated' => 0,
                'state_specialize' => 0,
                'state_cancelled' => 0,
                'state_data' => 0, // for migrate to data table 
                'payment_method' => "payment_method",
            ]);
            // 2. Upload and associate the image

            $subscription_id = subscriptions::latest()->first()->id;
            $requestFiles = $request->file('url');
            if (!is_array($requestFiles)) {
                return ['status' => 'Error', 'message' => 'The input must be an array of files '];
            }

            $uploadedImages = []; 
            foreach ($requestFiles as $file) { 
                $dir = 'public/documents'; 
                $randomFileName = Str::random(32) . '.' . $file->getClientOriginalExtension(); // Generate a random file name 
                $filePath = $file->storeAs($dir, $randomFileName); // Store the file with the random name 
             
                if ($filePath) { 
                    images::create([ 
                        'url' =>  $randomFileName, 
                        'subscription_id' => $subscription_id, 
                    ]); 
                    array_push($uploadedImages, $randomFileName); 
                } 
            }
                        
            DB::table('earth_user')->insert([
                'user_id' => $user->id,
                'earth_id' => $request->input('earth_id'),
                'subscription_id' => $subscription->id,
            ]);

            $notes = Note::create([
                'subscription_id' => $subscription_id,
                'description' => $request->input('description') ?? 'تم استقبال طلبك , سيتم تدقيقه ومراجعتك في حال كان هناك أي خطأ',
            ]);
    
            $payments = Payment::create([
                'subscription_id' => $subscription_id,
                'firstBatch' => 0,
                'SecondBatch' => 0,
                'thirdBatch_25' => 0,
                'cartNumber' => 0,
                'amount' => 0,
                'batchName' => 'batchName',
            ]);
            $data =[$subscription,$notes,$payments];

            return $this->buildResponse($data,'Success', 'تم ارسال طلبك بنجاح بانتظار مراجعته', 200);
        } catch (\Exception $ex) {
            return $this->buildResponse($ex,'Error',$ex->getMessage(), 500);
        }
    }

                    
    // UPDATE CHANGE APPROVALL

    public function update_state_check(Request $request)
    {
        try {
            if (!Auth::user()->role == 'employee') {
                return $this->unAuthorizeResponse(null, 0,'Unauthorize', 401);
            }
            $changeState = subscriptions::findOrFail($request->id);
            $changeState->update([
                'state_approval' => 1
            ]);
            $subscription_id = subscriptions::latest()->first()->id;
            $notes = Note::create([
               'subscription_id' => $subscription_id,
               'description' => $request->input('description') ?? 'تم قبول الطلب',
            ]);
            $data = [$changeState, $notes];

            return $this->buildResponse($data, 'success','تمت معالجة طلبك , انتظر الموافقة  الأمنية', 200);
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'there is not update ', 404);
        }
    }
    // ................................. SHOW ALL SUBSCRTIOPTER BEFOR APPROVAL................................
    
   

    public function stateCancelled(Request $request)
    {
       
        try {
            if (Auth::user()->role != 'employee') {
                return $this->unAuthorizeResponse(null, 0,'Unauthorize', 401);
            }
            $changeState = subscriptions::findOrFail($request->id);
            $changeState->update([
                'state_cancelled' => 1
            ]);
           
            $subscription_id = subscriptions::latest()->first()->id;
            $notes = Note::create([
               'subscription_id' => $subscription_id,
               'description' => $request->input('description')
            ]);
            $data =[
                $changeState, $notes
            ];
            return $this->buildResponse($data, 'success','الغاء', 200);
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'حدث خطأ في عملية الغاء ', 404);
        }
    }


}
