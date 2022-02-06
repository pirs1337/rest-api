<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImgController extends Controller
{
  private const DEF_DIR = 'public/';

  public const AVATARS = 'avatars/';

  private const DEFAULT_AVATAR = self::AVATARS.'/default';

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

  public static function getDefaultAvatarPath(){
    $path = Storage::files(self::DEF_DIR.self::DEFAULT_AVATAR);
    return $path;
  }

  public static function getFullPathImg($img){
    $path = url('/').Storage::url($img);
    return $path;
  }
}
