<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
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
     * Get records.
     */
    public function getCustomers(Request $request)
    {
        if ($request->ajax()) {
            $term = $request->term ? trim($request->term) : null;
            $customers = Customer::select('id',  'name AS text')
                ->where(function($query) use($term) {
                    $sql = "name like ?";
                    $query->whereRaw($sql, ["%{$term}%"]);
                })
                ->orderBy('name', 'asc')
                ->simplePaginate(10);
            $morePages = true;
            if (empty($customers->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $customers->items(),
                "pagination" => ["more" => $morePages]
            );
            return Response::json($results);
        }
    }

    /**
     * Get record by id.
     */
    public function getCustomer(Request $request, string $id)
    {
        if ($request->ajax()) {
            $customer = Customer::findOrFail($id);
            return Response::json($customer);
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
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($id);
            $customer->fill($request->validated());
            $customer->save();

            DB::commit();
            return Redirect::route('customers.edit', $id)->with('status', 'Cliente atualizado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('customers.edit', $id)->withErrors($th->getMessage());
        }
    }
}
