<?php

namespace App\Model;

use App\User;
use Dot\Posts\Models\Collection as Model;

class Collection extends Model
{
    protected $fillable=['title','excerpt','image_id','lang','user_id'];

    /**
     * User relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    /**
     * Sets relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sets()
    {
        return $this->belongsToMany(Set::class, "collections_sets", "collection_id","set_id");
    }


    /**
     * Likes relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, "users_posts_like", "object_id", "user_id")
            ->where('type', 'collection');
    }
}
