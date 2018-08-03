<?php

namespace Dot\Posts\Controllers;

use Action;
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
class SetsController extends Controller
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

        $query = Set::with('image', 'items')->orderBy($this->data["sort"], $this->data["order"]);


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
        $this->data["sets"] = $query->paginate($this->data['per_page']);



        return View::make("posts::sets.show", $this->data);
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

            $set = Set::findOrFail($ID);

            // Fire deleting action

            Action::fire("post.deleting", $set);

            $set->delete();

            // Fire deleted action

            Action::fire("post.deleted", $set);
        }

        return Redirect::back()->with("message", trans("posts::posts.events.deleted"));
    }

    /**
     * Create a new post
     * @return mixed
     */
    public function create()
    {

        $set = new Set();

        if (Request::isMethod("post")) {

            $set->title = Request::get('title');
            $set->excerpt = Request::get('excerpt');
            $set->content = Request::get('content');
            $set->front_page = Request::get("front_page");
            $set->image_id = Request::get('image_id', 0);
            $set->user_id = Auth::user()->id;
            $set->lang = app()->getLocale();

            $set->published_at = Request::get('published_at');

            if (in_array($set->published_at, [NULL, ""])) {
                $set->published_at = date("Y-m-d H:i:s");
            }

            // Fire saving action

            Action::fire("set.saving", $set);

            if (!$set->validate()) {
                return Redirect::back()->withErrors($set->errors())->withInput(Request::all());
            }

            $set->save();
            $set->items()->sync(Request::get("items", []));

            // Fire saved action

            Action::fire("post.saved", $set);

            return Redirect::route("admin.posts.sets.edit", array("id" => $set->id))
                ->with("message", trans("posts::sets.events.created"));
        }

        $this->data["set_items"] = collect([]);
        $this->data["set"] = $set;

        return View::make("posts::sets.edit", $this->data);
    }

    /**
     * Edit post by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $set = Set::findOrFail($id);

        if (Request::isMethod("post")) {

            $set->title = Request::get('title');
            $set->excerpt = Request::get('excerpt');
            $set->content = Request::get('content');
            $set->front_page = Request::get("front_page");
            $set->image_id = Request::get('image_id', 0);
            $set->user_id = Auth::user()->id;

            $set->published_at = Request::get('published_at');

            if (in_array($set->published_at, [NULL, ""])) {
                $set->published_at = date("Y-m-d H:i:s");
            }

            // Fire saving action

            Action::fire("post.saving", $set);

            if (!$set->validate()) {
                return Redirect::back()->withErrors($set->errors())->withInput(Request::all());
            }

            $set->save();
            $set->items()->sync(Request::get("items", []));
            // Fire saved action

            Action::fire("set.saved", $set);

            return Redirect::route("admin.posts.sets.edit", array("id" => $id))->with("message", trans("posts::posts.events.updated"));
        }

        $this->data["set_items"] = $set->items;
        $this->data["set"] = $set;


        return View::make("posts::sets.edit", $this->data);
    }

}
