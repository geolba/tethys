<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models/CountryTranslation.php
class TitleTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $guarded = ['id'];
}
