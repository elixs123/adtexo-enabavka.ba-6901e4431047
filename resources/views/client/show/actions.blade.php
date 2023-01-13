<div class="row">
    <div class="col-12">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>{{ trans('client.data.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($actions as $key => $action)
                <tr>
                    <td>
                        <strong>{{ $action['name'] }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="no-results @if(empty($actions)){{ 'show' }}@endif">
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>
