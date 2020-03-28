<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Peace
 * @package App\Entities
 */
class Peace extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'stock_quantity',
        'minimal_quantity',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function maintenance()
    {
        return $this->belongsToMany(Maintenance::class, 'maintenance_peaces', 'peace_id', 'maintenance_id')
            ->withPivot('amount_used')
            ->withTimestamps();
    }
}
