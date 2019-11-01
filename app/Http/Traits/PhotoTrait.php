<?php
/**
 * GoOut.
 * User: Serg
 * Date: 02.08.2018
 * Time: 14:21
 */

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait PhotoTrait
{
    public function savePhoto($avatar, $path){
        $fileName = 'image_'.time().rand(1,999).'.png';
        if(preg_match('/utf-8/',$avatar)){
            $avatar = str_replace('data:image/*;charset=utf-8;base64,', '', $avatar);
            $avatar = str_replace(' ', '+', $avatar);
            if (is_array($avatar)) {
                $avatar = implode($avatar);
            }
        }
        else {
            @list(, $avatar) = explode(';', $avatar);
            @list(, $avatar) = explode(',', $avatar);
        }

        if($avatar != ""){
            Storage::disk('local')->put($path.$fileName, base64_decode($avatar));
        }
        $exists = Storage::disk('local')->exists($path.$fileName);


        if(!$exists){
            return response()->json(['success' => false, 'message' => trans('messages.errors.notSaveAvatar')]);
        }
        return $fileName;
    }

    public function deletePhoto($name, $path){
        Storage::disk('local')->delete($path.$name);
    }
}