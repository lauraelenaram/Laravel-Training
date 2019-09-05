<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $incomes= Income::join('people','incomes.supplier_id','=','people.id')
            ->join('users','incomes.user_id','=','users.id')
            ->select('incomes.id','incomes.voucher_type','incomes.voucher_serie','incomes.voucher_number',
            'incomes.date_hour','incomes.tax','incomes.total', 'incomes.status','people.name','users.user')
            ->orderBy('incomes.id','desc')->paginate(3);
        }
        else
        {
            $incomes= Income::join('people','incomes.supplier_id','=','people.id')
            ->join('users','incomes.user_id','=','users.id')
            ->select('incomes.id','incomes.voucher_type','incomes.voucher_serie','incomes.voucher_number',
            'incomes.date_hour','incomes.tax','incomes.total', 'incomes.status','people.name','users.user')
            ->where('incomes.'.$judgment, 'like', '%'.$search.'%')
            ->orderBy('incomes.id','desc')->paginate(3);         
        }
        
        return [
            'pagination' => [
                'total' => $incomes->total(),
                'current_page' => $incomes->currentPage(),
                'per_page' => $incomes->perPage(),
                'last_page' => $incomes->lastPage(),
                'from' => $incomes->firstItem(),
                'to' => $incomes->lastItem()
            ],
            'incomes' => $incomes
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
