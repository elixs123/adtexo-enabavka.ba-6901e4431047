<div class="timeline-block {{ $item->action_name }}">
<div class="timeline-point small">
</div>

<div class="timeline-content">
<div class="card social-card share full-width">

<div class="card-header clearfix">
<div class="user-pic">
@if($item->rUser->photo != '')
<img alt="" width="33" height="33" data-src-retina="{{ asset('assets/pictures/user/medium_' . $item->rUser->photo) }}" data-src="{{ asset('assets/pictures/user/medium_' . $item->rUser->photo) }}" src="{{ asset('assets/pictures/user/small_' . $item->rUser->photo) }}">
@else
<img alt="" width="33" height="33" data-src-retina="{{ asset('assets/pictures/user/no-photo.jpg') }}" data-src="{{ asset('assets/pictures/user/no-photo.jpg') }}" src="{{ asset('assets/pictures/user/no-photo.jpg') }}">
@endif
</div>
<h5>{{ isset($item->rUser->client->name) ? $item->rUser->client->name : $item->rUser->email }}</h5>
<h6>@foreach($item->rUser->roles as $i => $role){{ $role->label }} / @endforeach</h6>
</div>
<div class="card-description">
<p>
{{ trans('activity.' . $item->name) }}<i style="margin: 0 5px" class="fa"></i>
</p>
</div>
</div>
<div class="event-date">
<small class="fs-12 hint-text">{{ date('d.m.Y. H:i', strtotime($item->created_at)) }}</small>
</div>
</div>

</div>