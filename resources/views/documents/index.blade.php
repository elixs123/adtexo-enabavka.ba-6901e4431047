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
                            <h4 class="card-title">Narudžba</h4>
                            <hr>
                            <hr>
                        </div>
                        <div class="card-content">
                            
                            <div class="card-body">
                            <div class="col-12 p-0">
                                <hr>
                                <div class="row">
                                    <div class="col-md-3 border-right">
                                        <h5>Mjesto isporuke</h5>
                                        <p>{{ $subject->acName2 }}</p>
                                        <p>{{ $subject->acAddress }}</p>
                                        <p>{{ $subject->acFieldSH }} {{ $subject->acFieldSl }}</p>
                                        <p>{{ $subject->acCode }}</p>
                                    </div>
                                    <div class="col-md-3 border-right">
                                        <h5>Platitelj</h5>
                                        <p>{{ $acPayer->acName2 }}</p>
                                        <p>{{ $acPayer->acAddress }}</p>
                                        <p>{{ $acPayer->acFieldSH }} {{ $subject->acFieldSl }}</p>
                                        <p>{{ $acPayer->acCode }}</p>
                                    </div>
                                    <div class="col-md-3 border-right">
                                        <h5>Vrijeme kreiranja</h5>
                                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->adDate)
                                    ->format('d.m.Y') }}</p>
                                        <h5>Datum vazenja</h5>
                                        <p>{{ Carbon\Carbon::createFromFormat('Y-m-d', $order->anDaysForValid)
                                    ->format('d.m.Y') }}</p>
                                        <br>
                                        <br>
                                        <h5>Veleprodaja</h5>
                                    </div>
                                    <div class="col-md-2 border-right">
                                        <table>
                                            <tr>
                                                <th><h5>Ukupno</h5></th>
                                            </tr>
                                            <tr>
                                                <th>Vrijednost:</th>
                                                <th class="text-right">{{number_format($order->anForPay, 2, '.', ',')}} KM</th>
                                            </tr>
                                            <tr>
                                                <th>Iznos PDV-a:</th>
                                                <th class="text-right">{{number_format(($order->anForPay * 1.17) - ($order->anForPay * 1.17) / 1.17 , 2, '.', ',')}} KM</th>
                                            </tr>
                                            <tr>
                                                <th>Brza pošta:</th>
                                                @switch($order->anForPay)
                                                    @case($order->anForPay < 100)
                                                        <?php $ePost = 7.00 ?>
                                                        <th class="text-right">7,00 KM</th>
                                                    @break
                                                    @case($order->anForPay >= 100 && $order->anForPay < 160)
                                                        <?php $ePost = 3.50 ?>
                                                        <th class="text-right">3,50 KM</th>
                                                    @break
                                                    @case($order->anForPay >= 160)
                                                        <?php $ePost = 0.00 ?>
                                                        <th class="text-right">0,00 KM</th>
                                                    @break
                                                @endswitch
                                            </tr>
                                            <tr>
                                                <th>Ukupno sa PDV-om:</th>
                                                <th class="text-right">{{number_format(($order->anForPay * 1.17) + $ePost, 2, '.', ',')}} KM</th>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <table class="float-right">
                                            <tr>
                                                <th><h6 style="font-size: 12px;">Broj dokumenta</h6></th>
                                            </tr>
                                            <tr>
                                                <td><h4>{{$order->orderNumber}}</h4></td>
                                            </tr>
                                            <tr>
                                                @if($order->acStatus == 'R')
                                                <td class="text-info text-right">Rezervisano</td>
                                            @elseif($order->acStatus == 'N')
                                                <td class="text-danger text-right">Nepotvrđeno</td>
                                            @elseif($order->acStatus == 'O')
                                                <td class="text-success text-right">Otpremljeno</td>
                                            @endif
                                            </tr>
                                            <tr>
                                                <th><br></th>
                                            </tr>
                                            <tr>
                                                <th><br></th>
                                            </tr>
                                            <tr>
                                                <form action="" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                    <input type="hidden" name="acStatus" value="true">
                                                    <input type="hidden" name="orderNumber" value="{{$order->orderNumber}}">
                                                    <th class="btn-group"><button class="btn btn-success" type="submit" @if($order->acStatus == 'O' || $order->acStatus == 'R') disabled @endif>Potvrdi</button></th>
                                                </form>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form action="" method="post">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="orderNumber" value="{{$order->orderNumber}}">
                                    <input type="hidden" name="acSubject" class="acSubject"  value="{{$order->acSubject}}">
                                    <input type="hidden" name="acWayOfSale" class="acWayOfSale"  value="{{$subject->acWayOfSale}}">
                                    @if(count($orderItems) > 0)
                                        <input type="hidden" name="anNo" value="{{$orderItems[0]->anNo+1}}">
                                    @else
                                        <input type="hidden" name="anNo" value="1">
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label for="acIdent">Šifra artikla</label>
                                            <input type="text" class="form-control search acIdent" name="acIdent" id="acIdent" placeholder="Šifra aritkla" autocomplete="off" @if($order->acStatus == 'O' || $order->acStatus == 'R') disabled @endif  required>
                                            
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="acIdent">Naziv artikla</label>
                                            <input type="text" class="form-control search acName" name="acName" id="acName" placeholder="Naziv aritkla" autocomplete="off" @if($order->acStatus == 'O' || $order->acStatus == 'R') disabled @endif  required>
                                            
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">MPC</label>
                                            <input type="decimal" class="form-control anRTPrice" name="anRTPrice"  id="anRTPrice"  placeholder="MPC" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">VPC</label>
                                            <input type="decimal" class="form-control anWSPrice2" name="anWSPrice2"  id="anWSPrice2"  placeholder="VPC" style="pointer-events:none;background:#ddd;">
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
                                            <input type="decimal" class="form-control anRebate2" name="anRebate2" id="anRebate2"  placeholder="Rabat2" autocomplete="off" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label for="anQty">R3</label>
                                            <input type="decimal" class="form-control anRebate3" name="anRebate3" id="anRebate3"  placeholder="Rabat3" style="pointer-events:none;background:#ddd;">
                                        </div>
                                        <div class="form-group col-md-1">
                                            <button class="btn btn-success float-right" @if($order->acStatus == 'O' || $order->acStatus == 'R') disabled @endif  type="submit" style="width: 91%;">Dodaj</button>
                                        </div>
                                        <hr>
                                        <div class="form-control search-result col-md-3" style="overflow: scroll;display:none;">
                                            <table class='table table-sm table-bordered table-striped'>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sifra</th>
                                                        <th scope="col">Naziv</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="search-result-table">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <table class="table col-12">
                                    <thead>
                                        <tr style="padding:0">
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">Šifra artikla</th>
                                            <th scope="col" class="text-center">Naziv</th>
                                            <th scope="col" class="text-center">Količina</th>
                                            <th scope="col" class="text-center">Cijena</th>
                                            <th scope="col" class="text-center">Cijena bez PDV-a</th>
                                            
                                            <th scope="col" class="text-center">Rabat1</th>
                                            <th scope="col" class="text-center">Rabat2</th>
                                            <th scope="col" class="text-center">Rabat3</th>
                                            <th scope="col" class="text-center">Vrijednost</th>
                                            <th scope="col" class="text-center">Ukupno sa PDV-om</th>
                                            <th scope="col" class="text-center">Opcije</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            @forelse($orderItems as $x => $item )
                                            <form action="" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                <input type="hidden" name="orderNumber" value="{{$item->orderNumber}}">
                                                <input type="hidden" name="anNo" value="{{$item->anNo}}">
                                                <input type="hidden" name="anForPay" value="{{$item->anForPay}}">
                                                <input type="hidden" name="anPrice" value="{{$item->anPrice}}">
                                                <input type="hidden" name="anRebate1" value="{{$item->anRebate1}}">
                                                <input type="hidden" name="anRebate1" value="{{$item->anRebate2}}">
                                                <input type="hidden" name="anRebate3" value="{{$item->anRebate3}}">
                                                <tr>
                                                    <th scope="row"  class="text-right">{{$item->anNo}}</th>
                                                    <td class="text-right">{{$item->acIdent}}</td>
                                                    <td class="text-right">{{$item->items->acName}}</td>

                                                    <th class="text-right">

                                                        <input type="text" name="anQty" @if($order->acStatus == 'O' || $order->acStatus == 'R') disabled @endif class="form-control col-4 float-right mr-4" value="{{$item->anQty}}">
                                                    </th>

                                                    <td class="text-right">{{number_format($item->anPrice * 1.17, 2, '.', ',') }}</td>
                                                    <td class="text-right">{{number_format($item->anPrice, 2, '.', ',')}}</td>
                                                    
                                                    <th class="text-right">{{$item->anRebate1}}</th>
                                                    <th class="text-right">{{$item->anRebate2}}</th>
                                                    <th class="text-right">{{$item->anRebate3}}</th>
                                                    <th class="text-right">{{number_format($item->anForPay * $item->anQty, 2,'.', ',')}}</th>
                                                    <th class="text-right">{{number_format(($item->anForPay * $item->anQty) * 1.17, 2, '.', ',')}}</th>
                                                    <th class="p-0">
                                                        <button class="btn btn-success float-right m-1"  @if($order->acStatus == 'O' || $order->acStatus == 'R') disabled @endif  type="submit" name="btn_update">Izmjeni</button>
                                                    </th>
                                                </tr>
                                            </form>
                                                @empty
                                                <tr>
                                                    <th colspan="6"><h3 class="text-center">Nema dodatih artikala</h3></th>
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
            if(this.value < 1){
                $(".search-result").hide()
                $(".appended-search").remove()

                return false
            }
            $.ajax({
                type: "GET",
                url: location.origin+"/api/ba/projects/search",
                data:{
                    searchName: $(this).attr('name'),
                    search: this.value,
                    acSubject: acSubject
                },
                success: function(res){
                    console.log(res)
                    $(".appended-search").remove()
                    $(".search-result").css('height', '298px')
                    //$(".search-result").css('display', 'grid')
                    if(res['products'].length > 0){
                        $(".search-result").show()

                        // for(var x = 0;x < res['products'].length;x++){
                        //     $(".search-result").append("<p value="+res['products'][x].acIdent+" anRTPrice="+res['products'][x].anSalePrice+" anWSPrice2="+res['products'][x].anWSPrice+" class='border appended-search p1 cursor-pointer' style='color: black !important;padding:5px;'>"+res['products'][x].acIdent + ' || ' +res['products'][x].acName +"</p>")
                        // }

                        for(var x = 0;x < res['products'].length;x++){
                            $(".search-result-table").append("<tr value="+res['products'][x].acIdent+" anRTPrice="+res['products'][x].anSalePrice+" anWSPrice2="+res['products'][x].anWSPrice+" acName="+res['products'][x].acName+"  class='appended-search p1 cursor-pointer'><td>"+res['products'][x].acIdent+"</td><td>"+res['products'][x].acName+"</td></tr>")
                        }

                        
                    }
                   $('.cursor-pointer').click(function(){
                    var anRebate2 = 0
                    var anQty = 1

                    $('.acIdent').val($(this).attr('value'))
                    $('.acName').val($(this).attr('acName'))
                    $('.anRTPrice').val($(this).attr('anRTPrice'))
                    $('.anWSPrice2').val($(this).attr('anWSPrice2'))
                    $('.anQty').val(anQty.toFixed(2))
                    $('.anRebate1').val(res['acSubject'][0]['anRebate'])
                    $('.anRebate2').val(anRebate2.toFixed(2))

                    //anRabate3

                    $.ajax({
                        type: "GET",
                        url: location.origin+"/api/ba/projects/search/rabat",
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
