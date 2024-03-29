<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Models\Dataset;

class User extends Authenticatable
{
    // use Authenticatable, CanResetPassword, Authorizable;
    use HasFactory, Notifiable;

    // use HasRoles;
    use EntrustUserTrait;


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

    public function datasets()
    {
        //model, foreign key on the User model is account_id, local id of user
        return $this->hasMany(Dataset::class, 'account_id', 'id');
    }

    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = app('hash')->needsRehash($password) ? Hash::make($password) : $password;
        }
    }

    
    public function getAvatarUrl()
    {
        return "https://www.gravatar.com/avatar/" . md5($this->email) . "?d=mm";
    }

    public function getRoleNames(): Collection
    {
        return $this->roles->pluck('name');
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
