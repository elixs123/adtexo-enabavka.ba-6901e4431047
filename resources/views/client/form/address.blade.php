<div class="row">
    <div class="col-12">
        {!! VuexyAdmin::text('address', null, ['maxlength' => 100, 'required'], trans('client.data.address')) !!}
    </div>
    <div class="col-8 col-md-5">
        {!! VuexyAdmin::selectTwoAjax('city', [$item->city => $item->full_city], [$item->city => $item->full_city], ['required', 'data-plugin-options' => '{"placeholder": "'.trans('client.data.city').'", "ajax": {"url": "'.route('city.search').'", "type": "get"}}', 'id' => 'form-control-city'], trans('client.data.city')) !!}
    </div>
    <div class="col-4 col-md-2">
        {!! VuexyAdmin::text('postal_code', null, ['maxlength' => 20, 'required', 'readonly'], trans('client.data.postal_code')) !!}
    </div>
    <div class="col-12 col-md-5">
        {!! VuexyAdmin::selectTwo('country_id', $item->getCountries(), 'bih', ['data-plugin-options' => '{"minimumResultsForSearch": -1, "placeholder" : "-"}', 'id' => 'form-control-country_id-'.$item->getTable(), 'required'], trans('client.data.country_id')) !!}
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            {!! VuexyAdmin::label('map', trans('client.data.map'), []) !!}
            <div class="input-group">
                {!! Form::text('latitude', null, ['class' => 'form-control', 'placeholder' => 'Latitude', 'readonly']) !!}
                {!! Form::text('longitude', null, ['class' => 'form-control', 'placeholder' => 'Longitude', 'readonly']) !!}
                <div class="input-group-append">
                    <a class="btn btn-primary" href="{{ route('location.picker', ['lat' => $item->latitude, 'lon' => $item->longitude, 'callback' => 'setClientMapLocation']) }}" data-toggle="modal" data-target="#form-modal2"><span class="feather icon-map"></span></a>
                    <button class="btn btn-secondary" data-map-location-reset><span class="fa fa-remove"></span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        {!! VuexyAdmin::text('phone', null, ['maxlength' => 30, 'required'], trans('client.data.phone')) !!}
    </div>
</div>
