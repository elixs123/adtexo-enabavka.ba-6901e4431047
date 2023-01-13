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
            font-size: 10px;
            color: #58585A;
            padding: 5px 0;
        }
		.page-break {
			page-break-after: always;
		}
		
		h1, h2{
			text-transform: uppercase
		}

    </style>
</head>
<?php 


	$grouped = $items->sortByDesc(function($item) {
		return (int) $item->rFatherCategory->rCategory->priority;
	})->groupBy(function($item, $key){
		return $item->rFatherCategory->rCategory->priority.'. '.$item->rFatherCategory->name;
	});

	$sorted = $grouped->map(function($items) {
		return $items->sortByDesc('category_priority');
	});
	
	$stock = request('stock_id') > 0 ? $stocks->keyBy('id')[request('stock_id')] : null;
 ?>
<body>
@foreach($sorted as $category_name => $categories)
<?php $photo = $categories->first()->rFatherCategory->rCategory->photo; ?>
@if($photo != '')
<img width="700" height="1022" src="{{ asset('assets/pictures/category/original/' . $photo) }}" />
<div class="page-break"></div>
@else
<h1>{{ $category_name }}</h1>	
@endif
<?php $subcategories = $categories->groupBy('category_name'); ?>
	@foreach($subcategories as $subcategory_name => $products)
	<?php $photo = $products->first()->category_photo; ?>
	@if($photo != '')
	<img width="700" height="1022" src="{{ asset('assets/pictures/category/original/' . $photo) }}" />
	<div class="page-break"></div>
	@else
	<h2>{{ $subcategory_name }}</h2>	
	@endif	
    <table  class="table data-thumb-view">
        <thead>
            <tr>
                <th style="width: 60px">{{ trans('product.data.photo') }}</th>
                <th style="width: 50px">{{ trans('product.data.code') }}</th>
                <th style="width: 120px">{{ trans('product.data.barcode') }}</th>				
                <th>{{ trans('product.data.name') }}</th>
                <th style="width: 120px">{{ trans('product.data.packing') }}</th>								
				@if(isset($stock->country_id))
                <th style="text-align: right; width: 60px">VPC</th>
				@endif
            </tr>
        </thead>                                    
        <tbody>
			<?php $products = $products->sortByDesc('rang'); ?>
            @foreach($products as $id => $item)
            <tr>
                <td><img width="60" src="{{ asset(config('picture.product_path').'/small_' . $item->photo) }}" alt=""></td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->barcode }}</td>				
                <td>{{ $item->name }}</td>
                <td>{{ $item->packing }}</td>				
				@if(isset($stock->country_id))
				<?php $price = $item->rProductPrices->where('country_id', $stock->country_id)->first(); ?>
				<td style="text-align: right;">{{ format_price(isset($price->vpc) ? $price->vpc : 0) }}</td>
				@endif
            </tr>
            @endforeach 
    </tbody>
</table>  
<div class="page-break"></div>
@endforeach
@endforeach      
</body>
</html>