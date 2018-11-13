<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models/CountryTranslation.php
class PageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $guarded = ['id'];
}
