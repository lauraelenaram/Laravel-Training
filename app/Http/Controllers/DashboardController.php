<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $year= date('Y');

        $incomes= DB::table('incomes as i')
        ->select(DB::raw('MONTH(i.date_hour) as month'),
        DB::raw('YEAR(i.date_hour) as year'),
        DB::raw('SUM(i.total) as total'))
        ->whereYear('i.date_hour',$year)
        ->groupBy(DB::raw('MONTH(i.date_hour)'),DB::raw('YEAR(i.date_hour)'))
        ->get();

        $sales= DB::table('sales as s')
        ->select(DB::raw('MONTH(s.date_hour) as month'),
        DB::raw('YEAR(s.date_hour) as year'),
        DB::raw('SUM(s.total) as total'))
        ->whereYear('s.date_hour',$year)
        ->groupBy(DB::raw('MONTH(s.date_hour)'),DB::raw('YEAR(s.date_hour)'))
        ->get();

        return ['incomes' => $incomes,'sales' => $sales, 'year' => $year];
    }
}
