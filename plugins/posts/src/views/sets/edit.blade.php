@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">

        <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-clone"></i>
                    {{ $set->id ? trans("posts::sets.edit") : trans("posts::sets.add_new") }}
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                    </li>
                    <li>
                        <a href="{{ route("admin.posts.sets.show") }}">{{ trans("posts::sets.posts") }}</a>
                    </li>
                    <li class="active">
                        <strong>
                            {{ $set->id ? trans("posts::sets.edit") : trans("posts::sets.add_new") }}
                        </strong>
                    </li>
                </ol>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

                @if ($set->id)
                    <a href="{{ route("admin.posts.sets.create") }}" class="btn btn-primary btn-labeled btn-main"> <span
                            class="btn-label icon fa fa-plus"></span>
                        {{ trans("posts::sets.add_new") }}</a>
                @endif

                <button type="submit" class="btn btn-flat btn-danger btn-main">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    {{ trans("posts::sets.save_post") }}
                </button>

            </div>
        </div>

        <div class="wrapper wrapper-content fadeInRight">

            @include("admin::partials.messages")

            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="form-group">
                        <textarea name="title" class="form-control input-lg" rows="1" id="post_title"
                                  placeholder="{{ trans("posts::sets.attributes.title") }}">{{ @Request
                                ::old("title", $set->title) }}</textarea>
                            </div>

                            <div class="form-group">
                        <textarea name="excerpt" class="form-control" id="post_excerpt"
                                  placeholder="{{ trans("posts::sets.attributes.excerpt") }}">{{ @Request
                                ::old("excerpt", $set->excerpt) }}</textarea>
                            </div>

                            <div class="form-group">
                                @include("admin::partials.editor", ["name" => "content", "id" => "postcontent", "value" => $set->content])
                            </div>

                        </div>
                    </div>

                    @foreach(Action::fire("post.sets.form.featured", $set) as $output)
                        {!!  $output !!}
                    @endforeach

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-check-square"></i>
                            {{ trans("posts::sets.post_status") }}
                        </div>
                        <div class="panel-body">
                            <div class="form-group switch-row">
                                <label class="col-sm-9 control-label"
                                       for="input-front_page">{{ trans("posts::sets.attributes.front_page") }}</label>
                                <div class="col-sm-3">
                                    <input type="checkbox"
                                           @if (@Request::old("front_page", $set->front_page)) checked="checked"
                                           @endif id="input-front_page" name="front_page" value="1"
                                           class="status-switcher switcher-sm">
                                </div>
                            </div>
                            <div class="form-group date-time-pick">
                                <div class="input-group date datetimepick">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input name="published_at" type="text"
                                           value="{{ (!$set->id) ? date("Y-m-d H:i:s") : @Request::old('published_at', $set->published_at) }}"
                                           class="form-control" id="input-published_at"
                                           placeholder="{{ trans("posts::sets.attributes.published_at") }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-camera"></i>
                            {{ trans("posts::sets.add_image") }}
                            <a class="remove-post-image pull-right" href="javascript:void(0)">
                                <i class="fa fa-times text-navy"></i>
                            </a>
                        </div>
                        <div class="panel-body form-group">
                            <div class="row post-image-block">
                                <input type="hidden" name="image_id" class="post-image-id" id="post-image-id"
                                       value="{{ ($set->image) ? $set->image->id : 0 }}">

                                <a class="change-post-image label" href="javascript:void(0)">
                                    <i class="fa fa-pencil text-navy"></i>
                                    {{ trans("posts::sets.change_image") }}
                                </a>

                                <a class="post-media-preview" href="javascript:void(0)">
                                    <img width="100%" height="130px" class="post-image"
                                         src="{{ ($set and @$set->image) ? thumbnail($set->image->path) : assets("admin::default/image.png") }}">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-newspaper-o"></i>
                            <?php echo trans("posts::sets.items"); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="position:relative">
                                <select name="items[]" class="form-control" multiple="multiple" id="input-items">
                                    @foreach($set->items as $item)
                                        <option value="{{$item->id}}" selected>{{$item->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @foreach(Action::fire("post.sets.form.sidebar") as $output)
                        {!! $output !!}
                    @endforeach

                </div>

            </div>

        </div>

    </form>

@stop


@section("head")

    <link href="{{ assets("admin::tagit") }}/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="{{ assets("admin::tagit") }}/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
    <link href="{{ assets('admin::css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ assets('admin::css/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}"
          rel="stylesheet" type="text/css">


    <style>
        .custom-field-name {
            width: 40%;
            margin: 5px;
        }

        .custom-field-value {
            width: 50%;
            margin: 5px;
        }

        .remove-custom-field {
            margin: 10px;
        }

        .meta-rows {

        }

        .meta-row {
            background: #f1f1f1;
            overflow: hidden;
            margin-top: 4px;
        }

        .sale_price {
            padding-top: 25px;
        }

        .sizes {
            margin-bottom: 100px;
        }
    </style>

@stop

@section("footer")

    <script type="text/javascript" src="{{ assets("admin::tagit") }}/tag-it.js"></script>
    <script type="text/javascript" src="{{ assets('admin::js/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ assets('admin::js/select2.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ assets('admin::js/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script>

        $(document).ready(function () {


            $('.datetimepick').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
            });


            $("[name=format]").on('ifChecked', function () {
                $(this).iCheck('check');
                $(this).change();
                switch_format($(this));
            });

            $("[name=status]").change(function (e) {
                if (e.target.checked) {
                    $('.reason').fadeOut();
                } else {
                    $('.reason').fadeIn();
                }
            });
            switch_format($("[name=format]:checked"));

            function switch_format(radio) {

                var format = radio.val();

                $(".format-area").hide();
                $("." + format + "-format-area").show();
            }


            var elems = Array.prototype.slice.call(document.querySelectorAll('.status-switcher'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html, {size: 'small'});
            });
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $('.tree-views input[type=checkbox]').on('ifChecked', function () {
                var checkbox = $(this).closest('ul').parent("li").find("input[type=checkbox]").first();
                checkbox.iCheck('check');
                checkbox.change();
            });

            $('.tree-views input[type=checkbox]').on('ifUnchecked', function () {
                var checkbox = $(this).closest('ul').parent("li").find("input[type=checkbox]").first();
                checkbox.iCheck('uncheck');
                checkbox.change();
            });

            $(".expand").each(function (index, element) {
                var base = $(this);
                if (base.parents("li").find("ul").first().length > 0) {
                    base.text("+");
                } else {
                    base.text("-");
                }
            });
            $("body").on("click", ".expand", function () {
                var base = $(this);
                if (base.text() == "+") {
                    if (base.closest("li").find("ul").length > 0) {
                        base.closest("li").find("ul").first().slideDown("fast");
                        base.text("-");
                    }
                    base.closest("li").find(".expand").last().text("-");
                } else {
                    if (base.closest("li").find("ul").length > 0) {
                        base.closest("li").find("ul").first().slideUp("fast");
                        base.text("+");
                    }
                }
                return false;
            });


            $(".change-post-image").filemanager({
                types: "image",
                panel: "media",
                done: function (result, base) {
                    if (result.length) {
                        var file = result[0];
                        base.parents(".post-image-block").find(".post-image-id").first().val(file.id);
                        base.parents(".post-image-block").find(".post-image").first().attr("src", file.thumbnail);
                    }
                },
                error: function (media_path) {
                    alert_box("{{ trans("posts::sets.not_image_file") }}");
                }
            });

            $(".change-post-media").filemanager({
                types: "video",
                panel: "media",
                done: function (result, base) {
                    if (result.length) {
                        var file = result[0];
                        base.parents(".post-media-block").find(".post-media-id").first().val(file.id);
                        base.parents(".post-media-block").find(".post-media").first().attr("src", file.thumbnail);
                    }
                },
                error: function (media_path) {
                    alert_box("{{ trans("posts::sets.not_media_file") }}");
                }
            });

            $(".remove-post-image").click(function () {
                var base = $(this);
                $(".post-image-id").first().val(0);
                $(".post-image").attr("src", "{{ assets("admin::default/post.png") }}");
            });

            $(".remove-post-media").click(function () {
                var base = $(this);
                $(".post-media-id").first().val(0);
                $(".post-media").attr("src", "{{ assets("admin::default/media.gif") }}");
            });


            $(".add_gallery").filemanager({
                types: "image|video|audio|pdf",
                panel: "galleries",
                gallery_id: function () {
                    return 0;
                },
                galleries: function (result) {
                    result.forEach(function (row) {
                        if ($(".post_galleries [data-gallery-id=" + row.id + "]").length == 0) {
                            var html = '<div class="iwell post_gallery" data-gallery-id="' + row.id + '">' + row.name
                                + '<input type="hidden" name="galleries[]" value="' + row.id + '" />'
                                + '<a href="javascript:void(0)" class="remove_gallery pull-right text-navy"><i class="fa fa-times"></i></a></div>';
                            $(".post_galleries").html(html);
                        }
                    });
                    if ($(".post_galleries [data-gallery-id]").length != 0) {
                        $(".iwell.add_gallery").slideUp();
                    } else {
                        $(".iwell.add_gallery").slideDown();
                    }

                },
                error: function (media_path) {
                    alert(media_path + " is not an image");
                }
            });
            $("body").on("click", ".remove_gallery", function () {
                var base = $(this);
                var data_gallery = base.parents(".post_gallery");
                var data_gallery_id = data_gallery.attr("data-gallery-id");
                bootbox.dialog({
                    message: "هل أنت متأكد من الحذف ؟",
                    buttons: {
                        success: {
                            label: "موافق",
                            className: "btn-success",
                            callback: function () {
                                data_gallery.remove();
                                if ($(".post_galleries [data-gallery-id]").length != 0) {
                                    $(".iwell.add_gallery").slideUp();
                                } else {
                                    $(".iwell.add_gallery").slideDown();
                                }

                            }
                        },
                        danger: {
                            label: "إلغاء",
                            className: "btn-primary",
                            callback: function () {
                            }
                        },
                    },
                    className: "bootbox-sm"
                });
            });

            $("#input-items").select2({
                ajax: {
                    url: "<?php echo route("admin.posts.show"); ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            q: params.term
                        };
                        return query;
                    },
                    processResults: function (data) {
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        var items = data.map(function (e) {
                            return {
                                text: e.title,
                                id: e.id,
                                self: e,
                            }
                        });
                        return {
                            results: items
                        };
                    }
                },
                dir: "{{app()->getLocale()=="ar"?"rtl":"ltr"}}"
            });
        });


    </script>

@stop
