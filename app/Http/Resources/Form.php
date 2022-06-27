<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Form extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $preserveKeys = true;

    public function toArray($request)
    {
        return [
            // Show data
            'id' => $this->id,
            'responsible' => $this->responsible,
            'reason' => $this->reason,
            'home' => $this->home,
            'description' => $this->description,
            'diseases' => $this->diseases,
            'children' => $this->children,
            'time' => $this->time,
            'trip' => $this->trip,
            'new' => $this->new,
            'animals' => $this->animals,
            'state' => $this->state,
            'user' => '/api/users/' . $this->user_id, // Route already defined, more easy to use on the clients
            'pet' => '/api/pets/' . $this->pet_id,
            'category' => '/api/categories/' . $this->category_id, // No controller and route for category
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
