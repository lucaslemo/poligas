<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
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
        //
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
