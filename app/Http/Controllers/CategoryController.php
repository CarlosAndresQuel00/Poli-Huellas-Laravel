<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
     * @return CategoryCollection
     */
    public function index()
    {
        return new CategoryCollection(Category::all());

    }

    public function categoriesByUser()
    {
        $user = Auth::user();
        return new CategoryCollection($user->categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $rules = [
            'type' => 'in:dogs,cats,others',
        ];
        $request->validate($rules, self::$messages);
        $category = new Category($request->all()); // New instance with data
        $category->save();
        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category); // instance of that pet
        $rules = [
            'type' => 'in:dogs,cats,others',
        ];
        $request->validate($rules, self::$messages);
        $category->update($request->all()); // It's updating, directly without looking for in DB
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete(); // It's deleting
        return response()->json(null, 204);
    }
}
