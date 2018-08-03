<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seen',
        'action',
        'object_id',
        'sender_id',
        'receiver_id',
        'message'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['since'];

    /**
     * User relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sender()
    {
        return $this->hasOne(User::class, "id", "sender_id");
    }

    /**
     * Human deference
     * @return mixed
     */
    public function getSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
