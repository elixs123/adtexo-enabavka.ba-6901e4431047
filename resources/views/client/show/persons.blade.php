<div class="row">
    @foreach($person_types as $name => $type)
        @if($name != 'salesman_person_id')
    <div class="col-12 col-md-12">
        <div class="select2ajax">
            {!! VuexyAdmin::locked($name, $item->$name, is_null($item->$name) ? '-' : $item->{$type['relation']}->name, [], $type['value']) !!}
        </div>
    </div>
        @endif
    @endforeach
</div>
