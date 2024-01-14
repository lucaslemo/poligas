<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select();
            return DataTables::eloquent($users)
                ->addColumn('full_name', function ($user) {
                    return $user->fullname();
                })
                ->addColumn('routeEdit', function ($user) {
                    return route('users.edit', $user->id);
                })
                ->addColumn('statusButton', function ($user) {
                    return view('users.partials.statusButton', ['user' => $user]);
                })
                ->filterColumn('full_name', function($query, $keyword) {
                    $sql = "CONCAT(users.first_name,'-',users.last_name) like ?";
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
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = new User;
            $user->fill($request->validated());
            $user->password = Hash::make(Str::random());
            $user->save();

            DB::commit();
            return Redirect::route('users.create')->with('status', 'Usuário cadastrado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('users.create')->withErrors($th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->fill($request->validated());
            $user->save();

            DB::commit();
            return Redirect::route('users.edit', $id)->with('status', 'Usuário atualizado com sucesso.');
        } catch (\Throwable $th) {
            DB::rollback();
            return Redirect::route('users.edit', $id)->withErrors($th->getMessage());
        }
    }

    /**
     * Update the status.
     */
    public function activateUser(Request $request, string $id)
    {
        if($request->ajax()) {
            try {
                DB::beginTransaction();
                $user = User::find($id);
                $user->status = true;
                $user->save();
                DB::commit();
                return response()->json(['message' => 'Usuário ativado com sucesso.']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }

    /**
     * Update the status.
     */
    public function deactivateUser(Request $request, string $id)
    {
        if($request->ajax()) {
            try {
                DB::beginTransaction();
                $user = User::find($id);
                $user->status = false;
                $user->save();
                DB::commit();
                return response()->json(['message' => 'Usuário desativado com sucesso.']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }

}
