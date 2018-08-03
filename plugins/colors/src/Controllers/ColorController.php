<?php

namespace Dot\Colors\Controllers;

use Action;
use Dot\Colors\Models\Color;
use Illuminate\Support\Facades\Auth;
use Dot\Platform\Controller;
use Redirect;
use Request;
use View;


/**
 * Class ColorController
 * @package Dot\Colors\Controllers
 */
class ColorController extends Controller
{

    /**
     * View payload
     * @var array
     */
    protected $data = [];


    /**
     * Show all Colors
     * @return mixed
     */
    function index()
    {

        if (Request::isMethod("post")) {
            if (Request::filled("action")) {
                switch (Request::get("action")) {
                    case "delete":
                        return $this->delete();
                    case "add_to_filter":
                        return $this->add_to_filter(1);
                    case "remove_form_filter":
                        return $this->add_to_filter(0);
                }
            }
        }

        $this->data["sort"] = (Request::filled("sort")) ? Request::get("sort") : "created_at";
        $this->data["order"] = (Request::filled("order")) ? Request::get("order") : "DESC";
        $this->data['per_page'] = (Request::filled("per_page")) ? Request::get("per_page") : NULL;

        $query = Color::with('user')->orderBy($this->data["sort"], $this->data["order"]);

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

        if (Request::filled("add_to_filter")) {
            $query->where("add_to_filter", Request::get("add_to_filter"));
        }

        if (Request::filled("q")) {
            $query->search(urldecode(Request::get("q")));
        }
        $this->data["colors"] = $query->paginate($this->data['per_page']);

        return View::make("colors::show", $this->data);
    }

    /**
     * Delete color by id
     * @return mixed
     */
    public function delete()
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $ID) {

            $color = Color::findOrFail($ID);

            // Fire deleting action

            Action::fire("color.deleting", $color);

            $color->delete();

            // Fire deleted action

            Action::fire("color.deleted", $color);
        }

        return Redirect::back()->with("message", trans("colors::colors.events.deleted"));
    }

    /**
     * add to filter color by id
     * @param $add_to_filter
     * @return mixed
     */
    public function add_to_filter($add_to_filter)
    {
        $ids = Request::get("id");

        $ids = is_array($ids) ? $ids : [$ids];

        foreach ($ids as $id) {

            $color = Color::findOrFail($id);

            // Fire saving action
            Action::fire("color.saving", $color);

            $color->add_to_filter = $add_to_filter;
            $color->save();

            // Fire saved action

            Action::fire("color.saved", $color);
        }

        if ($add_to_filter) {
            $message = trans("colors::colors.events.activated");
        } else {
            $message = trans("colors::colors.events.deactivated");
        }

        return Redirect::back()->with("message", $message);
    }

    /**
     * Create a new color
     * @return mixed
     */
    public function create()
    {

        $color = new Color();

        if (Request::isMethod("post")) {

            $color->name = Request::get('name');
            $color->value = Request::get('value');
            $color->user_id = Auth::user()->id;
            $color->add_to_filter = Request::get("add_to_filter", 0);
            $color->lang = app()->getLocale();

            // Fire saving action

            Action::fire("color.saving", $color);

            if (!$color->validate()) {
                return Redirect::back()->withErrors($color->errors())->withInput(Request::all());
            }

            $color->save();

            // Fire saved action

            Action::fire("color.saved", $color);

            return Redirect::route("admin.colors.edit", array("id" => $color->id))
                ->with("message", trans("colors::colors.events.created"));
        }

        $this->data["color"] = $color;

        return View::make("colors::edit", $this->data);
    }

    /**
     * Edit color by id
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        $color = Color::findOrFail($id);

        if (Request::isMethod("post")) {

            $color->name = Request::get('name');
            $color->value = Request::get('value');
            $color->add_to_filter = Request::get("add_to_filter", 0);
            // Fire saving action

            Action::fire("color.saving", $color);

            if (!$color->validate()) {
                return Redirect::back()->withErrors($color->errors())->withInput(Request::all());
            }

            $color->save();

            // Fire saved action

            Action::fire("color.saved", $color);

            return Redirect::route("admin.colors.edit", array("id" => $id))->with("message", trans("colors::colors.events.updated"));
        }

        $this->data["color"] = $color;

        return View::make("colors::edit", $this->data);
    }

}
