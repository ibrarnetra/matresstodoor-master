<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Roles')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'user_management';
            $active = 'roles';
            $title = 'Roles';

            $roles = \Spatie\Permission\Models\Role::select('id', 'name')
                ->with([
                    'permissions' => function ($q) {
                        return $q->select('id', 'name')->orderBy('id', 'ASC');
                    }
                ])
                ->withCount('users')
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.roles.index', compact('menu_1', 'sub_menu', 'active', 'title', 'roles'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Roles')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'user_management';
            $active = 'roles';
            $title = 'Create Role';
            $type = 'create';

            $permissions = Permission::orderBy('id', 'ASC')->get(['id', 'name']);

            return view('admin.roles.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'permissions'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions.*' => 'required',
        ]);

        $role = \Spatie\Permission\Models\Role::create(['name' => capAll($request->name)]);

        $role->givePermissionTo($request->permissions);

        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role Added Successfully');
        }
    }

    public function show($id)
    {
        $role = \Spatie\Permission\Models\Role::select('id', 'name')
            ->with([
                'permissions' => function ($q) {
                    return $q->select('id', 'name')->orderBy('id', 'ASC');
                }
            ])->where('id', $id)
            ->first();

        $view = view('admin.roles.modal', compact('role'))->render();
        return json_encode(['status' => true, 'data' => $view]);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Roles')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'user_management';
            $active = 'roles';
            $title = 'Edit Role';
            $type = 'edit';

            $modal = \Spatie\Permission\Models\Role::select('id', 'name')->where('id', $id)->first();
            $old_permission_arr = $modal->permissions->pluck('id')->toArray();
            $permissions = Permission::orderBy('id', 'ASC')->get(['id', 'name']);

            return view('admin.roles.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'modal', 'permissions', 'old_permission_arr', 'id'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permissions.*' => 'required',
        ]);

        $role = \Spatie\Permission\Models\Role::where(['id' => $id])->first();

        $role->syncPermissions($request->permissions);

        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted role.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Roles')) {
            $del = \Spatie\Permission\Models\Role::where('id', $id)->delete();

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function dataTable(Request $request)
    {
        if ($request->ajax()) {
            $users = self::where('is_deleted', getConstant('IS_NOT_DELETED'))

                ->get(['id', 'username', 'email', 'status', 'is_deleted']);

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $role = 'N/A';
                    if (count($row->roles) > 0) {
                        $role = $row->roles[0]->name;
                    }
                    return $role;
                })
                ->addColumn('permissions', function ($row) {
                    $permissions = 'N/A';
                    $param = "'" . route('users.show', ['id' => $row->id]) . "'";
                    if (count($row->roles) > 0) {
                        $permissions = '<a href="javascript:void(0);" class="badge badge-light-primary" onclick="loadModal(' . $param . ')" data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Allowed Permissions">
                                        Permissions
                                    </a>';
                    }
                    return $permissions;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('users.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if ($row->hasPermissionTo('Edit-Users')) {
                        $action .= '<a href="' . route('users.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if ($row->hasPermissionTo('Delete-Users')) {
                        $param = "'" . route('users.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                        onclick="deleteData(' . $param . ')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>';
                    }
                    return $action;
                })
                ->rawColumns(['role', 'permissions', 'status', 'action'])
                ->make(true);
        }
    }

    public function bulkDelete(Request $request)
    {
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = Role::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
    }
}
