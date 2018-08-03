<?php

namespace App;

use App\Model\Set;
use App\Model\Post;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \Dot\Users\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = ['avatar'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'photo'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login', 'created_at', 'updated_at','suspended_to'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany(User::class, "user_follow", "following_id", "follower_id");
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function follower()
    {
        return $this->belongsToMany(User::class, "user_follow", "follower_id", "following_id");
    }


    /**
     * Likes items relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function liked_items()
    {
        return $this->belongsToMany(Post::class, "users_posts_like", "user_id", 'object_id')
            ->where('type', 'item');
    }

    /**
     * Likes sets relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function liked_sets()
    {
        return $this->belongsToMany(Set::class, "users_posts_like", "user_id", 'object_id')
            ->where('type', 'set');
    }


    /**
     * Blocked Users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function blocked_users()
    {
        return $this->belongsToMany(User::class, "users_blocked", "user_id", "blocked_id");
    }

    /**
     * @return string
     */
    public function getAvatarAttribute()
    {
        return $this->photo ? thumbnail($this->photo->path) : null;
    }
}
