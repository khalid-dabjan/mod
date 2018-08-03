<?php

namespace Dot\Posts\Controllers;

use Action;
use Dot\Posts\Models\Brand;
use Dot\Posts\Models\PostSize;
use Illuminate\Support\Facades\Auth;
use Dot\Platform\Controller;
use Dot\Posts\Models\Post;
use Dot\Posts\Models\PostMeta;
use Redirect;
use Request;
use View;


/**
 * Class PostsController
 * @package Dot\Posts\Controllers
 */
class BrandsController extends Controller
{

    /**
     * View payload
     * @var array
     */
    protected $data = [];


    /**
     * Show all posts
     * @return mixed
     */
    function index()
    {

        if (Request::isMethod("post")) {
            if (Request::filled("action")) {
                switch (Request::get("action")) {
                    case "delete":
                        return $this->delete();
                    case "activate":
                        return $this->status(1);
                    case "deactivate":
                        return $this->status(0);
                }
            }
        }

        $this->data["sort"] = (Request::filled("sort")) ? Request::get("sort") : "created_at";
        $this->data["order"] = (Request::filled("order")) ? Request::get("order") : "DESC";
        $this->data['per_page'] = (Request::filled("per_page")) ? Request::get("per_page") : NULL;

        $query = Brand::with('image', 'user')->orderBy($this->data["sort"], $this->data["order"]);

        if (Request::filled("from")) {
            $query->where("created_at", ">=", Request::get("from"));
        }

        if (Request::filled("to")) {
            $query->where("created_at", "<=", Request::get("to"));
        }

        if (Request::filled("user_id")) {
            $query->whereHas("user", function ($query) {
                $query->where("users.id", Request::get("user_id"));
            });
        }

        if (Request::filled("q")) {
            $query->search(urldecode(Request::get("q")));
        }
        $this->data["brands"] = $query->paginate($this->data['per_page']);

        return View::make("posts::brands.show", $this->data);
    }

    /**
     * Delete post by id
     * @return mixed
     */
    public function delete()
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $ID) {

            $brand = Post::findOrFail($ID);

            // Fire deleting action

            Action::fire("brands.deleting", $brand);


            $brand->delete();

            // Fire deleted action

            Action::fire("post.deleted", $brand);
        }

        return Redirect::back()->with("message", trans("posts::posts.events.deleted"));
    }


    /**
     * Create a new post
     * @return mixed
     */
    public function create()
    {

        $brand = new Brand();

        if (Request::isMethod("post")) {

            $brand->title = Request::get('title');
            $brand->excerpt = Request::get('excerpt');
            $brand->image_id = Request::get('image_id', 0);
            $brand->user_id = Auth::user()->id;


            $brand->lang = app()->getLocale();


            // Fire saving action

            Action::fire("post.saving", $brand);

            if (!$brand->validate()) {
                return Redirect::back()->withErrors($brand->errors())->withInput(Request::all());
            }

            $brand->save();

            // Fire saved action

            Action::fire("brand.saved", $brand);

            return Redirect::route("admin.posts.brands.edit", array("id" => $brand->id))
                ->with("message", trans("posts::brands.events.created"));
        }

        $this->data["brand"] = $brand;

        return View::make("posts::brands.edit", $this->data);
    }

    /**
     * Edit post by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $brand = Brand::findOrFail($id);

        if (Request::isMethod("post")) {

            $brand->title = Request::get('title');
            $brand->excerpt = Request::get('excerpt');
            $brand->image_id = Request::get('image_id', 0);

            $brand->lang = app()->getLocale();

            // Fire saving action

            Action::fire("brand.saving", $brand);

            if (!$brand->validate()) {
                return Redirect::back()->withErrors($brand->errors())->withInput(Request::all());
            }

            $brand->save();

            // Fire saved action

            Action::fire("brand.saved", $brand);

            return Redirect::route("admin.posts.brands.edit", array("id" => $id))->with("message", trans("posts::brands.events.updated"));
        }
        $this->data["brand"] = $brand;


        return View::make("posts::brands.edit", $this->data);
    }

}
