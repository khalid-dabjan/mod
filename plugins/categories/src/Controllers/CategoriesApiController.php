<?php

namespace Dot\Categories\Controllers;

use Dot\Categories\Models\Category;
use Dot\Platform\APIController;
use Illuminate\Http\Request;

/*
 * Class CategoriesApiController
 */
class CategoriesApiController extends APIController
{

    /*
     * CategoriesApiController constructor.
     */
    function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware("permission:categories.manage");
    }

    /*
     * List categories
     * @param int $id (optional) The object identifier.
     * @param string $q (optional) The search query string.
     * @param string $parent (default: 0) The parent object identifier.
     * @param array $with (optional) extra related category components [user, image, posts, categories].
     * @param int $limit (default: 10) The number of retrieved records.
     * @param int $page (default: 1) The page number.
     * @param string $order_by (default: id) The column you wish to sort by.
     * @param string $order_direction (default: DESC) The sort direction ASC or DESC.
     * @return \Illuminate\Http\JsonResponse
     */
    function show(Request $request)
    {

        $id = $request->get("id");
        $parent = $request->get("parent", 0);
        $limit = $request->get("limit", 10);
        $sort_by = $request->get("order_by", "id");
        $sort_direction = $request->get("order_direction", "DESC");

        $components = $request->get("with", []);

        foreach ($components as $relation => $data) {
            $components[$relation] = function ($query) use ($data) {
                return $query->orderBy(array_get($data, 'order_by', "id"), array_get($data, 'order_direction', "DESC"));
            };
        }

        $query = Category::with($components)->orderBy($sort_by, $sort_direction);

        if ($request->filled("q")) {
            $query->search($request->get("q"));
        }

        $query->where("parent", $parent);

        if ($id) {
            $categories = $query->where("id", $id)->first();
        } else {
            $categories = $query->paginate($limit)->appends($request->all());
        }

        return $this->response($categories);

    }

    /*
     * List categories with sample posts
     * @param int $id (optional) The object identifier.
     * @param string $q (optional) The search query string.
     * @param string $parent (default: 0) The parent object identifier.
     * @param array $with (optional) extra related category components [user, image, posts, categories].
     * @param int $limit (default: 10) The number of retrieved records.
     * @param int $page (default: 1) The page number.
     * @param string $order_by (default: id) The column you wish to sort by.
     * @param string $order_direction (default: DESC) The sort direction ASC or DESC.
     * @return \Illuminate\Http\JsonResponse
     */
    function samples(Request $request)
    {

        $id = $request->get("id");

        $parent = $request->get("parent", 0);
        $limit = $request->get("limit", 10);
        $sort_by = $request->get("order_by", "id");
        $sort_direction = $request->get("order_direction", "DESC");

        $components = $request->get("with", []);

        foreach ($components as $relation => $data) {
            $components[$relation] = function ($query) use ($data) {
                return $query->orderBy(array_get($data, 'order_by', "id"), array_get($data, 'order_direction', "DESC"));
            };
        }

        $query = Category::with($components)->orderBy($sort_by, $sort_direction);

        if ($request->filled("q")) {
            $query->search($request->get("q"));
        }

        $query->where("parent", $parent);

        if ($id) {
            $categories = $query->where("id", $id)->first();
        } else {
            $categories = $query->paginate($limit)->appends($request->all());
        }

        $categories->each(function ($category) {
            $category->load("samples.image", "samples.tags", "samples.categories", "samples.media");
            return $category;
        });

        return $this->response($categories);

    }


    /*
     * Create a new category
     * @param string $name (required) The category name.
     * @param string $slug (optional) The category slug.
     * @return \Illuminate\Http\JsonResponse
     */
    function create(Request $request)
    {

        $category = new Category();

        $category->name = $request->name;
        //   $category->slug = $request->slug;
        //  $category->lang = $this->user->lang;
        //  $category->user_id = $this->user->id;

        // Validate and save requested user
        if (!$category->validate()) {

            // return validation error
            return $this->response($category->errors(), "validation error");

        }

        if ($category->save()) {
            return $this->response($category);
        }

    }

    /*
     * Update category by id
     * @param int $id (required) The category id.
     * @param string $name (required) The category name.
     * @param string $slug (optional) The category slug.
     * @return \Illuminate\Http\JsonResponse
     */
    function update(Request $request)
    {

        if (!$request->id) {
            return $this->error("Missing tag id");
        }

        $category = Category::find($request->id);

        if (!$category) {
            return $this->error("Post #" . $request->id . " is not exists");
        }

        $category->name = $request->get('name', $category->name);
        $category->slug = $request->get('slug', $category->slug);

        if ($category->save()) {
            return $this->response($category);
        }

    }

    /*
     * Delete category by id
     * @param int $id (required) The category id.
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(Request $request)
    {

        if (!$request->id) {
            return $this->error("Missing tag id");
        }

        $category = Category::find($request->id);

        if (!$category) {
            return $this->error("Category #" . $request->id . " is not exists");
        }

        // Destroy requested post
        $category->delete();

        return $this->response($category);

    }


}
