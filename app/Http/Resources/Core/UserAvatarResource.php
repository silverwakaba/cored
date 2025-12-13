<?php

namespace App\Http\Resources\Core;

use App\Helpers\Core\FileHelper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAvatarResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array{
        return [
            'path' => $this->whenNotNull(FileHelper::avatar($this->path)),
        ];
    }
}






