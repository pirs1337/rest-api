<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'api_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function generateBearerToken(){
        $token = Str::random(80);
        $this->api_token = $token;
        $this->save();
        return $token;
    }

    public static function getUserByBearerToken($request, $token = false){
        if (!$token) {
            $token = $request->bearerToken();
        }
        $user = User::where('api_token', $token)->first();
        if ($user) {
            return $user;
        }

        return false; 
    }

    // public static function changeUserBearerToken($request){
    //     $user = self::getUserByBearerToken($request);
    //     $user->generateBearerToken();
    // }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
}
