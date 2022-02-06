<?php

namespace App\Http\Resources;

use App\Http\Controllers\ImgController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
           'id' => $this->id,
           'login' => $this->login,
           'email' => $this->email,
           'avatar' => ImgController::getFullPathImg($this->avatar),
        ];
    }
}
