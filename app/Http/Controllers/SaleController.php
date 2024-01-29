<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::with(['customer', 'user', 'deliveryman', 'paymentType'])
                ->whereHas('stocks', function($query) {
                    $query->where('status', 'sold');
                });
            return DataTables::eloquent($sales)->make(true);
        }
    }

    /**
     * Info ajax.
     */
    public function info(Request $request, string $filter)
    {
        if ($request->ajax()) {
            $data = [];
            switch ($filter) {
                case 'today':
                    $today = Sale::whereDate('created_at', Carbon::now()->toDateString())->count();
                    $yesterday = Sale::whereDate('created_at', Carbon::now()->subDay()->toDateString())->count();
                    $data = ['current' => $today, 'latest' => $yesterday];
                    break;
                case 'month':
                    $month = Sale::whereDate('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())
                        ->whereDate('created_at', '<=', Carbon::now()->endOfMonth()->toDateString())->count();

                    $lastMonth = Sale::whereDate('created_at', '>=', Carbon::now()->subMonth()->startOfMonth()->toDateString())
                        ->whereDate('created_at', '<=', Carbon::now()->subMonth()->endOfMonth()->toDateString())->count();
                    $data = ['current' => $month, 'latest' => $lastMonth];
                    break;
                case 'year':
                    $year = Sale::whereDate('created_at', '>=', Carbon::now()->startOfYear()->toDateString())
                        ->whereDate('created_at', '<=', Carbon::now()->endOfYear()->toDateString())->count();
                    $lastYear = Sale::whereDate('created_at', '>=', Carbon::now()->subYear()->startOfYear()->toDateString())
                        ->whereDate('created_at', '<=', Carbon::now()->subYear()->endOfYear()->toDateString())->count();
                    $data = ['current' => $year, 'latest' => $lastYear];
                    break;
            }
            $results = [
                'total' => $data['current'],
                'diference' => $data['current'] - $data['latest'],
                'diferencePercentage' => (($data['current'] - $data['latest']) / $data['latest']) * 100
            ];
            return Response::json($results);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
