@extends('layouts.app')

@section('head_title', $title = 'Kreiranje narudzbe')

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
    </div>

    <div class="content-body">


        
        <div class="row">
            <div class="col-12">
                <!-- start: filters -->
                <div id="collapse-filters" class="filters collapse show">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Kreiranje narudžbe</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Korisnik</th>
                                        <th scope="col">Adresa</th>
                                        <th scope="col">Kupac</th>
                                        <th scope="col">Platisa</th>
                                        <th scope="col">Datum</th>
                                        <th scope="col">Opcije</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $subject->acName2 }}</td>
                                        <td>{{ $subject->acAddress }}</td>
                                        <td>{{ $subject->acWayOfSale == 'Z' ? 'Veleprodajni' : 'Maloprodajni' }}</td>
                                        <td>{{ $subject->acPayer }}</td>
                                        <td>{{ $order[0]->adDate }}</td>
                                        <td><button class="btn btn-success">Potvrdi dokument</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                        </div>
                        <div class="card-content">
                            
                            <div class="card-body">
                            <form action="{{route('createorder')}}" method="post">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="orderNumber" value="{{$order[0]->id}}">
                                    <input type="hidden" name="acSubject" class="acSubject"  value="{{$order[0]->acSubject}}">
                                    <input type="hidden" name="acWayOfSale" class="acWayOfSale"  value="{{$subject->acWayOfSale}}">
                                    <div class="form-row">
                                        <div class="form-group col-md-1">
                                            <label for="acIdent">Šifra artikla</label>
                                            <input type="text" class="form-control search acIdent" name="acIdent" id="acIdent" placeholder="Šifra aritkla" autocomplete="off" required>
                                            
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="acIdent">Naziv artikla</label>
                                            <input type="text" class="form-control search acName" name="acName" id="acName" placeholder="Naziv aritkla" autocomplete="off" required>
                                            
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">MPC</label>
                                            <input type="decimal" class="form-control anSalePrice" name="anSalePrice"  id="anSalePrice"  placeholder="MPC" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">VPC</label>
                                            <input type="decimal" class="form-control anWSPrice" name="anWSPrice"  id="anWSPrice"  placeholder="VPC" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">Količina</label>
                                            <input type="decimal" class="form-control anQty" name="anQty" id="anQty"  placeholder="Kolicina" required autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">R1</label>
                                            <input type="decimal" class="form-control anRebate1" name="anRebate1" id="anRebate1"  placeholder="Rabat1" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">R2</label>
                                            <input type="decimal" class="form-control anRebate2" name="anRebate2" id="anRebate2"  placeholder="Rabat2" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">R3</label>
                                            <input type="decimal" class="form-control anRebate3" name="anRebate3" id="anRebate3"  placeholder="Rabat3" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button class="btn btn-success" type="submit">Dodaj</button>
                                        </div>
                                        <hr>
                                        <div class="form-control search-result col-md-3" style="overflow: scroll;display:none;">
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <table class="table col-12">
                                    <thead>
                                        <tr style="padding:0">
                                            <th scope="col">#</th>
                                            <th scope="col">Šifra artikla</th>
                                            <th scope="col">Količina</th>
                                            <th scope="col">Cijena</th>
                                            <th scope="col">Cijena bez PDV-a</th>
                                            
                                            <th scope="col">Rabat1</th>
                                            <th scope="col">Rabat2</th>
                                            <th scope="col">Rabat3</th>
                                            <th scope="col">Vrijednost</th>
                                            <th scope="col">Ukupno sa PDV-om</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orderItems as $x => $item )
                                        <tr>
                                            <th scope="row">{{$x+1}}</th>
                                            <td>{{$item->acIdent}}</td>
                                            <th>{{$item->anQty}}</th>
                                            <td>{{$item->anPrice}}</td>
                                            <td>{{round(($item->anPrice) / 1.17, 2)}}</td>
                                            
                                            <th>{{$item->anRebate1}}</th>
                                            <th>{{$item->anRebate2}}</th>
                                            <th>{{$item->anRebate3}}</th>
                                            <th>{{$item->anForPay * $item->anQty}}</th>
                                            <th>{{round(($item->anForPay * $item->anQty) * 1.17, 2)}}</th>
                                        </tr>
                                        @empty
                                        <tr>
                                            <th colspan="6"><h3 class="text-center">Nema dodati artikala</h3></th>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: filters -->
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function(){
        $(".search").keyup(function(){

            var acSubject = $('.acSubject').val()

            $(".search-result").hide()
            $(".appended-search").remove()
            $.ajax({
                type: "GET",
                url: "api/ba/projects/search",
                data:{
                    searchName: $(this).attr('name'),
                    search: this.value,
                    acSubject: acSubject
                },
                success: function(res){
                    $(".appended-search").remove()
                    $(".search-result").css('height', '298px')
                    $(".search-result").css('display', 'grid')
                    if(res['products'].length > 0){
                        $(".search-result").show()

                        for(var x = 0;x < res['products'].length;x++){
                            $(".search-result").append("<p value="+res['products'][x].acIdent+" anSalePrice="+res['products'][x].anSalePrice+" anWSPrice="+res['products'][x].anWSPrice+" class='border appended-search p1 cursor-pointer' style='color: black !important;padding:5px;'>"+res['products'][x].acName +"</p>")
                        }
                    }
                   $('.cursor-pointer').click(function(){
                    var anRebate2 = 0
                    var anQty = 1

                    $('.acIdent').val($(this).attr('value'))
                    $('.acName').val($(this).html())
                    $('.anSalePrice').val($(this).attr('anSalePrice'))
                    $('.anWSPrice').val($(this).attr('anWSPrice'))
                    $('.anQty').val(anQty.toFixed(2))
                    $('.anRebate1').val(res['acSubject'][0]['anRebate'])
                    $('.anRebate2').val(anRebate2.toFixed(2))

                    //anRabate3

                    $.ajax({
                        type: "GET",
                        url: "api/ba/projects/search/rabat",
                        data:{
                            acIdent: $('.acIdent').val(),
                            acSubject: $('.acSubject').val()
                        },
                        success: function(res){

                            if(res['priceItem'].length > 0){
                                $('.anRebate3').val(res['priceItem'][0]['anRebate'])
                            }else{
                                var anRebate3 = 0
                                $('.anRebate3').val(anRebate3.toFixed(2))
                            }
                            
                        },
                        error:function(error)
                        {
                        }
                    });

                        //anRABATE3 END

                    $(".search-result").hide()
                    $(".appended-search").remove()
                    
                   })
                },
                error:function(error)
                {
                }
            });
        })
    });
</script>
@endsection
