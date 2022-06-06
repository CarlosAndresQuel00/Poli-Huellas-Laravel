<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlCollection;
use App\Models\Articl;
use App\Http\Resources\Articl as ArticleResource; //It doesn't know if it's model or resource
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticlController extends Controller
{
    private static $messages = [
        'same' => 'Los campos :attribute y :other deben coincidir.',
        'size' => 'El campo :attribute debe tener exactamente :size.',
        'between' => 'El valor del campo :attribute :input no está entre :min - :max.',
        'in' => 'El campo :attribute debe estar entre las siguientes opciones: :values',
    ];

    public function index()
    {
        //return Articl::all();  // Return all
        //return ArticleResource::collection(Articl::all()); // Introduce to a new position of the array "Data"
        //return response()->json(ArticleResource::collection(Articl::all()), 200); // Without introduction to array "Data"
        //return response()->json(new ArticlCollection(Articl::all()), 200); // Individually control models collection
        //$this->authorize('viewAny', Articl::class);
        return new ArticlCollection(Articl::paginate(5));
    }

    public function image(Articl $article)
    {
        return response()->download(public_path(Storage::url($article->image)), $article->title);
    }

    public function show(Articl $article) // Relationship with an instance that corresponded from article/model, instead of sending the ID it sends the object
    {
        //return $article; // Look for some in database for return it | laravel with eloquent make internally
        $this->authorize('view', $article);
        return response()->json(new ArticleResource($article), 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Articl::class);
        $rules = [
            'title' => 'required|string|unique:articls|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id', // Field category_id doesn't exist
            'image' => 'required|image|dimensions:min_width=200,min_height=200'
        ];
        //$article = Articl::create($request->all()); // It's creating
        //return response()->json($article, 201); // Return a json object and answers more specifics
        /*
        $messages = [
            'same' => 'Los campos :attribute y :other deben coincidir.',
            'size' => 'El campo :attribute debe tener exactamente :size.',
            'between' => 'El valor del campo :attribute :input no está entre :min - :max.',
            'in' => 'El campo :attribute debe estar entre las siguientes opciones: :values',
        ];
        $validatedData = $request->validate([
            'title' => 'required|string|unique:articles|max:255',
            'body' => 'required',
        ], $messages);
        */
        $request->validate($rules, self::$messages);
        //$articl = Articl::create($request->all());
        // Upload file and after save article
        $articl = new Articl($request->all()); // New instance with data
        $path = $request->image->store('public/articles'); // upload file to server | route -> store method
        //$path = $request->image->storeAs('public/articles',  $request->user()->id . '_' . $articl->title . '.' . $request->image->extension());
        $articl->image = $path; // Field image
        $articl->save();
        return response()->json(new ArticleResource($articl), 201); // New instance
    }

    public function update(Request $request, Articl $article)
    {
        $this->authorize('update', $article); // instance of that article
        $rules = [
            'title' => 'required|string|unique:articls,title,'.$article->id.'|max:255', // Allow to update our own article|Validation no with the same article to update
            'body' => 'required',
            'category_id' => 'required|exists:categories,id' // Field category_id doesn't exist
        ];
        $request->validate($rules, self::$messages);
        $article->update($request->all()); // It's updating, directly without looking for in DB
        return response()->json($article, 200);
    }

    public function delete(Articl $article)
    {
        $this->authorize('delete', $article);
        $article->delete(); // It's deleting
        return response()->json(null, 204); // Empty content or nothing, all okay.
    }
}
