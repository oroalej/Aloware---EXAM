<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray( $request ): array|JsonSerializable|Arrayable
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'message'    => $this->message,
            'depth'      => $this->depth,
            'parent_id'  => $this->parent_id,
            'created_at' => $this->created_at,
            'children'   => self::collection( $this->whenLoaded( 'nestedChildren', $this->nestedChildren, [] ) )
        ];
    }
}
