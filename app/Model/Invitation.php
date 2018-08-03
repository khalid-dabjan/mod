<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='groups_users';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps=false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];




    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('invite', function (Builder $builder) {
            $builder->where('type', 'invite');
        });
    }

    /**
     * user relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function user()
    {
        return $this->hasOne(User::class, "id", "inviter_id");
    }

    /**
     * Group relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function group()
    {
        return $this->hasOne(Group::class, "id", "group_id");
    }
}
