<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class AddressController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $id = trim($request->customer_id);
            $addresses = Address::with('customer')->whereHas('customer', function($query) use($id){
                $query->where('id', $id);
            });
            return DataTables::eloquent($addresses)
                ->editColumn('street', function($address) {
                    return $address->street . ', ' . $address->number;
                })
                ->editColumn('zip_code', function($address) {
                    return $address->zipCodeFormatted();
                })
                ->editColumn('primary', function($address) {
                    return $address->primary ? 'Sim' : 'Não';
                })
                ->addColumn('routeEdit', function($address) {
                    return route('addresses.edit', $address->id);
                })
                ->addColumn('hoverComplement', function($address) {
                    return view('customers.partials.hoverComplement', ['complement' => $address->complement]);
                })
                ->addColumn('primaryButton', function($address) {
                    return view('customers.partials.primaryButton', ['address' => $address]);
                })
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(AddressRequest $request)
    {
        if($request->ajax()) {
            try {
                DB::beginTransaction();

                $address = new Address();
                $address->fill($request->validated());
                $address->save();

                DB::commit();
                return response()->json(['message' => 'Endereço atribuído com sucesso.']);
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
        $address = Address::with('customer')->findOrFail($id);
        return view('addresses.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $address = Address::findOrFail($id);
            $address->fill($request->validated());
            $address->save();

            DB::commit();
            return Redirect::route('addresses.edit', $id)->with('status', 'Endereço atualizado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('addresses.edit', $id)->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $address = Address::findOrFail($id);
            $customerId = $address->get_customer_id;
            $address->delete();

            DB::commit();
            return Redirect::route('customers.edit', $customerId)->with('status', 'Endereço excluído com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('customers.edit', $customerId)->withErrors($th->getMessage());
        }
    }

    /**
     * Update primary specified resource in storage.
     */
    public function primary(Request $request, string $id)
    {
        if($request->ajax()) {
            try {
                DB::beginTransaction();

                Address::where('primary', true)->update(['primary' => false]);
                $address = Address::findOrFail($id);
                $address->primary = true;
                $address->save();

                DB::commit();
                return response()->json(['message' => 'Endereço atualizado como principal com sucesso.']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }
}
