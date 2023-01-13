@extends('layouts.app')

@section('head_title', $title = trans('skeleton.invoicing'))

@section('content')
<!-- start: content header -->
<div class="content-header row">
    <div class="content-header-left col-6 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">{{ trans('skeleton.invoicing') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header-right text-right col-6">
        <div class="form-group breadcrum-right">
            @if(can('create-document') && !ScopedDocument::exist() && userIsClient())
            <a class="btn btn-primary" href="{{ route('document.create', ['type_id' => 'order']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('document.actions.new.order') }}</a>
            @else
            <div class="dropdown">
                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-settings"></i></button>
                <div class="dropdown-menu dropdown-menu-right p-0">
                    @if(can('create-document') && !ScopedDocument::exist())
                    @foreach(trans('document.actions.new') as $key => $value)
                    <a class="dropdown-item" href="{{ route('document.create', ['type_id' => $key, 'callback' => 'documentRedirect']) }}" data-toggle="modal" data-target="#form-modal1">{{ $value }}</a>
                    @endforeach
                    @endif
                    @can('create-client')
                    <a class="dropdown-item" href="{{ route('client.create', ['callback' => 'documentReload']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('client.actions.new') }}</a>
                    @endcan
                    @can('edit-person')
                    <a class="dropdown-item" href="{{ route('person.create', ['callback' => 'documentReload']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('person.actions.new') }}</a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- end: content header -->
<!-- start: content body -->
<div class="content-body">
    @if(userIsWarehouse() && isset($user_documents))
    @include('invoice.document.warehouse')
    @endif
    @if(userIsAdmin())
    @include('invoice.document.admin')
    @endif
</div>
<!-- end: content body -->
@endsection