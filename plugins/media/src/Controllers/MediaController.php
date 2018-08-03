<?php

namespace Dot\Media\Controllers;

use Config;
use Dot\Media\Models\Media;
use Dot\Platform\Controller;
use Image;
use Request;
use Response;
use stdClass;
use View;

/*
 * Class MediaController
 * @package Dot\Media\Controllers
 */
class MediaController extends Controller
{

    /*
     * View payload
     * @var array
     */
    public $data = [];


    /*
     * Show all media
     * @param int $page
     * @param string $type
     * @param string $q
     * @return mixed
     */
    function index($page = 1, $type = "all", $q = "")
    {

        $limit = 60;
        $offset = ($page - 1) * $limit;

        $query = Media::orderBy("updated_at", "DESC");

        if ($type != "all") {
            if ($type == "application") {
                $query->whereIn("type", ["text", "application"]);
            } else {
                $query->where("type", $type);
            }
        }

        if ($q != "") {
            $query->search(urldecode($q));
        }


        if (Request::filled("id")) {
            $query->where("id", "=", Request::get("id"));
        }

        $files = $query->limit($limit)->skip($offset)->get();

        $new_files = array();
        foreach ($files as $file) {
            $new_files[] = $file->response($file);
        }

        $this->data["files"] = $new_files;

        $this->data["q"] = $q;
        $this->data["page"] = $page;

        return View::make("media::index", $this->data);
    }


    /*
     * Save links to media
     * @accept youtube, soundcloud and direct static links
     * @return mixed
     */
    function link()
    {

        if ($link = Request::get("link")) {

            $media = new Media();

            if (strstr($link, "youtube.") and get_youtube_video_id($link)) {
                $response = $media->saveYoutube($link)->response();
            } else if (strstr($link, "soundcloud.")) {
                $response = $media->saveSoundcloud($link)->response();
            } else {
                $response = $media->saveLink($link)->response();
            }

            $response = View::make("media::index", $response)->render();

            return Response::json($response, 200);
        }
    }

