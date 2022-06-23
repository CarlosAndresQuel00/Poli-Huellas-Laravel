<?php

namespace App\Http\Controllers;

use App\Http\Resources\Form as FormResource;
use App\Models\Form;
use App\Models\Pet;
use App\Notifications\FormNotification;
use Illuminate\Http\Request;

class FormController extends Controller
{
    private static $messages = [
        'same' => 'Los campos :attribute y :other deben coincidir.',
        'size' => 'El campo :attribute debe tener exactamente :size.',
        'between' => 'El valor del campo :attribute :input no está entre :min - :max.',
        'in' => 'El campo :attribute debe estar entre las siguientes opciones: :values',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pet $pet)
    {
        $this->authorize('view', Form::class);
        $form = $pet->forms;
        return response()->json(FormResource::collection($form), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Pet $pet, Form $form)
    {
        $this->authorize('view', $form);
        $forms = $pet->forms()->where('id', $form->id)->firstOrFail();
        return response()->json(new FormResource($forms), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Pet $pet)
    {
        $this->authorize('create', Form::class);
        $rules = [
            'responsible' => 'required|string|max:100',
            'reason' => 'required',
            'home' => 'required|string|max:100',
            'description' => 'required',
            'diseases' => 'required|boolean',
            'children' => 'required|boolean',
            'time' => 'required|boolean',
            'trip' => 'required|string|max:255',
            'new' => 'required|boolean',
            'animals' => 'required|boolean',
            'category_id' => 'required|exists:categories,id', // Field category_id doesn't exist
        ];
        $request->validate($rules, self::$messages);
        $forms = $pet->forms()->save(new Form($request->all())); // New instance with data
        auth()->user()->notify(new FormNotification($forms));
        return response()->json(new FormResource($forms), 201); // New instance
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pet $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        $this->authorize('update', Form::class);
        $rules = [
            'responsible' => 'required|string|max:100',
            'reason' => 'required',
            'home' => 'required|string|max:100',
            'description' => 'required',
            'diseases' => 'required|boolean',
            'children' => 'required|boolean',
            'time' => 'required|boolean',
            'trip' => 'required|string|max:255',
            'new' => 'required|boolean',
            'animals' => 'required|boolean',
            'category_id' => 'required|exists:categories,id' // Field category_id doesn't exist
        ];
        $request->validate($rules, self::$messages);
        $forms = $pet->forms()->update($request->all());
        return response()->json($forms, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet $pet
     * @return \Illuminate\Http\Response
     */
    public function delete(Pet $pet)
    {
        $this->authorize('delete', Form::class);
        //$form->delete(); // It's deleting
        $pet->forms()->delete();
        return response()->json(null, 204); // Empty content or nothing, all okay.
    }
}
