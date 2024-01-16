<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request, string|null $role = null)
    {
        if ($request->ajax()) {
            $id = trim($request->delivery_men_from_manger_id);
            $users = User::select()
                ->when($role, function($query) use($role) {
                    $query->where('type', $role);
                })
                ->when($id, function($query) use($id){
                    $query->with('managers');
                    $query->whereHas('managers', function($query) use($id){
                        $query->where('get_manager_user_id', $id);
                    });
                });
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
                ->addColumn('detachButton', function ($user) {
                    return view('users.partials.detachButton', ['user' => $user]);
                })
                ->addColumn('assign_at', function ($user) {
                    $assign_at = isset($user->managers[0])
                        ? $user->managers[0]->pivot->created_at
                        : null;
                    return $assign_at;
                })
                ->filterColumn('full_name', function($query, $keyword) {
                    $sql = "CONCAT(users.first_name,' ',users.last_name) like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->orderColumn('full_name', function($query, $order) {
                    $query->orderBy('first_name', $order);
                    $query->orderBy('last_name', $order);
                })
                ->make(true);
        }
    }

    /**
     * Get records.
     */
    public function getUsers(Request $request, string|null $role = null)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $users = User::select('id',  DB::raw("CONCAT(first_name, ' ', last_name) as text"))
                ->where(function($query) use($term) {
                    $sql = "CONCAT(users.first_name,'-',users.last_name) like ?";
                    $query->whereRaw($sql, ["%{$term}%"]);
                })
                ->when($role, function($query) use($role) {
                    $query->where('type', $role);
                })
                ->orderBy('first_name', 'asc')
                ->simplePaginate(10);
            $morePages = true;
            if (empty($users->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $users->items(),
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
        return view('users.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function deliveryMenIndex()
    {
        return view('deliveryMen.index');
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

            $user->assignRole($user->type);

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

            $user->syncRoles($user->type);
            $user->deliveryMen()->detach();

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
                $user = User::findOrFail($id);
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
                $user = User::findOrFail($id);

                if ($user->hasRole('Administrador')) {
                    throw new \Exception('Essa ação não pode ser realizada.');
                }

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

    /**
     * Update the assign deliveryman.
     */
    public function assignDeliveryman(Request $request, string $id)
    {
        if($request->ajax()) {
            try {
                $request->validate([
                    'deliveryman_id' => ['required', 'numeric']
                ]);

                DB::beginTransaction();
                $user = User::findOrFail($id);

                $user->deliveryMen()->sync($request->deliveryman_id, false);

                DB::commit();
                return response()->json(['message' => 'Entregador adicionado com sucesso.']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }

    /**
     * Update the assign deliveryman.
     */
    public function unassignDeliveryman(Request $request, string $id)
    {
        if($request->ajax()) {
            try {
                $request->validate([
                    'deliveryman_id' => ['required', 'numeric']
                ]);

                DB::beginTransaction();
                $user = User::findOrFail($id);

                $user->deliveryMen()->detach($request->deliveryman_id);

                DB::commit();
                return response()->json(['message' => 'Entregador removido com sucesso.']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }

}
