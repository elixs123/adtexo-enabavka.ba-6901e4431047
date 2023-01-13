<table>
        <thead>
            <tr>
                <th>Šifra/artikl</th>
                <th>Barcode</th>
                <th>Naziv</th>
                <th>MJ</th>
                <th>Kolicina</th>
                <th>Tip proizvoda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $id => $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ strtoupper($item->rUnit->name) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->type }}</td>
            </tr>
            @endforeach
    </tbody>
</table>
