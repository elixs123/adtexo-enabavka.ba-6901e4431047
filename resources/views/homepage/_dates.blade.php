{!! Form::open(['url' => route($route), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'form-dates-range']) !!}
    {!! VuexyAdmin::dateRange('start', 'end', $dates_data['start_date'], $dates_data['end_date'], []) !!}
    @if(isset($query))
        @foreach($query as $key => $value)
            @if(!in_array($key, ['start', 'end']))
        {!! Form::hidden($key, $value) !!}
            @endif
        @endforeach
    @endif
{!! Form::close() !!}

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
            $('input[name="start"], input[name="end"]').change(function () {
                loader_on();
                $('form.form-dates-range').submit();
            });
        });
    </script>
@endsection
