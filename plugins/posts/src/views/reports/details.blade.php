@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">
        {{csrf_field()}}
        <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-bug"></i>
                    {{ trans("posts::reports.reports") }}
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                    </li>
                    <li>
                        <a href="{{ route("admin.posts.reports.show") }}">{{ trans("posts::reports.reports") }}</a>
                    </li>
                    <li class="active">
                        <strong>
                            {{ trans("posts::reports.report")}}
                        </strong>
                    </li>
                </ol>
            </div>

        </div>

        <div class="wrapper wrapper-content fadeInRight">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label>
                                    {{ trans("posts::reports.user") }}:
                                </label>
                                <input readonly class="form-control input-lg" id="post_title"
                                       placeholder="{{ trans("posts::reports.attributes.url") }}"
                                       value="{{  ($report->user->first_name .' '.$report->user->last_name) .' @email: '.$report->user->username }}"/>
                            </div>
                            <div class="form-group">
                                <label>{{ trans("posts::reports.attributes.title") }}:</label>
                                <textarea name="title" readonly class="form-control input-lg" rows="1" id="post_title"
                                          placeholder="{{ trans("posts::reports.attributes.title") }}">{{  ($report->title) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{ trans("posts::reports.attributes.url") }}:
                                </label>
                                <input readonly class="form-control input-lg" id="post_title"
                                       placeholder="{{ trans("posts::reports.attributes.url") }}"
                                       value="{{  ($report->url) }}"/>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{ trans("posts::reports.attributes.message") }}:
                                </label>
                                <textarea rows="10" readonly class="form-control"
                                          placeholder="{{ trans("posts::reports.attributes.message") }}">{{  $report->message }}</textarea>
                            </div>

                        </div>
                    </div>

                    @foreach(Action::fire("post.reports.form.featured", $report) as $output)
                        {!!  $output !!}
                    @endforeach

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Actions
                                {{$report->target?' for ('.$report->target->user->first_name.' '.$report->target->user->last_name.')':''}}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <select name="action_id" {{$report->action_id!=0?'disabled':''}} id="select-action"
                                    class="form-control">
                                <option value> -- Select action</option>
                                @foreach([1=>'Delete Reported '.$report->type,2=>'Suspend for ever',3=>'Suspend to'] as $key=>$text)
                                    <option
                                        value="{{$key}}" {{$report->action_id==$key?'selected':''}}>{{$text}}</option>
                                @endforeach
                            </select>
                            @if($report->action_id==0)
                                <button type="submit" class="btn btn-primary">Save Action</button>
                            @endif


                            <div class="form-group date-time-pick"
                                 style=" display:{{$report->action_id==3?'block':'none'}};" id="suspended_to">
                                <label class="col-sm-3 control-label">Suspended to</label>
                                <div class="input-group date datetimepick">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input name="suspended_to" type="text"
                                           value="{{ (!isset($report->target)||!isset($report->target->user)) ? date("Y-m-d H:i:s") : @Request::old('suspended_to',$report->target->user->suspended_to) }}"
                                           class="form-control" id="input-suspended_to"
                                           placeholder="Suspended to">
                                </div>
                            </div>

                            @if(($report->action_id==3||$report->action_id==2)&&($report->target->user->suspended==1
                          ||(new Carbon\Carbon($report->target->user->suspended_to))->getTimestamp()>=Carbon\Carbon::now()->getTimestamp()))
                                <input type="submit" class="btn btn-primary" name="action" value="unblock"/>
                            @endif
                            <br>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>

@stop


@section("head")

    <link href="{{ assets("admin::tagit") }}/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="{{ assets("admin::tagit") }}/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">

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
                    alert_box("{{ trans("posts::reports.not_image_file") }}");
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
                    alert_box("{{ trans("posts::reports.not_media_file") }}");
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


            $("#mytags").tagit({
                singleField: true,
                singleFieldNode: $('#tags_names'),
                allowSpaces: true,
                minLength: 2,
                placeholderText: "",
                removeConfirmation: true,
                tagSource: function (request, response) {
                    $.ajax({
                        url: "{{ route("admin.tags.search") }}",
                        data: {q: request.term},
                        dataType: "json",
                        success: function (data) {
                            response($.map(data, function (item) {
                                return {
                                    label: item.name,
                                    value: item.name
                                }
                            }));
                        }
                    });
                },
                beforeTagAdded: function (event, ui) {
                    $("#metakeywords").tagit("createTag", ui.tagLabel);
                }
            });

            $("#mySizes").tagit({
                singleField: true,
                singleFieldNode: $('#sizes_names'),
                allowSpaces: true,
                minLength: 2,
                placeholderText: "",
                removeConfirmation: true,
                beforeTagAdded: function (event, ui) {
                    $("#metakeywords").tagit("createTag", ui.tagLabel);
                }
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

            $('#select-action').change(function (e) {
                if ($(this).val() == 3) {
                    $('#suspended_to').fadeIn(1000);
                }
            });

        });


    </script>

@stop
