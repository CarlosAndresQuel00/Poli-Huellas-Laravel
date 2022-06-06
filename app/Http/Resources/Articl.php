<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Articl extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            //'user_id' => $this->user_id,
            //'user' => User::find($this->user_id), // Instance of the object
            //'category' => Category::find($this->category_id),
            'user' => '/api/users/' . $this->user_id, // Route already defined, more easy to use on the clients
            'category' => '/api/categories/' . $this->category_id, // No controller and route for category
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
