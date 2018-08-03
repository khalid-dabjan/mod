<?php

namespace Dot\Posts;

use Dot\Categories\Models\Category;
use Dot\Galleries\Models\Gallery;
use Dot\Posts\Models\Post;
use Dot\Tags\Models\Tag;
use Dot\Users\Models\User;
use Dot\Platform\Facades\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Navigation;
use URL;

class Posts extends \Dot\Platform\Plugin
{

    /*
     * @var array
     */
    protected $dependencies = [
        "categories" => \Dot\Categories\Categories::class,
        "tags" => \Dot\Tags\Tags::class,
        "blocks" => \Dot\Blocks\Blocks::class
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

            if (Auth::user()->can("posts.manage")) {

                $menu->item('posts', trans("posts::posts.posts"), route("admin.posts.show"))
                    ->order(0)
                    ->icon("fa-cart-plus");
            }
        });
        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("posts.manage")) {

                $menu->item('posts.brands', trans("posts::brands.posts"), route("admin.posts.brands.show"))
                    ->order(1)
                    ->icon("fa-fort-awesome");
            }
        });

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("posts.manage")) {

                $menu->item('sets', trans("posts::sets.posts"), route("admin.posts.sets.show"))
                    ->order(1)
                    ->icon("fa-clone");
            }
        });

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("posts.manage")) {

                $menu->item('collections', trans("posts::collections.posts"), route("admin.posts.collections.show"))
                    ->order(1)
                    ->icon("fa-clone");
            }
        });

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("posts.manage")) {

                $menu->item('contests', trans("posts::contests.posts"), route("admin.posts.contests.show"))
                    ->order(1)
                    ->icon("fa-trophy");
            }
        });

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("posts.manage")) {

                $menu->item('galleries',"Help", route("admin.posts.questions.show"))
                    ->order(2)
                    ->icon("fa-question");
            }
        });

        Navigation::menu("sidebar", function ($menu) {

            if (Auth::user()->can("posts.manage")) {

                $menu->item('reports', trans("posts::reports.reports"), route("admin.posts.reports.show"))
                    ->order(3)
                    ->icon("fa-bug");
            }
        });


        Action::listen("dashboard.featured", function () {

            $data = [];

            $data["articles_count"] = Post::status("published")->format("article")->count();
            $data["videos_count"] = Post::status("published")->format("video")->count();
            $data["users_count"] = User::where("status", 1)->count();
            $data["categories_count"] = Category::where("parent", 0)->count();
            $data["tags_count"] = Tag::count();

            $posts_charts = array();

            for ($i = 0; $i <= 8; $i++) {

                $today = time();

                $current_day = $today - $i * 24 * 60 * 60;
                $end_current_day = $current_day + 24 * 60 * 60;

                $e = $i + 1;

                $start_of_day = date("Y-m-d H:i:s", $current_day);
                $end_of_day = date("Y-m-d H:i:s", $end_current_day);

                $posts_charts[date("Y-m-d", $current_day)] = Post::status("published")->format("post")
                    ->where("created_at", '>', $start_of_day)
                    ->where("created_at", '<', $end_of_day)
                    ->count();
            }

            $data["posts_charts"] = array_reverse($posts_charts);

            return view("posts::widgets.featured", $data);

        });

        Action::listen('admin.head', function(){
            return view("posts::styles");
        });
    }
}
