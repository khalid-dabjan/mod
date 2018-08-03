<?php

namespace Dot\Colors;

use Illuminate\Support\Facades\Auth;
use Navigation;
use URL;

class Colors extends \Dot\Platform\Plugin
{

    /*
     * @var array
     */
    protected $dependencies = [
    ];

    /**
     * @var array
     */
    protected $permissions = [
        "manage"
    ];

    /**
     *  initialize plugin
     */
    function boot()
    {

        parent::boot();

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("colors.manage")) {

                $menu->item('colors', trans("colors::colors.colors"), route("admin.colors.show"))
                    ->order(1)
                    ->icon("fa-paint-brush");
            }
        });

    }
}
