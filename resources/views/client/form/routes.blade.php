@if((($method == 'post') && can('create-route')) || (($method == 'put') && can('edit-route')))
<div class="row">
    <div class="col-12">
        @if(userIsSalesman())
        {!! Form::hidden('salesman_person_id', auth()->user()->rPerson->id) !!}
            @else
        <div class="select2ajax">
            {!! VuexyAdmin::selectTwoAjax('salesman_person_id', is_null($item->salesman_person_id) ? [] : [$item->salesman_person_id => $item->rSalesmanPerson->name], null, ['data-plugin-options' => '{"placeholder": "'.trans('client.placeholders.person').'", "ajax": {"url": "'.route('person.search', ['t' => 'salesman_person']).'", "type": "get"}}', 'id' => 'form-control-salesman_person_id-'.$item->getTable(), 'data-route-url' => route('route.rank'), 'required'], $person_types['salesman_person_id']['value']) !!}
            @can('create-person')
            <div class="input-group-append">
                <a href="{{ route('person.create', ['type_id' => 'salesman_person', 'callback' => 'clientPersonAssign']) }}" class="btn btn-success" data-toggle="modal" data-target="#form-modal2" title="{{ trans('person.actions.create') }}" data-tooltip><span class="feather icon-plus"></span></a>
            </div>
            @endcan
        </div>
        @endif
    </div>
</div>
<div class="text-center hidden" data-routes-loader>
    <div class="spinner-border text-primary mb-1 text-center" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div data-routes-holder>
    @if(!is_null($routes))
    @include('route.rank', ['readonly' => false])
    @endif
</div>
@endif
