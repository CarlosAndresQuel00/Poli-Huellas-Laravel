<?php

namespace App\Http\Controllers;

use App\Http\Resources\PetCollection;
use App\Models\Pet;
use App\Http\Resources\Pet as PetResource; //It doesn't know if it's model or resource
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    private static $messages = [
        'same' => 'Los campos :attribute y :other deben coincidir.',
        'size' => 'El campo :attribute debe tener exactamente :size.',
        'between' => 'El valor del campo :attribute :input no está entre :min - :max.',
        'in' => 'El campo :attribute debe estar entre las siguientes opciones: :values',
    ];

    public function index()
    {
        $this->authorize('viewAny', Pet::class);
        return new PetCollection(Pet::paginate(5));
    }

    public function image(Pet $pet)
    {
        return response()->download(public_path(Storage::url($pet->image)), $pet->name);
    }

    public function show(Pet $pet) // Relationship with an instance that corresponded from pet/model, instead of sending the ID it sends the object
    {
        $this->authorize('view', $pet);
        return response()->json(new PetResource($pet), 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pet::class);
        $rules = [
            'name' => 'required|string|unique:pets|max:100',
            'gender' => 'in:Macho,Hembra',
            'type' => 'in:Perro,Gato,Otros',
            'size' => 'in:Pequeño,Mediano,Grande',
            'description' => 'required',
            'date_of_birth' => 'required|string|max:255',
            'image' => 'required|image|dimensions:min_width=200,min_height=200'
        ];
        $request->validate($rules, self::$messages);
        // Upload file and after save pet
        $pet = new Pet($request->all()); // New instance with data
        $path = $request->image->store('public/pets'); // upload file to server | route -> store method
        $pet->image = $path; // Field image
        $pet->save();
        return response()->json(new PetResource($pet), 201); // New instance
    }

    public function update(Request $request, Pet $pet)
    {
        $this->authorize('update', $pet); // instance of that pet
        $rules = [
            'name' => 'required|string|unique:pets,name,'.$pet->id.'|max:100', // Allow to update our own pet|Validation no with the same pet to update
            'gender' => 'in:Macho,Hembra',
            'type' => 'in:Perro,Gato,Otros',
            'size' => 'in:Pequeño,Mediano,Grande',
            'description' => 'required',
            'date_of_birth' => 'required|string|max:255',
        ];
        $request->validate($rules, self::$messages);
        $pet->update($request->all()); // It's updating, directly without looking for in DB
        return response()->json($pet, 200);
    }

    public function delete(Pet $pet)
    {
        $this->authorize('delete', $pet);
        $pet->delete(); // It's deleting
        return response()->json(null, 204); // Empty content or nothing, all okay.
    }
}
