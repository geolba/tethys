<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    protected $fillable = [
        'academic_title',
        'last_name',
        'first_name',
        'email',
        'identifier_orcid',
        'status'
    ];
    protected $table = 'persons';
    public $timestamps  = false;

    public function documents()
    {
        return $this->belongsToMany(\App\Dataset::class, 'link_documents_persons', 'person_id', 'document_id')
        ->withPivot('role');
    }


    // public function scopeNotLimit($query)
    // {
    // return $query->where('borrow', '<', 3);
    // }
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrderByName($query)
    {
        return $query->orderBy('last_name');
    }
}
