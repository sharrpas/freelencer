<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResourceWithDescription extends JsonResource
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
            'user' => User::query()->find($this->user_id)->name,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'tags' => explode(" ",$this->tags),
        ];
    }
}
