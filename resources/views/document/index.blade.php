@extends('layouts.app')

@section('head_title', $title = trans('document.title'))

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
                            <li class="breadcrumb-item active">{{ userIsClient() ? trans('document.title_client') : $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-right col-3">
            <div class="form-group breadcrum-right">
            </div>
        </div>
    </div>
    <!-- end: content header -->
    <!-- start: content body -->
    <div class="content-body">
        <div class="row">
            <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kupac</th>
                        <th scope="col">Platitelj</th>
                        <th scope="col">Status</th>
                        <th scope="col">Datum kreiranja</th>
                        <th scope="col">Datum vazenja</th>
                        <th scope="col">Broj narudžbe</th>
                        <th scope="col">Vrijednost</th>
                        <th scope="col">Iznos PDV-a</th>
                        <th scope="col">Ukupno sa PDV-om</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $key => $order)
                    <tr>
                        <th scope="row" class="text-right">{{$key+1}}</th>
                        <td class="text-right">{{$order->subject->acName2}}</td>
                        <td class="text-right">{{$order->acPayerName}}</td>
                        @if($order->acStatus == 'R')
                            <td class="text-info text-right">Rezervisano</td>
                        @elseif($order->acStatus == 'N')
                            <td class="text-danger text-right">Nepotvrđeno</td>
                        @endif
                        <td class="text-right">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->adDate)->format('d.m.Y') }}</td>
                        <td class="text-right">{{ Carbon\Carbon::createFromFormat('Y-m-d', $order->anDaysForValid)->format('d.m.Y') }}</td>
                        <td class="text-right"><a href="{{route('createorder', ['id' => $order->id])}}">{{$order->orderNumber}}</a></td>
                        <td class="text-right">{{round($order->anForPay / 1.17, 2)}} KM</td>
                        <td class="text-right">{{round($order->anForPay * 0.145292, 2)}} KM</td>
                        <td class="text-right">{{round($order->anForPay, 2)}} KM</td>
                    </tr>
                    @empty
                    <tr>
                        <th colspan="6"><h3 class="text-center">Nema dodati narudjbi</h3></th>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end: content body -->
@endsection
