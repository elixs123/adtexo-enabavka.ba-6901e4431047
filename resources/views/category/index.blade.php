@extends('layouts.app')

@section('head_title', $title = trans('category.title'))

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
                        @can('create-category')
                        <a class="dropdown-item" href="{{ route('category.create') }}" data-toggle="modal" data-target="#form-modal1">{{ trans('category.actions.create') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: content header -->
    <?php $filters = true; ?>
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
                            <div class="table-responsive-lg">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ trans('category.data.acClassif') }}</th>
                                            <th>{{ trans('category.data.name') }}</th>
                                            <th>{{ trans('skeleton.data.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @include('category._row')
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="no-results @if($items->count() == 0){{ 'show' }}@endif" data-no-results>
                                <h5>{{ trans('skeleton.no_results') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: items -->
            </div>
        </div>
    </div>
    <!-- end: content body -->
@endsection