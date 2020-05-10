<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
class AdminController extends Controller
{

    public function index( Request $request){
        //Getting the Year numver for the chart
        $overviewYear = $request->input('year');        
        if (!($overviewYear)) {
            $overviewYear = 2016;
        } else {
            $overviewYear =   $request->input('year');
        }
        //Overall data information
        $data = DB::table('salesdata')
                ->orderBy('purchase_date', 'desc')
                ->limit(10)
                ->get();

     // SALES TOTAL + TAX + SHIPPING && Total users
        $salestotal = DB::table('salesdata')
                ->sum('grand_total');
        $tax = DB::table('salesdata')
                ->sum('tax');

        $shipping = DB::table('salesdata')
            ->sum('shipping');

        $totalUsers = DB::table('salesdata')
            ->select('cust_fname', 'cust_city','cust_province')
            ->distinct()
            ->get();
        $totalUsersCount = count($totalUsers);

        // SALES BY provinces
        $provinces = json_encode(DB::table('salesdata')
            ->select('cust_province', DB::raw('SUM(grand_total) as total_sales'))
            ->groupBy('cust_province')
            ->get());

        // SALES BY CUSTOMER
        $topcustomers = json_encode(DB::table('salesdata')
            ->select('cust_fname', DB::raw('SUM(grand_total) as total_sales'))
            ->groupBy('cust_fname')
            ->orderBy('total_sales','desc')
            ->limit(5)
            ->get());
        
        // total ORDERS
        $totalorder = DB::table('salesdata')
                    ->count();

        // SALES BY year
        $yearly = DB::table('salesdata')
            ->select(DB::raw('YEAR(purchase_date) year'), DB::raw('SUM(grand_total) as total_sales'), DB::raw('SUM(shipping) as shipping'), DB::raw('SUM(tax) as tax'))
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // SALES BY year
        $yearly_sales = json_encode(DB::table('salesdata')
            ->select(DB::raw('YEAR(purchase_date) year'), DB::raw('SUM(grand_total) as total_sales'), DB::raw('SUM(shipping) as shipping'), DB::raw('SUM(tax) as tax'))
            ->groupBy('year')
            ->orderBy('year')
            ->get());

        //Filter by year
        $yearFilter = json_encode(DB::table('salesdata')
            ->select(DB::raw(' MONTH(purchase_date) month'), DB::raw('SUM(grand_total) as total_sales'), DB::raw('SUM(shipping) as shipping'), DB::raw('SUM(tax) as tax'))
            ->whereYear('purchase_date', $overviewYear)
            ->orderBy('month')
            ->groupBy('month')
            ->get());


        //  dd($totalUsers);
        return view('welcome', compact('data','yearFilter', 'yearly','yearly_sales', 'topcustomers', 'totalorder', 'provinces', 'shipping','tax', 'salestotal', 'totalUsersCount', 'totalUsers'));
    }
}
