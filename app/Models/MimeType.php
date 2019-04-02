<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MimeType extends Model
{

    protected $table = 'mime_types';

    // for using $input = $request->all();
    //$project = Project::create($input);
    protected $fillable = [
        'name', 'file_extension', 'enabled',
    ];
}
