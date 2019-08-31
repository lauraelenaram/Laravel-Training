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

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $categories= Category::orderBy('id','desc')->paginate(2);
        }
        else
        {
            $categories= Category::where($judgment, 'like', '%'.$search.'%')->orderBy('id','desc')->paginate(7);         
        }
        
        return [
            'pagination' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem()
            ],
            'categories' => $categories
        ];
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
