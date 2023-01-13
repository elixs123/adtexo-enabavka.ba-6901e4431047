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
                        @can('create-payment')
                        <a class="dropdown-item" href="{{ route('payment.create', ['type_id' => request('type_id')]) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('payment.actions.create') }}</a>
                        @endcan
                        <a class="dropdown-item" href="javascript:" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ ($filters = (request('filters', 0) == 1) ? true : false) ? 'true' : 'false' }}">{{ trans('skeleton.actions.filters') }}</a>
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
                                    <div class="col-12 col-sm-4 col-lg-3">
                                        {!! VuexyAdmin::selectTwo('status', get_codebook_opts('payment_status')->pluck('name', 'code')->prepend('-', '')->toArray(), request('status'), ['data-plugin-options' => '', 'id' => 'filters-status'], trans('payment.data.status')) !!}
                                    </div>
                                    <div class="col-12 col-sm-4 col-lg-3">
                                        {!! VuexyAdmin::selectTwo('type', ['' => '-'] + trans('payment.vars.type'), request('type'), ['data-plugin-options' => '{"placeholder" : "-", "allowClear" : true}', 'id' => 'filters-type'], trans('payment.data.type')) !!}
                                    </div>
                                    <div class="col-12 col-sm-4 col-lg-3">
                                        {!! VuexyAdmin::selectTwo('service', ['' => '-'] + trans('payment.vars.services'), request('service'), ['data-plugin-options' => '{"placeholder" : "-", "allowClear" : true}', 'id' => 'filters-service'], trans('payment.data.service')) !!}
                                    </div>
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
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary" data-row-count>{{ $items->total() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ trans('payment.data.date') }}</th>
                                            <th>{{ trans('payment.data.uploaded_at') }}</th>
                                            <th>{{ trans('payment.data.confirmed_at') }}</th>
                                            <th>{{ trans('payment.data.type') }} / {{ trans('payment.data.service') }}</th>
                                            <th class="text-right">{{ trans('payment.data.total_payments') }}</th>
                                            <th class="text-right">{{ trans('payment.data.total_documents') }}</th>
                                            <th>{{ trans('skeleton.data.status') }}</th>
                                            <th style="width: 30px;">&nbsp;</th>
                                            @if(can('edit-payment'))
                                            <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="payments">
                                        @foreach($items as $id => $item)
                                        @include('payment._row')
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

@section('script_inline')
<script>

</script>
@endsection
