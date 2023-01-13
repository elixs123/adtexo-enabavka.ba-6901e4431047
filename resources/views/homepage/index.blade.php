@extends('layouts.app')

@section('head_title', $title = 'Dashboard')

@section('content')
    <!-- start: content header -->
    <div class="content-header row">
        <div class="content-header-left col-6 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-right col-6">
            @if(can('create-document') || can('create-client') || can('create-person'))
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
                        @can('create-person')
                        <a class="dropdown-item" href="{{ route('person.create', ['callback' => 'documentReload']) }}" data-toggle="modal" data-target="#form-modal1">{{ trans('person.actions.new') }}</a>
                        @endcan
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <!-- end: content header -->
    <!-- start: content body -->
    <div class="content-body">
        @if(userIsSalesman())
        @include('homepage.person.routes')
        @endif
        @if(userIsSupervisor())
        @include('homepage.document.supervisor')
        @endif
        @if(userIsEditor())
        @include('homepage.document.editor')
        @endif
        @if(userIsWarehouse() && isset($user_documents))
        @include('homepage.document.warehouse')
        @endif
        @if(userIsClient() && isset($user_documents))
        @include('homepage.document.client', ['dates_data' => userIsSalesAgent() ? $dates_data : [], 'sales_documents' => userIsSalesAgent() ? $sales_documents : []])
        @endif
        @if(userIsAdmin())
        @include('homepage.document.admin')
        @endif
    </div>
    <!-- end: content body -->
@endsection

