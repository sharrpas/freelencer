<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'price' => $this->price,
            'status' => $this->status,
            'tags' => explode(" ",$this->tags),
        ];
    }
}
