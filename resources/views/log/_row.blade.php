<tr id="row{{ $item->uid }}">
    <td>{{ date('d.m.Y. H:i', strtotime($item->created_at)) }}</td>
    <td>{{ $item->loggable_id }}</td>
    <td>{{ $item->loggable_type }}</td>
    <td>{{ $item->body }}</td>
</tr>
