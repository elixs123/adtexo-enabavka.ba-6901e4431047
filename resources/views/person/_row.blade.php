<tr id="row{{ $item->uid }}">
    <td>{{ $item->id }}</td>
    <td>
        <span>{{ $item->name }}</span>
        @if(!is_null($item->user_id))
        <span class="badge badge-success" data-toggle="tooltip" data-placement="top" title="{{ trans('person.data.access') }}"><span class="feather icon-user-check"></span></span>
        @endif
    </td>
    <td>
        <span class="badge badge-info text-uppercase">{{ $item->rType->name }}</span>
    </td>
    <td><a href="tel:{{ $item->phone }}">{{ $item->phone }}</a></td>
    <td><div class="bullet bullet-sm" style="background-color: {{ $item->rStatus->background_color }};" title="{{ $item->rStatus->name }}" data-tooltip></div></td>
    @if(can('edit-person') || can('view-activity'))
    <td class="td-actions">
        @can('edit-person')
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('person.actions.edit') }}" data-href="{{ route('person.edit', [$item->id]) }}" data-tooltip><i class="feather icon-edit-1"></i></a>
        @endcan
        @if(can('view-route') && ($item->type_id == 'salesman_person'))
        <a data-toggle="modal" data-target="#form-modal1" title="{{ trans('person.actions.route') }}" data-href="{{ route('route.person.index', [$item->id]) }}" data-tooltip><i class="feather icon-map"></i></a>
        @endif
        @if(($item->type_id != 'focuser_person') && can('view-client'))
        <a href="{{ route('client.index', ['person_type' => (($item->type_id == 'sales_agent_person') ? 'responsible_person' : $item->type_id), 'person_id' => $item->id]) }}" title="{{ trans('person.actions.clients') }}" data-tooltip><i class="feather icon-briefcase"></i></a>
        @endif
        @if(!is_null($item->user_id) && can('login-as'))
        <a href="{{ url('/user/login-as/' . $item->user_id) }}" title="{{ trans('user.actions.login_as') }}" data-tooltip><i class="feather icon-user-check"></i></a>
        @endif
        @if(can('view-activity') && !is_null($item->user_id))
        <a href="{{ route('activity.index', ['user_id' => $item->user_id]) }}" title="{{ trans('skeleton.actions.activity') }}" data-tooltip><i class="feather icon-clock"></i></a>
        @endif
    </td>
    @endif
</tr>
