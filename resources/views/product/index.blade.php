@extends('layouts.app')

@section('head_title', $title = trans('product.title'))

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
                        @can('create-product')
                        <a class="dropdown-item" href="{{ route('product.create') }}" data-toggle="modal" data-target="#form-modal1">{{ trans('product.actions.create') }}</a>
                    <a class="dropdown-item" href="{{ route('product_stock.mass_create') }}" data-toggle="modal" data-target="#form-modal1">{{ trans('product.actions.create_supplies') }}</a>
                        @endcan
                        <a class="dropdown-item" href="javascript:" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="{{ ($filters = (request('filters', 0) == 1) ? true : false) ? 'true' : 'false' }}">{{ trans('skeleton.actions.filters') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">{{ trans('skeleton.actions.export2pdf') }}</a>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'xls']) }}">{{ trans('skeleton.actions.export2xls') }}</a>
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
                
                <!-- end: filters -->
                <!-- start: items -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }} <span class="badge badge-primary" data-row-count>0</span></h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                    <table class="table table-hover data-thumb-view">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Sifra</th>
                                                <th>Naziv</th>
                                                <th>Primarna klasifikacija</th>
                                                <th>Sekundarna klasifikacija</th>
                                                <th>Barcode</th>
                                                <th>Valuta</th>
                                                <th>MPC</th>
                                                <th>Osnovica</th>
                                                <th>VPC</th>
                                                <th>Zadnja nabavna cijena</th>
                                                <th>Fakturna cijena</th>
                                                <th>Valuta dobavljaca</th>
                                                <th>J.MJ.</th>
                                                <th>Iznos PDV-A</th>
                                                <th>PDV stopa</th>
                                                <th>Aktivan</th>
                                            </tr>
                                        </thead>
                                        <tbody data-ajax-form-body="products">
                                        @foreach($items as $id => $item)
                                        <tr>
                                            <td>{{ $item->acIdent }}</td>
                                            <td>{{ $item->acName }}</td>
                                            <td>{{ $item->acClassif }}</td>
                                            <td>{{ $item->acClassif2 }}</td>
                                            <td>{{ $item->acCode }}</td>
                                            <td>{{ $item->acCurrency }}</td>
                                            <td>{{ $item->anSalePrice }}</td>
                                            <td>{{ $item->anRTPrice }}</td>
                                            <td>{{ $item->anWSPrice }}</td>
                                            <td>{{ $item->anBuyPrice }}</td>
                                            <td>{{ $item->anPriceSupp }}</td>
                                            <td>{{ $item->acPurchCurr }}</td>
                                            <td>{{ $item->acUM }}</td>
                                            <td>{{ $item->anVAT }}</td>
                                            <td>{{ $item->acVATCode }}</td>
                                            <td>{{ $item->acActive }}</td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="no-results" data-no-results>
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
