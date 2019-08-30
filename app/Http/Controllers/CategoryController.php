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
        $category->fill($request->all());
        $category->condition= 1;
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
        $category->fill($request->all());
        $category->condition= 1;
        $category->save();
    }

    public function update_condition(Request $request)
    {
        $category= Category::findOrFail($request->id);
        $category->condition = ($category->condition) ? false : true;
        $category->save();

        return $category;
    }




}
