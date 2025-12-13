<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemMasterResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array{
        return [
            'id'            => $this->id,
            'name'          => $this->whenNotNull($this->name),
            'description'   => $this->whenNotNull($this->description),
            'relation'      => [
                'detail'    => null,
            ],
        ];
    }
}






