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
                <div id="collapse-filters" class="filters collapse show">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ trans('skeleton.actions.filters') }}</h4>
                            <a href="javascript:" class="pull-right" data-toggle="collapse" data-target="#collapse-filters" aria-expanded="true"><i class="feather icon-x"></i></a>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="form-group-keywords" class="form-group" data-name="keywords">
                                            <label for="form-control-keywords">Pretraga</label>
                                            <input id="form-control-keywords" class="form-control search" maxlength="100" placeholder="Naziv klijenta, JIB, PIB ili šifra" name="keywords" type="text">
                                        </div>
                                        <div id="form-group-keywords" class="form-group border p-0 search-result" style="overflow: scroll;display:none;">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                            <th>PDV</th>
                                            <th>Akcije</th>
                                        </tr>
                                    </thead>
                                    <tbody data-ajax-form-body="clients">
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{$item->acSubject}}</td>
                                                <td>{{$item->acName2}}</td>
                                                <td>{{$item->acAddress}}</td>
                                                <td>{{$item->acCode}}</td>
                                                <td><a data-toggle="modal" data-target="#editModal{{$item->anQID}}"><i class="feather icon-external-link"></i></a></td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="editModal{{$item->anQID}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{$item->anQID}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">{{$item->acName2}}</h5>
                                                        
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        
                                                    </div>
                                                <div class="modal-body">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active " id="main-tab" data-toggle="tab" href="#main" aria-controls="main" role="tab" aria-selected="true">Osnovno</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" aria-controls="address" role="tab" aria-selected="false">Ostalo</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="nav-tabContent">
                                                        <div class="tab-pane active" id="main" aria-labelledby="main-tab" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-12 col-md-4">
                                                                    <div id="form-group-type_id" class="form-group" data-name="type_id"><label for="form-control-type_id">Tip</label><p class="form-control">Pravno lice</p></div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div id="form-group-jib" class="form-group" data-name="jib"><label for="form-control-jib">JIB</label><p class="form-control">{{$item->acCode}}</p></div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div id="form-group-pib" class="form-group" data-name="pib"><label for="form-control-pib">PIB</label><p class="form-control">{{$item->acCode}}</p></div>
                                                                </div>
                                                                <div class="col-4 col-md-3">
                                                                    <div id="form-group-code" class="form-group" data-name="code"><label for="form-control-code">Šifra</label><p class="form-control">{{$item->acSubject}}</p></div>
                                                                </div>
                                                                <div class="col-8 col-md-9">
                                                                    <div id="form-group-name" class="form-group" data-name="name"><label for="form-control-name">Naziv</label><p class="form-control">{{$item->acName2}}</p></div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4 col-md-3">
                                                                    <div id="form-group-location_code" class="form-group" data-name="location_code"><label for="form-control-location_code">Šifra lokacije</label><p class="form-control">{{$item->acFieldSI}}</p></div>
                                                                </div>
                                                                <div class="col-8 col-md-9">
                                                                    <div id="form-group-location_name" class="form-group" data-name="location_name"><label for="form-control-location_name">Naziv lokacije</label><p class="form-control">{{$item->acFieldSH}}</p></div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12 col-md-12">
                                                                    <div id="form-group-location_name" class="form-group" data-name="location_name"><label for="form-control-location_name">Država</label><p class="form-control">{{$item->acCountry}}</p></div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4 col-md-6">
                                                                    <div id="form-group-location_code" class="form-group" data-name="location_code"><label for="form-control-location_code">Telefon</label><p class="form-control">{{$item->acPhone}}</p></div>
                                                                </div>
                                                                <div class="col-8 col-md-6">
                                                                    <div id="form-group-location_name" class="form-group" data-name="location_name"><label for="form-control-location_name">Fax</label><p class="form-control">{{$item->acFax}}</p></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="address" aria-labelledby="address-tab" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-12 col-md-4">
                                                                    <div id="form-group-type_id" class="form-group" data-name="type_id"><label for="form-control-type_id">Odgoda plačanja</label><p class="form-control">{{$item->anDaysForPayment}}</p></div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div id="form-group-jib" class="form-group" data-name="jib"><label for="form-control-jib">Rabat</label><p class="form-control">{{$item->anRebate}}</p></div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div id="form-group-pib" class="form-group" data-name="pib"><label for="form-control-pib">Valuta</label><p class="form-control">{{$item->acCurrency}}</p></div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4 col-md-12">
                                                                    <div id="form-group-location_code" class="form-group" data-name="location_code"><label for="form-control-location_code">Nacin placanja</label>
                                                                        @if($item->acPayMethod == 2)
                                                                            <p class="form-control">Virman</p>
                                                                        @else if($item->acPayMethod == 1)
                                                                            <p class="form-control">Gotovina</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        @if(!request()->get('search'))
                        <div class="card-footer">
                            {!! $items->appends(request()->query())->render() !!}
                        </div>
                        @endif
                    </div>
                </div>
                <!-- end: items -->
            </div>
        </div>
    </div>
    <!-- end: content body -->
    
    <script>
    $(document).ready(function(){
        $(".search").keyup(function(){
            $(".search-result").hide()
            $(".appended-search").remove()
            $.ajax({
                type: "GET",
                url: "api/ba/subjects/search",
                data:{search: this.value},
                success: function(res){
                    $(".search-result").css('height', '298px')
                    $(".search-result").css('display', 'grid')
                    if(res.length > 0){
                        $(".search-result").show()

                        for(var x = 0;x < res.length;x++){
                            $(".search-result").append("<a href='?search="+res[x].acSubject+"' class='border appended-search p1' style='color: black !important;'>"+res[x].acName2 + ' || ' + res[x].acAddress+"</a>")
                        }
                    }
                },
                error:function(error)
                {
                }
            });
        })
    });
</script>
@endsection
