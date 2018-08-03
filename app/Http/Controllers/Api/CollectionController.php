<?php

namespace App\Http\Controllers\Api;

use App\Model\Collection;
use App\Model\CollectionComment;
use App\Model\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;
use Validator;

class CollectionController extends Controller
{
    /**
     * POST api/createCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCollection(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required|max:254',
            'items.*' => 'exists:posts,id',
            'sets.*' => 'exists:sets,id',
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
        $collection = Collection::create([
            'user_id' => fauth()->user()->id,
            'title' => $request->get('title'),
            'excerpt' => $request->get('description'),
            'image_id' => isset($media->id) ? $media->id : 0,
            'lang' => 'en',
        ]);
        $data['data']['collection_id'] = $collection->id;

        $collection->items()->attach($request->get('items', []));
        $collection->sets()->attach($request->get('sets', []));

        return response()->json($data);
    }

    /**
     * POST api/getCollections
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollections(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $collections = Collection::with('user', 'items', 'items.image', 'sets')
            ->where(['user_id' => $request->get('userId', fauth()->user()->id)])
            ->take($limit)
            ->offset($offset)
            ->get();
        $data['data'] = \Maps\Collection\collections($collections);
        return response()->json($data);
    }

    /**
     * POST api/deleteCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCollection(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'collectionId' => 'required|exists:collections,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $collection = Collection::find($request->get('collectionId'));
        $collection->items()->detach();
        $collection->sets()->detach();
        $collection->delete();
        return response()->json($data);
    }

    /**
     * POST api/addItemToCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItemToCollection(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'collectionId' => 'required|exists:collections,id',
            'itemId' => 'required|exists:posts,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $collection = Collection::with('user', 'sets')
            ->where('id', $request->get('collectionId'))->first();

        if ($collection->items()->where('post_id', $request->get('itemId'))->count() > 0) {
            $data['errors'][] = "This item already exist";
            return response()->json($data, 400);
        }
        $collection->items()->attach($request->get('itemId'));
        $data['data'] = \Maps\Collection\collection($collection);
        return response()->json($data);
    }


    /**
     * POST api/addItemToCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSetToCollection(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'collectionId' => 'required|exists:collections,id',
            'setId' => 'required|exists:sets,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $collection = Collection::with('user', 'items')
            ->where('id', $request->get('collectionId'))->first();

        if ($collection->sets()->where('set_id', $request->get('setId'))->count() > 0) {
            $data['errors'][] = "This set already exist";
            return response()->json($data, 400);
        }
        $collection->sets()->attach($request->get('setId'));
        $data['data'] = \Maps\Collection\collection($collection);
        return response()->json($data);
    }

    /**
     * POST api/addCommentToCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCommentToCollection(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'collectionId' => 'required|exists:collections,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        CollectionComment::create([
            'collection_id' => $request->get('collectionId'),
            'comment' => $request->get('text'),
            'user_id' => fauth()->user()->id
        ]);

        return response()->json($data);
    }

    /**
     * POST /api/getCollectionComments
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionComments(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 4);
        $data = ['data' => [], 'errors' => []];
        $collection_id = $request->get('collectionId');
        if (!Collection::find($collection_id)) {
            $data['errors'] = "Collection not found";
            return response()->json($data, 400);
        }
        $comments = CollectionComment::with('user')->where(['collection_id' => $collection_id])->
        orderBy('created_at', 'desc')->offset($offset)->limit($limit)->get();
        $data['data']['comments'] = \Maps\Set\comments($comments);
        return response()->json($data);
    }

    /**
     * POST api/deleteCollectionComment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCollectionComment(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'commentId' => 'required|exists:collection_comments,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $comment = CollectionComment::where(['id' => $request->get('commentId'), 'user_id' => fauth()->id()])->first();
        if (!$comment) {
            $data['errors'] = "It is not your comment";
            return response()->json($data, 400);
        }
        $comment->delete();
        return response()->json($data);
    }


    /**
     * POST api/editCollection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCollection(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required|max:254',
            'collectionId' => 'required|exists:collections,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $updated = Collection::where([
            'user_id' => fauth()->user()->id,
            'id' => $request->get('collectionId'),
        ])->update([
            'title' => $request->get('title'),
            'excerpt' => $request->get('description')
        ]);
        $data['data']['updated'] = $updated ? true : false;
        return response()->json($data);
    }


    /**
     * POST api/collectionDetails
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectionDetails(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'collectionId' => 'required|exists:collections,id',
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $collection = Collection::with('image')->where('id', $request->get('collectionId'))->first();
        $data['data'] = \Maps\Collection\collection($collection);
        return response()->json($data);
    }

}
