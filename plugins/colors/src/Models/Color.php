<?php

namespace Dot\Colors\Models;

use Cache;
use Dot\Platform\Model;
use Dot\Users\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as OModel;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Class Post
 * @package Dot\Posts\Models
 */
class Color extends Model
{

    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * @var string
     */
    protected $table = 'colors';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var array
     */
    protected $searchable = ['name'];

    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var array
     */
    protected $sluggable = [
    ];

    /**
     * @var array
     */
    protected $creatingRules = [
        'value' => 'required',
        'name' => 'required',
    ];

    /**
     * @var array
     */
    protected $updatingRules = [
        'value' => 'required',
        'name' => 'required',

    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new class implements Scope
        {
            /**
             * Apply the scope to a given Eloquent query builder.
             *
             * @param  \Illuminate\Database\Eloquent\Builder $builder
             * @param  \Illuminate\Database\Eloquent\Model $model
             * @return Builder
             */
            public function apply(Builder $builder, OModel $model)
            {

                return $builder->where('colors.lang', app()->getLocale());
            }
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
