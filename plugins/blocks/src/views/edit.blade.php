@extends("admin::layouts.master")

@section("content")

    <form action="" method="post" class="BlocksForm">

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-th-large"></i>
                    {{ $block ? trans("blocks::blocks.edit") : trans("blocks::blocks.add_new") }}
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                    </li>
                    <li>
                        <a href="{{ route("admin.blocks.show") }}">{{ trans("blocks::blocks.blocks") }}</a>
                    </li>
                    <li class="active">
                        <strong>
                            {{ $block ? trans("blocks::blocks.edit") : trans("blocks::blocks.add_new") }}
                        </strong>
                    </li>
                </ol>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

                @if ($block)
                    <a href="{{ route("admin.blocks.create") }}"
                       class="btn btn-primary btn-labeled btn-main"> <span
                            class="btn-label icon fa fa-plus"></span>
                        {{ trans("blocks::blocks.add_new") }}</a>
                @endif

                <button type="submit" class="btn btn-flat btn-danger btn-main">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    {{ trans("blocks::blocks.save_block") }}
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
                                <label for="input-name">{{ trans("blocks::blocks.attributes.name") }}</label>
                                <input name="name" type="text"
                                       value="{{ @Request::old("name", $block->name) }}"
                                       class="form-control" id="input-name"
                                       placeholder="{{ trans("blocks::blocks.attributes.name") }}">
                            </div>

                            <div class="form-group">
                                <label for="input-type">{{ trans("blocks::blocks.attributes.type") }}</label>
                                <select id="input-type" class="form-control chosen-select chosen-rtl" name="type">
                                    @foreach(array("post", "tag", "category") as $type)
                                        <option value="{{ $type }}"
                                                @if($block and $block->type == $type) selected="selected" @endif>{{ trans("blocks::blocks.type_" . $type) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="input-limit">{{ trans("blocks::blocks.attributes.limit") }}</label>
                                <input name="limit" min="0" type="number"
                                       value="{{ @Request::old("limit", $block->limit, 0) }}"
                                       class="form-control"
                                       id="input-limit"
                                       placeholder="{{ trans("blocks::blocks.attributes.limit") }}">
                            </div>

                        </div>
                    </div>

                    @if(isset($block->id))
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <p>{{trans('blocks::blocks.blocks_items')}}</p>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-push-2 col-sm-8 ui-front">
                                    <input type="search" name="items" id="group-items" class="form-control"
                                           title="{{trans('categories::categories.item-search')}}"
                                           placeholder="{{trans('categories::categories.item-search')}}"/>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover">

                                                <tbody id="sortable">
                                                @if(!empty($block->orderedPosts))
                                                    @foreach($block->orderedPosts as $post)
                                                        <tr id="item-{{$post->id}}" class="items">
                                                            <input type="hidden" name="items[]" class="item-id"
                                                                   value="{{$post->id}}">
                                                            <td>
                                                                <img class="img-responsive img-preview"
                                                                     src="{{$post->image_id?thumbnail($post->image->path):'/plugins/admin/default/image.png'}}"/>
                                                            </td>
                                                            <td class="title">
                                                                <strong>{{$post->title}}</strong>
                                                                <br/>
                                                                <small>{{$post->brand->title or ''}}</small>
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0)" class="close">
                                                                    <i class="fa fa-times" hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                            <p class="no-record"
                                               style="display: {{empty($block->posts)?'block':'none'}}">{{ trans("posts::posts.no_records") }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach(Action::fire("block.form.featured") as $output)
                        {!! $output !!}
                    @endforeach

                </div>
                <div class="col-md-4">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-folder"></i>
                            {{ trans("blocks::blocks.add_category") }}
                        </div>
                        <div class="panel-body">

                            @if(Dot\Categories\Models\Category::count())
                                <ul class='tree-views'>
                                    <?php
                                    echo Dot\Categories\Models\Category::tree(array(
                                        "row" => function ($row, $depth) use ($block, $block_categories) {
                                            $html = "<li><div class='tree-row checkbox i-checks'><a class='expand' href='javascript:void(0)'>+</a> <label><input type='checkbox' ";
                                            if ($block and in_array($row->id, $block_categories->pluck("id")->toArray())) {
                                                $html .= 'checked="checked"';
                                            }
                                            $html .= "name='categories[]' value='" . $row->id . "'> &nbsp;" . $row->name . "</label></div>";
                                            return $html;
                                        }
                                    ));
                                    ?>
                                </ul>
                            @else
                                {{ trans("categories::categories.no_records") }}
                            @endif
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tags"></i>
                            {{ trans("blocks::blocks.add_tag") }}
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="position:relative">
                                <input type="hidden" name="tags" id="tags_names"
                                       value="{{ join(",", $block_tags) }}">
                                <ul id="mytags"></ul>
                            </div>
                        </div>
                    </div>

                    @foreach(Action::fire("block.form.sidebar") as $output)
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
    <link rel="stylesheet" href="{{assets('admin::css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css')}}">
    <style>
        .ui-autocomplete {
            list-style-type: none;
            padding: 0 !important;
        }

        .ui-autocomplete li {
            padding: 5px;
            border-bottom: 1px solid grey;
        }

        a.ui-corner-all {
            width: 100%;
            display: block;
        }

        a.ui-corner-all:hover {
            color: white;
            background: #1e8cbe;
            border-color: #1e8cbe;
            border-radius: 3px;
        }

        .img-responsive {
            height: 72px;
            width: 72px;
        }

        .label-success {
            background-color: #5cb85c;
        }

        .no-record {
            margin: auto;

        }

        .ui-state-highlight-2 {
            height: 90px;
            background-color: #ddd;
        }

        .close {
            position: relative;
            top: 20px;
            right: 20px;
        }
    </style>
@stop

@section("footer")
    <script type="text/javascript" src="{{ assets("admin::tagit") }}/tag-it.js"></script>

    <script>
        $(document).ready(function () {

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
            $("#group-items").on("keydown", function (event) {
                if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            }).autocomplete({

                source: function (request, response) {
                    $.getJSON("{{route('admin.posts.show')}}", {
                        q: request.term
                    }, function (data) {
                        var ids = [];

                        $('input.item-id').each(function (e, input) {
                            ids.push(input.value)
                        });
                        var filteredData = data.filter(function (item) {
                            for (let id of ids) {
                                if (id == item.id) {
                                    return false;
                                }
                            }
                            return true;
                        });
                        var newData = filteredData.map(function (e) {
                            return {
                                label: e.title, value: e.title,
                                item: e
                            }
                        });
                        response(newData);
                    });
                },

                select: function (event, ui) {

                    let post = ui.item.item;

                    this.value = "";

                    if ($("#item-" + post.id).length) {
                        return false;
                    }

                    var html = `<tr id="item-${post.id}" class="items">
                            <input type="hidden" name="items[]" class="item-id" value="${post.id}">
                            <td>
                                <img class="img-responsive img-preview" src="${post.image ? '{{ uploads_url() }}/' + post.image.path : '{{ url("/") }}/plugins/admin/default/image.png' }"/>
                            </td>
                            <td class="title">
                                <strong>${post.title}</strong>
                                <br/>
                                <small>${ post.brand ? post.brand.title : "" }</small>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="close">
                                    <i class="fa fa-times" hidden="true"></i>
                                </a>
                            </td>
                        </tr>`;

                    $('#sortable').append(html);
                    $("#sortable").sortable("refresh");
                    $('.no-record').fadeOut();

                    return false;
                },

            });

            $("#sortable").sortable({
                placeholder: "ui-state-highlight-2",
                axis: "y",
                opacity: 0.7,
                cursor: "move",
                helper: function (e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function (index) {
                        // Set helper cell sizes to match the original sizes
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },

            });
            $("#sortable").disableSelection();

            $('table').on('click', '.close', function () {

                if ($('#sortable tr').length == 1) {
                    $('.no-record').fadeIn();
                }
                $(this).closest('tr').fadeOut().remove();
            })

        });

    </script>
@stop

