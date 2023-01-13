<tr id="row{{ $item->uid }}">
    <td>{{ $item->name }}</td>
    <td>{{ $item->code }}</td>
    <td><span class="badge badge-info text-uppercase">{{ $item->type }}</span></td>
    <td>
        @if($item->background_color)
        <span class="badge text-uppercase border" style="background-color: {{ $item->background_color }};min-width: 21px;">&nbsp;</span> {{ $item->background_color }}
            @else
        <span>&nbsp;</span>
        @endif
    </td>
    <td>
        @if($item->color)
        <span class="badge text-uppercase border" style="background-color: {{ $item->color }};min-width: 21px;">&nbsp;</span> {{ $item->color }}
            @else
        <span>&nbsp;</span>
        @endif
    </td>
    @can('edit-codebook')
    <td class="td-actions">
        <a data-toggle="modal" data-target="#form-modal1" data-href="{{ route('code-book.edit', [$item->id, 'type' => $item->type]) }}" title="{{ trans('codebook.actions.edit') }}" data-tooltip><i class="feather icon-edit-1"></i></a>
    </td>
    @endcan
</tr>
