<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageTranslation extends Model
{
    Use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $guarded = ['id'];
}
