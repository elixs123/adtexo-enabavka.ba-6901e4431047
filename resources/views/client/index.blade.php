@extends('layouts.app')

@section('head_title', $title = trans('client.title'))

@section('content')
    <!-- start: content header -->
    <div class="content-header row">
        
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
                        <h4 class="card-title">Subjekti</h4>
                        <a data-action="expand" class="pull-right"><i class="feather icon-maximize"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class="table table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Subjekat</th>
                                            <th>Naziv</th>
                                            <th>Adresa</th>
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="clients">
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{$item->acSubject}}</td>
                                                <td>{{$item->acName2}}</td>
                                                <td>{{$item->acAddress}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <div class="card-footer">
                            {!! $items->appends(request()->query())->render() !!}
                        </div>
                    </div>
                </div>
                <!-- end: items -->
            </div>
        </div>
    </div>
    <!-- end: content body -->
@endsection
