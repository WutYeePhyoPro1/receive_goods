<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'employee_code',
        'password',
        'password_str',
        'role',
        'department_id',
        'branch_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }


    public function user_branches()
    {
        return $this->hasMany(UserBranch::class,'user_id','id');
    }

    public function roleName()
    {
        return $this->roles->first()->name;
    }

    public function getGRBy(){
        $user = $this;

        $role = Role::where('name','user')->first();
        $role_id = $role->id;

        $user_branches = $user->user_branches;
        $branch_ids = $user_branches->pluck('branch_id')->toArray();
        $branch_ids[] = $user->branch_id;

        $users = User::where('role',$role_id)
                    ->whereIn('branch_id',$branch_ids)
                    ->orWhereHas('user_branches',function($query) use($branch_ids){
                        $query->where('branch_id',$branch_ids);
                    })
                    ->get();

        // dd($users);

        return $users;
    }


}
