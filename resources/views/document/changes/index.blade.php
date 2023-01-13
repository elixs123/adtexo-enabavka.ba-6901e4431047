<div class="modal-body">
    <p><strong class="text-uppercase">{{ $title }}</strong>@if(!is_null($product)) - <span class="text-primary">{{ $product->name }}</span>@endif</p>
    <hr>
    <div class="table-responsive-lg">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Datum</th>
                    <th>Osoba</th>
                    <th>Tip</th>
                    <th>Stara vrijednost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($changes as $change)
                <tr>
                    <td>{{ $change->created_at->format('d.m.Y H:i') }}</td>
                    <td>{{ is_null($change->rChangedBy->rPerson) ? $change->rChangedBy->email : $change->rChangedBy->rPerson->name }}</td>
                    <td>{{ $change->type_desc }}</td>
                    <td>{{ $change->value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.close') }}</button>
</div>
