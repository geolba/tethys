<?php

namespace App\Models;

// 1. To specify package’s class you are using
use App\Models\ModelTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;// use Dimsav\Translatable\Translatable;

class Page extends Model implements TranslatableContract
{
    use ModelTrait;
    use Translatable; // 2. To add translation methods
   
    // 3. To define which attributes needs to be translated
    public $translatedAttributes = ['title', 'description'];

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

    //You can specify default eager loaded relationships using the $with property on the model.
    //https://stackoverflow.com/questions/25674143/laravel-whenever-i-return-a-model-always-return-a-relationship-with-it
    protected $with = ['owner'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'pages'; //config('module.pages.table');
        // $this->defaultLocale = 'de';
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
                    ' . $this->getEditButtonAttribute('page', 'settings.page.edit') . '
                    ' . $this->getViewButtonAttribute() . '
                </div>';
        // '.$this->getDeleteButtonAttribute('page', 'settings.page.destroy').'
    }

    /**
     * @return string
     */
    public function getViewButtonAttribute()
    {
        return '<a target="_blank" href="
                ' . route('frontend.pages.show', $this->page_slug) . ' " class="btn btn-flat btn-default">
                    <i data-toggle="tooltip" data-placement="top" title="View Page" class="fa fa-eye"></i>
                </a>';
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        if ($this->isActive()) {
            return "<label class='label label-success'>" . trans('labels.general.active') . '</label>';
        }

        return "<label class='label label-danger'>" . trans('labels.general.inactive') . '</label>';
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }
}
