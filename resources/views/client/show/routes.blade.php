@if(can('view-route'))
<div class="row">
    <div class="col-12">
        {!! VuexyAdmin::locked('salesman_person_id', $item->salesman_person_id, is_null($item->salesman_person_id) ? '-' : $item->rSalesmanPerson->name, [], trans('client.data.salesman_person_id')) !!}
    </div>
</div>
<div class="text-center hidden" data-routes-loader>
    <div class="spinner-border text-primary mb-1 text-center" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div data-routes-holder>
    @if(!is_null($routes))
    @include('route.rank', ['readonly' => true])
    @endif
</div>
@endif
