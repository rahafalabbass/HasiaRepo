<?php
namespace App\Traits;

trait GeneralTrait
{
    public function buildResponse($data=null,$type=null,$message=null,$status=null, $imageUrl = null){
        $responseArray=[
            'data'=>$data,
            'type'=>$type,
            'message'=>$message,
            'status'=>$status,
            'imageUrl'=>$imageUrl
        ];
        if ($imageUrl !== null) {
            $responseArray['image_url'] = $imageUrl;
        }
    
        return response($responseArray,$status);
    }
    
    public function paymentResponse($data=null,$errorMessage=null,$errorCode=null){
        $responseArray=[
            'Data'=>$data,
            'ErrorMessage'=>$errorMessage,
            'ErrorCode'=>$errorCode,
        ];
    }
}


