<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImgController extends Controller
{
    const DEF_DIR = 'public/';

    public static function uploadImg($dir, $folderName, $img){
      $path = Storage::put($dir.$folderName, $img);
      return $path;
    }
}
