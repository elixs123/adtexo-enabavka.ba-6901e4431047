<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Šifra kupca</th>@php $grand_total = [];  @endphp
                            <th>Kupac</th>
                            <th>Dozvoljeni limit</th>
                            <th>Blokiran</th>
                            @foreach(trans('demand.vars.kpi') as $overdue_key => $overdue)
                            <th class="text-right">{{ $overdue }}</th>@php $grand_total[$overdue_key] = 0;  @endphp
                            @endforeach
                            <th class="text-right">Total</th>@php $grand_total['total'] = 0;  @endphp
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fund_source_demands as $demand)
                        <tr>
                            <td>{{ $demand['code'] }}</td>@php $total = 0;  @endphp
                            <td>{{ $demand['name'] }}</td>
                            <td>{{ format_price($demand['allowed_limit_outside'], 2) }} {{ $currency }}</td>
                            <td class="text-center {{ ($demand['status'] == 'blocked') ? 'bg-danger text-white' : '' }}">{{ ($demand['status'] == 'blocked') ? 'BLOKIRAN' : '' }}</td>
                            @foreach(trans('demand.vars.kpi') as $overdue_key => $overdue)
                            <td class="text-right">{{ format_price($value = array_get($demand, $overdue_key, 0), 2) }} {{ $currency }}</td>@php $grand_total[$overdue_key] += $value; $total += $value; @endphp
                            @endforeach
                            <td class="text-right">{{ format_price($total, 2) }} {{ $currency }}</td>@php $grand_total['total'] += $total; @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light">
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>Ukupno:</th>
                            @foreach(trans('demand.vars.kpi') as $overdue_key => $overdue)
                            <th class="text-right">{{ format_price($grand_total[$overdue_key], 2) }} {{ $currency }}</th>
                            @endforeach
                            <th class="text-right">{{ format_price($grand_total['total'], 2) }} {{ $currency }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
