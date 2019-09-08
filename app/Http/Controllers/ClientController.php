<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $people= Person::orderBy('name','asc')->paginate(3);
        }
        else
        {
            $people= Person::where($judgment, 'like', '%'.$search.'%')->orderBy('name','asc')->paginate(3);         
        }
        
        return [
            'pagination' => [
                'total' => $people->total(),
                'current_page' => $people->currentPage(),
                'per_page' => $people->perPage(),
                'last_page' => $people->lastPage(),
                'from' => $people->firstItem(),
                'to' => $people->lastItem()
            ],
            'people' => $people
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $people= new Person();
        $people->fill($request->all());
        $people->save();
        return $people;
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $people= Person::findOrFail($request->id);
        $people->fill($request->all());
        $people->save();
        return $people;
    }

    public function selectClient(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filter= $request->filter;
        $clients= Person::where('name','like','%'.$filter.'%')
        ->orWhere('document_number','like','%'.$filter.'%')
        ->select('id','name','document_number')
        ->orderBy('name','asc')->get();

        return ['clients'=>$clients];
    } 
}
