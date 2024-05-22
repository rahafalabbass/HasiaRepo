<?php

use App\Http\Controllers\API\V1\AddFormrController;
use App\Http\Controllers\API\V1\AreaController;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Employee\ProcessingOrdersController;
use App\Http\Controllers\API\V1\Employee\StatusOrdersController;
use App\Http\Controllers\API\V1\Payment\PaymentController;
use App\Http\Controllers\API\V1\Employee\subscriberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('/subscriptions')->middleware('auth:sanctum')->group(function () {
    Route::post('/add_form', [AddFormrController::class, 'create']); //create form subscription
    Route::post('/upload_documents/{id}', [subscriberController::class, 'upload_documents']); // رفع بقية الوثائق بعد الموافقة الأمنية
    Route::get('/get/{id}', [AreaController::class, 'showEarths']); // عرض المقاسم الخاصة بمنطقة
    Route::delete('/delete-image/{id}', [subscriberController::class, 'deleteImage']); // حذف الصورة من قبل مكتتب
    Route::get('/userSubscriptions', [subscriberController::class, 'getUserSubscriptions']); // عرض طلبات المكتتب 

});
// Payment Route
Route::post('/create-payment',[PaymentController::class, 'createPayment'])->middleware(['auth:sanctum']);
Route::get('callback',function(){
    return 'success';
});

Route::get('triggerURL',function(){
    return 'payment failed';
});


// show areas and earths
Route::get('/areas', [AreaController::class, 'show_areas']); //عرض جميع المناطق
Route::get('/earths/{id}', [AreaController::class, 'show_earths']); // عرض المقاسم الخاصة بمنطقة

// Auth routes
    Route::controller(AuthController::class)->group(function(){
        Route::post('/register','register');
        Route::post('/login','login');
    });

// Logout routes
Route::group(['prefix' => 'api'], function () {
Route::controller(LogoutController::class)->group(function(){
    Route::options('/logout')->middleware(['auth:sanctum'])->name('logout');
    Route::options('/logout-all-devices')->middleware(['auth:sanctum'])->name('logout_all_devices');
});
});

// CURD proccess orders routes
Route::prefix('/subscriptions')->middleware('auth:sanctum')->group(function () {
    Route::controller(ProcessingOrdersController::class)->group(function(){
        Route::get('/checkImages/{id?}','checkImagesOrders')->middleware(['auth.employee']); // بانتظار تشييك الصور
        Route::get('approval/{id?}','approvalOrders')->middleware(['auth.employee']); // approval order, تشيك بانتظار موافقة أمنية
        Route::get('uncomplete/{id?}','uncompleteOrders')->middleware(['auth.employee']);//قبل رفع بقية الأوراق
        Route::get('/waitingCustomization/{id?}' , 'waitingCustomizationOrders')->middleware(['auth.employee']);//بانتظار قرار التخصيص
        Route::get('/canceled/{id?}' , 'canceledOrders')->middleware(['auth.employee']);// canceled order
        Route::get('/{id?}','reviewsOrders')->middleware(['auth.employee']); // review order ,طلبات جديدة 
    });
});

// Status update order
Route::prefix('/status')->middleware('auth:sanctum')->group(function () {
    Route::controller(StatusOrdersController::class)->middleware(['auth.employee','auth.checkRejected'])->group(function() {
        Route::patch('/update-state-check/{id}', 'updateStateCheck'); // يننتقل لجدول الموافقة
        Route::patch('/update-state-approval/{id}', 'updateStateApproval'); // update status for approval, وصلت الموافقة الأمنية
        Route::patch('/updateStateComplate/{id}', 'updateStateComplate'); // update order status for complete images, ينتقل على بانتظار جدول التخصيص
        Route::patch('/afterCustomization/{id}', 'afterCustomization'); // تم التخصيص
        Route::patch('/cancelled/{id}', 'stateCancelled'); // for cancelling order
        Route::post('/message/{id}', 'message'); // من أجل إرسال رسالة للمكتتب
        Route::delete('/delete/{id}', 'delete'); // حذف الصورة من قبل موظف
    });
});


Route::post('/addArea',[AreaController::class,'createArea'])->middleware(['auth:sanctum','auth.employee']);
Route::post('/updateArea/{id}',[AreaController::class,'updateArea'])->middleware(['auth:sanctum','auth.employee']);
Route::post('/addEarth/{id}',[AreaController::class,'createEarth'])->middleware(['auth:sanctum','auth.employee']);
Route::patch('/updateEarth/{id}',[AreaController::class,'updateEarth'])->middleware(['auth:sanctum','auth.employee']);


