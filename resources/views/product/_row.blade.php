<tr id="row{{ $item->uid }}">
    <td class="product-img"><img width="60" src="{{ $item->photo_small }}" alt=""></td>
    <td class="product-name">{{ $item->code }}</td>
    <td class="product-name">{{ $item->name }}</td>
    <td class="product-category">{{ $item->category_name }}</td>
    <td class="product-price">
		<div class="bullet bullet-sm bullet-{{ $item->status == 'active' ? 'success' : 'secondary'  }}"></div>
	</td>
    @if($item->relationLoaded('rProductQuantities'))
    <td class="product-price">{{ $item->relationLoaded('rProductQuantities') ? $item->rProductQuantities->sum('qty') : 0 }}</td>
    @endif
    <td class="td-actions">
        @can('edit-product')
    <span class="action-edit"><a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('product.edit', [$item->id]) }}?product_id={{ $item->product_id }}" title="{{ trans('product.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a></span>
        <span class="action-edit"><a data-toggle="modal" data-target="#form-modal1" data-href="product/translate/{{ $item->id }}/{{ $item->lang_id }}" title="{{ trans('product.actions.translate') }}" data-tooltip><i class="feather icon-file-text"></i></a></span>
        @endcan
        @can('remove-product')@endcan
        <span class="action-delete">
        <a class="delete-link" data-id="{{ $item->uid }}" data-action="{{ url('/product/' . $item->translation_id) }}" data-text="{{ trans('skeleton.delete_msg') }}"><i data-tooltip {{ trans('product.actions.translate') }} class="feather icon-trash"></i></a>
        </span>
    </td>
</tr>
