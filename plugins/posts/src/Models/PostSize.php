<?php

namespace Dot\Posts\Models;

use Dot\Platform\Model;

/**
 * Class PostMeta
 * @package Dot\Posts\Models
 */
class PostSize extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;


    public $incrementing =false;
    /**
     * @var string
     */
    protected $table = "posts_sizes";

}
