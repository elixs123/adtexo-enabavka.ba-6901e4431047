@extends('layouts.app')

@section('head_title', $title = trans('log.title'))

<?php $filters = true; ?>

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
                                    <div class="col-12 col-lg-8">
                                        {!! VuexyAdmin::text('keywords', null, ['maxlength' => 100], trans('skeleton.actions.search')) !!}
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
                        <h4 class="card-title">{{ userIsClient() ? trans('log.title_client') : $title }} <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            {!! Form::open(['url' => route('log.index'), 'method' => 'get', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-log-xml').' table-responsive-lg']) !!}
                                <input type="hidden" name="export" value="xml">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ trans('log.data.date') }}</th>										
                                            <th>Loggable id</th>
                                            <th>Loggable type</th>
                                            <th>Body</th>
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="logs">
                                        @foreach($items as $id => $item)
                                        @include('log._row')
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

