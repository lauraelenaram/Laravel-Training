<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Supplier;
use App\Person;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search= $request->search;
        $judgment= $request->judgment;

        if($search=='')
        {
            $supplier= Supplier::join('people','suppliers.id','=','people.id')
            ->select('people.id','people.name','people.document_type','people.document_number',
            'people.address','people.telephone','people.email', 'suppliers.contact','suppliers.contact_telephone')
            ->orderBy('people.name','asc')->paginate(3);
        }
        else
        {
            $supplier= Supplier::join('people','suppliers.id','=','people.id')
            ->select('people.id','people.name','people.document_type','people.document_number',
            'people.address','people.telephone','people.email', 'suppliers.contact','suppliers.contact_telephone')
            ->where('people.'.$judgment, 'like', '%'.$search.'%')->orderBy('people.name','asc')->paginate(3);         
        }
        
        return [
            'pagination' => [
                'total' => $supplier->total(),
                'current_page' => $supplier->currentPage(),
                'per_page' => $supplier->perPage(),
                'last_page' => $supplier->lastPage(),
                'from' => $supplier->firstItem(),
                'to' => $supplier->lastItem()
            ],
            'supplier' => $supplier
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        try
        {
            DB::beginTransaction();

            $people= new Person();
            $people->fill($request->all());
            $people->save();

            $supplier= new Supplier();
            $supplier->fill($request->all());
            $supplier->id= $people->id;
            $supplier->save();
            return $supplier;

            DB::commit();        }
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
            $supplier= Supplier::findOrFail($request->id);
            $people= Person::findOrFail($supplier->id);

            $people->fill($request->all());
            $people->save();

            $supplier->fill($request->all());
            $supplier->save();
            return $supplier;

            DB::commit();        }
        catch (Exception $e)
        {
            DB::rollBack();
        }
    }
}
