<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category= new Category();
        $category->fill([
            'name'=> $request->name,
            'description'=> $request->description,
            'condition'=> '1'
        ]);
        $category->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category= Category::findOrFail($request->id);
        $category->fill([
            'name'=> $request->name,
            'description'=> $request->description,
            'condition'=> '1'
        ]);
        $category->save();
    }

    public function activate(Request $request)
    {
        $category= Category::findOrFail($request->id);
        $category->fill([
            'condition'=> '1'
        ]);
        $category->save();
    }

    public function desactivate(Request $request)
    {
        $category= Category::findOrFail($request->id);
        $category->fill([
            'condition'=> '0'
        ]);
        $category->save();
    }



}
