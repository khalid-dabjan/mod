<?php

namespace App\Http\Controllers\Api;

use App\Events\LikesEvent;
use App\Model\Category;
use App\Model\ContestItem;
use App\Model\Media;
use App\Model\Post;
use App\User;
use Dot\Colors\Models\Color;
use Dot\Posts\Models\Brand;
use Dot\Posts\Models\PostSize;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;

class ItemsController extends Controller
{


    /**
     * POST api/switchLike
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchLike(Request $request)
    {

        $response = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'objId' => 'required',
            'targetObject' => 'required',
        ]);

        if ($validator->fails()) {
            $response['errors'] = $validator->errors();
            return response()->json($response, 400);
        }

        $user = fauth()->user();
        $id = $request->get('objId');
        $type = $request->get('targetObject');
        $table = DB::table('users_posts_like');
        $data = [
            'object_id' => $id,
            'user_id' => $user->id,
            'type' => $type
        ];
        $object = $table->where($data)->get();

        if (count($object)) {
            $table->where($data)->delete();
            $this->counterLikes($data['object_id'], $data['type'], false);
            $response = ['result' => "Like removed"];
            return response()->json($response, 200);
        }
        $table->insert($data);
        $this->counterLikes($data['object_id'], $data['type'], true);
        event(new LikesEvent($data));
        $response = ['result' => "Like added"];
        return response()->json($response, 200);
    }

    /**
     * POST api/getLikedItems
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLikedItems(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 6);
        $user = User::where(['id' => $request->get('userId', fauth()->user()->id), 'status' => 1])->first();

        if (!$user) {
            $data['errors'][] = 'User not found';
            return response()->json($data);
        }

        $query = $user->liked_items()->with('image', 'brand', 'user');

        // Search if Exits
        if ($request->filled('q')) {
            $query->where('title', 'LIKE', '%' . $request->get('q') . '%');
        }

        $items = $query->take($limit)->offset($offset)->get();
        $data['data'] = \Maps\Item\items($items);
        return response()->json($data);
    }


    /**
     * POST api/itemDetails
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemDetails(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'itemId' => 'required|exists:posts,id',
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        $item = Post::find($request->get('itemId'));
        $item->views++;
        $item->save();
        $similarItems = collect();
        if ($item->brand_id) {
            $similarItems = Post::with('image')->where(function ($query) use($item){
                $query->where('brand_id', $item->brand_id)->whereHas("categories",function ($query) use($item){
                    $query->whereIn('categories.id',$item->categories->pluck('id')->toArray());
                });
            })->orWhere(function ($query) use($item){
                $query->where('brand_id', $item->brand_id);
            })->take(4)->get();
        }
        $count=count($similarItems);
        if($count<4){
            $category = $item->categories()->where('parent', '<>', 0)->first();
            if ($category) {
                $similarItems = $similarItems->concat($category->posts()->take(4-$count)->get());
            }
        }
        $data['data'] = \Maps\Item\itemDetails($item, $similarItems);
        return response()->json($data);

    }

    /**
     * POST api/listItems
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function listItems(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $items = Post::with('image', 'brand', 'categories')
            ->where('user_id', fauth()->id())
            ->offset($offset)
            ->take($limit)
            ->orderBy("created_at", "DESC")
            ->get();
        return response()->json(['errors' => [], 'data' => $items]);
    }

    /**
     * POST api/addItem
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItem(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'color' => 'required|exists:colors,id',
            'category' => 'required|exists:categories,id',
            'brand' => 'required',
            'shop_url' => 'required|url',
            'price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'size' => 'required',
            'currency' => 'required',
            'coverage' => 'required|in:1,2,4',
            'sizeSystem' => 'required|in:eu,uk,us,jp,cn',
            'image' => 'required',
        ]);
        $media = new Media();
        if ($validator->fails() && ($request->filled('image') && !$media->isBase64($request->get('image')))) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        $media = $media->saveContent(explode('base64,', $request->get('image'))[1]);
        $post = new Post();
        $post->title = $request->get('title');
        $post->content = $request->get('description');
        $post->excerpt = $request->get('description');
        $post->color_id = $request->get('color');
        $post->brand_id = getBrandId($request->get('brand'));
        $post->url = ($request->get('shop_url'));
        $post->price = ($request->get('price'));
        $post->user_id = (fauth()->id());
        $post->sale_price = ($request->get('sale_price'));
        $post->currency = ($request->get('currency'));
        $post->coverage = ($request->get('coverage'));
        $post->size_system = ($request->get('sizeSystem'));
        $post->image_id = $media->id;
        $post->lang = "en";
        $post->save();
        $post->categories()->attach($request->get('category'));

        $sizes_fields = explode(',', $request->get("size", ''));
        foreach ($sizes_fields as $value) {
            $meta = new PostSize();
            $meta->size = $value;
            $post->sizes()->save($meta);
        }
        return response()->json($data, 200);
    }

    /**
     * POST api/deleteItems
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItems(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $itemId = $request->get('itemId');
        $item = Post::where(['id' => $itemId, 'user_id' => fauth()->id()])->first();
        if (!$item) {
            $data['errors'][] = "Items not found";
            return response()->json($data, 400);
        }
        DB::table('posts_blocks')->where(['post_id' => $itemId])->delete();
        DB::table('posts_blocks_orders')->where(['post_id' => $itemId])->delete();
        DB::table('posts_categories_orders')->where(['post_id' => $itemId])->delete();
        DB::table('posts_sizes')->where(['post_id' => $itemId])->delete();
        DB::table('users_posts_like')->where(['object_id' => $itemId])->delete();
        DB::table('collections_posts')->where(['post_id' => $itemId])->delete();
        $item->delete();
        return response()->json($data, 200);
    }


    /**
     * POST api/getEditingItemDetails
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEditingItemDetails(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'itemId' => 'required|exists:posts,id',
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $post = Post::find($request->get('itemId'));
        $category = $post->categories()->where('parent', '<>', 0)->first();
        $data['data'] = [
            'title' => $post->title,
            'description' => $post->title,
            'color' => ($color = Color::find($post->color_id)) ? $color->id : null,
            'brand' => $post->brand_id,
            'price' => $post->price,
            'sale_price' => $post->sale_price,
            'coverage' => $post->coverage,
            'sizeSystem' => $post->size_system,
            'category' => $category ? $category->id : 0,
            'image' => $post->image ? uploads_url($post->image->path) : '',
            'currency' => $post->currency,
            'shop_url' => $post->url,
            'size' => implode(",", $post->sizes->pluck("size")->toArray())
        ];
        return response()->json($data);
    }

    /**
     * POST api/editItem
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editItem(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'itemId' => 'required|exists:posts,id',
            'title' => 'required',
            'description' => 'required',
            'color' => 'required|exists:colors,id',
            'category' => 'required|exists:categories,id',
            'brand' => 'required',
            'shop_url' => 'required|url',
            'price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'coverage' => 'in:1,2,3,4',
            'image' => 'required',
            'currency' => 'required',

        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $media = null;
        if ($request->filled('image') && $request->get('image')) {
            $media = new Media();
            if ($media->isBase64($request->get('image'))) {
                $data['errors'][] = "Image not base64";
                return response()->json($data, 400);
            }
            $media = $media->saveContent(explode('base64,', $request->get('image'))[1]);
        }
        $post = Post::find($request->get('itemId'));
        $post->title = $request->get('title');
        $post->content = $request->get('description');
        $post->excerpt = $request->get('description');
        $post->color_id = $request->get('color');
        $post->brand_id = getBrandId($request->get('brand'));
        $post->url = ($request->get('shop_url'));
        $post->price = ($request->get('price'));
        $post->sale_price = ($request->get('sale_price'));
        $post->coverage = ($request->get('coverage'));
        $post->currency = ($request->get('currency'));
        $post->size_system = ($request->get('sizeSystem'));
        $post->image_id = $media ? $media->id : $post->image_id;
        $post->save();
        $post->categories()->attach($request->get('category'));

        $sizes_fields = explode(',', $request->get("size", ''));
        $post->sizes()->delete();
        foreach ($sizes_fields as $value) {
            $meta = new PostSize();
            $meta->size = $value;
            $post->sizes()->save($meta);
        }
        return response()->json($data, 200);
    }


    /**
     * POST api/getBrands
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrands(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $query = Brand::with('image');
        if ($request->filled('q')) {
            $query->search($request->get('q'));
        }
        if($request->filled('categoriesId')){
            $query->whereHas('posts',function ($query)use($request){
                $query->whereHas('categories',function ($query) use($request){
                    $query->whereIn('categories.id',$request->get('categoriesId'));
                });
            });
        }
        $data['data'] = $query->take(30)->get();
        return response()->json($data);
    }


    /**
     * POST api/getSearchForAddSet
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchForAddSet(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        
        //nasser
        if ($request->filled('category') && ($request->get('category') == 0)){
            return response()->json($data);
        }
       
        $query = Post::with('image');
        if ($request->filled('color') && $request->get('color') != 0) {
            $query->where('color_id', $request->get('color'));
        }

        if ($request->filled('category') && ($request->get('category') != 0) && $request->get('category') != "liked_items") {
            $category = Category::find($request->get('category'));
            $categoriesIds = [$category->id];
            if ($category->parent == 0) {
                $categoriesIds = $category->categories()->get()->pluck('id')->toArray();
                $categoriesIds[] = $category->id;
            }
            $query->whereHas('categories', function ($query) use ($request, $categoriesIds) {
                $query->whereIn('category_id', $categoriesIds);
            });
        }

        if ($request->filled('category') && ($request->get('category') === "liked_items")) {
            $query->whereHas('likes', function ($query) use ($request) {
                $query->where('user_id', fauth()->id());
            });
        }


        if ($request->filled('query')) {
            $query->where('title', 'LIKE', '%' . $request->get('query') . '%');
        }
        $items = $query->orderBy('likes', 'DESC')
            ->take($limit)
            ->offset($offset)
            ->get();

        $data['data'] = \Maps\Item\items($items);
        return response()->json($data);
    }

    /**
     * Incrementing Functionality for likes
     * @param $id
     * @param $target
     * @param bool $up
     */
    private function counterLikes($id, $target, $up = true)
    {
        switch ($target) {
            case 'item':
                $object = Post::where('id', $id)->first();
                break;
            case 'contest_item':
                $object = ContestItem::where('id', $id)->first();
                break;
            default:
                return;
        }
        if ($up) {
            $object->likes++;
        } else {
            $object->likes--;
        }
        $object->save();
        return;
    }
}
