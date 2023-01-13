<div class="row">
    <div class="col-12" data-client-action-search>
        {!! VuexyAdmin::select('actions_search', [], null, ['data-plugin-selectTwoAjax', 'data-plugin-options' => '{"placeholder": "'.trans('action.placeholders.search').'", "ajax": {"url": "'.route('action.search').'", "type": "get"}}', 'id' => 'form-control-actions_search-'.$item->getTable()], [], '') !!}
    </div>
    <div class="col-12">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>{{ trans('client.data.actions') }}</th>
                    <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                </tr>
            </thead>
            <tbody data-client-action-list>
                @foreach($actions_selected as $key => $action)
                <tr>
                    <td>
                        {{ $action['name'] }}
                        <input type="hidden" name="actions[]" value="{{ $action['id'] }}">
                    </td>
                    <td class="td-actions">
                        <a title="{{ trans('client.actions.remove_action') }}" data-tooltip data-client-action-remove="{{ $action['id'] }}"><i class="feather icon-trash-2"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="no-results @if(empty($actions_selected)){{ 'show' }}@endif" data-client-action-no-results>
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
</div>

<script id="client-action-template" type="text/x-custom-template">
    <tr>
        <td>
            @{{ text }}
            <input type="hidden" name="actions[]" value="@{{ id }}">
        </td>
        <td class="td-actions">
            <a title="{{ trans('client.actions.remove_action') }}" data-tooltip data-client-action-remove="@{{ id }}"><i class="feather icon-trash-2"></i></a>
        </td>
    </tr>
</script>
