<div class="card">
    <div class="card-header pb-1 border-bottom">@php $doc_status = get_codebook_opts('status')->where('code', $status)->first(); @endphp
        <h4 class="card-title"><span class="badge badge-dark">{{ trans('client.title') }}</span> <span class="badge" style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span></h4>
    </div>
    <div class="card-content">
        @if($no_results = isset($user_clients[$status]) && !empty($user_clients[$status]))
        {!! Form::open(['url' => route('client.status.change'), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-client-'.$type.'-'.$status).' table-responsive-lg', 'data-status' => $type.'-'.$status]) !!}
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 40px;">
                            <div class="custom-control custom-checkbox checkbox-default">
                                <input id="form-control-clients-{{ $type }}-{{ $status }}" class="custom-control-input" type="checkbox" data-select-all data-status="{{ $type.'-'.$status }}">
                                <label for="form-control-clients-{{ $type }}-{{ $status }}" class="custom-control-label">&nbsp;</label>
                            </div>
                        </th>
                        <th>#</th>
                        <th>{{ trans('client.data.name') }}</th>
                        <th>{{ trans('client.data.address') }}</th>
                        <th>{{ trans('client.data.is_location') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_clients[$status] as $client)
                    <tr id="client{{ $client['uid'] }}" data-tr-status="{{ $type.'-'.$status }}">
                        <td>
                            <div class="custom-control custom-checkbox checkbox-default">
                                <input id="form-control-clients-{{ $client->id }}" class="custom-control-input" name="c[]" type="checkbox" value="{{ $client->id }}" data-select-{{ $type.'-'.$status }}>
                                <label for="form-control-clients-{{ $client->id }}" class="custom-control-label">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $client->id }}</td>
                        <td>
                            <a href="{{ can('edit-client') ? route('client.edit', ['id' => $client->id]) : route('client.show', ['id' => $client->id]) }}" data-toggle="modal" data-target="#form-modal1"><strong title="{{ trans('client.actions.show') }}" data-tooltip>{{ $client->full_name }}</strong></a>
                            <br><small>({{ is_null($client->rSalesmanPerson) ? '-' : $client->rSalesmanPerson->name }})</small>
                        </td>
                        <td>{{ $client->full_address }}</td>
                        <td>{{ $client->is_location ? 'Da' : 'Ne' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(can('edit-client'))
            <div class="border-top p-1">
                @if($status == 'pending')
                <button type="button" class="btn btn-danger" data-client-status data-status="inactive" data-type="client">{{ trans('client.actions.status_to.inactive') }}</button>
                <button type="button" class="btn btn-success" data-client-status data-status="active" data-type="client">{{ trans('client.actions.status_to.active') }}</button>
                @endif
            </div>
            <input type="hidden" name="s" value="" required>
            @endif
        {!! Form::close() !!}
        @endif
        <div class="no-results @if(!$no_results){{ 'show' }}@endif" data-no-results="{{ $type.'-'.$status }}">
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
    <div class="card-footer text-right">
        <a href="{{ route('client.index', ['status' => $status]) }}" class="btn btn-info">{{ trans('skeleton.view_all') }}</a>
    </div>
</div>
