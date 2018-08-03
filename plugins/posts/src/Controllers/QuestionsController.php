<?php

namespace Dot\Posts\Controllers;

use Action;
use Dot\Posts\Models\Question;
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
 * Class QuestionsController
 * @package Dot\Posts\Controllers
 */
class QuestionsController extends Controller
{

    /**
     * View payload
     * @var array
     */
    protected $data = [];


    /**
     * Show all questions
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

        $query = Question::orderBy($this->data["sort"], $this->data["order"]);


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
        $this->data["questions"] = $query->paginate($this->data['per_page']);



        return View::make("posts::questions.show", $this->data);
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

            $question = Question::findOrFail($ID);

            // Fire deleting action

            Action::fire("post.question.deleting", $question);

            $question->delete();

            // Fire deleted action

            Action::fire("post.question.deleted", $question);
        }

        return Redirect::back()->with("message", trans("posts::questions.events.deleted"));
    }

    /**
     * Create a new post
     * @return mixed
     */
    public function create()
    {

        $question = new Question();

        if (Request::isMethod("post")) {

            $question->title = Request::get('title');
            $question->answer = Request::get('answer');
            $question->status = Request::get('status',0);

            $question->user_id = Auth::user()->id;


            // Fire saving action

            Action::fire("question.saving", $question);

            if (!$question->validate()) {
                return Redirect::back()->withErrors($question->errors())->withInput(Request::all());
            }

            $question->save();
            // Fire saved action

            Action::fire("post.saved", $question);

            return Redirect::route("admin.posts.questions.edit", array("id" => $question->id))
                ->with("message", trans("posts::questions.events.created"));
        }

        $this->data["question"] = $question;

        return View::make("posts::questions.edit", $this->data);
    }

    /**
     * Edit post by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $question = Question::findOrFail($id);

        if (Request::isMethod("post")) {

            $question->title = Request::get('title');
            $question->answer = Request::get('answer');
            $question->status = Request::get('status',0);


            // Fire saving action

            Action::fire("question.saving", $question);

            if (!$question->validate()) {
                return Redirect::back()->withErrors($question->errors())->withInput(Request::all());
            }

            $question->save();
            // Fire saved action

            Action::fire("question.saved", $question);

            return Redirect::route("admin.posts.questions.edit", array("id" => $id))->with("message", trans("posts::questions.events.updated"));
        }

        $this->data["question"] = $question;


        return View::make("posts::questions.edit", $this->data);
    }

}
