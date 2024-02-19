<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PaymentTypeController extends Controller
{
    /**
     * Get records.
     */
    public function getPaymentTypes(Request $request)
    {
        if ($request->ajax()) {
            $term = $request->term ? trim($request->term) : null;
            $paymentTypes = PaymentType::select('id',  'name AS text')
                ->where(function($query) use($term) {
                    $sql = "name like ?";
                    $query->whereRaw($sql, ["%{$term}%"]);
                })
                ->orderBy('id', 'asc')
                ->simplePaginate(10);
            $morePages = true;
            if (empty($paymentTypes->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $paymentTypes->items(),
                "pagination" => ["more" => $morePages]
            );
            return Response::json($results);
        }
    }

    /**
     * Get record by id.
     */
    public function getPaymentType(Request $request, string $id)
    {
        if ($request->ajax()) {
            $paymentType = PaymentType::findOrFail($id);
            return Response::json($paymentType);
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
