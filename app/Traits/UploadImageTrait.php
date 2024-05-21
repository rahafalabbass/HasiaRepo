<?php
namespace App\Traits;
use Illuminate\Http\Request;

trait UploadImageTrait
{
    public function uploadImaeg(Request $request,$folderName){
        
        $image=$request->file('img')->getClientOriginalName();
        $path = $request->file('img')->storeAs($folderName,$image,'public');
        return $path;
    }


    public function uploadMultiImages(Request $request,$folderName)
    { 
        // $images = $request->file('images');
        // $uploadedImages = [];

        // foreach ($images as $image) {
        //     $imageName = $image->getClientOriginalName();
        //     $path = $image->storeAs($folderName,$imageName,'public');
        //     $uploadedImages[] = $imageName;
        // }

        // return $uploadedImages;

        // if (!$request->hasFile('images')) {
        //     return $this->errorResponse("no image upload",403);
        // } 
        $images = $request->file('images');
        $uploadedImages = [];
        // foreach ($images as $image) {
        //     if ($image->isValid()) {
        //         $imageName = $request->file('url_image')->getClientOriginalName();
        //         $imageUrl = asset("Attachments/$imageName");
        //         return $this->buildResponse($image, 'success', 'تم إضافة السجل بنجاح', 200, $imageUrl);
               
        //     } else {
             
        //         return $this->errorResponse("don't upload images",403);
        //     }
        // }
        $images = $request->file('img');

        // Check if $images is not null
        if (!is_null($images)) {
            $uploadedImages = [null];

            foreach ($images as $image) {
                $imageName = $image->getClientOriginalName();
                $path = $image->storeAs($folderName, $imageName, 'public');
                $uploadedImages[] = $imageName;
            }

            return $uploadedImages;
        } else {
            // Handle the case where $images is null (no images uploaded)
          dd("ss");
        }
    }
}


?>