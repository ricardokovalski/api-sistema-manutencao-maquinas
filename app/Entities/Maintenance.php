<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

/**
 * Class Maintenance
 * @package App\Entities
 */
class Maintenance extends Model implements AuditableContract
{
    use Auditable;

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
        'review_at',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pieces()
    {
        return $this->belongsToMany(Peace::class, 'maintenance_peaces', 'maintenance_id', 'peace_id')
            ->withPivot('amount_used')
            ->withTimestamps();
    }

}
