@extends('layouts.app')

@section('head_title', $title = trans('product.title'))

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
                        @can('create-product')
                        <a class="dropdown-item" href="{{ route('product.create') }}" data-toggle="modal" data-target="#form-modal1">{{ trans('product.actions.create') }}</a>
                    <a class="dropdown-item" href="{{ route('product_stock.mass_create') }}" data-toggle="modal" data-target="#form-modal1">{{ trans('product.actions.create_supplies') }}</a>
                        @endcan
                        <a class="dropdown-item" href="javascript:" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ ($filters = (request('filters', 0) == 1) ? true : false) ? 'true' : 'false' }}">{{ trans('skeleton.actions.filters') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">{{ trans('skeleton.actions.export2pdf') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'xls']) }}">{{ trans('skeleton.actions.export2xls') }}</a>
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
                                    <div class="col-12">
                                        {!! VuexyAdmin::text('keywords', request('keywords'), ['maxlength' => 100, 'placeholder' => 'Upišite naziv'], trans('skeleton.actions.search')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('lang_id', config('app.locales'), request('lang_id'), ['data-plugin-options' => '', 'id' => 'filters-lang_id'], trans('skeleton.lang')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('category_id', $categories->pluck('name_length', 'id')->prepend('All', '')->toArray(), request('category_id'), ['data-plugin-options' => '', 'id' => 'filters-category_id'], trans('product.data.category')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('brand_id', $brands->pluck('name', 'id')->prepend('All', '')->toArray(), request('brand_id'), ['data-plugin-options' => '', 'id' => 'filters-brand_id'], trans('product.data.brand')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('stock_id', $stocks->pluck('name', 'id')->prepend('All', '')->toArray(), request('stock_id'), ['data-plugin-options' => '', 'id' => 'filters-stock_id'], trans('product.data.stock')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
                                        {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->prepend('All', '')->toArray(), request('status'), ['data-plugin-options' => '', 'id' => 'filters-status'], trans('skeleton.data.status')) !!}
                                    </div>
                                    <div class="col-12 col-sm-3 col-lg-2">
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
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                    <table class="table table-hover data-thumb-view">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 60px">{{ trans('product.data.photo') }}</th>
                                                <th style="width: 50px">{{ trans('product.data.code') }}</th>
                                                <th>{{ trans('product.data.name') }}</th>
                                                <th style="width: 150px">{{ trans('product.data.category') }}</th>
                                                <th style="width: 50px">{{ trans('skeleton.data.status') }}</th>
												 <th style="width: 50px">{{ trans('product.data.qty') }}</th>
                                                <th style="width: 100px" class="text-right">{{ trans('skeleton.data.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody data-ajax-form-body="products">
                                        @foreach($items as $id => $item)
                                        @include('product._row', ['source' => 'products'])
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
