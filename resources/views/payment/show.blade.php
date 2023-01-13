@extends('layouts.app')

@section('head_title', $title = trans('payment.title'))

@section('content')
    <!-- start: content header -->
    <div class="content-header row">
        <div class="content-header-left col-9 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ trans('skeleton.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-right col-3">
            <div class="form-group breadcrum-right">
                <div class="dropdown">
                    <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        @if(false)
                        <a class="dropdown-item" href="{{ route('payment.create', ['type_id' => request('type_id')]) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('payment.actions.create') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: content header -->
    <!-- start: content body -->
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <!-- start: items -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary" data-row-count>{{ $items->count() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @include('partials.alert_box')
                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    <p class="mb-0"><span class="text-muted">Kreiran:</span> <strong>{{ $payment->created_at->format('d.m.Y \u H:i') }}</strong></p>
                                    <p><span class="text-muted">Izmjenjen:</span> <strong>{{ $payment->updated_at->format('d.m.Y \u H:i') }}</strong></p>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <p class="mb-0"><span class="text-muted">Uploadovan:</span> <strong>{{ $payment->uploaded_at->format('d.m.Y \u H:i') }}</strong></p>
                                    <p><span class="text-muted">Korisnik:</span> <strong>{{ $payment->rUploadedBy->email }}</strong></p>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <p class="mb-0"><span class="text-muted">Odobren:</span> <strong>{{ is_null($payment->confirmed_at) ? '-' : $payment->confirmed_at->format('d.m.Y \u H:i') }}</strong></p>
                                    <p><span class="text-muted">Korisnik:</span> <strong>{{ is_null($payment->rConfirmedBy) ? '-' : $payment->rUploadedBy->email }}</strong></p>
                                </div>
                            </div>
                            <div class="table-responsive-lg">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ trans('payment.data.config.document_id') }}</th>
                                            <th class="text-right">{{ trans('payment.data.config.amount') }}</th>
                                            <th class="text-right">Dokument</th>
                                            <th style="width: 30px;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>@php $total_payments = 0; $total_documents = 0; @endphp
                                        @foreach($items as $id => $item)
                                        <tr>@php $document_amount = $documents[$item->document_id] ?? 0 @endphp
                                            <td><a href="{{ route('document.show', ['id' => $item->document_id]) }}" target="_blank">{{ $item->document_id }}</a></td>
                                            <td class="text-right">{{ format_price($item->amount, 2) }} {{ $payment->currency }}</td>@php $total_payments += $item->amount; @endphp
                                            <td class="text-right">{{ isset($documents[$item->document_id]) ? format_price($documents[$item->document_id], 2) : '-' }} {{ $payment->currency }}</td>@php $total_documents += $documents[$item->document_id] ?? 0; @endphp
                                            <td class="{{ ($item->amount - $document_amount) == 0 ? 'bg-success' : 'bg-danger' }}">&nbsp;</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="thead-light">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th class="text-right" title="{{ $total_payments }}">{{ format_price($payment->total_payments, 2) }} {{ $payment->currency }}</th>
                                            <th class="text-right" title="{{ $total_documents }}">{{ format_price($payment->total_documents, 2) }} {{ $payment->currency }}</th>
                                            <th class="{{ ($payment->total_payments - $payment->total_documents) == 0 ? 'bg-success' : 'bg-danger' }}">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="no-results @if($items->count() == 0){{ 'show' }}@endif" data-no-results>
                                <h5>{{ trans('skeleton.no_results') }}</h5>
                            </div>
                            @if($payment->status == 'not_confirmed')
                            {!! Form::open(['url' => route('payment.confirm', ['id' => $payment->id]), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'js-form-payment-confirm')]) !!}
                                {!! Form::hidden('total_payments', $payment->total_payments) !!}
                                {!! Form::hidden('total_documents', $payment->total_documents) !!}
                                <div class="">
                                    <button class="btn btn-success btn-block" type="submit">{{ trans('payment.actions.confirm') }}</button>
                                </div>
                            {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- end: items -->
            </div>
        </div>
    </div>
    <!-- end: content body -->
@endsection

@section('script_inline')
<script>
    $(document).ready(function () {
        $('form.js-form-payment-confirm').submit(function () {
            loader_on();
        });
    });
</script>
@endsection
