@extends('layouts.public')

@section('head_title', $client->name)

@section('content')
    <div class="row">
        <div class="col-12 col-lg-6">
            <ul class="nav nav-tabs" role="tablist">
                @foreach($tabs as $tab_key => $tab)
                <li class="nav-item">
                    <a class="nav-link{{ ($tab_key == $status) ? ' active' : '' }}" href="{{ $client_public_url }}?status={{ $tab_key }}" data-loader>{{ $tab }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="col-12">
            <div class="card">@php $doc_type = get_codebook_opts('document_type')->where('code', 'order')->first(); @endphp
                <div class="card-header pb-1 border-bottom">@php $doc_status = get_codebook_opts('document_status')->where('code', $status)->first(); @endphp
                    <h4 class="card-title"><span class="badge" style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span> <span class="badge" style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span></h4>
                </div>
                <div class="card-content">
                    @if($documents->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('document.data.date_of_order') }}</th>
                                    <th>{{ trans('document.data.client_id') }}</th>
                                    <th class="text-center">{{ trans('document.data.payment_type') }}</th>
                                    <th class="text-right">{{ trans('document.data.subtotal') }}</th>
                                    <th class="text-center">{{ trans('document.data.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->date_of_order->format('d.m.Y.') }}</td>
                                    <td><a href="{{ $document->public_url }}" title="Pogledaj dokument" data-toggle="tooltip">{{ $client->name }}</a></td>
                                    <td class="text-center"><small class="badge" style="background-color: {{ $document->rPaymentType->background_color }};color: {{ $document->rPaymentType->color }};">{{ $document->rPaymentType->name }}</small></td>
                                    <td class="text-right"><strong>{{ format_price($document->fiscal_discounted_price + $document->fiscal_delivery_gross_price, 2) }}</strong> {{ $document->currency }}</td>
                                    <td class="text-center"><span class="badge" style="background-color: {{ $document->rStatus->background_color }};color: {{ $document->rStatus->color }};">{{ $document->rStatus->name }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
