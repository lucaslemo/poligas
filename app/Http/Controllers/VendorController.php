<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Models\Address;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $vendors = Vendor::with('addresses');
            return DataTables::eloquent($vendors)
                ->editColumn('cnpj', function($vendor) {
                    return $vendor->cnpjFormatted();
                })
                ->editColumn('phone_number', function($vendor) {
                    return $vendor->phoneNumberFormatted();
                })
                ->addColumn('routeEdit', function($vendor) {
                    return route('vendors.edit', $vendor->id);
                })
                ->addColumn('addresses_count', function($vendor) {
                    return $vendor->addresses()->count();
                })
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vendors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VendorRequest $request)
    {
        try {
            DB::beginTransaction();

            $vendor = new Vendor();
            $vendor->fill($request->validated());
            $vendor->save();

            $address = new Address();
            $address->fill($request->validated());
            $address->primary = true;
            $address->get_vendor_id = $vendor->id;
            $address->save();

            DB::commit();
            return Redirect::route('vendors.create')->with('status', 'Fornecedor cadastrado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('vendors.create')->withErrors($th->getMessage());
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
        $vendor = Vendor::findOrFail($id);
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VendorRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $vendor = Vendor::findOrFail($id);
            $vendor->fill($request->validated());
            $vendor->save();

            DB::commit();
            return Redirect::route('vendors.edit', $id)->with('status', 'Fornecedor atualizado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('vendors.edit', $id)->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
