<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'year',
        'stock',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }

    // public function shelf()
    // {
    // return $this->belongsTo('App\Shelf');
    // }

    public function transactions()
    {
        //model, foreign key on the Transaction model is book_id, local id
        return $this->hasMany('App\Transaction', 'book_id', 'id');
    }



    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOrderByTitle($query)
    {
        return $query->orderBy('title');
    }

    public function hasProject()
    {
        return $this->project()->exists();
    }
}
