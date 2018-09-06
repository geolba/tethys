<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\ModelTrait;

class Page extends Model
{
    use ModelTrait;
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
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group action-btn">
                    '.$this->getEditButtonAttribute('page', 'settings.page.edit').'
                    '.$this->getViewButtonAttribute().'                    
                    '.$this->getDeleteButtonAttribute('page', 'settings.page.destroy').'
                </div>';
    }

      /**
     * @return string
     */
    public function getViewButtonAttribute()
    {
        return '<a target="_blank" href="'. route('frontend.pages.show', $this->page_slug) .'" class="btn btn-flat btn-default">
                    <i data-toggle="tooltip" data-placement="top" title="View Page" class="fa fa-eye"></i>
                </a>';
    }

     /**
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        if ($this->isActive()) {
            return "<label class='label label-success'>".trans('labels.general.active').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.inactive').'</label>';
    }
    
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }
}
