<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Šifra kupca</th>
                            <th>Kupac</th>
                            @foreach(trans('billing.vars.kpi') as $overdue)
                            <th class="text-right">{{ $overdue }}</th>
                            @endforeach
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>@php $total = []; @endphp
                        @foreach($billings_per_client as $client_code => $client_data)
                        <tr>
                            <td>{{ $client_code }}</td>
                            <td>{{ $client_data['client_name'] }}</td>
                            @foreach(trans('billing.vars.kpi') as $overdue_key => $overdue) @php $value = $client_data['overdue'][$overdue_key] ?? 0; @endphp
                            <td class="text-right">{{ ($value > 0) ? format_price($value, 2).' '.$currency : '' }}</td>@php $total[$overdue_key] = ($total[$overdue_key] ?? 0) + $value; @endphp
                            @endforeach
                            <td class="text-right">{{ format_price($client_total = collect($client_data['overdue'])->sum(), 2) }} {{ $currency }}</td>@php $total['total'] = ($total['total'] ?? 0) + $client_total; @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light">
                        <th>&nbsp;</th>
                        <th>Ukupno</th>
                        @foreach(trans('billing.vars.kpi') as $overdue_key => $overdue)
                       <th class="text-right">{{ format_price($total[$overdue_key] ?? 0, 2) }} {{ $currency }}</th>
                        @endforeach
                       <th class="text-right">{{ format_price($total['total'] ?? 0, 2) }} {{ $currency }}</th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
