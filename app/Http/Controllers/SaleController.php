<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleHasStocksRequest;
use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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
            $userId = $request->user_id ? trim($request->user_id) : null;
            $status = $request->status ? trim($request->status) : null;
            $sales = Sale::with(['customer', 'user', 'deliveryman', 'paymentType'])
                ->when($filter, function ($query) use ($filter) {
                    $dates = json_decode(getDatesFilter($filter));
                    $query->whereDate('sales.created_at', '>=', $dates->current->start);
                    $query->whereDate('sales.created_at', '<=', $dates->current->finish);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->where('get_user_id', $userId);
                })
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status);
                });
            return DataTables::eloquent($sales)
                ->addColumn('routeEdit', function ($sale) {
                    return route('sales.edit', $sale->id);
                })
                ->addColumn('routeShow', function ($sale) {
                    return route('sales.show', $sale->id);
                })
                ->make(true);
        }
    }

    /**
     * Card.
     */
    public function loadCard(Request $request, string $filter)
    {
        if ($request->ajax()) {
            $dates = json_decode(getDatesFilter($filter));
            $current = Sale::whereDate('created_at', '>=', $dates->current->start)
                ->whereDate('created_at', '<=', $dates->current->finish)
                ->where('status', 'closed')
                ->count();
            $previous = Sale::whereDate('created_at', '>=', $dates->previous->start)
                ->whereDate('created_at', '<=', $dates->previous->finish)
                ->where('status', 'closed')
                ->count();
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
    public function store(SaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $sale = new Sale();
            $sale->fill($request->validated());
            $sale->save();

            DB::commit();
            return Redirect::route('sales.edit', $sale->id)->with('status', 'Venda iniciada com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('sales.create')->withErrors($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with(['customer', 'user', 'deliveryman', 'paymentType'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sale = Sale::findOrFail($id);
        if (auth()->user()->type != 'Administrador' && $sale->get_user_id != auth()->user()->id) {
            return Redirect::route('sales.index')->withErrors('Essa venda não pertence ao seu usuário');
        }
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $sale = Sale::with('stocks')->findOrFail($id);
            if ($sale->status == 'closed') {
                throw new \Exception('Essa venda já foi consolidada');
            }
            if ($sale->get_payment_type_id) {
                throw new \Exception('Essa venda já foi finalizada');
            }

            $sale->fill($request->validated());
            $sale->status = 'closed';
            $sale->save();

            foreach ($sale->stocks as $stock) {
                $stock->status = 'sold';
                $stock->save();
            }

            DB::commit();
            return Redirect::route('sales.edit', $sale->id)->with('status', 'Venda consolidada com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('sales.index')->withErrors($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateFinishSale(Request $request, string $id)
    {
        try {

            $request->validate([
                'get_deliveryman_user_id' => ['nullable', 'numeric'],
                'get_payment_type_id' => ['nullable', 'numeric'],
            ]);

            DB::beginTransaction();
            $sale = Sale::findOrFail($id);
            if ($sale->status == 'opened') {
                throw new \Exception('Essa venda ainda não foi consolidada');
            }
            if ($sale->get_payment_type_id) {
                throw new \Exception('Essa venda já foi finalizada');
            }

            $sale->get_deliveryman_user_id = $request->get_deliveryman_user_id;
            $sale->get_payment_type_id = $request->get_payment_type_id;
            if ($request->get_payment_type_id) {
                $sale->payment_date = Carbon::now();
            }
            $sale->save();

            DB::commit();

            if ($request->get_payment_type_id) {
                return Redirect::route('sales.show', $sale->id)->with('status', 'Venda finalizada com sucesso.');
            }
            return Redirect::route('sales.edit', $sale->id)->with('status', 'Venda atualizada com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('sales.index')->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('destroy.sale');
    }

    /**
     * Update the assign stocks.
     */
    public function assignStocks(SaleHasStocksRequest $request, string $id)
    {
        if ($request->ajax()) {
            try {
                DB::beginTransaction();
                $sale = Sale::findOrFail($id);
                if ($sale->status == 'closed') {
                    throw new \Exception('Essa venda já foi consolidada');
                }

                $stocks = Stock::where('status', 'available')
                    ->where('get_product_id', $request->get_product_id)
                    ->lockForUpdate()
                    ->take($request->quantity)
                    ->get();

                if ($stocks->count() != $request->quantity) {
                    throw new \Exception('Quantidade em estoque insuficiente');
                }

                foreach ($stocks as $stock) {
                    if ($stock->status != 'available') {
                        throw new \Exception('Erro ao adicionar os produtos');
                    }
                    $sale->total_value += $request->value;
                    $stock->status = 'unavailable';
                    $stock->save();

                    $stock->sales()->attach($sale->id, ['sale_value' => $request->value]);
                }
                $sale->save();

                DB::commit();
                return response()->json(['message' => 'Produtos adicionados com sucesso.']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }

    /**
     * Report Chart.
     */
    private function loadReportChart(string $filter)
    {
        try {
            $dates = json_decode(getDatesFilter($filter));
            $sales = Sale::whereDate('created_at', '>=', $dates->current->start)
                ->whereDate('created_at', '<=', $dates->current->finish)
                ->where('status', 'closed')
                ->orderBy('created_at')->get();
            $data = [];
            $series = [];
            $categories = [];

            foreach ($sales as $sale) {
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

            foreach ($data as $key => $value) {
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
        } catch (\Throwable $th) {
            return Response::json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Payment type Chart.
     */
    private function loadPaymentTypeChart(string $filter)
    {
        try {
            $dates = json_decode(getDatesFilter($filter));

            $sales = DB::table('sales')
                ->select([
                    'payment_types.name AS name',
                    DB::raw("COUNT(sales.id) AS value"),
                ])
                ->leftJoin('payment_types', 'sales.get_payment_type_id', '=', 'payment_types.id')
                ->whereDate('sales.created_at', '>=', $dates->current->start)
                ->whereDate('sales.created_at', '<=', $dates->current->finish)
                ->where('status', 'closed')
                ->groupBy('name')
                ->get();

            foreach ($sales as $sale) {
                $sale->name = $sale->name ? ucfirst($sale->name) : 'Sem pagamento';
            }

            return Response::json($sales);
        } catch (\Throwable $th) {
            return Response::json(['error' => $th->getMessage()], 500);
        }
    }
}
