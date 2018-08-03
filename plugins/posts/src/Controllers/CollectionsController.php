<?php

namespace Dot\Posts\Controllers;

use Action;
use Dot\Posts\Models\Collection;
use Dot\Posts\Models\PostSize;
use Dot\Posts\Models\Set;
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
class CollectionsController extends Controller
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

        $query = Collection::with('image', 'items')->orderBy($this->data["sort"], $this->data["order"]);


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
        $this->data["collections"] = $query->paginate($this->data['per_page']);



        return View::make("posts::collections.show", $this->data);
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

            $collection = Collection::findOrFail($ID);

            // Fire deleting action

            Action::fire("post.deleting", $collection);

            $collection->delete();

            // Fire deleted action

            Action::fire("post.deleted", $collection);
        }

        return Redirect::back()->with("message", trans("posts::posts.events.deleted"));
    }

    /**
     * Create a new post
     * @return mixed
     */
    public function create()
    {

        $collection = new Collection();

        if (Request::isMethod("post")) {

            $collection->title = Request::get('title');
            $collection->excerpt = Request::get('excerpt');
            $collection->content = Request::get('content');
            $collection->front_page = Request::get("front_page");
            $collection->image_id = Request::get('image_id', 0);
            $collection->user_id = Auth::user()->id;
            $collection->lang = app()->getLocale();

            $collection->published_at = Request::get('published_at');

            if (in_array($collection->published_at, [NULL, ""])) {
                $collection->published_at = date("Y-m-d H:i:s");
            }

            // Fire saving action

            Action::fire("collection.saving", $collection);

            if (!$collection->validate()) {
                return Redirect::back()->withErrors($collection->errors())->withInput(Request::all());
            }

            $collection->save();
            $collection->items()->sync(Request::get("items", []));

            // Fire saved action

            Action::fire("post.saved", $collection);

            return Redirect::route("admin.posts.collections.edit", array("id" => $collection->id))
                ->with("message", trans("posts::collections.events.created"));
        }

        $this->data["collection"] = $collection;

        return View::make("posts::collections.edit", $this->data);
    }

    /**
     * Edit post by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $collection = Collection::findOrFail($id);

        if (Request::isMethod("post")) {

            $collection->title = Request::get('title');
            $collection->excerpt = Request::get('excerpt');
            $collection->content = Request::get('content');
            $collection->front_page = Request::get("front_page");
            $collection->image_id = Request::get('image_id', 0);
            $collection->user_id = Auth::user()->id;

            $collection->published_at = Request::get('published_at');

            if (in_array($collection->published_at, [NULL, ""])) {
                $collection->published_at = date("Y-m-d H:i:s");
            }

            // Fire saving action

            Action::fire("collection.saving", $collection);

            if (!$collection->validate()) {
                return Redirect::back()->withErrors($collection->errors())->withInput(Request::all());
            }

            $collection->save();
            $collection->items()->sync(Request::get("items", []));
            // Fire saved action

            Action::fire("collection.saved", $collection);

            return Redirect::route("admin.posts.collections.edit", array("id" => $id))->with("message", trans("posts::posts.events.updated"));
        }

        $this->data["collection"] = $collection;


        return View::make("posts::collections.edit", $this->data);
    }

}
