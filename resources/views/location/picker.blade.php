<div class="modal-body">
    <p><strong class="text-uppercase">{{ trans('skeleton.location_picker') }}</strong></p>
    <hr>
    <div class="row">
        <div class="col-12">
            {!! VuexyAdmin::text('picker[location]', null, ['id' => 'us3-address'], trans('client.vars.location.address')) !!}
        </div>
        <div class="col-4">
            {!! VuexyAdmin::number('picker[radius]', 300, ['id' => 'us3-radius'], trans('client.vars.location.radius')) !!}
        </div>
        <div class="col-4">
            {!! VuexyAdmin::text('picker[latitude]', $latitude, ['id' => 'us3-lat', 'maxlength' => 12, 'readonly'], trans('client.vars.location.latitude')) !!}
        </div>
        <div class="col-4">
            {!! VuexyAdmin::text('picker[longitude]', $longitude, ['id' => 'us3-lon', 'maxlength' => 12, 'readonly'], trans('client.vars.location.longitude')) !!}
        </div>
        <div class="col-12">
            <div class="form-group">
                <div id="us3" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.close') }}</button>
</div>

<script src="{{ asset('assets/vendor/location-picker/dist/locationpicker.jquery.min.js') }}"></script>
<script>
    var googleMapsScriptIsInjected = googleMapsScriptIsInjected || false;
    
    function injectGoogleMapsApiScript() {
        if (googleMapsScriptIsInjected) {
            initLocationPicker();
            
            return;
        }
    
        const options = [
            'libraries=places',
            'sensor=false',
            'callback=initLocationPicker',
            'key=AIzaSyA45xBdLoX_V-jFGs8JAol6c564YnlnsfI'
        ];
    
        const url = `https://maps.googleapis.com/maps/api/js?` + options.join('&');
    
        const script = document.createElement('script');
    
        script.setAttribute('src', url);
        script.setAttribute('async', '');
        script.setAttribute('defer', '');
    
        document.head.appendChild(script);
        
        googleMapsScriptIsInjected = true;
    }

    injectGoogleMapsApiScript();
    
    function initLocationPicker() {
        $('#us3').locationpicker({
            location: {
                latitude: {{ $latitude }},
                longitude: {{ $longitude }}
            },
            radius: 300,
            inputBinding: {
                latitudeInput: $('#us3-lat'),
                longitudeInput: $('#us3-lon'),
                radiusInput: $('#us3-radius'),
                locationNameInput: $('#us3-address')
            },
            onchanged: function (currentLocation, radius, isMarkerDropped) {
                var callback = '{{ $callback }}';
                $.isFunction(window[callback])&&window[callback].call(this, currentLocation);
            },
            enableAutocomplete: true,
            markerIcon: '/assets/img/pointer.png'
        });
    }
</script>