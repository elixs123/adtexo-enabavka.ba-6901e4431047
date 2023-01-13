@extends('layouts.app')

@section('head_title', $title = trans('action.actions.stats'))

@section('css-vendor')
    <link href="{{ asset('assets/theme/vendors/css/charts/apexcharts.css').assetVersion() }}" rel="stylesheet"
          type="text/css">
@endsection

@section('content')
    <!-- start: content header -->
    <div class="content-header row">
        <div class="content-header-left col-6 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ trans('skeleton.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('action.index') }}">{{ trans('action.title') }}</a>
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
        {!! Form::open(['url' => route('action.stats'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'row form-dates-range']) !!}
        <div class="col-12 col-md-4">
            {!! VuexyAdmin::dateRange('start', 'end', $dates['start_date'], $dates['end_date'], []) !!}
        </div>
        <div class="col-6 col-md-3">
            {!! VuexyAdmin::selectTwo('salesmen_id', $salesmen, null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-salesmen_id'], '') !!}
        </div>
        <div class="col-6 col-md-3">
            {!! VuexyAdmin::selectTwo('action_id', $actions->pluck('name', 'id')->prepend(trans('action.stats.labels.action'), '')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-action_id'], '') !!}
        </div>
        <div class="col-12 col-md-2">
            <button type="button" class="btn btn-info btn-block" data-export-xls>{{ trans('skeleton.actions.export2xls') }}</button>
            <input type="hidden" name="export" value="xls" disabled>
        </div>
        {!! Form::close() !!}
        <div class="row">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-header pb-1 border-bottom d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-center">
                            <span class="badge badge-dark">{{ trans('action.stats.total.actions') }}</span>
                            <span class="badge badge-primary">{{ $actions->count() }}</span>
                        </h3>
                        <h3 class="mb-0 text-center">
                            <span class="badge badge-dark">{{ trans('action.stats.total.stock') }}</span>
                            <span class="badge badge-info">{{ $actions->sum('qty') }}</span>
                        </h3>
                        <h3 class="mb-0 text-center">
                            <span class="badge badge-dark">{{ trans('action.stats.total.sales') }}</span>
                            <span class="badge badge-success">{{ $actions->sum('bought') }}</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary"
                                                                  data-row-count>{{ $documents->count() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="scrollbar" data-card-scrollbar>
                                <div class="table-responsive-lg">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ trans('action.stats.documents.document') }}</th>
                                                <th>{{ trans('action.stats.documents.date') }}</th>
                                                <th>{{ trans('action.stats.documents.action') }}</th>
                                                <th>{{ trans('action.stats.documents.qty') }}</th>
                                                <th>{{ trans('action.stats.documents.salesman_id') }}</th>
                                                <th>{{ trans('skeleton.data.status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($documents as $item)
                                            <tr>
                                                <td>#<a href="{{ route('document.show', ['id' => $item->id]) }}" title="{{ trans('document.actions.show') }}" data-toggle="tooltip">{{ $item->id }}</a></td>
                                                <td>{{ $item->date_of_order->format('d.m.Y.') }}</td>
                                                <td>
                                                    {{ $item->rAction->name }}<br><span
                                                        class="badge badge-info text-uppercase">{{ $item->rAction->rType->name }}</span>
                                                </td>
                                                <td>{{ $item->action_qty }}</td>
                                                <td>{{ isset($salesmen[$item->created_by]) ? $salesmen[$item->created_by] : '' }}</td>
                                                <td>
                                                    <div class="chip"
                                                         style="background-color: {{ $item->rStatus->background_color }}">
                                                        <div class="chip-body">
                                                            <div class="chip-text"
                                                                 style="color: {{ $item->rStatus->color }}">{{ $item->rStatus->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Po danima <span class="badge badge-primary" data-row-count>{{ $documents->count() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="revenue-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Po komercijalistima <span class="badge badge-primary" data-row-count>{{ $documents->count() }}</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="salesmen-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: content body -->
@endsection

@section('script-vendor')
    <script src="{{ asset('assets/theme/vendors/js/charts/apexcharts.min.js').assetVersion() }}" type="text/javascript"></script>
@endsection

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
            $('form.form-dates-range').change(function () {
                loader_on();
                $('form.form-dates-range').submit();
            });
            
            $('button[data-export-xls]').click(function () {
                $(this).next('input').prop('disabled', false).removeAttr('disabled');
                $('form.form-dates-range').submit();
                $(this).next('input').prop('disabled', true).attr('disabled', 'disabled');
            });
            
            new PerfectScrollbar("[data-card-scrollbar]", {
                suppressScrollX: true,
                wheelPropagation: false
            });
        });
        
        $(window).on("load", function () {
            var $primary = '#7367f0';
            var $success = '#28c76f';
            var $danger = '#ea5455';
            var $warning = '#ff9f43';
            var $info = '#00cfe8';
            var $primary_light = '#a9a2f6';
            var $danger_light = '#f29292';
            var $success_light = '#55dd92';
            var $warning_light = '#ffc085';
            var $info_light = '#1fcadb';
            var $strok_color = '#b9c3cd';
            var $label_color = '#e7e7e7';
            var $white = '#fff';
            
            var revenueChartoptions = {
                chart: {
                    height: 270,
                    toolbar: {show: false},
                    type: 'line',
                },
                stroke: {
                    curve: 'straight',
                    dashArray: [0, 8],
                    width: [4, 2],
                },
                grid: {
                    borderColor: $label_color,
                },
                legend: {
                    show: false,
                },
                colors: [$danger_light, $strok_color],
                
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        inverseColors: false,
                        gradientToColors: [$primary, $strok_color],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                markers: {
                    size: 0,
                    hover: {
                        size: 5
                    }
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: $strok_color,
                        }
                    },
                    axisTicks: {
                        show: false,
                    },
                    categories: {!! json_encode($sales_chart_data->keys()) !!},
                    axisBorder: {
                        show: false,
                    },
                    tickPlacement: 'on',
                },
                yaxis: {
                    tickAmount: 5,
                    labels: {
                        style: {
                            color: $strok_color,
                        },
                        formatter: function (val) {
                            return val > 999 ? (val / 1000).toFixed(1) + 'k' : val.toFixed(0);
                        }
                    }
                },
                tooltip: {
                    x: {show: false}
                },
                series: [{
                    name: "Prodaja",
                    data: {!! json_encode($sales_chart_data->values()) !!}
                }],
                
            }
            
            var revenueChart = new ApexCharts(
                document.querySelector("#revenue-chart"),
                revenueChartoptions
            );
            revenueChart.render();
    
            var salesmenOptions = {
                series: [{
                    name: 'Prodaja',
                    data: {!! json_encode($salesmen_chart_data->values()) !!}
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%'
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        colors: ["#ffffff"]
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                grid: {
                    borderColor: $label_color,
                },
                colors: [$primary, $strok_color],
    
                fill: {
                    opacity: 1,
                    // type: 'gradient',
                    // gradient: {
                    //     shade: 'dark',
                    //     inverseColors: false,
                    //     gradientToColors: [$primary, $strok_color],
                    //     shadeIntensity: 1,
                    //     type: 'vertical',
                    //     opacityFrom: 1,
                    //     opacityTo: 1,
                    //     stops: [0, 100, 100, 100]
                    // },
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: $strok_color,
                        }
                    },
                    categories: {!! json_encode($salesmen_chart_data->keys()) !!},
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: $strok_color,
                        }
                    },
                    title: {
                        text: 'Prodaja u %'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + "%"
                        }
                    }
                }
            };
    
            var salesmenChart = new ApexCharts(document.querySelector("#salesmen-chart"), salesmenOptions);
            salesmenChart.render();
        });
    </script>
@endsection

