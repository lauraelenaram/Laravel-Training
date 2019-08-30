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
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
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
        if(!$request->ajax()) return redirect('/');
        $category= new Category();
        $category->fill($request->all());
        $category->condition= 1;
        $category->save();
        return $category;
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
        if(!$request->ajax()) return redirect('/');
        $category= Category::findOrFail($request->id);
        $category->fill($request->all());
        $category->condition= 1;
        $category->save();
        return $category;
    }

    public function update_condition(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $category= Category::findOrFail($request->id);
        $category->condition = ($category->condition) ? false : true;
        $category->save();

        return $category;
    }




}
