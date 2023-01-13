<tr>
    <td>{{ $item->id }}</td>
    <td>{{ date('d.m.Y.', strtotime($item->date_of_order)) }}</td>
    <td>{{  $item->rType->name ?? '-' }}</td>
    <td>{{ is_null($item->rClient) ? '-' : $item->rClient->full_name }}</td>
    <td>{{ array_get($item->shipping_data, 'name', '-') }}</td>
    <td>{{ array_get($item->shipping_data, 'email', '-') }}</td>
    <td>{{ array_get($item->shipping_data, 'address', '-') }}</td>
    <td>{{ array_get($item->shipping_data, 'city', '-') }}</td>
    <td>{{ array_get($item->shipping_data, 'postal_code', '-') }}</td>
    <td>{{ array_get($item->shipping_data, 'country', '-') }}</td>
    <td>{{ array_get($item->shipping_data, 'phone', '-') }}</td>
    <td>{{ $product->name }}</td>
    <td>{{ format_price($product->fiscal_net_discounted_price) }}</td>
    <td>{{ $product->qty }}</td>
    <td>{{ $product_type }}</td>
    <td>{{ $product->rProduct->category->rFatherCategory->name ?? '-'  }}</td>
    <td>{{ $product->rProduct->rCategory->name ?? '-'  }}</td>
    <td>{{ is_null($item->rClient) ? '-' : $item->rClient->rCountry->name }}</td>
    <td>{{  $item->rStatus->name }} </td>
    <td>{{ format_price($product->fiscal_net_discounted_price * $product->qty, 2) }}</td>
    <td>{{ format_price($item_total, 2) }}</td>
    <td>{{ is_null($item->rCreatedBy) ? '-' : (($item->rCreatedBy->rPerson->id == 0) ? $item->rCreatedBy->email : $item->rCreatedBy->rPerson->name ) }}</td>
    <td>{{ is_null($item->rCreatedBy) ? '-' : $item->rCreatedBy->roles->first()->name }}</td>
</tr>
