<div class="row">
    <div class="col-12">
        {!! VuexyAdmin::locked('address', $item->address, $item->address, [], trans('client.data.address')) !!}
    </div>
    <div class="col-8 col-md-5">
        {!! VuexyAdmin::locked('city', $item->city, $item->city, [], trans('client.data.city')) !!}
    </div>
    <div class="col-4 col-md-2">
        {!! VuexyAdmin::locked('postal_code', $item->postal_code, $item->postal_code, [], trans('client.data.postal_code')) !!}
    </div>
    <div class="col-12 col-md-5">
        {!! VuexyAdmin::locked('country_id', $item->country_id, $item->getCountries($item->country_id), [], trans('client.data.country_id')) !!}
    </div>
    @if($item->latitude && $item->longitude)
    <div class="col-12 col-md-6">
        <div class="form-group">
            {!! VuexyAdmin::label('map', trans('client.data.map'), []) !!}
            <div class="input-group">
                <p class="form-control">{{ $item->latitude }}</p>
                <p class="form-control">{{ $item->longitude }}</p>
                <div class="input-group-append">
                    <a class="btn btn-primary" href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}" target="_blank" rel="noopener"><span class="feather icon-map"></span></a>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-6 col-md-6">
        {!! VuexyAdmin::locked('phone', $item->phone, '<a href="tel:'.$item->phone.'">'.$item->phone.'</a>', [], trans('client.data.phone')) !!}
    </div>
</div>
