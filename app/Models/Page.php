<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Page extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The guarded field which are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'created_by' => 1,
    ];

    protected $with = ['owner'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'pages';//config('module.pages.table');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }
}
