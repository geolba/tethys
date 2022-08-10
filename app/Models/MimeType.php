<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MimeType extends Model
{
    use HasFactory;
    protected $table = 'mime_types';

    // for using $input = $request->all();
    //$project = Project::create($input);
    protected $fillable = [
        'name', 'file_extension', 'enabled',
    ];
}
