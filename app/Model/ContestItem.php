<?php

namespace App\Model;

use App\User;
use Dot\Media\Models\Media;
use Illuminate\Database\Eloquent\Model;

class ContestItem extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates=[
        'created_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected  $fillable=[
        'image_id','user_id','contest_id','title'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contests_items';

    /**
     * Contest
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contest()
    {
        return $this->hasOne(Contest::class, "id", "image_id");
    }

    /**
     * Image relation
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(Media::class, "id", "image_id");
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
     * Likes relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, "users_posts_like", "object_id", "user_id")
            ->where('type', 'contest_item');
    }
}
