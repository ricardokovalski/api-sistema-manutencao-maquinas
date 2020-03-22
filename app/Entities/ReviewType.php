<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
//use Prettus\Repository\Contracts\Transformable;
//use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ReviewType.
 *
 * @package namespace App\Entities;
 */
class ReviewType extends Model
{
    //use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

}
