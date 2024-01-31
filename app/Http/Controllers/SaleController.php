<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $filter = $request->filter ? trim($request->filter) : null;

            $sales = Sale::with(['customer', 'user', 'deliveryman', 'paymentType'])
                ->when($filter, function($query) use($filter) {
                    $dates = json_decode(getDatesFilter($filter));
                    $query->whereDate('sales.created_at', '>=', $dates->current->start);
                    $query->whereDate('sales.created_at', '<=', $dates->current->finish);
                })
                ->whereHas('stocks', function($query) {
                    $query->where('status', 'sold');
                });
            return DataTables::eloquent($sales)->make(true);
        }
    }

    /**
     * Card.
     */
    public function loadCard(Request $request, string $filter)
    {
        if ($request->ajax()) {
            $dates = json_decode(getDatesFilter($filter));
            $current = Sale::whereDate('created_at', '>=', $dates->current->start)->whereDate('created_at', '<=', $dates->current->finish)->count();
            $previous = Sale::whereDate('created_at', '>=', $dates->previous->start)->whereDate('created_at', '<=', $dates->previous->finish)->count();
            $results = [
                'total' => $current,
                'diference' => $current - $previous,
                'diferencePercentage' => $previous != 0 ? (($current - $previous) / $previous) * 100 : 0,
            ];
            return Response::json($results);
        }
    }

    /**
     * Charts.
     */
    public function loadChart(Request $request, string $filter, string $chartType)
    {
        if ($request->ajax()) {
            if ($chartType == 'reportChart') {
                return $this->loadReportChart($filter);
            }
            if ($chartType == 'paymentTypeChart') {
                return $this->loadPaymentTypeChart($filter);
            }
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

    /**
     * Report Chart.
     */
    private function loadReportChart(string $filter)
    {
        $dates = json_decode(getDatesFilter($filter));
        $sales = Sale::whereDate('created_at', '>=', $dates->current->start)->whereDate('created_at', '<=', $dates->current->finish)
            ->orderBy('created_at')->get();
        $data = [];
        $series = [];
        $categories = [];

        foreach($sales as $sale) {
            $label = '';
            if ($filter == 'today') {
                $label = Carbon::parse($sale->created_at)->format('H:i');
            } else if ($filter == 'month') {
                $label = Carbon::parse($sale->created_at)->day;
            } else if ($filter == 'year') {
                $label = ucfirst(Carbon::parse($sale->created_at)->monthName);
            }
            isset($data[$label]) ? $data[$label] += 1 : $data[$label] = 1;
        }

        foreach($data as $key => $value){
            $series[] = $value;
            $categories[] = $key;
        }

        $label = '';
        if ($filter == 'today') {
            $label = 'Horários de hoje';
        } else if ($filter == 'month') {
            $label = 'Dias de ' . ucfirst(Carbon::now()->monthName);
        } else if ($filter == 'year') {
            $label = 'Meses de ' . ucfirst(Carbon::now()->year);
        }

        return Response::json(['series' => $series, 'categories' => $categories, 'label' => $label]);
    }

    /**
     * Payment type Chart.
     */
    private function loadPaymentTypeChart(string $filter)
    {
        $dates = json_decode(getDatesFilter($filter));

        $sales = DB::table('sales')
            ->select([
                'payment_types.name AS name',
                DB::raw("COUNT(sales.id) AS value"),
            ])
            ->leftJoin('payment_types', 'sales.get_payment_type_id', '=', 'payment_types.id')
            ->whereDate('sales.created_at', '>=', $dates->current->start)
            ->whereDate('sales.created_at', '<=', $dates->current->finish)
            ->groupBy('name')
            ->get();

        foreach($sales as $sale) {
            $sale->name = $sale->name ? ucfirst($sale->name) : 'Sem pagamento';
        }

        return Response::json($sales);
    }
}
