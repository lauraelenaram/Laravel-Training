<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;  
use App\Sale;
use App\Sale_detail;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $sales= Sale::join('people','sales.client_id','=','people.id')
            ->join('users','sales.user_id','=','users.id')
            ->select('sales.id','sales.voucher_type','sales.voucher_serie','sales.voucher_number',
            'sales.date_hour','sales.tax','sales.total', 'sales.status','people.name','users.user')
            ->orderBy('sales.id','desc')->paginate(3);
        }
        else
        {
            $sales= Sale::join('people','sales.client_id','=','people.id')
            ->join('users','sales.user_id','=','users.id')
            ->select('sales.id','sales.voucher_type','sales.voucher_serie','sales.voucher_number',
            'sales.date_hour','sales.tax','sales.total', 'sales.status','people.name','users.user')
            ->where('sales.'.$judgment, 'like', '%'.$search.'%')
            ->orderBy('sales.id','desc')->paginate(3);         
        }
        
        return [
            'pagination' => [
                'total' => $sales->total(),
                'current_page' => $sales->currentPage(),
                'per_page' => $sales->perPage(),
                'last_page' => $sales->lastPage(),
                'from' => $sales->firstItem(),
                'to' => $sales->lastItem()
            ],
            'sales' => $sales
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
            
        try
        {
            DB::beginTransaction();

            $mytime= Carbon::now('America/Mexico_City');

            $sales = new Sale();
            $sales->fill($request->all());
            $sales->user_id= \Auth::user()->id;
            $sales->date_hour= $mytime->toDateString();
            $sales->status= 'Registrado';
            $sales->save();

            $details= $request->data;

            foreach($details as $ep=>$det)
            {
                $details= new Sale_detail();
                $details->sale_id= $sales->id;
                $details->article_id= $det['article_id'];
                $details->quantity= $det['quantity'];
                $details->price= $det['price'];
                $details->discount= $det['discount'];
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
        $sales= Sale::findOrFail($request->id);
        $sales->status = 'Anulado';
        $sales->save();

        return $sales;
    }

    public function getHeader(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id= $request->id;

        $sales= Sale::join('people','sales.client_id','=','people.id')
        ->join('users','sales.user_id','=','users.id')
        ->select('sales.id','sales.voucher_type','sales.voucher_serie','sales.voucher_number',
        'sales.date_hour','sales.tax','sales.total', 'sales.status','people.name','users.user')
        ->where('sales.id','=',$id)
        ->orderBy('sales.id','desc')->take(1)->get();
        
        return ['sales' => $sales];
    }

    public function getDetails(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $id= $request->id;

        $details= Sale_detail::join('articles','sales_details.article_id','=','articles.id')
        ->select('sales_details.quantity','sales_details.price','sales_price.discount','articles.name as article')
        ->where('sales_details.sale_id','=',$id)
        ->orderBy('sales_details.id','desc')->get();
        
        return ['details' => $details];
    }
}
