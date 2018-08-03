<?php

namespace Dot\Posts\Controllers;

use Action;
use App\Mail\ReportMail;
use App\Model\Set;
use Dot\Platform\Classes\Carbon;
use Dot\Posts\Models\Collection;
use Dot\Posts\Models\Report;
use Illuminate\Support\Facades\Auth;
use Dot\Platform\Controller;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Request;
use View;


/**
 * Class PostsController
 * @package Dot\Posts\Controllers
 */
class ReportsController extends Controller
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

        $query = Report::orderBy($this->data["sort"], $this->data["order"]);


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
        $this->data["reports"] = $query->paginate($this->data['per_page']);


        return View::make("posts::reports.show", $this->data);
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

            $report = Report::findOrFail($ID);

            // Fire deleting action

            Action::fire("post.report.deleting", $report);

            $report->delete();

            // Fire deleted action

            Action::fire("post.report.deleted", $report);
        }

        return Redirect::back()->with("message", trans("posts::reports.events.deleted"));
    }

    /**
     * Edit post by id
     * @param $id
     * @return mixed
     */
    public function details($id)
    {

        $report = Report::findOrFail($id);


        if (Request::isMethod("post") && $report->action_id == 0) {


            $report->action_id = Request::get('action_id');
            // Fire saving action

            Action::fire("report.change", $report);

            $report->save();
            $this->doActions($report, Request::get('action_id'));

            // Fire saved action

            Action::fire("report.saved", $report);

            return Redirect::route("admin.posts.reports.details", array("id" => $id))->with("message", trans("posts::reports.events.updated"));
        }

        if (Request::isMethod("post") && Request::get('action') == 'unblock') {
            $user = $report->target->user;
            $user->suspended = 0;
            $user->suspended_to = Carbon::now()->subDay(1);
            $user->save();
            return Redirect::route("admin.posts.reports.details", array("id" => $id))->with("message", trans("posts::reports.events.updated"));
        }

        if (Request::isMethod("post")){
            $user = $report->target->user;
            $user->suspended = 0;
            $user->suspended_to = Request::get('suspended_to');
            $user->save();
            return Redirect::route("admin.posts.reports.details", array("id" => $id))->with("message", trans("posts::reports.events.updated"));
        }

        $this->data["report"] = $report;
        return View::make("posts::reports.details", $this->data);
    }

    /**
     * @param $report
     * @param $action_id
     */
    protected function doActions($report, $action_id)
    {
        $object = $report->target;


        if ($action_id == 1) {
            $object->delete();
        }


        $user = $object->user;

        if ($action_id == 2) {
            $user->suspended = 1;
        }

        if ($action_id == 3) {
            $user->suspended_to = Request::get('suspended_to');
        }
        Mail::to($user->email)->send(new ReportMail($user, $report));
        $user->api_token = str_random(60);
        $user->save();
    }

}
