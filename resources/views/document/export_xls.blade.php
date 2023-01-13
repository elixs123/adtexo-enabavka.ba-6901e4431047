<table>
        <thead>
            <tr>
                <th>Broj</th>
                <th>Datum</th>
                <th>Tip</th>
                <th>Klijent</th>
                <th>Naziv / Ime i prezime</th>
                <th>Email</th>
                <th>Adresa</th>
                <th>Grad</th>
                <th>Poštanski broj</th>
                <th>Država</th>
                <th>Telefon</th>
                <th>Proizvod</th>
                <th>Cijena proizvoda</th>
                <th>Količina</th>
                <th>Tip proizvoda</th>
                <th>Kategorija</th>
                <th>Potkategorija</th>
                <th>Država</th>
                <th>Status</th>
                <th>Iznos proizvoda</th>
                <th>Iznos narudžbe</th>
                <th>Korisnik</th>
                <th>Uloga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $id => $item) @php $item_total = $item->useMpcPrice() ? getPriceWithoutVat($item->total_discounted, $item->tax_rate) : $item->subtotal_discounted; @endphp
			@foreach($item->rDocumentProduct as $product)
                @include('document._export_xls_item', ['product_type' => 'regular', 'item_total' => $item_total])
			@endforeach
            @foreach($item->rDocumentGratisProducts as $product)
                @include('document._export_xls_item', ['product_type' => 'gratis', 'item_total' => $item_total])
			@endforeach
            @endforeach
    </tbody>
</table>
