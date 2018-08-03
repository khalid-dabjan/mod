<?php

namespace App\Model;

use App\User;
use Dot\Posts\Models\Set as Model;

class Set extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'excerpt',
        'lang',
        'image_id',
        'user_id',
        'front_page',
        'background'
    ];

    /**
     * Likes relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, "users_posts_like", "object_id", "user_id")
            ->where('type', 'set');
    }

    /**
     * Comments relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(SetComment::class, "set_id", "id")->where('parent', 0);
    }


    /**
     * User relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }


    /**
     * Items relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Post::class, 'sets_posts', "set_id", "post_id")->withPivot(['x','y','width','height','rotation']);
    }

}
