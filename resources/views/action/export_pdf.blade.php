<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>eNabavka.ba</title>
    <style type="text/css">
        body {
            font-size: 13px;
            line-height: 20px;
            font-family: DejaVu Sans !important;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

       td,th {
           border-bottom: 1px solid #000;
           line-height: 1;
       }

        .table thead tr th {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 11px;
            padding-top: 14px;
            padding-bottom: 14px;
            vertical-align: middle;
            text-align: left;
        }

        .table td {
            font-size: 12px;
            color: #58585A;
            padding: 5px;
        }
        
        .table-total td {
            border-top: 1px solid #000;
            padding: 15px;
        }
        
		.page-break {
			page-break-after: always;
		}
		
		h1, h2{
			text-transform: uppercase
		}
        
        figure {
            display: block;
        }
        
        figure img {
            width: 100%;
            height: auto;
            display: block;
        }

    </style>
</head>
    <h1>{{ $action->name }}</h1>
    <h2>Akcija vrijedi u periodu od <strong>{{ $action->started_at->format('d.m.Y') }}</strong> do <strong>{{ $action->finished_at->format('d.m.Y') }}</strong></h2>
    @if($action->photo)
    <figure>
        <img src="{{ asset(config('picture.action_path').'/big_'.$action->photo) }}" alt="{{ $action->name }}">
    </figure>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th style="width: 120px">{{ trans('product.data.photo') }}</th>
                <th style="width: 50px">{{ trans('product.data.code') }}</th>
                <th style="width: 120px">{{ trans('product.data.barcode') }}</th>
                <th>{{ trans('product.data.name') }}</th>
                <th>Količina</th>
                <th>Cijena bez popusta</th>
                <th>Cijena sa popustom</th>
            </tr>
        </thead>
        <tbody>@php $total = 0; @endphp
            @foreach($products_action as $product_id => $product_action)
                @if(isset($products[$product_id])) @php $item = $products[$product_id]; $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; @endphp
            <tr>
                <td><img width="120" src="{{ $item->photo_small }}" alt="{{ $item->name }}"></td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $product_action['qty'] }} {{ $unit }}</td>
                <td >{{ format_price($product_action->price, 2) }} {{ $currency }}</td>@php $total += $product_action->qty * $product_action->price; @endphp
                <td>{{ format_price($product_action->price_discounted, 2) }} {{ $currency }}</td>
            </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <h2>Vrijednost akcije</h2>
    <table class="table table-total">
        <tr>
            <td style="text-align: center;width: 33.333333%">Ukupno: <strong>{{ format_price($total, 2) }} {{ $currency }}</strong></td>@php $discounted = calculateDiscount($total, ScopedDocument::discount1(), ScopedDocument::discount2(), (ScopedDocument::useMpcPrice()) ? $action->total_discount : $action->subtotal_discount); @endphp
            <td style="text-align: center;width: 33.333333%">Rabat: <strong>{{ format_price($total - $discounted, 2) }} {{ $currency }}</strong></td>
            <td style="text-align: center;width: 33.333333%">Vrijednost akcije: <strong>{{ format_price($discounted, 2) }} {{ $currency }}</strong></td>
        </tr>
    </table>
    @if(($action->isGratis()) && $products_gratis->count())
    <h2>Gratis proizvodi</h2>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 120px">{{ trans('product.data.photo') }}</th>
                <th style="width: 50px">{{ trans('product.data.code') }}</th>
                <th style="width: 120px">{{ trans('product.data.barcode') }}</th>
                <th>{{ trans('product.data.name') }}</th>
                <th>Količina</th>
                <th>Cijena</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products_gratis as $product_id => $product_gratis)
                @if(isset($products[$product_id])) @php $item = $products[$product_id]; $unit = is_null($item->rUnit) ? '' : $item->rUnit->name; @endphp
            <tr>
                <td><img width="120" src="{{ $item->photo_small }}" alt="{{ $item->name }}"></td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $product_gratis->qty }} {{ $unit }}</td>
                <td><strong>{{ format_price($product_gratis->price, 2) }} {{ $currency }}</strong></td>
            </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
