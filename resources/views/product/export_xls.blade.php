<table>
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                @foreach($stocks as $stock_id => $stock_name)
                <th>{{ trans('product.data.stock') }}</th>
                @endforeach
                @foreach(['bih' => 'BiH', 'srb' => 'Srbija'] as $lang_id => $lang)
                <th>{{ $lang }}</th>
                <th>{{ $lang }}</th>
                @endforeach
            </tr>
            <tr>
                <th>{{ trans('product.data.code') }}</th>
                <th>{{ trans('product.data.barcode') }}</th>
                <th>{{ trans('product.data.name') }}</th>
                <th>{{ trans('product.data.category') }}</th>				
                <th>{{ trans('product.data.category') }}</th>
                <th>{{ trans('product.data.brand') }}</th>
                @foreach($stocks as $stock_id => $stock_name)
                <th>{{ $stock_name }}</th>
                @endforeach
                @foreach(['bih' => 'BiH', 'srb' => 'Srbija'] as $lang_id => $lang)
                <th>VPC</th>
                <th>MPC</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($items as $id => $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->rFatherCategory->name }}</td>				
                <td>{{ $item->category_name }}</td>
                <td>{{ $item->brand_name }}</td>@php $ps = $item->rProductQuantities->groupBy('stock_id')->map(function($items) {return $items->sum('qty'); })->toArray(); @endphp
                @foreach($stocks as $stock_id => $stock_name)
                <td style="text-align: right;">{{ isset($ps[$stock_id]) ? $ps[$stock_id] : '' }}</td>
                @endforeach
                @foreach(['bih' => 'BiH', 'srb' => 'Srbija'] as $lang_id => $lang) <?php $price = $item->rProductPrices->where('country_id', $lang_id)->first(); ?>
                <td style="text-align: right;">{{ format_price(isset($price->vpc) ? $price->vpc : 0) }}</td>
                <td style="text-align: right;">{{ format_price(isset($price->mpc) ? $price->mpc : 0) }}</td>
                @endforeach
            </tr>
            @endforeach
    </tbody>
</table>
