<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Tip uplate</th>
                            <th class="text-right">Za {{ $dates_data['start_date']->format('d.m.Y.') }} - {{ $dates_data['end_date']->format('d.m.Y.') }}</th>
                            <th class="text-right">Za {{ now()->subDay()->format('d.m.Y.') }}</th>
                            @if(not_null($salesman_person))
                            <th class="text-center bg-danger text-white">%</th>
                            <th class="text-right bg-success text-white">Iznos %</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>@php $salesman_person_total = 0;  @endphp
                        @foreach($billings_per_fund_source as $fund_source_key => $billings)
                        <tr>
                            <td>{{ $billings['title'] }}</td>
                            <td class="text-right">{{ format_price($value = $billings['period'], 2) }} {{ $currency }}</td>
                            <td class="text-right">{{ format_price($billings['yesterday'], 2) }} {{ $currency }}</td>
                            @if(not_null($salesman_person))
                            <td class="text-center bg-danger text-white">{{ $percent = array_get($salesman_person->kpi_values, $fund_source_key, 0) }} %</td>
                            <td class="text-right bg-success text-white">{{ format_price($value = calculatePercentValue($percent, $value), 2) }} {{ $currency }}</td>@php $salesman_person_total += $value;  @endphp
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light">
                        <tr>@php $billings_per_fund_source = collect($billings_per_fund_source); @endphp
                            <th>Ukupno:</th>
                            <th class="text-right">{{ format_price($billings_per_fund_source->sum('period'), 2) }} {{ $currency }}</th>
                            <th class="text-right">{{ format_price($billings_per_fund_source->sum('yesterday'), 2) }} {{ $currency }}</th>
                            @if(not_null($salesman_person))
                            <th class="text-center bg-danger text-white">&nbsp;</th>
                            <th class="text-right bg-success text-white">{{ format_price($salesman_person_total, 2) }} {{ $currency }}</th>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
