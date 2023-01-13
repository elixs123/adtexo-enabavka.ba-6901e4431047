<!-- start: week -->
<div class="col-12 col-lg-6">
    {!! Form::open(['url' => route('dashboard'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'form-group']) !!}
    <div class="input-group input-group-weeks">
        <div class="input-group-prepend">
            @if($week_data['prev'] == 0)
            <span class="btn btn-secondary"><span class="feather icon-chevrons-left"></span></span>
            @else
            <a href="{{ route('dashboard', ['week' => $week_data['prev']]) }}" class="btn btn-primary" title="{{ trans('skeleton.vars.date.prev.week') }}" data-tooltip data-loader><span class="feather icon-chevrons-left"></span></a>
            @endif
        </div>
        <div class="select-weeks">
            {!! Form::select('week', $week_data['weeks'], $week_data['current'], ['class' => 'form-control populate plugin-selectTwo', 'data-plugin-selectTwo', 'data-plugin-options' => '{"placeholder" : "-", "width" : "resolve", "containerCssClass" : "select2-container-squared", "dropdownCssClass" : "select2-dropdown-square"}', 'id' => 'form-control-week-number']) !!}
        </div>
        <div class="input-group-append">
            @if($week_data['next'] > $week_data['max'])
            <span class="btn btn-secondary"><span class="feather icon-chevrons-right"></span></span>
            @else
            <a href="{{ route('dashboard', ['week' => $week_data['next']]) }}" class="btn btn-primary" title="{{ trans('skeleton.vars.date.next.week') }}" data-tooltip data-loader><span class="feather icon-chevrons-right"></span></a>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
</div>
<!-- end: week -->

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
            $('select#form-control-week-number').change(function () {
                loader_on();
                $(this).parent().parent().parent().submit();
            });
        });
    </script>
@endsection
