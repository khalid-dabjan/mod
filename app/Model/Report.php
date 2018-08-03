<?php

namespace App\Model;

use Dot\Posts\Models\Report as Model;

class Report extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'message', 'title', 'user_id', 'type', 'object_id', 'report','format'];
}
