<?php

namespace App\Models\Admin;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use DataTables;
use App\Models\Admin\OrderManagementComment;
use App\Models\Admin\Order;
use App\Models\Admin\UserDiscount;
use App\Models\Admin\LoadingSheet;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'store_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function team_leads()
    {
        return $this->belongsToMany(User::class, 'lead_member', 'team_lead_id', 'team_member_id');
    }

    public function team_members()
    {
        return $this->belongsToMany(User::class, 'lead_member', 'team_lead_id', 'team_member_id');
    }

    public function order_management_comments()
    {
        return $this->hasMany(OrderManagementComment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by', 'id');
    }

    public function user_discount()
    {
        return $this->hasOne(UserDiscount::class, 'user_id', 'id');
    }

    public function assigned_orders()
    {
        return $this->hasMany(Order::class, 'assigned_to', 'id');
    }

    public function loading_sheets()
    {
        return $this->hasMany(LoadingSheet::class);
    }

    function passwordUpdate($user_id, $old_pass = "-1", $new_pass="123456")
    {
        $user = self::where('id', $user_id)->first();
        if ($old_pass != "-1") {
            if (Hash::check($old_pass, $user->password)) {
                self::where('id', $user_id)->update(['password' => Hash::make($new_pass), 'textual_password' => $new_pass]);
                $status = "message";
                $msg = "Password updated successfully.";
            } else {
                $status = "error";
                $msg = "Password did not match!";
            }
        } else {
            self::where('id', $user_id)->update(['password' => Hash::make($new_pass), 'textual_password' => $new_pass]);
            $status = "message";
            $msg = "Password updated successfully.";
        }
        return [$status, $msg];
    }

    private function generateUsername($first_name, $last_name)
    {
        return strtolower(trim($first_name)) . "_" . strtolower(trim($last_name));
    }

    function _store($request)
    {
        $user =  new User();
        $user->first_name = capAll($request->first_name);
        $user->last_name = capAll($request->last_name);
        $user->username = $this->generateUsername($request->first_name, $request->last_name);
        $user->telephone = validateValAndNull($request->telephone);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->textual_password = $request->password;

        $user->country_id = validateValAndNull($request->country_id);
        $user->state_id = validateValAndNull($request->state_id);
        $user->city = validateValAndNull($request->city);
        $user->zip_code = validateValAndNull($request->zip_code);
        $user->address = validateValAndNull($request->address);
        $user->created_by = Auth::guard('web')->user()->id;

        $user->save();

        $user->assignRole($request->role);

        return $user;
    }

    function _update($request, $id)
    {
        $user = self::where('id', $id)->first();

        self::where('id', $id)->update([
            'first_name' => capAll($request->first_name),
            'last_name' => capAll($request->last_name),
            'username' => $this->generateUsername($request->first_name, $request->last_name),
            'telephone' => validateValAndNull($request->telephone),
            'email' => (isset($request->email) && !is_null($request->email)) ? $request->email : $user->email,
            'country_id' => validateValAndNull($request->country_id),
            'state_id' => validateValAndNull($request->state_id),
            'city' => validateValAndNull($request->city),
            'zip_code' => validateValAndNull($request->zip_code),
            'address' => validateValAndNull($request->address),
        ]);

        if ($request->has('password') && !is_null($request->password)) {
            self::where('id', $id)->update([
                'password' => Hash::make($request->password),
                'textual_password' => $request->password,
            ]);
        }

        if ($request->has('role') && !is_null($request->role)) {
            $user->syncRoles($request->role);
        }

        return $user;
    }

    function del($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        return self::with([
            'roles',
            'team_members',
            'user_discount'
        ])->where('id', $id)->first();
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            $users = self::select('id', 'first_name', 'last_name', 'email', 'textual_password', 'status', 'is_deleted')
                ->with('team_members')
                ->where('is_deleted', getConstant('IS_NOT_DELETED'));
                
                if (!Auth::guard('web')->user()->hasRole("Super Admin")) {
                    $users = $users->where('created_by', Auth::guard('web')->user()->id);
                }
                $users = $users->get();

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('team_members_count', function ($row) {
                    return count($row->team_members);
                })
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
                    $action = '<div class="dropdown">
                                    <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle" type="button" id="' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="' . $row->id . '">';

                    if ($row->roles['0']->name != "Super Admin") {
                        $team_members_count = 0;
                        if ($row->team_members) {
                            $team_members_count = count($row->team_members);
                        }
                        $action .= '<li>
                                        <a href="' . route('users.manageTeam', ['id' => $row->id]) . '"  class="dropdown-item">
                                            Manage Team  <span>(' . $team_members_count . ')</span>
                                        </a>
                                    </li>';
                    }

                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Users')) {
                        $action .= '<li>
                                        <a href="' . route('users.edit', ['id' => $row->id]) . '"  class="dropdown-item">
                                            <i class="far fa-edit me-2"></i> Edit
                                        </a>
                                    </li>';
                    }

                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Users')) {
                        $param = "'" . route('users.delete', ['id' => $row->id]) . "'";
                        $action .= '<li>
                                        <a href="javascript:void(0);"  class="dropdown-item" onclick="deleteData(' . $param . ')">
                                            <i class="far fa-trash-alt me-2"></i> Delete
                                        </a>
                                    </li>';
                    }

                    $action .= '</ul></div>';
                    return $action;
                })
                ->rawColumns(['role', 'permissions', 'status', 'action', 'team_members_count'])
                ->make(true);
        }
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = self::where(['id' => $id])->update(['status' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
    }

    function _bulkDelete($request)
    {
        // return $request;
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = self::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
    }

    function _assignUnassignTeamLead($request)
    {
        /**
         * request params
         */
        $users = $request->user_id;
        $team_lead_id = $request->team_lead_id;
        /**
         * filtering users id list to get users with roles others than `Super Admin`
         */
        $users_in = self::whereIn('id', $users)->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'Super Admin');
        })->pluck('id');
        /** */
        if (($key = array_search($team_lead_id, $users_in)) !== false) {
            unset($users_in[$key]);
        }
        /**
         * updating users team lead in based on filtered user ids
         */
        self::whereIn('id', $users_in)->update(['team_lead_id' => $team_lead_id]);

        return json_encode(['status' => true, 'data' => 'Success']);
    }
}
