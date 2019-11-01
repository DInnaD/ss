<?php

namespace App\Http\Helpers;


use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

abstract class Download
{

    const AVATARS_DIRECTORY = 'avatars/';
    const PRODUCT_PHOTO_DIRECTORY = 'product/';
    const CHAT_FILE_DIRECTORY = 'files/';

    /**
     * Update user avatar
     *
     * @param $base64
     * @param string $key
     * @return string
     */
    public static function downloadFile($base64, $key = 'product', $product = null)
    {
        $content = self::deleteHelpInformation($base64);
        $content = self::convertArrayToString($content);
        $filename = self::determineFileType($key, $content, $product);

        return $filename;
    }

    /**
     * Delete base64 help information
     *
     * @param $base64
     * @return mixed
     */
    public static function deleteHelpInformation($base64)
    {
        $content = str_replace('data:image/*;charset=utf-8;base64,', '', $base64);
        $content = str_replace('data:image/png;base64,', '', $content);
        $content = str_replace('data:image/jpg;base64,', '', $content);
        $content = str_replace('data:image/jpeg;base64,', '', $content);
        $content = str_replace(' ', '+', $content);
        return $content;
    }

    /**
     * Convert input information
     *
     * @param $content
     * @return string
     */
    public static function convertArrayToString($content)
    {
        if (is_array($content)) {
            return implode($content);
        } else {
            return $content;
        }
    }

    /**
     * Determine directory to save
     *
     * @param $key
     * @param $content
     * @return string
     */
    public static function determineFileType($key, $content, $product)
    {
        if ($key == 'product') {
            self::checkAndDeleteOldAvatar($product);
            $path = self::PRODUCT_PHOTO_DIRECTORY;
            $filename = self::fileName('png');
        } elseif ($key == 'avatar') {
            $path = self::AVATARS_DIRECTORY;
            $filename = self::fileName('png');
        } else {
            $path = self::CHAT_FILE_DIRECTORY;
            $filename = self::fileName($key);
        }

        self::saveNewFile($filename, $content, $path);
        return $filename;
    }

    /**
     * Delete old image
     *
     * @param User $user
     */
    public static function checkAndDeleteOldAvatar($product)
    {
        if (isset($product)) {
            Storage::disk('public')->delete(self::PRODUCT_PHOTO_DIRECTORY.$product);
        }
    }

    /**
     * Set file name
     *
     * @param $extension
     * @return string
     */
    public static function fileName($extension)
    {
        return strtotime("now").rand(1111,9999).".$extension";
    }

    /**
     * Download new file
     *
     * @param $filename
     * @param $content
     * @param $path
     * @return bool
     */
    public static function saveNewFile($filename, $content, $path)
    {
        return Storage::disk('public')->put($path.$filename, base64_decode($content));
    }

    /**
     * Save new file from chat
     *
     * @param $file
     * @return string
     */
    public static function chatDownload($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = 'file_'.rand(1111,9999).time().'.'.$extension;
        Storage::putFileAs(self::CHAT_FILE_DIRECTORY, new File($file), $filename);
        return $filename;
    }

    /**
     * Delete $file from storage
     *
     * @param $path
     * @param $file
     */
    public static function deleteFile($path, $file)
    {
        Storage::disk('public')->delete($path.$file);
    }
}
