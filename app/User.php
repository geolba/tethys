<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
#use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{

    use Authenticatable, CanResetPassword, Authorizable;
    use HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['login', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = app('hash')->needsRehash($password) ? Hash::make($password) : $password;
        }
    }

     //public function roles()
     //{
     //    return $this->belongsToMany(\App\Role::class, 'link_accounts_roles', 'account_id', 'role_id');
     //}

    public function is($roleName)
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->name == $roleName) {
                return true;
            }
        }

        return false;
    }

    // public function assignRole($role)
    // {
    //     return $this->roles()->attach($role);
    // }

    // public function revokeRole($role)
    // {
    //     return $this->roles()->detach($role);
    // }

    // public function hasRole($name)
    // {
    //     foreach ($this->roles as $role)
    //     {
    //         if ($role->name === $name)
    //         {
    //             return true;
    //         }
    //         return false;
    //     }
    //     return false;
    // }
}
