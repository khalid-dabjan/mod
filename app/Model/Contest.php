<?php

namespace App\Model;

use App\User;
use Dot\Posts\Models\Contest as Model;

class Contest extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates=[
        'published_at',
        'expired_at'
    ];

    /**
     * Winner items
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function winner(){
        return $this->belongsTo(ContestItem::class,'winner_id','id');
    }

    /**
     * Winner items
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function winners(){
        return $this->belongsToMany(ContestItem::class,'contests_winners','contest_id','winner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(){
        return $this->hasMany(ContestItem::class,'contest_id','id');
    }

    /**
     * Likes relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, "users_posts_like", "object_id", "user_id")
            ->where('type', 'contest');
    }
}
