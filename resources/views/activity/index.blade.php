@extends('layouts.app')

@section('head_title', 'Aktivnosti')

@section('content')
<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper">		
    <!-- START PAGE CONTENT -->
    <div class="content">
        <div class="container-fluid container-fixed-lg">	
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">Naslovnica</a></li>
                <li><a href="{{ url('/activity') }}" class="active">Aktivnosti</a></li>
            </ul>	
        </div>		
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid container-fixed-lg bg-white">	
            <!-- START PANEL -->
            <div class="panel panel-transparent">
                <div class="panel-heading">
                    <div class="panel-title">Aktivnosti <span class="badge badge-info" data-row-count>{{ $items->total() }}</span></div>
                    <div class="btn-group panel-btn-group">
                        <button class="btn btn-primary btn-filters" type="button"><i class="fa fa-filter"></i><span> Filteri</span></button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
    
                    {!! Form::open(array('url' => request()->url(), 'method' => 'get', 'files' => false, 'class' => 'validate filters-form'.((request('filters', 0) == 1) ? ' open' : ''), 'autocomplete' => 'off', 'role' => 'form')) !!}
                    <input type="hidden" name="filters" value="{{ request('filters', 0) }}">
                    <div class="filter-items">
                    <div class="row">
                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group form-group-default form-group-default-select2">
                                <label>Korisnik</label>
                                {!! Form::select('user_id', dropdown($users, 'email'), request('lawyer_id'), ['data-placeholder' => 'Odaberi korisnika','class' => 'full-width', 'data-init-plugin' => 'select2', 'data-allow-clear' => 'true']); !!}
                            </div>				
                        </div>	
                        <div class="col-xs-6 col-sm-2">
                            <div class="form-group form-group-default">
                                <label>Datum (početak)</label>
                                <input type="text" value="{{ request('date_start', date('Y-m-d', strtotime('-30 days'))) }}" name="date_start" class="form-control date datepicker-component" data-date-format="yyyy-mm-dd" required placeholder="Odaberite startni datum">
                            </div>				
                        </div>
                        <div class="col-xs-6 col-sm-2">
                            <div class="form-group form-group-default">
                                <label>Datum (kraj)</label>
                                <input type="text" value="{{ request('date_end', date('Y-m-d')) }}" name="date_end" class="form-control date datepicker-component" data-date-format="yyyy-mm-dd" required placeholder="Odaberite krajnji datum">
                            </div>				
                        </div>   
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <button class="btn btn-default" type="submit"><i class="pg-settings_small"></i> Generiši</button>
                            </div>
                        </div>   						
                    </div>
                    </div>

                    {!! Form::close() !!}					

						<div class="container-fluid sm-p-l-5 bg-master-lighter ">
						<div class="timeline-container top-circle">
						<section class="timeline">

						@foreach($items as $id => $item)
						@include('activity._row')
						@endforeach                             
						</section>

						</div>

						</div>

                    </div>
                    {!! $items->render() !!}
                </div>
            </div>
            <!-- END PANEL -->
        </div>
        <!-- END CONTAINER FLUID -->

    </div>
    <!-- END PAGE CONTENT -->
    @endsection

    @section('script')
    <link href="{{ asset('assets/admin/assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css" media="screen">
    <script src="{{ asset('assets/admin/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/assets/plugins/moment/moment.min.js') }}"></script>
    @endsection	