    /*
     * Add watermark to images
     * @return void
     */
    function watermark()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!File::exists(UPLOADS_PATH . "/" . $_POST['path'])) {
                $this->download($_POST['amazon_path']);
            }

            $sizes = Config::get("media.sizes");

            foreach ($sizes as $size => $dimensions) {

                $img = Image::make(UPLOADS_PATH . "/" . $size . "-" . $_POST['path']);
                $img->insert('admin/watermarks/text_' . $size . '.png', "center", 0, 0);
                $img->save(UPLOADS_PATH . "/" . $size . "-" . $_POST['path']);

                $img = Image::make(UPLOADS_PATH . "/" . $size . "-" . $_POST['path']);
                $img->insert('admin/watermarks/logo_' . $size . '.png', "bottom-left", 0, 0);
                $img->save(UPLOADS_PATH . "/" . $size . "-" . $_POST['path']);

                if (strstr($_POST['amazon_path'], "/")) {
                    $parts = explode("/", $_POST['amazon_path']);
                    @s3_save($parts[0] . "/" . $parts[1] . "/" . $size . "-" . $_POST['path']);
                }
            }

            echo json_encode(array(
                "path" => $_POST['path']
            ));

            exit;
        }
    }

    /*
     * Download media
     * @return string
     */
    function download()
    {

        if (in_array(Request::get("path"), [null, []])) {
            return json_encode([]);
        }

        $path = Request::get("path");

        $sizes = [];

        // Adding original size

        $size = new stdClass();

        $size->name = "original";
        $size->path = $path;
        $size->url = uploads_url($path);
        $size->width = NULL;
        $size->height = NULL;

        $sizes[] = $size;


        foreach (Config::get("media.sizes", []) as $name => $dimensions) {

            $size = new stdClass();

            list($year, $month, $filename) = @explode("/", $path);

            $size->name = $name;
            $size->path = $year . "/" . $month . "/" . $name . "-" . $filename;
            $size->url = thumbnail($path, $name);
            $size->width = $dimensions[0];
            $size->height = $dimensions[1];

            $sizes[] = $size;

        }

        return json_encode($sizes);
    }

    /*
     * Crop images
     * @return void
     */
    function crop()
    {

        $amazon_path = Request::get("amazon_path");
        $path = Request::get("path");
        $size = Request::get("size");

        $w = (int)Request::get("w");
        $h = (int)Request::get("h");
        $x = (int)Request::get("x");
        $y = (int)Request::get("y");

        list($year, $month, $filename) = @explode("/", $path);

        $src = UPLOADS_PATH . "/" . $year . "/" . $month . "/" . $size . "-" . $filename;

        if ($w != "") {

            $img = Image::make(UPLOADS_PATH . "/" . $path);

            $img->crop((int)$w, (int)$h, (int)$x, (int)$y)->save($src);

            $sizes = Config::get("media.sizes");

            $current_size = $sizes[$size];

            echo json_encode(array(
                "url" => uploads_url($year . "/" . $month . "/" . $size . "-" . $filename),
                "path" => $year . "/" . $month . "/" . $size . "-" . $filename,
                "width" => $current_size[0],
                "height" => $current_size[1],
                "status" => 1,
            ));

        } else {
            echo json_encode(array(
                "url" => "sdaf",
                "path" => $size . "-" . $path,
                "status" => 0,
                "message" => "image is already cropped"
            ));
        }

        if (strstr($_POST['amazon_path'], "/")) {
            $parts = explode("/", $amazon_path);
            @s3_save($parts[0] . "/" . $parts[1] . "/" . $size . "-" . $path);
        }

        exit;
    }

    /*
     * Upload files from local computer
     * @return mixed
     */
    public function upload()
    {

        $file = Request::file('files')[0];

        $media = new Media();

        $validator = $media->validateFile($file);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return Response::json(array(
                "error" => join("<br />", str_replace("files.0", $file->getClientOriginalName(), $errors["files.0"]))
            ), 200);
        }

        $id = $media->saveFile($file);

        $media = Media::where("id", $id)->first();

        $row = new stdClass();
        $row->id = $id;
        $row->name = $media->path;
        $row->title = $media->title;
        $row->size = "";
        $row->url = uploads_url($media->path);
        $row->thumbnail = thumbnail($media->path);
        $row->html = View::make("media::index", array(
            "files" => array(0 => (object)array(
                "id" => $media->id,
                "provider" => "",
                "provider_id" => "",
                "type" => $media->type,
                "url" => uploads_url($media->path),
                "thumbnail" => thumbnail($media->path),
                "size" => "", //format_file_size($size),
                "path" => $media->path,
                "duration" => "",
                "title" => $media->title,
                "description" => "",
                "created_at" => $media->created_at
            ))
        ))->render();

        return Response::json(array('files' => array($row)), 200);
    }

    /*
     * Create a new gallery
     * @return void
     */
    function save_gallery()
    {

        $name = Request::get("name");

        $slug = str_slug($name);

        Gallery::insert(array(
            "gallery_slug" => $slug,
            "gallery_author" => "",
            "gallery_name" => $name
        ));

        $gallery_id = DB::getPdo()->lastInsertId();

        // insert gallery media

        if ($ids = Request::get("content")) {

            $i = 1;
            foreach ($ids as $id) {

                DB::table("media")->where("id", $id)->update(array(
                    "description" => $name . "-" . $i
                ));

                GalleryMedia::insert(array(
                    "gallery_id" => $gallery_id,
                    "id" => $id
                ));

                $i++;
            }
        }

        $type = DB::table("media")->where("id", $ids[0])->pluck("type");

        echo json_encode(array("gallery_id" => $gallery_id, "gallery_type" => $type));
    }

    /*
     * Create image thumbnail
     * @param $filename
     * @param int $s3_save
     * @return bool|string
     */
    function set_sizes($filename, $s3_save = 1)
    {

        if (!Config::get("media.thumbnails")) {
            return false;
        }

        if (file_exists(UPLOADS_PATH . "/" . $filename)) {

            $sizes = Config::get("media.sizes");
            $width = Image::make(UPLOADS_PATH . "/" . $filename)->width();
            $height = Image::make(UPLOADS_PATH . "/" . $filename)->height();

            foreach ($sizes as $size => $dimensions) {

                if ($width > $height) {
                    $new_width = $dimensions[0];
                    $new_height = null;
                } else {
                    $new_height = $dimensions[1];
                    $new_width = null;
                }

                $background = Image::make(UPLOADS_PATH . "/" . $filename)
                    ->fit($dimensions[0], $dimensions[1])
                    ->blur(100);

                $image = Image::make(UPLOADS_PATH . "/" . $filename)
                    ->resize($new_width, $new_height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                $background->insert($image, 'center');
                $background->save(UPLOADS_PATH . "/" . $size . "-" . $filename);


                if ($s3_save) {
                    // run after file upload only and not used in download case
                    s3_save(date("Y/m/") . $size . "-" . $filename);
                }
            }
        } else {
            return "Image Not found";
        }
    }

    function set_sizes_canvas($filename)
    {

        $sizes = Config::get("cms::app.sizes");
        $width = Image::make(UPLOADS_PATH . "/" . $filename)->width();
        $height = Image::make(UPLOADS_PATH . "/" . $filename)->height();

        foreach ($sizes as $size => $dimensions) {
            if ($size == "thumbnail") {
                Image::make(UPLOADS_PATH . "/" . $filename)
                    ->crop($dimensions[0], $dimensions[1])
                    ->save(UPLOADS_PATH . "/" . $size . "-" . $filename);;
            } else {

                if ($width > $height) {
                    $new_width = null;
                    $new_height = $dimensions[0];
                } else {
                    $new_width = $dimensions[1];
                    $new_height = null;
                }

                Image::make(UPLOADS_PATH . "/" . $filename)
                    ->resize($new_height, $new_width, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->resizeCanvas($dimensions[0], $dimensions[1], 'center', false, "#000000")
                    ->save(UPLOADS_PATH . "/" . $size . "-" . $filename);
            }
        }
    }

    /*
     * Galleries functions
     */
    function gallery_create()
    {

        if (Request::isMethod("post")) {

            $gallery = new Gallery();

            $gallery->name = Request::get("name");
            $gallery->author = Request::get("author");
            $gallery->user_id = Auth::user()->id;
            $gallery->lang = app()->getLocale();

            $gallery->save();

            return $gallery->id;
        }
    }

    function gallery_edit()
    {
        if (Request::isMethod("post")) {

            $gallery = Gallery::find(Request::get("gallery_id"));
            $gallery->name = Request::get("name");
            $gallery->author = Request::get("author");
            $gallery->save();

            return $gallery->id;

        }
    }

    /*
     * Delete gallery
     * @return void
     */
    function gallery_delete()
    {
        if (Request::isMethod("post")) {

            $gallery = Gallery::find(Request::get("id"));

            if (count($gallery)) {
                $gallery->delete();
            }

        }
    }

    /*
     * Delete media
     * @return void
     */
    public function save()
    {

        if (Request::isMethod("post")) {
            $media = Media::find(Request::get("file_id"));
            $media->title = Request::get("file_title");
            $media->description = Request::get("file_description");
            $media->save();
        }
    }

    /*
     * Download file
     * @return void
     */
    function download_file($path = false)
    {

        $link = uploads_url($path);
        $parts = explode("/", $path);
        $file = end($parts);

        if (strstr($path, "/")) {
            return copy($link, UPLOADS_PATH . "/" . $file);
        }
    }

    /*
     * Delete media
     * @return void
     */
    public function delete()
    {

        if (Request::isMethod("post")) {

            $media = Media::find(Request::get("id"));

            $media->delete();

            if ($media->provider == NULL or $media->provider == "") {

                if (file_exists(uploads_path($media->path))) {
                    @unlink(uploads_path($media->path));
                }

                $parts = explode(".", $media->path);

                $extension = end($parts);

                if (in_array(strtolower($extension), array("jpg", "jpeg", "gif", "png", "bmp"))) {

                    $sizes = Config::get("media.sizes");

                    foreach ($sizes as $size => $dimensions) {

                        $dir_parts = explode("/", $media->path);
                        $file = $dir_parts[0] . "/" . $dir_parts[1] . "/" . $size . "-" . $dir_parts[2];

                        if (Config::get("media.s3.status")) {
                            s3_delete($file);
                        } elseif ($file) {
                            @unlink(uploads_path($file));
                        }
                    }

                }

            }

        }
    }

}
