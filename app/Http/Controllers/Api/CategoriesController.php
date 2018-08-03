<?php

namespace App\Http\Controllers\Api;

use App\Model\Category;
use App\Model\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller;

class CategoriesController extends Controller
{
    /**
     * POST api/getItemsCategories
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItemsCategories(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $categories = Category::with(['categories' => function ($query) {
            $query->orderBy('order','ASC');
        }, 'image'])->orderBy('order', 'ASC')->where('parent', 0)->get();
        $data['data'] = \Maps\Category\categories($categories);
        return response()->json($data);
    }

    /**
     * POST api/getItemsFromCategory
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItemsFromCategory(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 6);
        $category = Category::where('id', $request->get('categoryId'))->first();
        if (!$category) {
            $data['errors'][] = 'Category not found';
            return response()->json($data);
        }

        if ($category->parent != 0) {
            // If first 8 items
            if ($offset == 0) {
                $items = $category->items()->with('image', 'brand', 'user')->orderBy('likes', 'DESC')->where('created_at', '>=', Carbon::now()->subDay(11))->take($limit)->offset($offset)->get();
            } else {
                $items = $category->items()->with('image', 'brand', 'user')->take($limit)->offset($offset)->get();
            }
        } else {
            $categoriesIds = $category->categories()->get()->pluck('id')->toArray();
            $categoriesIds[] = $category->id;
            $query = Post::with('image', 'brand', 'user')->
            whereHas('categories', function ($query) use ($categoriesIds) {
                $query->whereIn('category_id', $categoriesIds);
            })->take($limit)->offset($offset);

            if ($request->filled('q')) {
                $query->search($request->get('q'));
            }
            if ($offset == 0) {
                $query->orderBy('likes', 'DESC')->where('created_at', '>=', Carbon::now()->subDay(11))->take($limit)->offset($offset);
            }
            $items = $query->get();
        }
        $data['data'] = \Maps\Item\items($items);
        return response()->json($data);
    }
}