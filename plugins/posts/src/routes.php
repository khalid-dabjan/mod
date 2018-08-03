<?php

/*
 * WEB
 */

Route::group([
    "prefix" => ADMIN,
    "middleware" => ["web", "auth:backend", "can:posts.manage"],
    "namespace" => "Dot\\Posts\\Controllers"
], function ($route) {
    // Posts Routes
    $route->group(["prefix" => "posts"], function ($route) {
        $route->any('/', ["as" => "admin.posts.show", "uses" => "PostsController@index"]);
        $route->any('/create', ["as" => "admin.posts.create", "uses" => "PostsController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.posts.edit", "uses" => "PostsController@edit"]);
        $route->any('/delete', ["as" => "admin.posts.delete", "uses" => "PostsController@delete"]);
        $route->any('/export', ["as" => "admin.posts.export", "uses" => "ExportsController@export"]);
        $route->any('/{status}/status', ["as" => "admin.posts.status", "uses" => "PostsController@status"]);
        $route->post('newSlug', 'PostsController@new_slug');
    });

    // Brands Routes
    $route->group(["prefix" => "brands"], function ($route) {
        $route->any('/', ["as" => "admin.posts.brands.show", "uses" => "BrandsController@index"]);
        $route->any('/create', ["as" => "admin.posts.brands.create", "uses" => "BrandsController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.posts.brands.edit", "uses" => "BrandsController@edit"]);
        $route->any('/delete', ["as" => "admin.posts.brands.delete", "uses" => "BrandsController@delete"]);
        $route->any('/{status}/status', ["as" => "admin.posts.brands.status", "uses" => "BrandsController@status"]);
    });

    // Sets Routes
    $route->group(["prefix" => "sets"], function ($route) {
        $route->any('/', ["as" => "admin.posts.sets.show", "uses" => "SetsController@index"]);
        $route->any('/create', ["as" => "admin.posts.sets.create", "uses" => "SetsController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.posts.sets.edit", "uses" => "SetsController@edit"]);
        $route->any('/delete', ["as" => "admin.posts.sets.delete", "uses" => "SetsController@delete"]);
        $route->any('/{status}/status', ["as" => "admin.posts.sets.status", "uses" => "SetsController@status"]);
    });


    // Collections Routes
    $route->group(["prefix" => 'collections'], function ($route) {
        $route->any('/', ["as" => "admin.posts.collections.show", "uses" => "CollectionsController@index"]);
        $route->any('/create', ["as" => "admin.posts.collections.create", "uses" => "CollectionsController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.posts.collections.edit", "uses" => "CollectionsController@edit"]);
        $route->any('/delete', ["as" => "admin.posts.collections.delete", "uses" => "CollectionsController@delete"]);
        $route->any('/{status}/status', ["as" => "admin.posts.collections.status", "uses" => "CollectionsController@status"]);
    });

    // Contests Routes
    $route->group(["prefix" => 'contests'], function ($route) {
        $route->any('/', ["as" => "admin.posts.contests.show", "uses" => "ContestsController@index"]);
        $route->any('/create', ["as" => "admin.posts.contests.create", "uses" => "ContestsController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.posts.contests.edit", "uses" => "ContestsController@edit"]);
        $route->any('/delete', ["as" => "admin.posts.contests.delete", "uses" => "ContestsController@delete"]);
        $route->any('/deleteItem', ["as" => "admin.posts.contests.items.delete", "uses" => "ContestsController@deleteItem"]);
        $route->any('/{status}/status', ["as" => "admin.posts.contests.status", "uses" => "ContestsController@status"]);
    });

    // Questions
    $route->group(["prefix" => 'questions'], function ($route) {
        $route->any('/', ["as" => "admin.posts.questions.show", "uses" => "QuestionsController@index"]);
        $route->any('/create', ["as" => "admin.posts.questions.create", "uses" => "QuestionsController@create"]);
        $route->any('/{id}/edit', ["as" => "admin.posts.questions.edit", "uses" => "QuestionsController@edit"]);
        $route->any('/delete', ["as" => "admin.posts.questions.delete", "uses" => "QuestionsController@delete"]);
        $route->any('/{status}/status', ["as" => "admin.posts.questions.status", "uses" => "QuestionsController@status"]);
    });

    // Reports
    $route->group(["prefix" => 'reports'], function ($route) {
        $route->any('/', ["as" => "admin.posts.reports.show", "uses" => "ReportsController@index"]);
        $route->any('/{id}/details', ["as" => "admin.posts.reports.details", "uses" => "ReportsController@details"]);
        $route->any('/delete', ["as" => "admin.posts.reports.delete", "uses" => "ReportsController@delete"]);
    });
});

/*
 * API
 */

Route::group([
    "prefix" => API,
    "middleware" => ["auth:api"],
    "namespace" => "Dot\\Posts\\Controllers"
], function ($route) {
    $route->get("/posts/show", "Dot\Posts\Controllers\PostsApiController@show");
    $route->post("/posts/create", "Dot\Posts\Controllers\PostsApiController@create");
    $route->post("/posts/update", "Dot\Posts\Controllers\PostsApiController@update");
    $route->post("/posts/views", "Dot\Posts\Controllers\PostsApiController@views");
    $route->post("/posts/destroy", "Dot\Posts\Controllers\PostsApiController@destroy");
});


