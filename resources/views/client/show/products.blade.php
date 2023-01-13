<div class="row">
    <div class="col-12">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>{{ trans('client.data.products') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $key => $product)
                <tr>
                    <td>
                        <span>{{ $product['name'] }}</span><br>
                        <strong>{{ $product['code'] }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="no-results @if(empty($products)){{ 'show' }}@endif">
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>
