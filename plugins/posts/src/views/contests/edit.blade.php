@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">

        <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-trophy"></i>
                    {{ $contest->id ? trans("posts::contests.edit") : trans("posts::contests.add_new") }}
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                    </li>
                    <li>
                        <a href="{{ route("admin.posts.contests.show") }}">{{ trans("posts::contests.posts") }}</a>
                    </li>
                    <li class="active">
                        <strong>
                            {{ $contest->id ? trans("posts::contests.edit") : trans("posts::contests.add_new") }}
                        </strong>
                    </li>
                </ol>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

                @if ($contest->id)
                    <a href="{{ route("admin.posts.contests.create") }}"
                       class="btn btn-primary btn-labeled btn-main"> <span
                            class="btn-label icon fa fa-plus"></span>
                        {{ trans("posts::contests.add_new") }}</a>
                @endif

                <button type="submit" class="btn btn-flat btn-danger btn-main">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    {{ trans("posts::contests.save_post") }}
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
                                  placeholder="{{ trans("posts::contests.attributes.title") }}">{{ @Request
                                ::old("title", $contest->title) }}</textarea>
                            </div>
                            <div class="form-group">
                                @include("admin::partials.editor", ["name" => "content", "id" => "postcontent", "value" => $contest->content])
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            @foreach($contest->items as $item)
                                <div class="col-md-3 item-card">
                                    <div class="thumbnail">
                                        <img src="{{thumbnail($item->image->path)}}" alt="Iems"
                                             title="{{$item->title}}">
                                        <div class="caption">
                                            <h3>{{$item->title}}</h3>
                                            <p>
                                                Created by:{{$item->user->first_name.' '.$item->user->last_name}}
                                            </p>
                                            <p style="text-align: right;">
                                                <a href="javascript:void(0)" class="btn btn-danger action-delete"
                                                   data-key="{{$item->id}}"><i class="fa fa-trash" hidden="true"></i></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if(count($contest->items)==0)
                                <p>No items found</p>
                            @endif
                        </div>
                    </div>

                    @foreach(Action::fire("post.form.featured", $contest) as $output)
                        {!!  $output !!}
                    @endforeach

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-check-square"></i>
                            {{ trans("posts::contests.post_status") }}
                        </div>
                        <div class="panel-body">

                            <div class="form-group switch-row">
                                <label class="col-sm-9 control-label"
                                       for="input-status">{{ trans("posts::contests.attributes.status") }}</label>
                                <div class="col-sm-3">
                                    <input type="checkbox"
                                           @if (@Request::old("status", $contest->status)) checked="checked"
                                           @endif id="input-front_page" name="status" value="1"
                                           class="status-switcher switcher-sm">
                                </div>
                            </div>

                            <div class="form-group date-time-pick">
                                <label class="col-sm-3 control-label" style="padding-top: 8px;    margin-left: -13px;"
                                       for="input-reward_code">{{ trans("posts::contests.attributes.published_at") }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group date datetimepick">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="published_at" type="text"
                                               value="{{ (!$contest->id) ? date("Y-m-d H:i:s") : @Request::old('published_at', $contest->published_at) }}"
                                               class="form-control" id="input-published_at"
                                               placeholder="{{ trans("posts::contests.attributes.published_at") }}">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group date-time-pick" style="padding-top: 38px;">
                                <label class="col-sm-3 control-label" style="padding-top: 8px;    margin-left: -13px;"
                                       for="input-reward_code">{{ trans("posts::contests.attributes.expired_at") }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group date datetimepick">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="expired_at" type="text"
                                               value="{{ (!$contest->id) ? date("Y-m-d H:i:s") : @Request::old('expired_at', $contest->published_at) }}"
                                               class="form-control" id="input-published_at"
                                               placeholder="{{ trans("posts::contests.attributes.expired_at") }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-hashtag"></i>
                            {{ trans("posts::contests.attributes.hash_tag") }}
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top: 8px"
                                       for="input-hash_tag">{{ trans("posts::contests.attributes.hash_tag") }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="col-sm-10 form-control" id="input-hash_tag"
                                           value="{{@Request::old("hash_tag", $contest->hash_tag)}}"
                                           name="hash_tag">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-trophy"></i>
                            {{ trans("posts::contests.reward") }}
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="margin-bottom:0px">

                                @foreach (['coupon','discount'] as $key=>$reward)
                                    <div class="radio" style="margin-top: 0;">
                                        <label>
                                            <input type="radio" name="reward" value="{{ $key }}"
                                                   class="i-checks"
                                                   @if ((!$contest->id and $reward == "coupon") or ($contest and $contest->reward == $key)) checked @endif>&nbsp;
                                            <span class="lbl">{{ trans('posts::contests.format_' . $reward) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="padding-top: 8px"
                                       for="input-reward_code">{{ trans("posts::contests.attributes.reward_code") }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="col-sm-10 form-control" id="input-reward_code"
                                           value="{{@Request::old("reward", $contest->reward_code)}}"
                                           name="reward_code">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-camera"></i>
                            {{ trans("posts::contests.add_image") }}
                            <a class="remove-post-image pull-right" href="javascript:void(0)">
                                <i class="fa fa-times text-navy"></i>
                            </a>
                        </div>
                        <div class="panel-body form-group">
                            <div class="row post-image-block">
                                <input type="hidden" name="image_id" class="post-image-id" id="post-image-id"
                                       value="{{ ($contest->image) ? $contest->image->id : 0 }}">

                                <a class="change-post-image label" href="javascript:void(0)">
                                    <i class="fa fa-pencil text-navy"></i>
                                    {{ trans("posts::contests.change_image") }}
                                </a>

                                <a class="post-media-preview" href="javascript:void(0)">
                                    <img width="100%" height="130px" class="post-image"
                                         src="{{ ($contest and @$contest->image) ? thumbnail($contest->image->path) : assets("admin::default/image.png") }}">
                                </a>
                            </div>
                        </div>
                    </div>
                    @foreach(Action::fire("post.contest.form.sidebar") as $output)
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
                    alert_box("{{ trans("posts::contests.not_image_file") }}");
                },
                media_id:function () {
                    return $('#post-image-id').val();
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


        });


        $(function () {
            $('.action-delete').click(function () {
                $self = $(this);
                $.ajax({
                    url: "{{route('admin.posts.contests.items.delete')}}",
                    data: {
                        id: $self.data('key'),
                    },
                    "type": 'POST',
                    success: function () {
                        $self.closest('.item-card').fadeOut(500);
                    }
                });
            });
        })

    </script>

@stop
