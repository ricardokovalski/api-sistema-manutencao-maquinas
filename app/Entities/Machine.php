<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use SoftDeletes;

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
        'deleted_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'machine_users', 'machine_id', 'user_id')
            ->whereNull('machine_users.deleted_at')
            ->withTimestamps()
            ->withPivot('deleted_at');
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
            ->whereNull('machine_pieces.deleted_at')
            ->withTimestamps()
            ->withPivot('minimal_quantity', 'deleted_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'machine_files', 'machine_id', 'file_id')
            ->whereNull('machine_files.deleted_at')
            ->withTimestamps()
            ->withPivot('deleted_at');
    }
}
