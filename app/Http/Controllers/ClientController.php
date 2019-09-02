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
        $person= new Person();
        $person->fill($request->all());
        $person->condition= 1;
        $person->save();
        return $person;
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $person= Person::findOrFail($request->id);
        $person->fill($request->all());
        $person->condition= 1;
        $person->save();
        return $person;
    }
}
