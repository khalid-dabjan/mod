<?php

namespace App\Http\Controllers\Api;

use App\Model\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;
use Validator;

class NotificationsController extends Controller
{
    /**
     * POST api/getNotifications
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 8);
        $data['data']['notifications'] = Notification::with('sender','sender.photo')->where(['receiver_id' => fauth()->id()])->orderBy('created_at','DESC')->take($limit)->offset($offset)->get();
        $data['data']['unseen_count'] = Notification::where(['receiver_id' => fauth()->id(), 'seen' => 0])->count();
        return response()->json($data);
    }

    /**
     * POST api/setNotificationSeen
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setNotificationSeen(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'notificationId' => 'required|exists:notifications,id'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 400);
        }
        Notification::where(['receiver_id' => fauth()->id(), 'id' => $request->get('notificationId')])->update(['seen' => 1]);
        $data['data'] = ['notification seen.'];
        return response()->json($data);
    }

}
