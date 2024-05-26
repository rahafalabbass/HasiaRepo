<?php

namespace App\Http\Controllers\API\V1\Employee;

use App\Http\Controllers\Controller;
use App\Models\PaymentStatus;
use App\Models\subscriptions;
use App\Traits\GeneralTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessingOrdersController extends Controller
{
    use GeneralTrait;
    use UploadImageTrait;
    // Waiting proccess order و طلبات جديدة

    public function orderUnpaid($id =null){
        if(is_null($id)){
            $subscribers = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments','paymentStatus')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 0);
            })
                ->where('state_checked', 0)
                ->where('state_approval', 0)
                ->where('state_cancelled', 0)
                ->get();
                
                return $this->buildResponse($subscribers, 'Success', 'جميع طلبات الاكتتاب  قبل الدفع ', 200);

        }
        if (!is_numeric($id)) {
            return $this->buildResponse(null, 'Wraning', 'enter integer id', 400);
        }
    
        try {
            $subsWithImages = subscriptions::with('images','earth','earth.area','notes', 'payments.paymentStatus')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1);
            })
            ->where('state_checked', 0)
            ->where('state_approval', 0)
            ->where('state_cancelled', 0)
            ->findOrFail($id);
            
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'There are no subscriptions', 404);
        }
        return $this->buildResponse($subsWithImages, 'success', 'بيانات المكتتب بالكامل ', 200);
    
    }
    /////////// ReviewsOrder
    public function reviewsOrders($id = null){
      
        if (is_null($id) ) {

            $subscriber = subscriptions::with('images','earth','earth.area','notes', 'payments','paymentStatus')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1);
            })
            ->where('state_checked', 0)
            ->where('state_approval', 0)
            ->where('state_cancelled', 0)
            ->get();

           
            $subscriber->makeHidden('updated_at');
           
            return $this->buildResponse($subscriber, 'Success', 'جميع طلبات الاكتتاب قبل التدقيق ', 200);
        }
        if (!is_numeric($id)) {
            return $this->buildResponse(null, 'Wraning', 'enter integer id', 400);
        }
    
         try {
                $subsWithImages = subscriptions::with('images','earth','earth.area','notes', 'payments', 'paymentStatus')
                ->whereHas('paymentStatus', function($query) {
                    $query->where('subscription_fee', 1);
                })
                ->where('state_checked', 0)
                ->where('state_cancelled', 0)
                ->findOrFail($id);
                
            } catch (ModelNotFoundException $e) {
                return $this->buildResponse($e, 'Error', 'There are no subscriptions', 404);
            }
            return $this->buildResponse($subsWithImages, 'success', 'بيانات المكتتب بالكامل ', 200);
    }
    // Approval order تشيك بانتظار موافقة أمنية
    public function approvalOrders($id = null) {
    
        if (is_null($id) ) {
            $subscribers = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments', 'paymentStatus')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1);
            })
                ->where('state_checked', 1)
                ->where('state_approval', 0)
                ->where('state_cancelled', 0)
                ->get();
    
            $subscribers->makeHidden('updated_at');
               
            return $this->buildResponse($subscribers, 'Success', 'جميع طلبات الاكتتاب بانتظار الموافقة', 200);
        }
    
        if (!is_null($id) && !is_numeric($id)) {
            return $this->buildResponse(null, 'Warning', 'الرجاء إدخال رقم صحيح', 400);
        }
        
        try {
            $subscriberWithImages = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments', 'paymentStatus')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1);
            })
            ->where('state_checked', 1)
            ->where('state_approval', 0)
            ->where('state_cancelled', 0)
            ->findOrFail($id);
            return $this->buildResponse($subscriberWithImages, 'Success', 'بيانات المكتتب بالكامل', 200);

                    
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'لا توجد اشتراكات', 404);
        }
        
    }


    // قبل رفع بقية الأوراق, بعد وصول الموافقة
    public function uncompleteOrders($id = null) {
    
        if (is_null($id) ) {
            $subscribers = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1)
                ->where('insurance_fee', 0);
            })
                ->where('state_checked', 1)
                ->where('state_approval', 1)
                ->where('state_specialize', 0)
                ->where('state_cancelled', 0)
                ->get();
    
            $subscribers->makeHidden('updated_at');
               
            return $this->buildResponse($subscribers, 'Success', 'عرض المكتتبين قبل رفع بقية الصور', 200);
        }
    
        if (!is_null($id) && !is_numeric($id)) {
            return $this->buildResponse(null, 'Warning', 'الرجاء إدخال رقم صحيح', 400);
        }
        
        try {
            $subscriberWithImages = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1)
                ->where('insurance_fee', 0);
            })
                ->where('state_approval', 1)
                ->where('state_specialize', 1)
                ->where('state_cancelled', 0)
                
                ->findOrFail($id);
                return $this->buildResponse($subscriberWithImages, 'Success', 'عرض بيانات المكتتب بالكامل قبل رفع بقية الصور', 200);

                    
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'لا توجد اشتراكات', 404);
        }
        
    }



      //بعد رفع بقية الصور , بانتظار التشييك
    public function checkImagesOrders($id = null) {
    
        if (is_null($id) ) {
            $subscribers = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments')->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1)
                ->where('insurance_fee', 1);
            })
                ->where('state_specialize', 1)
                ->where('state_complated', 0)
                ->where('state_cancelled', 0)
                ->get();
    
            $subscribers->makeHidden('updated_at');
               
            return $this->buildResponse($subscribers, 'Success', 'عرض المكتتبين بعد رفع بقية الصور', 200);
        }
    
        if (!is_null($id) && !is_numeric($id)) {
            return $this->buildResponse(null, 'Warning', 'الرجاء إدخال رقم صحيح', 400);
        }
        
        try {
            $subscriberWithImages = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1)
                ->where('insurance_fee', 1);
            })
                ->where('state_specialize', 1)
                ->where('state_complated', 0)
                ->where('state_cancelled', 0)
                ->findOrFail($id);
                return $this->buildResponse($subscriberWithImages, 'Success', 'عرض بيانات المكتتب بالكامل مع بقية الصور', 200);

                    
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'لا توجد اشتراكات', 404);
        }
        
    }


    //بانتظار قرار التخصيص
    public function waitingCustomizationOrders($id = null) {
    
        if (is_null($id) ) {
            $subscribers = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1)
                ->where('insurance_fee', 1);
            })
                ->where('state_specialize', 1)
                ->where('state_complated', 1)
                ->where('state_cancelled', 0)
                ->get();
    
            $subscribers->makeHidden('updated_at');
               
            return $this->buildResponse($subscribers, 'Success', 'بانتظار قرار التخصيص', 200);
        }
    
        if (!is_null($id) && !is_numeric($id)) {
            return $this->buildResponse(null, 'Warning', 'الرجاء إدخال رقم صحيح', 400);
        }
        
        try {
            $subscriberWithImages = subscriptions::with('images', 'earth', 'earth.area','notes', 'payments')
            ->whereHas('paymentStatus', function($query) {
                $query->where('subscription_fee', 1)
                ->where('insurance_fee', 1);
            })
                ->where('state_specialize', 1)
                ->where('state_complated', 1)
                ->where('state_cancelled', 0)
                ->findOrFail($id);
                return $this->buildResponse($subscriberWithImages, 'Success', 'عرض بيانات المكتتب بالكامل مع بقية الصور', 200);

                    
        } catch (ModelNotFoundException $e) {
            return $this->buildResponse($e, 'Error', 'لا توجد اشتراكات', 404);
        }
        
    }




    //canceled orders
    public function canceledOrders($id = null){
      
        if (is_null($id) ) {

            $subscriber = subscriptions::with('images','earth','earth.area','notes', 'payments')
            ->where('state_cancelled', 1)->get();
            $subscriber->makeHidden('updated_at');
            
            return $this->buildResponse($subscriber, 'Success', 'عرض جميع الطلبات المرفوضة', 200);
        }
        if (!is_numeric($id)) {
            return $this->buildResponse(null, 'Wraning', 'enter integer id', 400);
        }
    
         try {
                $subsWithImages = subscriptions::with('images','earth','earth.area','notes', 'payments')
                ->where('state_cancelled', 1)->findOrFail($id);
                
            } catch (ModelNotFoundException $e) {
                return $this->buildResponse($e, 'Error', 'There are no subscriptions', 404);
            }
            return $this->buildResponse($subsWithImages, 'success', 'عرض بيانات المكتتب بالكامل ', 200);
    }
    

}
