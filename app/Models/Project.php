<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Project extends Model
{

    //protected $table = 'projects';

    // for using $input = $request->all();
    //$project = Project::create($input);
    protected $fillable = [
        'name', 'label'
    ];


    public function documents()
    {
        //model, foreign key on the Document model is project_id, local id of category
        return $this->hasMany(Dataset::class, 'project_id', 'id');
    }

    // public function books()
    // {
    // //model, foreign key on the Book model is project_id, local id of category
    // return $this->hasMany('App\Book', 'project_id', 'id');
    // }
}
