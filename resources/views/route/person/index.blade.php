<div class="modal-body">
    <p><strong class="text-uppercase"><span class="text-primary">{{ $item->name }}</span>: {{ trans('route.title') }}</strong></p>
    <hr>
    <div class="row">
        <div class="col-12">
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
                    <tbody>
                        @foreach(trans('route.vars.weeks') as $week_id => $week)
                        <tr>
                            <td><strong class="font-small-3">{{ $week }}</strong></td>
                            @foreach(trans('route.vars.days') as $day_id => $day)
                            <td class="td-route" data-week="{{ $week_id }}" data-day="{{ $day_id }}">
                                @if(isset($routes[$week_id.'-'.$day_id]))
                                <a class="badge badge-info" data-toggle="modal" data-target="#form-modal2" title="{{ trans('skeleton.view_details') }}" href="{{ route('route.person.details', [$item->id, 'week' => $week_id, 'day' => $day_id]) }}" data-tooltip>{{ trans_choice('route.data.clients_num', $num = count($routes[$week_id.'-'.$day_id]), ['num' => $num]) }}</a>
                                @else
                                <a class="d-block" data-toggle="modal" data-target="#form-modal2" title="{{ trans('route.actions.assign') }}" href="{{ route('route.person.details', [$item->id, 'week' => $week_id, 'day' => $day_id]) }}" data-tooltip><span class="feather icon-plus"></span></a>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.close') }}</button>
</div>

<script>
    $(document).ready(function () {
        App.tooltip();
    });
</script>
