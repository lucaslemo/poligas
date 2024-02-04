<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Price;
use App\Models\Product;
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
            $products = Product::with(['prices' => function ($query) {
                $query->latest();
            }]);
            return DataTables::eloquent($products)
                ->addColumn('updateButton', function ($product) {
                    return view('prices.partials.updateButton', ['product' => $product]);
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
                if ($oldPrice->value == $request->value) {
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
