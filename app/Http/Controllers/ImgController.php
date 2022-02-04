<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImgController extends Controller
{
    const DEF_DIR = 'public/';

    public static function uploadImg($dir, $folderName, $img){
      $path = Storage::put(self::DEF_DIR.$dir.$folderName, $img);
      return $path;
    }

    public static function moveImg($old, $new){
      return Storage::move(self:: DEF_DIR.$old, self:: DEF_DIR.$new);
      
    }

    public static function deleteDir($dir){
      return Storage::deleteDirectory(self::DEF_DIR.$dir);
    }
}
