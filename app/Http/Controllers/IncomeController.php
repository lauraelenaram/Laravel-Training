<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Income;
use App\Income_details;
use App\User;
use App\Notifications\NotifyAdmin;

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

            $actualDate= date('Y-m-d');
            $numSales= DB::table('sales')->whereDate('created_at',$actualDate)->count();
            $numIncomes= DB::table('incomes')->whereDate('created_at',$actualDate)->count();

            $dataArray= [
                'sales' => [
                    'number' => $numSales,
                    'msg' => 'Sales'
                ],
                'incomes' => [
                    'number' => $numIncomes,
                    'msg' => 'Incomes'
                ]
            ];
            $allUsers= User::all();

            foreach ($allUsers as $notify)
            {
                User::findOrFail($notify->id)->notify(new NotifyAdmin($dataArray));
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

    public function getHeader(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id= $request->id;

        $income= Income::join('people','incomes.supplier_id','=','people.id')
        ->join('users','incomes.user_id','=','users.id')
        ->select('incomes.id','incomes.voucher_type','incomes.voucher_serie','incomes.voucher_number',
        'incomes.date_hour','incomes.tax','incomes.total', 'incomes.status','people.name','users.user')
        ->where('incomes.id','=',$id)
        ->orderBy('incomes.id','desc')->take(1)->get();
        
        return ['income' => $income];
    }

    public function getDetails(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id= $request->id;

        $details= Income_details::join('articles','incomes_details.article_id','=','articles.id')
        ->select('incomes_details.quantity','incomes_details.price','articles.name as article')
        ->where('incomes_details.income_id','=',$id)
        ->orderBy('incomes_details.id','desc')->get();
        
        return ['details' => $details];
    }
}
