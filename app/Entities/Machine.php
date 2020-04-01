<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'technical',
        'patrimony',
        'review_period',
        'warning_period',
        'warning_email_address',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'machine_users', 'machine_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pieces()
    {
        return $this->belongsToMany(Peace::class, 'machine_pieces', 'machine_id', 'piece_id')
            ->withPivot('minimal_quantity')
            ->withTimestamps();
    }
}
