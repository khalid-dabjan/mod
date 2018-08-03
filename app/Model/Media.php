<?php

namespace App\Model;

use Dot\Media\Models\Media as Model;

class Media extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=['title','excerpt','image_id'];

}
