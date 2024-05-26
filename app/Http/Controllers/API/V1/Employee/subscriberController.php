<?php

namespace App\Http\Controllers\API\V1\Employee;

use App\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomitzeResource;
use App\Http\Resources\SubsecrebResource;
use App\Models\Custmition;
use App\Models\images;
use App\Models\subscriptions;
use App\Models\tb_permision;
use App\Traits\GeneralTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class subscriberController extends Controller
{
    use GeneralTrait;
    use UploadImageTrait;



//.....................SHOW ALL USER SUBSCRIPTIONS.......................

public function getUserSubscriptions()
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return $this->buildResponse(null, 'Warning', 'unauthorized', 401);
        }
        
        if ($user->role !== 'subscriper') {
            return $this->buildResponse(null, 'Warning', 'unauthorized', 401);
        }

        $subscribers = subscriptions::with(['images', 'earth', 'earth.area', 'notes', 'payments'])
            ->where('user_id', $user->id)
            ->get();

        $subscribers->makeHidden('updated_at');

        return $this->buildResponse($subscribers, 'Success', 'طلبات الاكتتاب التي تم تقديمها', 200);
    } catch (ModelNotFoundException $e) {
        return $this->buildResponse(null, 'Error', 'لا يوجد أي طلبات', 404);
    } catch (\Exception $e) {
        return $this->buildResponse(null, 'Error', 'حدث خطأ ما', 500);
    }
}



    // ---------------------------THIS IS FUNCTION FOR UPDLOAD ALL DOCUMENTS AFTER APRROVAL--------------------------------

    public function upload_documents(Request $request, $subscription_id)
    {
        $user_id = Auth::user()->id;
        if (Auth::user()->role != 'subscriper') {
            return $this->buildResponse(null, 'Warning', 'unauthorized', 401);
        }
    
        $subscription = subscriptions::findOrFail($subscription_id);
    
        if ($subscription->user_id != $user_id) {
            return $this->buildResponse(null, 'Warning', 'unauthorized', 401);
        }
    
        $validator = Validator::make($request->all(), [
            'images.*' => 'image',
        ]);
    
        try {
            $requestFiles = $request->file('url');
    
            if (!is_array($requestFiles)) {
                return ['status' => 'Error', 'message' => 'The input must be an array of files '];
            }
    
            $uploadedImages = [];
            foreach ($requestFiles as $file) {
                $dir = 'public/documents';
                $fixName = uniqid() . '.' . $file->getClientOriginalExtension();
    
                if ($file) {
                    Storage::putFileAs($dir, $file, $fixName);
                    $images = images::create([
                        'url' => $fixName,
                        'subscription_id' => $subscription_id,
                    ]);
                }
                array_push($uploadedImages, $fixName);
            }
    
            $subscription->state_specialize = 1;
            $subscription->save();
            

            $notes = Note::where('subscription_id', $subscription_id)->update([
                'description' => ' تم رفع الصور بنجاح , سيتم  التأكد منها وإعلامك في حال كان هناك أي مشكلة'
            ]);

            $data =[
                $subscription, $images, $notes
            ];
            return $this->buildResponse($data, 'Success', ' تم رفع الصور بنجاح ', 200);
        }catch (\Exception $ex) {
            return $this->buildResponse($ex->getMessage(), 500);
        }
    }
    
    

// delete image by subscriper
    public function deleteImage($id){
        try {
            if (Auth::user()->role != 'subscriper') {
                return $this->unAuthorizeResponse(null, 0,'Unauthorize', 401);
            }
            $image = images::where('id', $id)
                      ->whereHas('subscription', function($query) {
                          $query->where('user_id', Auth::user()->id);
                      })
                      ->first();
            if ($image) {
                $image->delete();
                return $this->buildResponse([], 'Success', 'تم حذف الصورة بنجاح', 200);
            } else {
                return $this->buildResponse(null, 'Error', 'هذه الصورة غير موجودة أو غير مرتبطة بك', 404);
            }
    
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'هناك خطأ في البحث عن الصورة', 404);
        }
    }
// delete image by employee
    public function delete($id){
       try{
        if (Auth::user()->role != 'employee') {
            return $this->unAuthorizeResponse(null, 0,'Unauthorize', 401);
        }
        $image =images::where('id',$id)->first();
        if ($image) {
            $image->delete();
            return $this->buildResponse([], 'Success', 'تم حذف الصورة بنجاح', 200);
        } else {
            return $this->buildResponse(null, 'Error', 'هذه الصورة غير موجودة أو غير مرتبطة بك', 404);
        }
       } 
       catch (ModelNotFoundException $e) {
        return $this->buildResponse($e, 'Error', 'هناك خطأ في البحث عن الصورة', 404);
    }
    }



}
