@extends('layouts.app')

@section('head_title', $title = trans('client.title'))

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
                            @if(is_null($parent))
                            <li class="breadcrumb-item active">{{ $title }}</li>
                                @else
                            <li class="breadcrumb-item">
                                <a href="{{ route('client.index') }}">{{ $title }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $parent->name }}</li>
                            @endif
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
                        @can('create-client')
                        <a class="dropdown-item" href="{{ route('client.create', ['parent_id' => $parent_id]) }}" data-toggle="modal" data-target="#form-modal1">{{ is_null($parent_id) ? trans('client.actions.create') : trans('client.actions.create_location') }}</a>
                        @endcan
                        <a class="dropdown-item" href="javascript:" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ ($filters = (request('filters', 0) == 1) ? true : false) ? 'true' : 'false' }}">{{ trans('skeleton.actions.filters') }}</a>
                        @can('edit-client')
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'xls']) }}">{{ trans('skeleton.actions.export2xls') }}</a>
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
                                @if(!is_null(request('person_type')) && !is_null(request('person_id')))
                                <input type="hidden" name="person_type" value="{{ request('person_type') }}">
                                <input type="hidden" name="person_id" value="{{ request('person_id') }}">
                                @endif
                                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                                <input type="hidden" name="filters" value="{{ request('filters', 1) }}">
                                <div class="row">
                                    <div class="col-12 @if(userIsSalesman()){{ 'col-lg-9' }}@endif">
                                        {!! VuexyAdmin::text('keywords', request('keywords'), ['maxlength' => 100, 'placeholder' => trans('client.placeholders.search')], trans('skeleton.actions.search')) !!}
                                    </div>
                                    @if(userIsAdmin())
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('country_id', get_codebook_opts('countries')->pluck('name', 'code')->prepend('-', '')->toArray(), request('country_id'), ['data-plugin-options' => '', 'id' => 'filters-country'], trans('client.data.country_id')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('type_id', get_codebook_opts('client_types')->pluck('name', 'code')->prepend('-', '')->toArray(), request('type_id'), ['data-plugin-options' => '', 'id' => 'filters-client_types'], trans('client.data.type_id')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->prepend('-', '')->toArray(), request('status'), ['data-plugin-options' => '{}', 'id' => 'filters-status'], trans('skeleton.data.status')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('payment_type', get_codebook_opts('payment_type')->pluck('name', 'code')->prepend('-', '')->toArray(), request('payment_type'), ['data-plugin-options' => '{}', 'id' => 'filters-payment_type'], trans('client.data.payment_type')) !!}
                                    </div>
                                    @endif
                                    <div class="col-12 col-sm-4 col-lg-3">
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
                <!-- start: items -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ is_null($parent) ? $title : $parent->name }}@if(!is_null($person)){{ ' - '.$person->name }}@endif <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ trans('client.data.name') }}</th>
                                            <th>{{ trans('client.data.address') }}</th>
                                            <th>{{ trans('client.data.phone') }}</th>
                                            <th>{{ trans('client.data.type_id') }}</th>
                                            <th>{{ trans('skeleton.data.status') }}</th>
                                            <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="clients">
                                        @foreach($items as $id => $item)
                                        @include('client._row')
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
