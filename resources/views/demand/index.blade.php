@extends('layouts.app')

@section('head_title', $title = 'Pregled dugovanja po kupcu')

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
        
        </div>
    </div>
    <!-- end: content header -->
    <!-- start: content body -->
    <div class="content-body">
        {!! Form::open(['url' => route('demand.index'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'row form-dates-range']) !!}
            <div class="col-12">
                @include('homepage._countries', ['route' => 'demand.index'])
            </div>
        {!! Form::close() !!}
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#billing-tab1" data-toggle="tab" aria-selected="true">Po kategoriji</a>
                    </li>
                    @if(isset($demands_per_fund_source['fund_wire_transfer']))
                    <li class="nav-item">
                        <a class="nav-link" href="#billing-tab2" data-toggle="tab" aria-selected="false">Tekući račun</a>
                    </li>
                    @endif
                    @if(isset($demands_per_fund_source['fund_compensation']))
                    <li class="nav-item">
                        <a class="nav-link" href="#billing-tab3" data-toggle="tab" aria-selected="false">Kompenzacije</a>
                    </li>
                    @endif
                    @if(false)
                    <li class="nav-item">
                        <a class="nav-link" href="#billing-tab4" data-toggle="tab" aria-selected="false">Konsignacije</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="billing-tab1" role="tabpanel">
                        @include('demand.tabs.tab1')
                    </div>
                    @if(isset($demands_by_fund_source['fund_wire_transfer']))
                    <div class="tab-pane" id="billing-tab2" role="tabpanel">
                        @include('demand.tabs.tab2', ['fund_source_demands' => $demands_by_fund_source['fund_wire_transfer']])
                    </div>
                    @endif
                    @if(isset($demands_by_fund_source['fund_compensation']))
                    <div class="tab-pane" id="billing-tab3" role="tabpanel">
                        @include('demand.tabs.tab2', ['fund_source_demands' => $demands_by_fund_source['fund_compensation']])
                    </div>
                    @endif
                    @if(false)
                    <div class="tab-pane" id="billing-tab4" role="tabpanel">
{{--                        @include('billing.tabs.tab4')--}}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- end: content body -->
@endsection

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
            $('input[name="start"], input[name="end"], select#form-control-salesman').change(function () {
                loader_on();
                $('form.form-dates-range').submit();
            });
        });
    </script>
@endsection
