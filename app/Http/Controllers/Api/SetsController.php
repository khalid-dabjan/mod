<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentEvent;
use App\Model\Media;
use App\Model\Post;
use App\Model\Set;
use App\Model\SetComment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller;
use Illuminate\Validation\Rule;
use PhpParser\Comment;
use Validator;

class SetsController extends Controller
{
    /**
     * POST /api/setDetails
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDetails(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $set = Set::with(['user', 'image', 'items'])->where('id', $request->get("setId"))->first();
        if (!$set) {
            $data['errors'] = ['set not exist'];
            return response()->json($data, 400);
        }
        $set->views++;
        $set->save();
        $data['data']['set'] = \Maps\Set\setDetails($set, true);

        if ($request->filled('forEdit') && $request->get('forEdit')) {
            $data['data']['editableItems'] = [];
            foreach ($set->items as $item) {
                $newItems = new \stdClass();
                $newItems->item_id = $item->id;
                $newItems->x = $item->pivot->x;
                $newItems->y = $item->pivot->y;
                $newItems->height = $item->pivot->height;
                $newItems->rotation = $item->pivot->rotation;
                $newItems->width = $item->pivot->width;
                $newItems->image = uploads_url($item->image->path);
                $data['data']['editableItems'][] = $newItems;
            }
        }
        return response()->json($data, 200);
    }


    /**
     * POST api/addCommentToSet
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCommentToSet(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'setId' => 'required|exists:sets,id',
            'parentId' => 'required'
        ]);

        $validator->sometimes('required', 'exists:set_comments,id', function () use ($request) {
            return !($request->get('parentId') == "0" && $request->get('parentId') == 0);
        });

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        $comment = SetComment::create([
            'set_id' => $request->get('setId'),
            'parent' => $request->get('parentId'),
            'comment' => $request->get('text'),
            'user_id' => fauth()->user()->id
        ]);
        event(new CommentEvent($comment, 'set'));
        return response()->json($data);
    }

    /**
     * POST /api/getSetComments
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSetComments(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 4);
        $data = ['data' => [], 'errors' => []];
        $set_id = $request->get('setId');
        if (!Set::find($set_id)) {
            $data['errors'] = "Set not found";
            return response()->json($data, 400);
        }

        $comments = SetComment::with('user')
            ->orderBy('created_at', 'desc')->where(['set_id' => $set_id, 'parent' => 0])->offset($offset)->take($limit)->get();
        $data['data']['comments'] = \Maps\Set\comments($comments);
        return response()->json($data);
    }

    /**
     * POST api/getLikedSets
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLikedSets(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 6);
        $user = User::where(['id' => $request->get('userId', fauth()->user()->id), 'status' => 1])->first();

        if (!$user) {
            $data['errors'][] = 'User not found';
            return response()->json($data, 400);
        }

        $sets = $user->liked_sets()->with('image', 'user')->take($limit)->offset($offset)->get();
        $data['data'] = \Maps\Set\sets($sets);
        return response()->json($data);
    }

    /**
     * POST api/deleteSet
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSet(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $set = Set::where([
            'id' => $request->get('setId'),
            'user_id' => fauth()->user()->id
        ])->first();

        if (!$set) {
            $data['errors'][] = 'Set not found';
            return response()->json($data, 400);
        }
        $set->likes()->detach();
        $set->items()->detach();
        $set->delete();
        SetComment::where('set_id', $set->id)->delete();
        return response()->json($data);
    }

    /**
     * POST api/addSet
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function addSet(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'items' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $item) {
                        if (Post::where('id', $item['item_id'])->count() == 0) {
                            return $fail($attribute . ' is invalid.');
                        };
                    }

                },
            ]//'required|exists:posts,id'
        ]);


        $validator->sometimes('image', 'required', function () use ($request) {
            return $request->filled('image');
        });

        $media = new Media();


        if ($validator->fails() && ($request->filled('image') && !$media->isBase64($request->get('image')))) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        $media = $media->saveContent(explode('base64,', $request->get('image'))[1]);

        $set = Set::create(
            [
                'title' => $request->get('title'),
                'excerpt' => $request->get('description'),
                'lang' => 'en',
                'image_id' => $media->id,
                'user_id' => fauth()->id(),
                'front_page' => '1',
                "background"=> $request->get('background')
            ]
        );
        $items = array_map(function ($item) {
            $item['post_id'] = $item['item_id'];
            unset($item['item_id']);
            return $item;
        }, $request->get('items'));
        $set->items()->attach($items);
        $data['data']['set_id'] = $set->id;
        return response()->json($data);
    }

    /**
     * POST api/deleteComment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'commentId' => 'required|exists:set_comments,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $comment = SetComment::where(['id' => $request->get('commentId'), 'user_id' => fauth()->id()])->first();
        if (!$comment) {
            $data['errors'] = "It is not your comment";
            return response()->json($data, 400);
        }
        $comment->delete();
        return response()->json($data);
    }

    /**
     * POST api/getSets
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSets(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 6);


        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $sets = Set::where(['user_id' => $request->get('userId')])
            ->take($limit)
            ->offset($offset)->get();
        $data['data'] = \Maps\Set\sets($sets);
        return response()->json($data);

    }

    /**
     * POST api/editSet
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editSet(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'setId' => 'required|exists:sets,id',
            'items' => [
                'required',
                function ($attribute, $value, $fail) {
                    foreach ($value as $item) {
                        if (Post::where('id', $item['item_id'])->count() == 0) {
                            return $fail($attribute . ' is invalid.');
                        };
                    }

                },
            ]//'required|exists:posts,id'
        ]);

        $validator->sometimes('image', 'required', function () use ($request) {
            return $request->filled('image');
        });

        $media = new Media();

        if ($validator->fails() && ($request->filled('image') && !$media->isBase64($request->get('image')))) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $set = Set::find($request->get('setId'));
        $media = $media->saveContent(explode('base64,', $request->get('image'))[1]);
        $updated = Set::where([
            'user_id' => fauth()->user()->id,
            'id' => $request->get('setId'),
        ])->update([
            'title' => $request->get('title'),
            'excerpt' => $request->get('description'),
            'image_id' => $media->id,
            "background"=> $request->get('backgound')
        ]);
        $items = array_map(function ($item) {
            $item['post_id'] = $item['item_id'];
            unset($item['item_id']);
            return $item;
        }, $request->get('items'));
        $set->items()->sync($items);
        $data['data']['updated'] = $updated ? true : false;
        return response()->json($data);
    }
}
