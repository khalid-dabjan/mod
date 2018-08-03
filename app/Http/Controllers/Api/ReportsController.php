<?php

namespace App\Http\Controllers\Api;

use App\Model\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller;
use Validator;

class ReportsController extends Controller
{

    /**
     * POST api/pushReport
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pushReport(Request $request)
    {
        $data = ['data' => [], 'errors' => []];
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'title' => 'required',
            'message' => 'required|min:30',
            'type' => 'required',
            'object_id' => 'required'
        ]);
        if ($validator->fails()) {
            $data['errors'] = ($validator->errors()->all());
            return response()->json($data, 404);
        }
        Report::create([
            'title' => $request->get('title'),
            'url' => $request->get('url'),
            'message' => $request->get('message'),
            'user_id' => fauth()->id(),
            'type' => $request->get('type'),
            'object_id' => $request->get('object_id'),
            'format'=>$request->get('format')
        ]);
        return response()->json($data);
    }
}
