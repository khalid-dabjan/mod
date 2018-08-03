<?php

namespace App\Model;

use Dot\Categories\Models\Category as Model;

class Category extends Model
{

    /**
     * Items relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function items()
    {
        return $this->belongsToMany(Post::class, "posts_categories", "category_id",'post_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function parentCategory(){
        return $this->belongsTo(Category::class,'parent');
    }
}
