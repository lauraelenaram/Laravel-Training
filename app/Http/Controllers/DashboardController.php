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

        return ['incomes' => $incomes, 'year' => $year];
    }
}
