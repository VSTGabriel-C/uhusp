<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
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
            'id' => (string)$this->id,
            'phone' => $this->phone,
            'relationship' => [
                'id' => (string)$this->provider->id,
                'name' => $this->provider->name,
                'type' => $this->provider->type
            ]
        ];
    }
}
