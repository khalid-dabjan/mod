<?php

namespace App\Http\Controllers\Api;

use Action;
use Dot\Categories\Models\Category;
use Dot\Colors\Models\Color;
use Dot\Media\Models\Media;
use Dot\Posts\Models\Brand;
use Dot\Posts\Models\PostSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dot\Platform\Controller;
use Dot\Posts\Models\Post;
use Dot\Posts\Models\PostMeta;
use Illuminate\Support\Facades\DB;
use Redirect;
//use Request;
use View;


/**
 * Class PostsController
 * @package Dot\Posts\Controllers
 */
class ExportsController extends Controller
{

    /**
     * View payload
     * @var array
     */
    protected $data = [];


    /**
     * POST api/importFile
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importFile(Request $request)
    {
        $data = ['data' => [], 'errors' => [],'notes'=>[]];
        if (!$request->hasFile('importItems')) {
            $data['errors'][] = 'File not exist.';
            return response()->json($data);
        }
        $count = 0;
        $foundCount = 0;
        $media = (new Media())->saveFile($request->file('importItems'));
        $url = public_path('uploads/' . Media::find($media)->path);
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($url);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        for ($row = 2; $row <= $highestRow; ++$row) {
            $title = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $url = (($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
            $image_id = ($this->getImageId($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
            if (Post::where(['title' => $title, 'url' => $url, 'image_id' => $image_id])->first()) {
                $foundCount++;
                continue;
            }

            $post = new Post();
            $post->title = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            if(!(isset($post->title)&&!empty($post->title))){
                $data['notes'][]="$row his title is null";
                break;
            }
            $post->brand_id = ($this->getBrandId($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
            $post->content = (($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
            $post->url = (($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
            $post->image_id = $image_id;
            $post->price = (($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
            $post->sale_price = (($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
            $post->currency = (($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
            $post->size_system = (($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
            $post->color_id = $this->getColorId(($worksheet->getCellByColumnAndRow(11, $row)->getValue()));
            $post->coverage = $this->getCoverage(($worksheet->getCellByColumnAndRow(13, $row)->getValue()));
            $post->user_id = fauth()->id();
            $post->lang = "en";

            $post->save();
            $count++;
            // Categories
            $post->categories()->sync($this->getCategoriesIds(($worksheet->getCellByColumnAndRow(12, $row)->getValue())));


            // Sizes
            $sizes_fields = explode(',', $worksheet->getCellByColumnAndRow(9, $row)->getValue());
            foreach ($sizes_fields as $value) {
                $meta = new PostSize();
                $meta->size = $value;
                $post->sizes()->save($meta);
            }

        }
        return response()->json(['status' => true, 'newItems' => $count, 'foundItems' => $foundCount,'notes'=>$data['notes']]);
    }

    /**
     * @param $categories
     * @return array
     */
    public function getCategoriesIds($categories)
    {
        $names = explode('>', $categories);
        $ids = [];
        $prev = null;
        foreach ($names as $name) {
            $category = DB::table('categories')
                ->whereRaw('`name` COLLATE UTF8_GENERAL_CI LIKE \'%' . trim($name) . '%\'')
                ->first();
            if ($category) {
                $ids[] = $category->id;
                $prev = $category;
                continue;
            }
            $category = new Category();

            $category->name = trim($name);
            $category->lang = "en";
            $category->user_id = fauth()->id();
            $category->status = 1;
            $category->parent = empty($prev) ? 0 : $prev->id;
            $category->save();
            $prev = $category;
            $ids[] = $category->id;
        }

        return $ids;
    }

    /**
     * @param $name
     * @return false|int|string
     */
    public function getCoverage($name)
    {
        return array_search(strtolower($name), config('posts.coverage'));
    }

    /**
     * @param $name
     * @return mixed id
     */
    public function getColorId($name)
    {

        $color = DB::table('colors')
            ->whereRaw('`name` COLLATE UTF8_GENERAL_CI LIKE \'%' . trim($name) . '%\'')
            ->first();
        if ($color) {
            return $color->id;
        }
        $color = new Color();
        $color->name = $name;
        $color->value = strtolower($name);
        $color->lang = "en";
        $color->add_to_filter = 1;
        $color->user_id = fauth()->id();
        $color->save();
        return $color->id;
    }

    /**
     * @param $name
     * @return mixed
     */
    private function getBrandId($name)
    {

        $brand = DB::table('brands')
            ->whereRaw('`title` COLLATE UTF8_GENERAL_CI LIKE \'%' . trim($name) . '%\'')
            ->first();

        if ($brand) {
            return $brand->id;
        }

        $brand = new Brand();
        $brand->title = $name;
        $brand->excerpt = $name;
        $brand->lang = "en";
        $brand->user_id = fauth()->id();

        $brand->save();
        return $brand->id;
    }

    /**
     * @param $path
     * @return int|mixed
     */
    private function getImageId($path)
    {

        if ($path) {


            $image = new \Dot\Media\Models\Media();

            $image = $image->saveLink($path);

            if ($image) {
                return $image->id;
            }
        }

        return 0;
    }

}
