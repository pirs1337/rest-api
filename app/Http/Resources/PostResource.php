<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
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
                $this->mergeWhen($this->img, [
                    'img' => $this->img,
                ]),
                'created_at' => Controller::formateDateToDmY($this->created_at),
                $this->mergeWhen($this->created_at != $this->updated_at, [
                'updated_at' => Controller::formateDateToDmY($this->updated_at),
            ]),
                
        ];
    }
}
