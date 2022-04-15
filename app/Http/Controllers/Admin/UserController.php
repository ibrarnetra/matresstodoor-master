<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\Zone;
use App\Models\Admin\User;
use App\Models\Admin\Country;
use App\Models\Admin\UserDiscount;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Users')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'user_management';
            $active = 'users';
            $title = 'Users';
            $users = User::where('is_deleted', getConstant('IS_NOT_DELETED'));
            if (!Auth::guard('web')->user()->hasRole("Super Admin")) {
                $users = $users->where('created_by', Auth::guard('web')->user()->id);
            }
            $users = $users->get();

            return view('admin.users.index', compact('menu_1', 'sub_menu', 'active', 'title', 'users'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Users')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'user_management';
            $active = 'users';
            $title = 'Create User';
            $type = 'create';

            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $roles = Role::select('id', 'name')->orderBy('id', 'ASC');
            if (Auth::guard('web')->user()->hasRole("Office Admin")) {
                $roles->whereIn('id', ['6', '9', '5', '3']);
            } else if (Auth::guard('web')->user()->hasRole("Sales Manager")) {
                $roles->whereIn('id', ['3']);
            } else if (Auth::guard('web')->user()->hasRole("Delivery Manager")) {
                $roles->whereIn('id', ['6']);
            }
            $roles = $roles->get();

            return view('admin.users.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'roles', 'countries', 'zones'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        //        return $request;
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,is_deleted,' . getConstant('IS_NOT_DELETED'),
            'password' => 'required|min:8',
            'telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'role' => 'required',
        ]);

        $allowed_discount =  (isset($request->allowed_discount) && !is_null($request->allowed_discount)) ? $request->allowed_discount : "0.0";

        $inserted = (new User())->_store($request);

        if ($inserted) {

            (new UserDiscount())->_insert($inserted->id, $allowed_discount);
            return redirect()->route('users.index')->with('success', 'User Added Successfully');
        }
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();
        $permissions = $user->getAllPermissions();
        $view = view('admin.users.modal', compact('permissions'))->render();
        return json_encode(['status' => true, 'data' => $view]);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Users')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'user_management';
            $active = 'users';
            $title = 'Edit User';
            $type = 'edit';

            $modal = (new User())->fetchData($id);

            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $country_id = (isset($modal->country_id) && !is_null($modal->country_id)) ? $modal->country_id : '38';

            $zones = Zone::where('country_id', $country_id) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $roles = Role::select('id', 'name')->orderBy('id', 'ASC');
            if (Auth::guard('web')->user()->hasRole("Office Admin")) {
                $roles->whereIn('id', ['6', '9', '5', '3']);
            } else if (Auth::guard('web')->user()->hasRole("Sales Manager")) {
                $roles->whereIn('id', ['3']);
            } else if (Auth::guard('web')->user()->hasRole("Delivery Manager")) {
                $roles->whereIn('id', ['6']);
            }
            $roles = $roles->get();

            return view('admin.users.form', compact('menu_1', 'sub_menu', 'active', 'title', 'modal', 'type', 'id', 'roles', 'countries', 'zones'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id,is_deleted,' . getConstant('IS_NOT_DELETED'),
            'telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'role' => 'required',

        ]);
        $allowed_discount =  (isset($request->allowed_discount) && !is_null($request->allowed_discount)) ? $request->allowed_discount : "0.0";
        $update = (new User())->_update($request, $id);


        if ($update) {
            (new UserDiscount())->_insert($update->id, $allowed_discount);
            return redirect()->route('users.index')->with('success', 'User Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted user.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Users')) {
            $del = (new User())->del($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new User())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new User())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new User())->_bulkDelete($request);
    }

    public function manageTeam($id)
    {

        ### CONST ###
        $menu_1 = 'system';
        $sub_menu = 'user_management';
        $active = 'users';
        $title = 'Manage Team';

        $modal = (new User())->fetchData($id);
        $users = User::select('id', 'first_name', 'last_name')->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'Super Admin');
        })
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('id', '!=', $id)
            ->get();

        $team_members = [];
        if (count($modal->team_members) > 0) {
            foreach ($modal->team_members as $team_member) {
                array_push($team_members, $team_member->id);
            }
        }

        return view('admin.users.manage_team', compact('menu_1', 'sub_menu', 'active', 'title', 'modal', 'users', 'id', 'team_members'));
    }

    public function assignUnassignTeamLead(Request $request)
    {
        $team_lead = User::where('id', $request->team_lead_id)->first();
        ### SYNCING FOR PIVOT ###
        $team_lead->team_members()->sync($request->team_members);

        if ($team_lead) {
            return redirect()->route('users.index')->with('success', 'Team members assigned successfully');
        }
    }
}
