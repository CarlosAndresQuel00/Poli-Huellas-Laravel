<?php

namespace App\Http\Controllers;

use App\Http\Resources\Form as FormResource;
use App\Http\Resources\FormCollection;
use App\Models\Form;
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
    public function index()
    {
        return new FormCollection(Form::paginate(5));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        $this->authorize('view', $form);
        return response()->json(new FormResource($form), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        // Upload file and after save article
        $form = new Form($request->all()); // New instance with data
        $form->save();
        auth()->user()->notify(new FormNotification($form));
        return response()->json(new FormResource($form), 201); // New instance
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form); // instance of that article
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
        $form->update($request->all()); // It's updating, directly without looking for in DB
        return response()->json($form, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function delete(Form $form)
    {
        $this->authorize('delete', $form);
        $form->delete(); // It's deleting
        return response()->json(null, 204); // Empty content or nothing, all okay.
    }
}
