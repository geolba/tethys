<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

trait ModelTrait
{
    /**
     * @return string
     */
    public function getEditButtonAttribute($permission, $route)
    {
        if (Auth::user()->can($permission)) {
            return '<a href="'.route($route, $this).'" class="btn btn-flat btn-default">
                    <i data-toggle="tooltip" data-placement="top" title="Edit" class="fas fa-pencil-ruler"></i>
                </a>';
        }
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute($permission, $route)
    {
        if (Auth::user()->can($permission)) {
            return '<a href="'.route($route, $this).'" 
                    class="btn btn-flat btn-default" data-method="delete"
                    data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                    data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                    data-trans-title="'.trans('strings.backend.general.are_you_sure').'">
                        <i data-toggle="tooltip" data-placement="top" title="Delete" class="fas fa-trash"></i>
                </a>';
        }
    }
}
