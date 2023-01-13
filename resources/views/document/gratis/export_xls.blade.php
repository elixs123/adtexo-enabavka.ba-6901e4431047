<table>
    <thead>
        <tr>
            <th>{{ trans('document.data.document_id') }}</th>
            <th>{{ trans('product.data.barcode') }}</th>
            <th>{{ trans('product.data.name') }}</th>
            <th>MPC</th>
            <th>VPC</th>
            <th>{{ trans('document.data.qty') }}</th>
        </tr>
    </thead>
    <tbody data-ajax-form-body="documents">
        @foreach($items as $id => $item)
        <tr>
            <td>{{ $item->document_id }}</td>
            <td>{{ $item->barcode }}</td>
            <td>{{ $item->name }}</td>
            <td class="text-right">{{ format_price($item->mpc, 2) }} {{ $item->rDocument->currency }}</td>
            <td class="text-right">{{ format_price($item->vpc, 2) }} {{ $item->rDocument->currency }}</td>
            <td class="text-center">{{ $item->qty }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
