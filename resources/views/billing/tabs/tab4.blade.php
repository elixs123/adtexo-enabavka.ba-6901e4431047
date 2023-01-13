<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Dokument</th>
                            <th>KIF</th>
                            @foreach(trans('billing.vars.kpi') as $overdue)
                            <th class="text-right">{{ $overdue }}</th>
                            @endforeach
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>@php $grand_total = []; @endphp
                        @foreach($billings_per_document as $document_id => $document_data)
                            @foreach($document_data as $data)
                        <tr>
                            <td>{{ $document_id }}</td>
                            <td>{{ $data['kif'] }}</td>@php $total = 0; @endphp
                            @foreach(trans('billing.vars.kpi') as $overdue_key => $overdue) @php $value = ($data['overdue_key'] == $overdue_key) ? $data['payed'] : 0; $total += $value; @endphp
                            <td class="text-right">{{ ($value <> 0) ? format_price($value, 2).' '.$currency : '' }}</td>@php $grand_total[$overdue_key] = ($grand_total[$overdue_key] ?? 0) + $value; @endphp
                            @endforeach
                            <td class="text-right">{{ ($total <> 0) ? format_price($total, 2).' '.$currency : '' }}</td>
                        </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light">
                        <th>&nbsp;</th>
                        <th>Ukupno</th>
                        @foreach(trans('billing.vars.kpi') as $overdue_key => $overdue)
                       <th class="text-right">{{ format_price($grand_total[$overdue_key] ?? 0, 2) }} {{ $currency }}</th>
                        @endforeach
                       <th class="text-right">{{ format_price(array_sum($grand_total), 2) }} {{ $currency }}</th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
