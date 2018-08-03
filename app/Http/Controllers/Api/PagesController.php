<?php

namespace App\Http\Controllers\Api;

use Dot\Pages\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller ;

class PagesController extends Controller
{
    /**
     * GET /api/getPages
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPages($slug)
    {
        $page = Page::where(['slug' => $slug, 'status' => 1])->firstOrFail();
        return response()->json(['data' => $page]);
    }
}
