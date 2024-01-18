<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request, string|null $type = null)
    {
        if ($request->ajax()) {
            $customers = Customer::with('addresses')
                ->when($type, function($query) use($type) {
                    $query->where('type', $type);
                });
            return DataTables::eloquent($customers)
                ->editColumn('code', function($customer) {
                    return $customer->codeFormatted();
                })
                ->editColumn('phone_number', function($customer) {
                    return $customer->phoneNumberFormatted();
                })
                ->addColumn('routeEdit', function($customer) {
                    return route('customers.edit', $customer->id);
                })
                ->addColumn('addresses_count', function($customer) {
                    return $customer->addresses()->count();
                })
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        try {
            DB::beginTransaction();

            $customer = new Customer();
            $customer->fill($request->validated());
            $customer->save();

            $address = new Address();
            $address->fill($request->validated());
            $address->primary = true;
            $address->get_customer_id = $customer->id;
            $address->save();

            DB::commit();
            return Redirect::route('customers.create')->with('status', 'Cliente cadastrado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('customers.create')->withErrors($th->getMessage());
        }
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
    public function update(CustomerRequest $request, string $id)
    {
        //
    }
}
