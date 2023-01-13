<ul class="nav nav-tabs" role="tablist">
    @foreach($countries as $country_code => $country_name)
    <li class="nav-item">
        <a class="nav-link {{ ($country_code == $query['country']) ? 'active' : '' }}" href="{{ route($route, httpQuery($query, ['country' => $country_code])) }}" data-loader>{{ $country_name }}</a>
    </li>
    @endforeach
</ul>
