<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::select();
            return DataTables::eloquent($products)
                ->addColumn('routeEdit', function($product) {
                    return route('products.edit', $product->id);
                })
                ->make(true);
        }
    }

    /**
     * Get records.
     */
    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $products = Product::select('id',  'name as text')
                ->where(function($query) use($term) {
                    $sql = "name like ?";
                    $query->whereRaw($sql, ["%{$term}%"]);
                })
                ->orderBy('name', 'asc')
                ->simplePaginate(10);
            $morePages = true;
            if (empty($products->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $products->items(),
                "pagination" => ["more" => $morePages]
            );
            return Response::json($results);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = new Product();
            $product->fill($request->validated());
            $product->save();

            DB::commit();
            return Redirect::route('products.create')->with('status', 'Produto cadastrado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('products.create')->withErrors($th->getMessage());
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
