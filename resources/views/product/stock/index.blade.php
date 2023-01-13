@extends('layouts.app')

@section('head_title', $title = trans('product.data.quantities'))

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
            @can('create-product')
            <div class="form-group breadcrum-right">
                <div class="dropdown">
                    <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                        <a class="dropdown-item" href="{{ route('product-stock.create') }}?product_id={{ $product->id }}" data-toggle="modal" data-target="#form-modal1">{{ trans('product.data.add_new_qty') }}</a>
                    </div>
                </div>
            </div>
            @endcan
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
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ trans('product.data.date') }}</th>
                                            <th>{{ trans('product.data.stock') }}</th>
                                            <th>{{ trans('product.data.qty') }}</th>
                                            @if(can('edit-product'))
                                            <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="product_stocks">
                                        @foreach($items as $id => $item)
                                        @include('product.stock._row', ['modal' => '#form-modal1'])
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
