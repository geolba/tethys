<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends EntrustRole
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];
}
