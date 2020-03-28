<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Maintenance
 * @package App\Entities
 */
class Maintenance extends Model
{
    public $table = 'maintenance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'machine_id',
        'review_type_id',
        'description',
    ];

    protected $dates = [
        'review_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviewType()
    {
        return $this->belongsTo(ReviewType::class);
    }

}
