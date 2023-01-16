<tr>
<td>{{ $item->acClassif }}</td>
    <td>{{ $item->acName }}</td>
    
    @if($item->acType == 'P')
    <td>Primarana</td>
    @elseif($item->acType == 'S')
    <td>Sekundarna</td>
    @else
    <td>Obje</td>
    @endif
    
</tr>

