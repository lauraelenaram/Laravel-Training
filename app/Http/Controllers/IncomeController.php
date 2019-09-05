<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Income;
use App\Income_details;

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

            $mytime= Carbon::now('America/Mexico_City');

            $incomes = new Income();
            $incomes->fill($request->all());
            $incomes->supplier_id= $request->supplier_id;
            $incomes->user_id= \Auth::user()->id;
            $incomes->date_hour= $mytime->toDateString();
            $incomes->status= 'Registrado';
            $incomes->save();

            $details= $request->data;

            foreach($details as $ep=>$det)
            {
                $details= new Income_details();
                $details->income_id= $incomes->id;
                $details->article_id= $det['article_id'];
                $details->quantity= $det['quantity'];
                $details->price= $det['price'];
                $details->save();
            }

            DB::commit();  
        }
        catch (Exception $e)
        {
            DB::rollBack();
        }
       
    }

    public function desactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $incomes= Income::findOrFail($request->id);
        $incomes->status = 'Anulado';
        $incomes->save();

        return $incomes;
    }
}
