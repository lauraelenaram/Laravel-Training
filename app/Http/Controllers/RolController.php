<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $roles= Rol::orderBy('id','desc')->paginate(3);
        }
        else
        {
            $roles= Rol::where($judgment, 'like', '%'.$search.'%')->orderBy('id','desc')->paginate(3);         
        }
        
        return [
            'pagination' => [
                'total' => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem()
            ],
            'roles' => $roles
        ];
    }

    public function selectRol(Request $request)
    {
        $roles= Rol::where('condition','=','1')
        ->select('id','name')
        ->orderBy('name','asc')->get();

        return ['roles'=>$roles]; 
    }
}
