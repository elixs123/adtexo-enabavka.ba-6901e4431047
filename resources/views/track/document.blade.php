@extends('layouts.public')

@section('head_title', $document->full_name)

@section('content')
    <!-- start: content body -->
    <div class="content-body">
        <!-- start: document -->
        <section id="document-print" class="card invoice-page">
            <div class="card-body">
                @include('document.show.company_header', ['document_name' => $document->rType->name, 'show_logo' => false])
                <!-- start: client details -->
                <div class="row pt-4">
                    <div class="col-sm-6 col-12">
                        @if($document->delivery_type == 'personal_takeover')
                            <h5>Preuzeto</h5>
                            @if(!is_null($takeover = $document->rTakeover))
                            <div class="recipient-info">
                                <p><strong>{{ $takeover->name }}</strong></p>
                                <p>Datum: {{ $takeover->picked_at->format('d.m.Y. H:i') }}</p>
                            </div>
                            @endif
                        @else
                            <h5>{{ trans('document.data.delivery') }}</h5>
                            @if(!is_null($document->shipping_data))
                            <div class="recipient-info">@php $is_location = array_get($document->buyer_data, 'is_location', false); $code = $is_location ? array_get($document->buyer_data, 'location_code', '-') : array_get($document->buyer_data, 'code', '-');  @endphp
                                <p><strong>{{ $code }}</strong>, {!! array_get($document->shipping_data, 'name', '&nbsp;') !!}</p>
                                <p>{!! array_get($document->shipping_data, 'address', '&nbsp;') !!}</p>
                                <p>{!! array_get($document->shipping_data, 'postal_code', '&nbsp;') !!} {!! array_get($document->shipping_data, 'city', '&nbsp;') !!}, <span class="text-uppercase">{!! array_get($document->shipping_data, 'country', '&nbsp;') !!}</span></p>
                                @if(!is_null($phone = array_get($document->shipping_data, 'phone')))
                                <p>Kontakt: <a href="tel:{{ $phone }}">{{ $phone }}</a></p>
                            </div>
                                @endif
                            @endif
                        @endif
                    </div>
                    <div class="col-sm-6 col-12">
                        @if(!is_null($express_post = $document->rExpressPost))
                        <h5>Brza pošta: <strong>{{ $express_post->express_post_name }}</strong></h5>
                        <div class="recipient-info">
                            <p>ID pošiljke: #{{ $express_post->shipment_id }}</p>
                            <p>Tracking number: {{ $express_post->tracking_number }}</p>
                            <p>Preuzeto: {{ is_null($express_post->picked_at) ? '' : $express_post->picked_at->format('d.m.Y. H:i') }}</p>
                            <p>Dostavljeno: {{ is_null($express_post->delivered_at) ? '' : $express_post->delivered_at->format('d.m.Y. H:i') }}</p>
                            <p>Iznos: {{ format_price($document->fiscal_discounted_price + $document->fiscal_delivery_gross_price, 2) }} {{ $document->currency }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- end: client details -->
                <!-- start: document items -->
                @if(!is_null($document->rExpressPost) && !is_null($document->rExpressPost->traces))
                <div class="pt-1 invoice-items-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-2 table-bordered">
                            <thead class="thead-light">
                                <tr class="text-uppercase">
                                    @if(userIsAdmin())
                                    <th style="width: 150px;">Datum</th>
                                    <th style="width: 150px;">Lokacija</th>
                                    @endif
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($document->rExpressPost->traces['traces'] as $trace)
                                <tr>
                                    @if(userIsAdmin())
                                    <td>{{ $trace['datetime'] }}</td>
                                    <td>{{ is_array($trace['center']) ? implode(' ', $trace['center']) : $trace['center'] }}</td>
                                    @endif
                                    <td>{{ $trace['status_label'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="pt-1">
                    <p class="text-center"><a href="javascript:" title="Uslovi kupovine" target="_blank">Uslovi kupovine</a> | <a href="javascript:" title="Politika privatnosti" target="_blank">Politika privatnosti</a></p>
                </div>
                <!-- end: document items -->
{{--                @include('document.show.company_footer')--}}
            </div>
        </section>
        <!-- end: document -->
    </div>
    <!-- end: content body -->
@endsection
