<div class="modal-body">
    <p><strong class="text-uppercase">{{ $item->full_name }}</strong></p>
    <hr>
    <ul class="nav nav-tabs" role="tablist">
        @foreach($item->getClientTabs($parent_id) as $tab_key => $tab_value)
        <li class="nav-item">
            <a class="nav-link @if($active = ($tab_key == 'main')){{ 'active' }}@endif @if(($tab_key == 'routes') && (is_null($parent_id) && !$item->is_location)){{ 'hidden' }}@endif" id="{{ $tab_key }}-tab" data-toggle="tab" href="#{{ $tab_key }}" aria-controls="{{ $tab_key }}" role="tab" aria-selected="@if($active){{ 'true' }}@else{{ 'false' }}@endif">{{ $tab_value }}</a>
        </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($item->getClientTabs($parent_id) as $tab_key => $tab_value)
        <div class="tab-pane @if($tab_key == 'main'){{ 'active' }}@endif" id="{{ $tab_key }}" aria-labelledby="{{ $tab_key }}-tab" role="tabpanel">
            @include('client.show.'.$tab_key)
        </div>
        @endforeach
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.close') }}</button>
</div>
