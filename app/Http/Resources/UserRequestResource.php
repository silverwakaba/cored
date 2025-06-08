<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRequestResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array{
        return [
            'id'        => $this->id,
            'token'     => $this->whenNotNull($this->token),
            'reference' => [
                'users_id'          => $this->whenNotNull($this->users_id),
                'base_request_id'   => $this->whenNotNull($this->base_request_id),
            ],
            'relation'  => [
                'user'      => new UserResource($this->whenLoaded('belongsToUser')),
                'request'   => new BaseRequestResource($this->whenLoaded('belongsToBaseRequest')),
            ],
        ];
    }
}
