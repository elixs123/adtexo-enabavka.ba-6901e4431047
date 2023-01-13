@extends('layouts.app')

@section('head_title', $title = 'Gratis lager')

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
                        <a class="dropdown-item" href="javascript:" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ ($filters = (request('filters', 0) == 1) ? true : false) ? 'true' : 'false' }}">{{ trans('skeleton.actions.filters') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'xls']) }}" data-export-xls>{{ trans('skeleton.actions.export2xls') }}</a>
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
                <!-- start: filters -->
                <div id="collapse-filters" class="filters collapse @if($filters){{ 'show' }}@endif">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ trans('skeleton.actions.filters') }}</h4>
                            <a href="javascript:" class="pull-right" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ $filters ? 'true' : 'false' }}"><i class="feather icon-x"></i></a>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                {!! Form::open(['url' => request()->url(), 'method' => 'get', 'files' => false, 'autocomplete' => 'off', 'class' => 'validate filters-form']) !!}
                                <input type="hidden" name="filters" value="{{ request('filters', 1) }}">
                                <div class="row">
                                    <div class="col-12 col-lg-3">
                                        {!! VuexyAdmin::selectTwo('stock', $stocks, request('stock'), ['data-plugin-options' => '', 'id' => 'filters-stock'], trans('document.data.stock_id')) !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('processed', ['' => 'All', '0' => 'Ne', '1' => 'Da'], $processed, ['data-plugin-options' => '', 'id' => 'filters-processed'], trans('document.data.processed_at')) !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::date('start_date', $start_date, [], 'Datum od') !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::date('end_date', $end_date, [], 'Datum do') !!}
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <div class="form-group">
                                            <button class="btn btn-info" type="submit"><i class="feather icon-search"></i> {{ trans('skeleton.actions.search') }}</button>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: filters -->

                @include('partials.alert_box')

                <!-- start: items -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            {!! Form::open(['url' => route('document.gratis.process'), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-xls').' table-responsive-lg']) !!}
                                <input type="hidden" name="export" value="xls">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 40px;">
                                                <div class="custom-control custom-checkbox checkbox-default">
                                                    <input id="form-control-documents" class="custom-control-input" type="checkbox" data-select-all>
                                                    <label for="form-control-documents" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </th>
                                            <th>{{ trans('document.data.document_id') }}</th>
                                            <th>{{ trans('document.data.date_of_order') }}</th>
                                            <th>{{ trans('product.data.barcode') }}</th>
                                            <th>{{ trans('product.data.name') }}</th>
                                            <th class="text-right">MPC</th>
                                            <th class="text-right">VPC</th>
                                            <th class="text-center">{{ trans('document.data.qty') }}</th>
                                            <th class="text-center">{{ trans('document.data.processed_at') }}</th>
                                         </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="documents">
                                        @foreach($items as $id => $item)
                                        <tr id="row{{ $item->uid }}">
                                            <td>
                                                @if($item->is_processed)
                                                <span></span>
                                                @else
                                                <div class="custom-control custom-checkbox checkbox-default">
                                                    <input id="form-control-documents-{{ $item->id }}" class="custom-control-input" name="p[]" type="checkbox" value="{{ $item->id }}" data-document-xls>
                                                    <label for="form-control-documents-{{ $item->id }}" class="custom-control-label">&nbsp;</label>
                                                </div>
                                                @endif
                                            </td>
                                            <td><a href="{{ route('document.show', ['id' => $item->document_id]) }}">{{ $item->document_id }}</a></td>
                                            <td>{{ $item->rDocument->date_of_order->format('d.m.Y') }}</td>
                                            <td>{{ $item->barcode }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-right">{{ format_price($item->mpc, 2) }} {{ $item->rDocument->currency }}</td>
                                            <td class="text-right">{{ format_price($item->vpc, 2) }} {{ $item->rDocument->currency }}</td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-center">{{ is_null($item->processed_at) ? '-' : $item->processed_at->format('d.m.Y \u H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            {!! Form::close() !!}
                            <div class="no-results @if($items->total() == 0){{ 'show' }}@endif" data-no-results>
                                <h5>{{ trans('skeleton.no_results') }}</h5>
                            </div>
                        </div>
                    </div>
                    @if($items->total())
                    <div class="card-footer">
                        {!! $items->appends(request()->query())->render() !!}
                    </div>
                    @endif
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
        var $form = $('form.ajax-form-document-xls'), $select = $('#filters-processed');
        $('a[data-export-xls]').click(function (e) {
            e.preventDefault();
            if ($select.val()) {
                loader_on();
                HttpRequest.post($form.attr('action'), $form.serializeArray(), function (response) {
                    loader_off();
                    $('input[data-document-xls]:checked').parents('tr').remove();
                    document.location = response.redirect;
                })
            } else {
                document.location = '{!! route('document.gratis', ['export' => 'xls', 'processed' => '', 'p' => 'all', 'start_date' => $start_date, 'end_date' => $end_date, 'stock' => request('stock')]) !!}';
            }
        });
        $('input[data-select-all]').change(function() {
            $('input[data-document-xls]').prop('checked', $(this).is(':checked')).trigger('change');
        });
    });
</script>
@endsection
