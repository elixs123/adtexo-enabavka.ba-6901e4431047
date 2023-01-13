<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Pregled</th>@php $grand_total = [];  @endphp
                            @foreach(trans('demand.vars.kpi') as $overdue_key => $overdue)
                            <th class="text-right">{{ $overdue }}</th>@php $grand_total[$overdue_key] = 0;  @endphp
                            @endforeach
                            <th class="text-right">Total</th>@php $grand_total['total'] = 0;  @endphp
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demands_per_fund_source as $fund_source_key => $demands)
                        <tr>
                            <td>{{ array_get($fund_sources, $fund_source_key, 'Nepoznato') }}</td>@php $total = 0;  @endphp
                            @foreach(trans('demand.vars.kpi') as $overdue_key => $overdue)
                            <td class="text-right">{{ format_price($value = array_get($demands, $overdue_key, 0), 2) }} {{ $currency }}</td>@php $grand_total[$overdue_key] += $value; $total += $value; @endphp
                            @endforeach
                            <td class="text-right">{{ format_price($total, 2) }} {{ $currency }}</td>@php $grand_total['total'] += $total; @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light">
                        <tr>
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
