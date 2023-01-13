@php $readonly = isset($readonly) ? $readonly : false; @endphp
<div class="table-responsive-lg">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>&nbsp;</th>
                @foreach(trans('route.vars.days') as $val)
                <th>{{ $val }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody data-route-body>
            @foreach(trans('route.vars.weeks') as $week_id => $week)
            <tr>
                <td><strong class="font-small-3">{{ $week }}</strong></td>
                @foreach(trans('route.vars.days') as $day_id => $day)
                <td class="td-route @if(isset($routes[$week_id.'-'.$day_id])){{ 'active' }}@endif">
                    <input name="routes[{{ $week_id }}][{{ $day_id }}]" type="text" class="form-control form-control-route" value="@if(isset($routes[$week_id.'-'.$day_id])){{ $routes[$week_id.'-'.$day_id] }}@else{{ '' }}@endif" aria-label="Rank" maxlength="5" data-plugin-mask data-plugin-options='{"mask": "##0", "placeholder": "", "reverse": false, "selectOnFocus": true}' data-route-input autocomplete="off" @if($readonly){{ 'readonly' }}@endif>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        // Plugin
        maskPlugin($('[data-route-body]'));
        // Input
        $('input[data-route-input]').change(function(e) {
            if (($(this).val() === '') || ($(this).val() === '0')) {
                $(this).parent().removeClass('active');
            } else {
                $(this).parent().addClass('active');
            }
        });
    });
</script>
