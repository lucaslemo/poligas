<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->type ? trim($request->type) : null;
            if ($type == 'general') {
                return $this->datatableGeneral();
            } else if ($type == 'detailed') {
                return $this->datatableDetailed();
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('stocks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockRequest $request)
    {
        try {
            DB::beginTransaction();
            $qty = $request->quantity;

            for($i = 0; $i < $qty; $i++) {
                $stock = new Stock();
                $stock->fill($request->validated());
                $stock->save();
            }

            $message = $qty == 1 ? $qty . ' produto cadastrado' : $qty . ' produtos cadastrados';

            DB::commit();
            return Redirect::route('stocks.create')->with('status', $message . ' com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('stocks.create')->withErrors($th->getMessage());
        }
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
    public function update(StockRequest $request, string $id)
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
     * Mount the datatable for stocks.
     */
    private function datatableGeneral() {
        $stocks = DB::table('stocks')
            ->select([
                'products.id AS id',
                DB::raw('ANY_VALUE(products.name) AS name'),
                DB::raw('COUNT(stocks.get_product_id) AS product_count'),
                DB::raw('COUNT(DISTINCT stocks.get_brand_id) AS brand_count'),
                DB::raw('COUNT(DISTINCT stocks.get_vendor_id) AS vendor_count'),
                DB::raw('SUM(stocks.vendor_value) AS value_sum'),
                DB::raw('MAX(stocks.created_at) AS latest_stock'),
            ])
            ->leftJoin('products', 'stocks.get_product_id', '=', 'products.id')
            ->leftJoin('brands', 'stocks.get_brand_id', '=', 'brands.id')
            ->leftJoin('vendors', 'stocks.get_vendor_id', '=', 'vendors.id')
            ->where('status', 'available')
            ->groupBy(DB::raw('id WITH ROLLUP'));
        return DataTables::of($stocks)
            ->editColumn('id', function($product) {
                return $product->id ? $product->id : '-';
            })
            ->editColumn('name', function($product) {
                return $product->id ? $product->name : 'Total';
            })
            ->addColumn('brands_and_vendors', function($product) {
                $brand = $product->brand_count == 1 ? 'marca' : 'marcas';
                $vendor = $product->vendor_count == 1 ? 'fornecedor' : 'fornecedores';
                return "de {$product->brand_count} {$brand} por {$product->vendor_count} {$vendor}";
            })
            ->filterColumn('id', function($query, $keyword) {
                $query->whereRaw("LOWER(products.id) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->whereRaw("LOWER(products.name) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('product_count', function($query, $keyword) {
                $sql = "EXISTS (
                    SELECT COUNT(sub_stocks.get_product_id)
                    FROM stocks AS sub_stocks
                    WHERE sub_stocks.get_product_id = stocks.get_product_id AND sub_stocks.status = 'available'
                    GROUP BY sub_stocks.get_product_id HAVING COUNT(sub_stocks.get_product_id) LIKE ?
                )";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('value_sum', function($query, $keyword) {
                $sql = "EXISTS (
                    SELECT SUM(sub_stocks.vendor_value)
                    FROM stocks AS sub_stocks
                    WHERE sub_stocks.get_product_id = stocks.get_product_id AND sub_stocks.status = 'available'
                    GROUP BY sub_stocks.get_product_id HAVING SUM(sub_stocks.vendor_value) LIKE ?
                )";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->make(true);
    }

    /**
     * Mount the datatable for stocks.
     */
    private function datatableDetailed() {
        $stocks = Stock::where('status', 'available')->with(['product', 'brand', 'vendor']);
        return DataTables::eloquent($stocks)->make(true);
    }
}
