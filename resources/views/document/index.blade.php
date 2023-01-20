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
                        <th scope="col">Opcije</th>
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
                        @if($order->acStatus == 'N')
                            <td class="text-center"><button type="button"   style="border: none; background: none;" data-toggle="modal" data-target="#deleteModal"><i  class="feather icon-trash"><i></button></td>
                        <!-- Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Da li ste sigurni</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Otkaži</button>
                                <form action="{{route('orders')}}" method="post">
                                <input type="hidden" name="orderNumber" value="{{$order->orderNumber}}">
                                    {{ csrf_field() }}
                                    <button class="btn btn-primary" type="submit">Izbriši</button>
                                </form>
                            </div>
                            </div>
                        </div>
                        </div>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <th colspan="6"><h3 class="text-center">Nema dodatih narudžbi</h3></th>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>

        
    </div>
    <!-- end: content body -->
@endsection
