<?php

/*
 * WEB
 */

Route::group([
    "prefix" => ADMIN,
    "middleware" => ["web", "auth:backend", "can:colors.manage"],
    "namespace" => "Dot\\Colors\\Controllers"
], function ($route) {
    $route->group(["prefix" => "colors"], function ($route) {
        $route->any('/', ["as" => "admin.colors.show", "uses" => "ColorController@index"]);
        $route->any('/create', ["as" => "admin.colors.create", "uses" => "ColorController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.colors.edit", "uses" => "ColorController@edit"]);
        $route->any('/delete', ["as" => "admin.colors.delete", "uses" => "ColorController@delete"]);
        $route->any('/{add_to_filter}/add_to_filter', ["as" => "admin.colors.add_to_filter", "uses" => "ColorController@add_to_filter"]);
    });
});