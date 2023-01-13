<div class="row">
    <div class="col-12 col-md-4">
        @if($method == 'post')
        {!! VuexyAdmin::selectTwo('type_id', $item->getTypes(), 'business_client', ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-", "allowClear" : true}', 'id' => 'form-control-type_id-'.$item->getTable(), 'required'], trans('client.data.type_id')) !!}
            @else
        {!! VuexyAdmin::text('type', $item->getTypes(isset($parent['type_id']) ? $parent['type_id'] : $item->type_id), ['required', 'readonly'], trans('client.data.type_id')) !!}
        {!! Form::hidden('type_id', isset($parent['type_id']) ? $parent['type_id'] : $item->type_id) !!}
        @endif
    </div>
    <div class="col-6 col-md-4">
        {!! VuexyAdmin::text('jib', isset($parent['jib']) ? $parent['jib'] : '', ['maxlength' => 13, 'required' => ($item->type_id == 'private_client') ? false : true], trans('client.data.jib')) !!}
    </div>
    <div class="col-6 col-md-4">
        @if(is_null($parent_id))
        {!! VuexyAdmin::text('pib', null, ['maxlength' => 12], trans('client.data.pib')) !!}
            @else
        {!! VuexyAdmin::text('pib', $parent['pib'], ['readonly'], trans('client.data.pib')) !!}
        @endif
    </div>
    <div class="col-4 col-md-3">
        @if(is_null($parent_id))
        {!! VuexyAdmin::text('code', null, ['maxlength' => 50, 'minlength' => 2], trans('client.data.code')) !!}
            @else
        {!! VuexyAdmin::text('code', $parent['code'], ['readonly'], trans('client.data.code')) !!}
        @endif
    </div>
    <div class="col-8 col-md-9">
        @if(is_null($parent_id))
        {!! VuexyAdmin::text('name', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('client.data.name')) !!}
            @else
        {!! VuexyAdmin::text('name', $parent['name'], ['readonly'], trans('client.data.name')) !!}
        @endif
    </div>
    <div class="col-6">
        <div class="form-group">
            {!! VuexyAdmin::checkbox('is_location', 1, $item->is_location || !is_null($parent_id), ['data-is-location', 'disabled' => !is_null($parent_id)], trans('client.data.is_location')) !!}
            @if(!is_null($parent_id))
            {!! Form::hidden('is_location', $item->is_location || !is_null($parent_id)) !!}
            @endif
        </div>
    </div>
    @if($no_other_locations)
    <div class="col-6">
        <div class="form-group">
            {!! VuexyAdmin::checkbox('no_other_locations', 1, $item->is_location && $no_other_locations, ['data-no-other-locations', 'disabled' => !$item->is_location], trans('client.data.no_other_locations')) !!}
        </div>
    </div>
    @endif
</div>
<div class="row @if(!($item->is_location || !is_null($parent_id))){{ 'hidden' }}@else{{ '' }}@endif" data-location-content>
    <div class="col-4 col-md-3">
        {!! VuexyAdmin::text('location_code', null, ['maxlength' => 50, 'minlength' => 2], trans('client.data.location_code')) !!}
    </div>
    <div class="col-8 col-md-9">
        {!! VuexyAdmin::text('location_name', null, ['maxlength' => 100, 'required', 'minlength' => 2], trans('client.data.location_name')) !!}
    </div>
    <div class="col-12 col-md-6">
        {!! VuexyAdmin::selectTwo('location_type_id', $item->getLocationTypes(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-", "allowClear" : true}', 'id' => 'form-control-location_type_id-'.$item->getTable(), 'required'], trans('client.data.location_type_id')) !!}
    </div>
    <div class="col-12 col-md-6">
        {!! VuexyAdmin::selectTwo('category_id', $item->getCategories(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-", "allowClear" : true}', 'id' => 'form-control-category_id-'.$item->getTable(), 'required'], trans('client.data.category_id')) !!}
    </div>
</div>
<div class="row">
    <div class="col-12">
        {!! VuexyAdmin::file('photo', ['path' => config('picture.client_path').'/original/', 'required' =>($photo_required = ((($method == 'post') || (($method == 'put') && ($item->type_id == 'business_client'))) && is_null($item->photo)))], trans('client.data.photo'), trans('skeleton.allowed_extensions', ['ext' => 'JPG'])) !!}
        {!! Form::hidden('photo_required', $photo_required ? 'required' : 'nullable') !!}
    </div>
    <div class="col-12">
        {!! VuexyAdmin::file('photo_contract', ['path' => config('picture.client_path').'/original/', 'required' =>($photo_contract_required = ((($method == 'post') || (($method == 'put') && ($item->type_id == 'business_client'))) && is_null($item->photo_contract)))], trans('client.data.photo_contract'), trans('skeleton.allowed_extensions', ['ext' => 'JPG'])) !!}
        {!! Form::hidden('photo_contract_required', $photo_contract_required ? 'required' : 'nullable') !!}
    </div>	
    @if(userIsAdmin() || userIsEditor())
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::selectTwo('stock_id', $stocks, null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-stock_id-'.$item->getTable(), 'required'], trans('client.data.stock_id')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::selectTwo('lang_id', config('app.locales'), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-lang_id-'.$item->getTable(), 'required'], trans('client.data.lang_id')) !!}
    </div>
    <div class="col-12 col-md-4">
        {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable(), 'required'], trans('skeleton.data.status')) !!}
    </div>
    @endif
</div>
