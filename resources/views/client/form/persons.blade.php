<div class="row">
    @foreach($person_types as $name => $type)
        @if($name != 'salesman_person_id')
    <div class="col-12 col-md-12">
        <div class="select2ajax">
            {!! VuexyAdmin::selectTwoAjax($name, is_null($item->$name) ? [] : [$item->$name => $item->{$type['relation']}->name], null, ['data-plugin-options' => '{"placeholder": "'.trans('client.placeholders.person').'", "ajax": {"url": "'.route('person.search', ['t' => ($type['type'] == 'responsible_person') ? 'responsible_person.sales_agent_person' : $type['type']]).'", "type": "get"}}', 'id' => 'form-control-'.$name.'-'.$item->getTable(), 'required' => ((in_array($name, ['responsible_person_id', 'client_person_id'])) || ((is_null($item->type_id) || ($item->type_id == 'business_client')) && ($name == 'payment_person_id')))], $type['value']) !!}
            @can('create-person')
            <div class="input-group-append">
                <a href="{{ route('person.create', ['type_id' => $type['type'], 'callback' => 'clientPersonAssign']) }}" class="btn btn-success" data-toggle="modal" data-target="#form-modal2" title="{{ trans('person.actions.create') }}" data-tooltip><span class="feather icon-plus"></span></a>
            </div>
            @endcan
        </div>
    </div>
        @endif
    @endforeach
</div>
