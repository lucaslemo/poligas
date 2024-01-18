<?php

namespace App\Http\Controllers\ACL;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * DataTable.
     */
    public function loadDataTable(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::with('roles')->select(['*']);
            return DataTables::eloquent($permissions)
                ->addColumn('roles', function($permission) {
                    $roles = '';
                    foreach($permission->roles as $role) {
                        $roles .= $role->name . ', ';
                    }
                    return rtrim($roles, ', ');
                })
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('acl.permissions.index');
    }
}