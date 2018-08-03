<?php

namespace Dot\Posts\Models;

use App\Model\ContestComment;
use App\Model\SetComment;
use Cache;
use Dot\Media\Models\Media;
use Dot\Platform\Model;
use Dot\Users\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as OModel;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;


/**
 * Class Report
 * @package Dot\Posts\Models
 */
class Report extends Model
{

    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * @var string
     */
    protected $table = 'reports';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var array
     */
    protected $searchable = ['title'];

    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var array
     */
    protected $creatingRules = [
        'title' => 'required',

    ];

    /**
     * @var array
     */
    protected $updatingRules = [
        'title' => 'required',
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

//                if (GUARD == "api" && Auth::guard("api")->check()) {
//                    $lang = Auth::guard("api")->user()->lang;
//                } else {
//
//                }
//                $lang = app()->getLocale();
//                if ($lang) {
//                    return $builder->where('brands.lang', $lang);
//                }

            }
        });
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
     * @return null
     */
    public function getTargetAttribute()
    {
        $object = null;

        if ($this->type == "set") {
            $object = Set::find($this->object_id);
        }

        if ($this->type == "collection") {
            $object = Collection::find($this->object_id);
        }

        if ($this->type == "set_comment") {
            $object = SetComment::find($this->object_id);
        }

        if ($this->type == "contest_comment") {
            $object = ContestComment::find($this->object_id);
        }

        return $object;
    }

//    public function
}
