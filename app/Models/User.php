<?php
namespace App\Models;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable //implements MustVerifyEmail {
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'fname',
        'email',
        'role',
        'department_id',
        'telephone1',
        'telephone2',
        'address',
        'password',
        'status',
        'profile',
        'gender',
        'cnic',
        'dob',
        'cnicf',
        'cnicb',
        'emgname',
        'emgrnum',
        'emgrrelation',
        'parent',
        'special',
	    'sales_target',
        'unit_target',
        'designation_name',
        'office_id',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function parentname() {
        return $this->belongsTo(\App\Models\User::class, 'parent');
    }
    public function department() {
        return $this->hasOne(Departments::class, 'id','department_id');
    }
    public function office() {
        return $this->hasOne(Offices::class, 'id','office_id');
    }
    public function designation() {
        return $this->hasOne(Designations::class, 'id','role');
    }
    public function childs() {
        return $this->hasMany('App\Models\User', 'parent', 'id');
    }

    /**
     * This user's id plus every descendant's id, walked down the `parent`
     * self-reference level by level (not just direct reports). Depth is
     * capped only as a guard against a corrupt/cyclic parent chain.
     *
     * @return int[]
     */
    public function subordinateIds(): array
    {
        $ids = [$this->id];
        $frontier = [$this->id];

        for ($depth = 0; $depth < 25 && !empty($frontier); $depth++) {
            $children = self::whereIn('parent', $frontier)->pluck('id')->diff($ids)->values()->all();
            if (empty($children)) {
                break;
            }
            $ids = array_merge($ids, $children);
            $frontier = $children;
        }

        return $ids;
    }

    /**
     * Resolves which user ids this user is allowed to see data for, within
     * a given permission module (e.g. 'lead', 'client', 'affiliator',
     * 'staff', 'target'), based on the "{module}.data.all" / "{module}.data.own"
     * permissions. Returns null when there is no restriction (show everything).
     *
     * @return int[]|null
     */
    public function visibleUserIds(string $module): ?array
    {
        if ($this->can("{$module}.data.all")) {
            return null;
        }
        if ($this->can("{$module}.data.own")) {
            return $this->subordinateIds();
        }
        return [$this->id];
    }

    public function canSeeAllData(string $module): bool
    {
        return $this->can("{$module}.data.all");
    }
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new CustomResetPasswordNotification($token));
    }
    public function leads_count()
    {
        return $this->hasMany(\App\Models\Leads::class, 'user_id','id');
    }
    public function client_count() {
        return $this->hasMany(\App\Models\Clients::class, 'user_id','id');
    }
    public function affiliator_count() {
        return $this->hasMany(\App\Models\Affiliator::class, 'user_id','id');
    }




    // getting all the group names
    public static function getPermissionGroups() // passing to role has permission controller in create function
    {
        // $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        $permission_groups = DB::table('permissions')
        ->select('group_name', DB::raw('MAX(id) as max_id'))
        ->groupBy('group_name')
        ->orderBy('max_id', 'asc')
        ->get();

        return $permission_groups;
    } // End Method

    // Getting all the permissions name againts group name
    public static function getPermissionByGroupName($group_name)
    {
        $permissions = DB::table('permissions')->select('name','id')->where('group_name',$group_name)->get();
        return $permissions;
    }// End Method

    // for edit page if role have permission then checkbox should be checked
    public static function roleHasPermissions($role,$permissions)
    {
        $hasPermission = true;
        foreach($permissions as $permission)
        {
            if (!$role->hasPermissionTo($permission->name))
            {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }// End Method
}
