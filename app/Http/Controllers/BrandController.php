<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;

class BrandController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::select();
            return DataTables::eloquent($brands)
                ->addColumn('routeEdit', function($brand) {
                    return route('brands.edit', $brand->id);
                })
                ->make(true);
        }
    }

    /**
     * Get records.
     */
    public function getBrands(Request $request)
    {
        if ($request->ajax()) {
            $term = $request->term ? trim($request->term) : null;
            $filter = $request->filter ? trim($request->filter) : null;
            $productId = $request->product_id ? trim($request->product_id) : null;
            $brands = Brand::select('id',  'name AS text')
                ->when($filter == 'stocked' && $productId, function($query) use($productId) {
                    $query->whereHas('stocks', function($query) use($productId) {
                        $query->where('status', 'available');
                        $query->where('get_product_id', $productId);
                    });
                })
                ->when($filter == 'stocked', function($query) {
                    $query->whereHas('stocks', function($query) {
                        $query->where('status', 'available');
                    });
                })
                ->where(function($query) use($term) {
                    $sql = "name like ?";
                    $query->whereRaw($sql, ["%{$term}%"]);
                })
                ->orderBy('name', 'asc')
                ->simplePaginate(10);
            $morePages = true;
            if (empty($brands->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $brands->items(),
                "pagination" => ["more" => $morePages]
            );
            return Response::json($results);
        }
    }

    /**
     * Get record by id.
     */
    public function getBrand(Request $request, string $id)
    {
        if ($request->ajax()) {
            $brand = Brand::findOrFail($id);
            return Response::json($brand);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('brands.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        try {
            DB::beginTransaction();

            $brand = new Brand();
            $brand->fill($request->validated());
            $brand->save();

            DB::commit();
            return Redirect::route('brands.create')->with('status', 'Marca cadastrada com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('brands.create')->withErrors($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        dd('show.brand');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $brand = Brand::findOrFail($id);
            $brand->fill($request->validated());
            $brand->save();

            DB::commit();
            return Redirect::route('brands.edit', $id)->with('status', 'Marca atualizada com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('brands.edit', $id)->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('destroy.brand');
    }
}
