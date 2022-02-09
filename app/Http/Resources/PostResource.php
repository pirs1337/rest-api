<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImgController;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
               'user_id' => $this->user_id,
               'title' => $this->title,
               'text' => $this->text,
               'img' => $this->when($this->img, ImgController::getFullPathImg($this->img)),
               'created_at' => Controller::formateDateToDmY($this->created_at),
               'updated_at' => $this->when($this->created_at != $this->updated_at, Controller::formateDateToDmY($this->updated_at))
        ];
    }
}
