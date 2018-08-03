<?php

namespace App\Http\Controllers\Api;

use App\Model\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;
use function Maps\Collection\collection;

class ColorController extends Controller
{

    /**
     * POST api/getColors
     * @return \Illuminate\Http\JsonResponse
     */
    public function getColors(Request $request)
    {
        $colors=collect();
        if($request->filled('add_to_filter')){
            $colors=Color::where(['add_to_filter'=>1])->get();
        }else{
            $colors= Color::all();
        }
        return response()->json(['errors' => [], 'data' => $colors]);
    }

    /**
     *
     */
}
