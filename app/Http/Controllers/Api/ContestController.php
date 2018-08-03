<?php

namespace App\Http\Controllers\Api;

use App\Model\Contest;
use App\Model\ContestComment;
use App\Model\ContestItem;
use App\Model\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;
use Validator;

class ContestController extends Controller
{
    /**
     * POST api/getContests
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContests(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);

        $contests = Contest::with('image', 'winners')->where(['status' => 1])
            ->take($limit)
            ->offset($offset)
            ->get();
        $contests = \Maps\Contest\contests($contests);
        return response()->json($contests);
    }

    /**
     * POST api/getContestPhotos
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContestPhotos(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $validator = Validator::make($request->all(), [
            'contestId' => 'required|exists:contests,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 404);
        }
        $contestItems = ContestItem::with('image', 'user')
            ->where(['contest_id' => $request->get('contestId')])->take($limit)->offset($offset)->get();

        $contestItems = \Maps\Contest\items($contestItems);
        return response()->json($contestItems);
    }

    /**
     * POST api/publishContestPhoto
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishContestPhoto(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'contestId' => 'required|exists:contests,id',
            'imageName' => 'required',
            'image' => 'required'
        ]);
        $media = new Media();
        if ($validator->fails() && ($request->filled('image') && !$media->isBase64($request->get('image')))) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        // User Must has one Item in contest >> check this
        $isExist = ContestItem::where(['user_id' => fauth()->id(), 'contest_id' => $request->get('contestId')])->count() ? true : false;
        if ($isExist) {
            $data['errors'] = ['You has already item in this contest'];
            return response()->json($data, 400);
        }

        $media = $media->saveContent(explode('base64,', $request->get('image'))[1]);
        $item = ContestItem::create([
            'image_id' => $media->id,
            'title' => $request->get('imageName'),
            'user_id' => fauth()->id(),
            'contest_id' => $request->get('contestId')
        ]);
        $data['data'] = $item;
        return response()->json($data);
    }

    /**
     * POST api/addCommentToContest
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCommentToContest(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'contestId' => 'required|exists:contests,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }

        ContestComment::create([
            'contest_id' => $request->get('contestId'),
            'comment' => $request->get('text'),
            'user_id' => fauth()->user()->id
        ]);

        return response()->json($data);
    }

    /**
     * POST /api/getContestComments
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContestComments(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 4);
        $data = ['data' => [], 'errors' => []];
        $contest_id = $request->get('contestId');
        if (!Contest::find($contest_id)) {
            $data['errors'] = "Contest not found";
            return response()->json($data, 400);
        }
        $comments = ContestComment::with('user')->where(['contest_id' => $contest_id])->
        orderBy('created_at', 'desc')->offset($offset)->limit($limit)->get();
        $data['data']['comments'] = \Maps\Set\comments($comments);
        return response()->json($data);
    }

    /**
     * POST api/deleteContestComment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteContestComment(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'commentId' => 'required|exists:contests_comments,id',
        ]);

        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        $comment = ContestComment::where(['id' => $request->get('commentId'), 'user_id' => fauth()->id()])->first();
        if (!$comment) {
            $data['errors'] = "It is not your comment";
            return response()->json($data, 400);
        }
        $comment->delete();
        return response()->json($data);
    }

    /**
     * POST api/getWins
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWins(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);

        $contests = Contest::with('image', 'winners')->whereHas('winners', function ($query) {
            $query->where('contests_items.user_id', fauth()->id());
        })->where(['status' => 1])
            ->take($limit)
            ->offset($offset)
            ->get();
        $data['data']=$contests = \Maps\Contest\contests($contests);
        return response()->json($data);
    }
}
