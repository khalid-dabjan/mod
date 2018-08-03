<?php

use Dot\Posts\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


if (!function_exists('fauth')) {
    /**
     * @return mixed
     */
    function fauth()
    {
        return Auth::guard('frontend');
    }
}


if (!function_exists('getBrandId')) {
    /**
     * @param $name
     * @return mixed
     */
    function getBrandId($name)
    {

        $brand = DB::table('brands')
            ->whereRaw('`title` COLLATE UTF8_GENERAL_CI LIKE \'%' . trim($name) . '%\'')
            ->first();

        if ($brand) {
            return $brand->id;
        }

        $brand = new Brand();
        $brand->title = $name;
        $brand->excerpt = $name;
        $brand->lang = "en";
        $brand->user_id = fauth()->id();
        $brand->save();
        return $brand->id;
    }
}