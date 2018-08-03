<?php

namespace App\Model;

use App\User;
use Dot\Posts\Models\Post as Model;

class Post extends Model
{

    /**
     * @var array
     */
    protected $searchable = ['title'];

    /**
     * User relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    /**
     * posts  which in  block relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function blocks()
    {
        return $this->belongsToMany(Post::class, "posts_blocks_orders", "post_id", "block_id")->withPivot('order')->orderBy("order", "ASC");
    }

    /**
     * Likes relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, "users_posts_like", "object_id", "user_id")
            ->where('type', 'item');
    }

}
