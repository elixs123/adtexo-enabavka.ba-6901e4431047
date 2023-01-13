<div class="row">
    <div class="col-12">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>{{ trans('client.data.categories') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td><strong>{{ $category->translation->name }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="no-results @if($categories->count() == 0){{ 'show' }}@endif">
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>
