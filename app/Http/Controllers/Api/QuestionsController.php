<?php

namespace App\Http\Controllers\Api;

use App\Model\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;

class QuestionsController extends Controller
{
    /**
     * GET api/getQuestions
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestions()
    {
        $questions = Question::where(['status' => 1])->get();
        return response()->json(['data'=>$questions]);
    }
}
