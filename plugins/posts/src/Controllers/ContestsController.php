<?php

namespace Dot\Posts\Controllers;

use Action;
use App\Model\ContestItem;
use Dot\Posts\Models\Contest;
use Dot\Posts\Models\PostSize;
use Illuminate\Support\Facades\Auth;
use Dot\Platform\Controller;
use Dot\Posts\Models\Post;
use Dot\Posts\Models\PostMeta;
use Redirect;
use Request;
use View;


/**
 * Class ContestsController
 * @package Dot\Posts\Controllers
 */
class ContestsController extends Controller
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

        $query = Contest::with('image', 'user')->orderBy($this->data["sort"], $this->data["order"]);

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

        if (Request::filled("status")) {
            $query->where("status", Request::get("status"));
        }

        if (Request::filled("q")) {
            $query->search(urldecode(Request::get("q")));
        }
        $this->data["contests"] = $query->paginate($this->data['per_page']);

        if (Request::ajax()) {
            return response()->json($this->data["contests"]->items());
        }

        return View::make("posts::contests.show", $this->data);
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

            $contest = Contest::findOrFail($ID);

            // Fire deleting action

            Action::fire("post.deleting", $contest);

            $contest->delete();


            // Fire deleted action

            Action::fire("post.deleted", $contest);
        }

        return Redirect::back()->with("message", trans("posts::posts.events.deleted"));
    }

    /**
     * Activating / Deactivating post by id
     * @param $status
     * @return mixed
     */
    public function status($status)
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $id) {

            $contest = Contest::findOrFail($id);

            // Fire saving action
            Action::fire("post.saving", $contest);

            $contest->status = $status;
            $contest->save();

            // Fire saved action

            Action::fire("post.saved", $contest);
        }

        if ($status) {
            $message = trans("posts::posts.events.activated");
        } else {
            $message = trans("posts::posts.events.deactivated");
        }

        return Redirect::back()->with("message", $message);
    }

    /**
     * Create a new post
     * @return mixed
     */
    public function create()
    {

        $contest = new Contest();

        if (Request::isMethod("post")) {

            $contest->title = Request::get('title');
            $contest->content = Request::get('content');
            $contest->hash_tag = Request::get('hash_tag');
            $contest->image_id = Request::get('image_id', 0);
            $contest->user_id = Auth::user()->id;
            $contest->status = Request::get("status", 0);
            $contest->reward = Request::get('reward');
            $contest->reward_code = Request::get('reward_code');
            $contest->lang = app()->getLocale();
            $contest->expired_at = Request::get('expired_at');
            $contest->published_at = Request::get('published_at');
            if (in_array($contest->published_at, [NULL, ""])) {
                $contest->published_at = date("Y-m-d H:i:s");
            }

            // Fire saving action

            Action::fire("post.contest.saving", $contest);

            if (!$contest->validate()) {
                return Redirect::back()->withErrors($contest->errors())->withInput(Request::all());
            }

            $contest->save();
            // Fire saved action

            Action::fire("post.contests.saved", $contest);

            return Redirect::route("admin.posts.contests.edit", array("id" => $contest->id))
                ->with("message", trans("posts::contests.events.created"));
        }

        $this->data["contest"] = $contest;

        return View::make("posts::contests.edit", $this->data);
    }

    /**
     * Edit post by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $contest = Contest::findOrFail($id);

        if (Request::isMethod("post")) {

            $contest->title = Request::get('title');
            $contest->content = Request::get('content');
            $contest->hash_tag = Request::get('hash_tag');
            $contest->image_id = Request::get('image_id', 0);
            $contest->reward = Request::get('reward');
            $contest->reward_code = Request::get('reward_code');
            $contest->status = Request::get("status", 0);

            $contest->lang = app()->getLocale();
            $contest->expired_at = Request::get('expired_at');
            $contest->published_at = Request::get('published_at');
            if (in_array($contest->published_at, [NULL, ""])) {
                $contest->published_at = date("Y-m-d H:i:s");
            }

            // Fire saving action

            Action::fire("post.contest.saving", $contest);

            if (!$contest->validate()) {
                return Redirect::back()->withErrors($contest->errors())->withInput(Request::all());
            }

            $contest->save();


            // Fire saved action

            Action::fire("post.contest.saved", $contest);

            return Redirect::route("admin.posts.contests.edit", array("id" => $id))->with("message", trans("posts::contests.events.updated"));
        }

        $this->data["contest"] = $contest;


        return View::make("posts::contests.edit", $this->data);
    }


    public function deleteItem()
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $ID) {

            $contest = ContestItem::findOrFail($ID);

            // Fire deleting action

            Action::fire("post.contest.item.deleting", $contest);

            $contest->delete();


            // Fire deleted action

            Action::fire("post.contest.item.deleted", $contest);
        }

    }

}
