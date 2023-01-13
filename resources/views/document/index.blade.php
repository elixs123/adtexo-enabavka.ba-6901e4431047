@extends('layouts.app')

@section('head_title', $title = trans('document.title'))

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
                            <li class="breadcrumb-item active">{{ userIsClient() ? trans('document.title_client') : $title }}</li>
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
                        @if(can('create-document') && !ScopedDocument::exist())
                            @if(userIsClient())
                        <a class="dropdown-item" href="{{ route('document.create', ['type_id' => 'order']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.new.order') }}</a>
                            @else
                                @foreach(trans('document.actions.create') as $key => $value)
                        <a class="dropdown-item" href="{{ route('document.create', ['type_id' => $key]) }}" data-toggle="modal" data-target="#form-modal1">{{ $value }}</a>
                                @endforeach
                            @endif
                        @endif
                        <a class="dropdown-item" href="javascript:" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ ($filters = (request('filters', 0) == 1) ? true : false) ? 'true' : 'false' }}">{{ trans('skeleton.actions.filters') }}</a>
                        @if(!userIsClient())
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">{{ trans('skeleton.actions.export2pdf') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'xls']) }}">{{ trans('skeleton.actions.export2xls') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'xml']) }}" data-export-xml>{{ trans('skeleton.actions.export2xml') }}</a>
                        @endif
                        @if(userIsAdmin())
                        <a class="dropdown-item" href="{{ route('document.gratis') }}">Gratis Lager</a>
                        @endif
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
                                    <div class="col-12 col-lg-6">
                                        {!! VuexyAdmin::text('keywords', null, ['maxlength' => 100, 'placeholder' => 'Shipping details ...'], trans('skeleton.actions.search')) !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::text('sid', request('sid'), ['maxlength' => 6, 'placeholder' => 'Document id'], trans('skeleton.actions.search')) !!}
                                    </div>
                                    @if(!userIsClient())
                                    <div class="col-12 col-lg-4">
                                        {!! VuexyAdmin::selectTwoAjax('client_id', $clients, null, ['data-plugin-options' => '{"placeholder": "'.trans('route.placeholders.client').'", "ajax": {"url": "'.route('client.search').'", "type": "get"}}', 'id' => 'form-control-client-id'], trans('document.data.client_id')) !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::selectTwoAjax('created_by', $persons, null, ['data-plugin-options' => '{"placeholder": "'.trans('client.data.salesman_person_id').'", "ajax": {"url": "'.route('person.search', ['t' => 'salesman_person', 'u' => 1]).'", "type": "get"}}', 'id' => 'form-control-created-by'], trans('client.data.salesman_person_id')) !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('type_id', get_codebook_opts('document_type')->pluck('name', 'code')->prepend('All', '')->toArray(), request('type_id'), ['data-plugin-options' => '', 'id' => 'filters-type_id'], trans('document.data.type_id')) !!}
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('sync_status', get_codebook_opts('sync_status')->pluck('name', 'code')->prepend('All', '')->toArray(), request('sync_status'), ['data-plugin-options' => '', 'id' => 'filters-sync_status'], trans('document.data.sync_status')) !!}
                                    </div>
                                    @endif
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('status', get_codebook_opts('document_status')->pluck('name', 'code')->prepend('All', '')->toArray(), request('status'), ['data-plugin-options' => '', 'id' => 'filters-status'], trans('document.data.status')) !!}
                                    </div>
                                    @if(userIsAdmin() || userIsEditor())
                                    <div class="col-12 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('payment', ['' => 'All', '0' => 'Neplaćeni', '1' => 'Plaćeni'], request('payment'), ['data-plugin-options' => '', 'id' => 'filters-payment'], 'Naplata') !!}
                                    </div>
                                    @endif
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
                        <h4 class="card-title">{{ userIsClient() ? trans('document.title_client') : $title }} <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            {!! Form::open(['url' => route('document.index'), 'method' => 'get', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-xml').' table-responsive-lg']) !!}
                                <input type="hidden" name="export" value="xml">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            @if(!userIsClient())
                                            <th style="width: 40px;">
                                                <div class="custom-control custom-checkbox checkbox-default">
                                                    <input id="form-control-documents" class="custom-control-input" type="checkbox" data-select-all>
                                                    <label for="form-control-documents" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </th>
                                            @endif
                                            <th>{{ trans('document.data.id') }}</th>
                                            <th>{{ trans('document.data.date') }}</th>
                                            <th>{{ trans('document.data.client_id') }}</th>
                                            <th>{{ trans('document.data.type_id') }}</th>
                                            <th>{{ trans('document.data.subtotal') }}</th>
                                            @if(userIsClient())
                                            <th>{{ trans('document.data.loyalty') }}</th>
                                            @endif
                                            <th>{{ trans('document.data.status') }}</th>
                                            @if(userIsAdmin() || userIsClient() || userIsSalesman())
                                            <th class="text-center" style="width: 90px;">Track</th>
                                            @endif
                                            <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="documents">
                                        @foreach($items as $id => $item)
                                        @include('document._row')
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
        select2ajax($('#form-control-client-id'));
        $('a[data-export-xml]').click(function (e) {
            e.preventDefault();
            $('form.ajax-form-document-xml').submit();
        });
        $('input[data-select-all]').change(function() {
            $('input[data-document-xml]').prop('checked', $(this).is(':checked')).trigger('change');
        });
    
        $('body').on('click', '[data-document-reverse]', function (e) {
            // Prevent default
            e.preventDefault();
            // Confirm
            var confirmed = confirm($(this).data('text'));
            if (confirmed) {
                // Loader: On
                loader_on();
                // Request
                HttpRequest.post($(this).attr('href'), {}, function (response) {
                    // Request
                    HttpRequest.post(response.url, {}, function (response) {
                        documentReload();
                    });
                });
            }
        });
        
        $('body').on('click', '[data-document-cancel]', function (e) {
            // Prevent default
            e.preventDefault();
            // Confirm
            var confirmed = confirm($(this).data('text'));
            if (confirmed) {
                // Loader: On
                loader_on();
                // Request
                HttpRequest.post($(this).attr('href'), {
                    s: 'canceled',
                    t: 'order',
                    d: [$(this).data('id')]
                }, function (response) {
                    documentReload();
                });
            }
        });
    });
</script>
@endsection
