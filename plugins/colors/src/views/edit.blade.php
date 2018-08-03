@extends("admin::layouts.master")

@section("content")

    <form action="" method="post">

        <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <h2>
                    <i class="fa fa-paint-brush"></i>
                    {{ $color->id ? trans("colors::colors.edit") : trans("colors::colors.add_new") }}
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                    </li>
                    <li>
                        <a href="{{ route("admin.colors.show") }}">{{ trans("colors::colors.colors") }}</a>
                    </li>
                    <li class="active">
                        <strong>
                            {{ $color->id ? trans("colors::colors.edit") : trans("colors::colors.add_new") }}
                        </strong>
                    </li>
                </ol>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">

                @if ($color->id)
                    <a href="{{ route("admin.colors.create") }}" class="btn btn-primary btn-labeled btn-main"> <span
                                class="btn-label icon fa fa-plus"></span>
                        {{ trans("colors::colors.add_new") }}</a>
                @endif

                <button type="submit" class="btn btn-flat btn-danger btn-main">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    {{ trans("colors::colors.save_color") }}
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
                        <textarea name="name" class="form-control input-lg" rows="1" id="post_title"
                                  placeholder="{{ trans("colors::colors.attributes.name") }}">
                            {{ @Request::old("name", $color->name) }}</textarea>
                            </div>

                            <div class="form-group">
                                <input type="color" title="{{ trans("colors::colors.attributes.value") }}"
                                       class="form-control small-square"
                                       value="{{ @Request::old("value", $color->value) }}" name="value">
                            </div>

                        </div>
                    </div>


                    @foreach(Action::fire("color.form.featured", $color) as $output)
                        {!!  $output !!}
                    @endforeach

                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-check-square"></i>
                            {{ trans("colors::colors.add_to_filter") }}
                        </div>
                        <div class="panel-body">
                            <div class="form-group switch-row">
                                <label class="col-sm-9 control-label"
                                       for="input-status">{{ trans("colors::colors.attributes.color_status") }}</label>
                                <div class="col-sm-3">
                                    <input @if (@Request::old("add_to_filter", $color->add_to_filter)) checked="checked"
                                           @endif
                                           type="checkbox" id="input-status" name="add_to_filter" value="1"
                                           class="status-switcher switcher-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach(Action::fire("color.form.sidebar") as $output)
                        {!! $output !!}
                    @endforeach

                </div>

            </div>

        </div>

    </form>

@stop


@section("head")

    <style>
        .small-square {
            width: 150px;
            height: 150px;
        }
    </style>

@stop

@section("footer")

    <script type="text/javascript" src="{{ assets('admin::js/plugins/moment/moment.min.js') }}"></script>

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

            $("body").on("click", ".remove-custom-field", function () {

                var item = $(this);
                confirm_box("{{ trans("colors::colors.sure_delete_field") }}", function () {
                    item.parents(".meta-row").remove();
                });

            });

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

        });


    </script>

@stop
