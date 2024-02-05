<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PriceController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $products = DB::table('products')
                ->select([
                    'products.id AS id',
                    'products.name AS name',
                    'p1.value AS value',
                    'p1.created_at AS created_at',
                ])
                ->leftJoin('prices AS p1', 'p1.get_product_id', '=', 'products.id')
                ->leftJoin('prices AS p2', function ($join) {
                    $join->on('p2.get_product_id', '=', 'products.id');
                    $join->on(function ($join) {
                        $join->on('p1.created_at', '<', 'p2.created_at');
                        $join->orOn(function ($join) {
                            $join->on('p1.created_at', '=', 'p2.created_at');
                            $join->on('p1.id', '<', 'p2.id');
                        });
                    });
                })
                ->whereNull('p2.id');
            return DataTables::of($products)
                ->addColumn('updateButton', function ($product) {
                    return view('prices.partials.updateButton', ['product' => $product]);
                })
                ->filterColumn('value', function($query, $keyword) {
                    $sql = "p1.value like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('prices.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PriceRequest $request)
    {
        if ($request->ajax()) {
            try {
                DB::beginTransaction();

                $oldPrice = Price::where('get_product_id', $request->get_product_id)
                    ->latest()
                    ->first();
                if ($oldPrice && $oldPrice->value == $request->value) {
                    throw new \Exception('Valor repetido');
                }

                $price = new Price();
                $price->fill($request->validated());
                $price->save();

                DB::commit();
                return response()->json(['message' => 'Novo preço cadastrado com sucesso']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
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
