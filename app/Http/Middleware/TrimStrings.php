<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as BaseTrimmer;

/**
* TrimStrings short summary.
*
* TrimStrings description.
*
* @version 1.0
* @author kaiarn
*/
class TrimStrings extends BaseTrimmer
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
