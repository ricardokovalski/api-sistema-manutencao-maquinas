<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Peace
 * @package App\Entities
 */
class Peace extends Model
{
    use SoftDeletes;

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
        'deleted_at',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function machines()
    {
        return $this->belongsToMany(Machine::class, 'machine_pieces', 'piece_id', 'machine_id')
            ->whereNull('machine_pieces.deleted_at')
            ->withTimestamps()
            ->withPivot('minimal_quantity', 'deleted_at');
    }
}
