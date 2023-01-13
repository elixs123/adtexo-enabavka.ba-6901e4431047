@extends('layouts.app')

@section('head_title', $title = 'Pregled uplata')

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
        {!! Form::open(['url' => route('billing.index'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'row form-dates-range']) !!}
            <div class="col-12 col-lg-4">
                @include('homepage._countries', ['route' => 'billing.index'])
            </div>
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::dateRange('start', 'end', $dates_data['start_date'], $dates_data['end_date'], []) !!}
                @if(isset($query))
                    @foreach($query as $key => $value)
                        @if(!in_array($key, ['start', 'end', 'salesman']))
                {!! Form::hidden($key, $value) !!}
                        @endif
                    @endforeach
                @endif
            </div>
            @if(!userIsSalesman())
            <div class="col-12 col-lg-4">
                {!! VuexyAdmin::selectTwo('salesman', $persons, null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}']) !!}
            </div>
            @endif
        {!! Form::close() !!}
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#billing-tab1" data-toggle="tab" aria-selected="true">Tip uplate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#billing-tab2" data-toggle="tab" aria-selected="false">Struktura virman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#billing-tab3" data-toggle="tab" aria-selected="false">Po kupcu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#billing-tab4" data-toggle="tab" aria-selected="false">Po dokumentu</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="billing-tab1" role="tabpanel">
                        @include('billing.tabs.tab1')
                    </div>
                    <div class="tab-pane" id="billing-tab2" role="tabpanel">
                        @include('billing.tabs.tab2')
                    </div>
                    <div class="tab-pane" id="billing-tab3" role="tabpanel">
                        @include('billing.tabs.tab3')
                    </div>
                    <div class="tab-pane" id="billing-tab4" role="tabpanel">
                        @include('billing.tabs.tab4')
                    </div>
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
