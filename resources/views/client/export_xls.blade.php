<table>
        <thead>
            <tr>
                <th>ID</th>
                @foreach($columns as $column)
                <th>{{ $column }}</th>
                @endforeach
                <th>Rute</th>
                <th>{{ trans('client.data.responsible_person_id') }}</th>
                <th>{{ trans('client.data.payment_person_id') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $id => $item)
                @foreach($item->rRoutes as $route)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->rType->name }}</td>
                <td>{{ $item->jib }}</td>
                <td>{{ $item->pib }}</td>
                <td>{{ $item->location_code }}</td>
                <td>{{ $item->location_name }}</td>
                <td>{{ $item->location_type_id ? $item->rLocationType->name : '' }}</td>
                <td>{{ $item->category_id ? $item->rCategory->name : '' }}</td>
                <td>{{ $item->address }}</td>
                <td>{{ $item->city }}</td>
                <td>{{ $item->postal_code }}</td>
                <td>{{ $item->rCountry->name }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->note }}</td>
                <td>{{ $item->rPaymentPeriod->name }}</td>
                <td>{{ $item->rPaymentType->name }}</td>
                <td>{{ $item->payment_discount }}</td>
                <td>{{ $item->discount_value1 }}</td>
                <td>{{ $item->stock_id ? $item->rStock->name : '' }}</td>
                <td>@if(!is_null($item->rSalesmanPerson)) {{ $item->rSalesmanPerson->name }} @endif</td>
                <td>@if(!is_null($item->rSupervisorPerson)) {{ $item->rSupervisorPerson->name }} @endif</td>
                <td>
                    W{{ $route->week }}<span style="text-transform: uppercase;">{{ isset(trans('route.vars.days')[$route->day]) ? substr(trans('route.vars.days')[$route->day], 0, 3) : '' }}</span><br>
                </td>
                <td>
                    @if(!is_null($item->rResponsiblePerson))
                    {{ $item->rResponsiblePerson->name }} / {{ $item->rResponsiblePerson->email }} / {{ $item->rResponsiblePerson->phone }}
                    @endif
                </td>
                <td>
                    @if(!is_null($item->rPaymentPerson))
                    {{ $item->rPaymentPerson->name }} / {{ $item->rPaymentPerson->email }} / {{ $item->rPaymentPerson->phone }}
                    @endif
                </td>
            </tr>
                @endforeach
            @endforeach
    </tbody>
</table>
