<?php

namespace App\Http\Controllers;
use App\User;
use App\Person;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $people= User::join('people','users.id','=','people.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('people.id','people.name','people.document_type','people.document_number',
            'people.address','people.telephone','people.email', 'users.user','users.password','users.condition',
            'users.rol_id','roles.name as rol')
            ->orderBy('people.name','asc')->paginate(3);
        }
        else
        {
            $people= User::join('people','users.id','=','people.id')
            ->join('roles','users.rol_id','=','roles.id')
            ->select('people.id','people.name','people.document_type','people.document_number',
            'people.address','people.telephone','people.email', 'users.user','users.password','users.condition',
            'users.rol_id','roles.name as rol')
            ->where('people.'.$judgment, 'like', '%'.$search.'%')
            ->orderBy('people.name','asc')->paginate(3);         
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
            
        try
        {
            DB::beginTransaction();

            $people = new Person();
            $people->fill($request->all());
            $people->save();
            

            $user= new User();
            $user->user = $request->user;
            $user->password= bcrypt($request->password);
            $user->condition = '1';
            $user->rol_id = $request->rol_id;
            $user->id = $people->id;
            $user->save();

            DB::commit();  
            return $user;
        }
        catch (Exception $e)
        {
            DB::rollBack();
        }
       
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        try
        {
            DB::beginTransaction();
            $user= User::findOrFail($request->id);
            $people= Person::findOrFail($user->id);

            $people->fill($request->all());
            $people->save();

            $user->user= $request->user;
            $user->password= bcrypt($request->password);
            $user->condition= '1';
            $user->rol_id= $request->rol_id;
            $user->save();

            DB::commit(); 
            return $user;
       }
        catch (Exception $e)
        {
            DB::rollBack();
        }
    }

    public function update_condition(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user= User::findOrFail($request->id);
        $user->condition = ($user->condition) ? false : true;
        $user->save();

        return $user;
    }
}
