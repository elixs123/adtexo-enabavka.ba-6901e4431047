<div class="row">
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('type_id', $item->type_id, $item->getTypes($item->type_id), [], trans('client.data.type_id')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::locked('jib', $item->jib, $item->jib, [], trans('client.data.jib')) !!}
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::locked('pib', $item->pib, $item->pib, [], trans('client.data.pib')) !!}
    </div>
    <div class="col-4 col-md-3">
        {!! VuexyAdmin::locked('code', $item->code, $item->code, [], trans('client.data.code')) !!}
    </div>
    <div class="col-8 col-md-9">
        {!! VuexyAdmin::locked('name', $item->name, $item->name, [], trans('client.data.name')) !!}
    </div>
    <div class="col-12">
        <div class="form-group">
            {!! VuexyAdmin::checkbox('is_location', 1, $item->is_location, ['disabled'], trans('client.data.is_location')) !!}
        </div>
    </div>
</div>
@if($item->is_location)
<div class="row @if(!$item->is_location){{ 'hidden' }}@else{{ '' }}@endif">
    <div class="col-4 col-md-3">
        {!! VuexyAdmin::locked('location_code', $item->location_code, $item->location_code, [], trans('client.data.location_code')) !!}
    </div>
    <div class="col-8 col-md-9">
        {!! VuexyAdmin::locked('location_name', $item->location_name, $item->location_name, [], trans('client.data.location_name')) !!}
    </div>
    <div class="col-12 col-md-6">
        {!! VuexyAdmin::locked('location_type_id', $item->location_type_id, is_null($item->location_type_id) ? '-' : $item->getLocationTypes($item->location_type_id), [], trans('client.data.location_type_id')) !!}
    </div>
    <div class="col-12 col-md-6">
        {!! VuexyAdmin::locked('category_id', $item->category_id, is_null($item->category_id) ? '-' : $item->getCategories($item->category_id), [], trans('client.data.category_id')) !!}
    </div>
</div>
@endif
<div class="row">
    @if($item->photo)
    <div class="col-12">
        <div class="form-group">
            <img src="{{ asset(config('picture.client_path').'/original/'.$item->photo) }}" class="img-fluid" alt="{{ $item->full_name }}">
        </div>
    </div>
    @endif
    @if(userIsAdmin())
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('stock_id', $item->stock_id,  $item->rStock->name, [], trans('client.data.stock_id')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('lang_id', $item->lang_id,  config('app.locales')[$item->lang_id], [], trans('client.data.lang_id')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::locked('status', $item->status,  $item->rStatus->name, [], trans('skeleton.data.status')) !!}
    </div>
    @endif
</div>
<div class="row">
    <div class="col-12">
        {!! VuexyAdmin::locked('tracking', null,  $item->public_url, [], 'Tracking') !!}
    </div>
</div>
