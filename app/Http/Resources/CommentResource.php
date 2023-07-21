<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->comment);
        return [
            'id'    =>  $this->id,
            'comment'  => $this->comment,
            'user'  => new UserResource($this->whenLoaded('user'))
        ];
    }
}
