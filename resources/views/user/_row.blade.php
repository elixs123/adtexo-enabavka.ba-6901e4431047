<tr id="row{{ $item->uid }}">
    <td><span class="avatar"><img alt="" src="@if($item->photo != ''){{ asset('assets/pictures/user/small_' . $item->photo) }}@else{{ asset('assets/img/no_photo.jpg') }}@endif" height="32" width="32"></span>{{ $item->email }}</td>
    <td>@foreach($item->roles as $role)<span class="badge badge-info">{{ $role->label }}</span> @endforeach</td>
    <td>
        <div class="bullet bullet-sm bullet-{{ $item->status == 'active' ? 'success' : 'secondary'  }}"></div>
    </td>
    @if(can('edit-user') || can('view-activity'))
    <td class="td-actions">
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('user.edit', [$item->id]) }}" title="{{ trans('user.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a>
{{--        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('activity.index', ['user_id' => $item->id]) }}" title="{{ trans('user.actions.activity') }}" data-tooltip><i class="feather icon-clock"></i></a>--}}
        @can('login-as')
        <a href="{{ url('/user/login-as/' . $item->id) }}" title="{{ trans('user.actions.login_as') }}" data-tooltip><i class="feather icon-user-check"></i></a>
        @endcan
    </td>
    @endif
</tr>
