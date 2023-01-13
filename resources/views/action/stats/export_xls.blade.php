<table>
    <thead>
        <tr>
            <th>{{ $dates['start_date']->format('d.m.Y') }} - {{ $dates['end_date']->format('d.m.Y') }}</th>
            @foreach($actions as $action)
            <th>{{ $action->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($salesmen as $user_id => $person)
            @if($user_id)
        <tr>
            <td>{{ $person }}</td>
            @foreach($actions as $action)
            <td>{!! (isset($documents[$user_id]) && isset($documents[$user_id][$action->id])) ? '<strong>'.$documents[$user_id][$action->id].'</strong>' : '-' !!}</td>
            @endforeach
        </tr>
            @endif
        @endforeach
    </tbody>
</table>
