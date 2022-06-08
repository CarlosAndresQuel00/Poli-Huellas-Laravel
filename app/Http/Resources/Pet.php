<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Pet extends JsonResource
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
            'name' => $this->name,
            'gender' => $this->gender,
            'type' => $this->type,
            'size' => $this->size,
            'description' => $this->description,
            'date_of_birth' => $this->date_of_birth,
            'adopted' => $this->adopted,
            'user' => '/api/users/' . $this->user_id, // Route already defined, more easy to use on the clients
            'category' => '/api/categories/' . $this->category_id, // No controller and route for category
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
