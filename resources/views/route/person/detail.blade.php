<div class="modal-body">
    <p><strong class="text-uppercase"><span class="text-primary">{{ $item->name }}</span> - <span class="text-info">{{ $week }}. {{ trans('route.vars.days')[$day] }}</span>: {{ trans('route.title') }}</strong></p>
    <hr>
    {!! Form::open(['url' => route('route.person.update', [$item->id]), 'method' => 'put', 'files' => false, 'autocomplete' => 'false', 'class' => 'row ajax-form-person-route']) !!}
        {!! Form::hidden('week_id', $week) !!}
        {!! Form::hidden('day_id', $day) !!}
        <div class="col-12">
            @include('partials.alert_box')
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>{{ trans('route.data.client_id') }}</th>
                        <th>{{ trans('route.data.rank') }}</th>
                        <th class="text-right">{{ trans('skeleton.data.actions') }}</th>
                    </tr>
                </thead>
                <tbody data-person-routes>
                    @foreach($routes as $route)
                    <tr id="row{{ $route->uid }}">
                        <td>
                            <span class="feather icon-move sortable-handle"></span> {{ $route->rClient->full_name }}
                            {!! Form::hidden('ranks[]', $route->client_id) !!}
                        </td>
                        <td><span class="badge badge-info" data-route-rank>{{ $route->rank }}</span></td>
                        <td class="td-actions">
                            @if(can('edit-route'))
                            <a data-action="{{ route('route.destroy', [$route->id, 'week' => $week, 'day' => $day]) }}" class="delete-link" title="{{ trans('route.actions.delete') }}" data-toggle="tooltip" data-text="{{ trans('skeleton.delete_msg') }}" data-id="{{ $route->uid }}" data-callback="personClientRouteDeleted"><span class="feather icon-trash-2"></span></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12 text-right">
            <button class="btn btn-success hidden" type="submit" data-person-route-nav>{{ trans('skeleton.actions.save') }}</button>
        </div>
    {!! Form::close() !!}
    @if(can('create-route'))
    <hr>
    <p><strong class="text-uppercase">{{ trans('route.actions.assign') }} <span class="text-info">{{ $week }}. {{ trans('route.vars.days')[$day] }}</span></strong></p>
    {!! Form::open(['url' => route('route.person.assign', [$item->id]), 'method' => 'put', 'files' => false, 'autocomplete' => 'false', 'class' => 'row ajax-form-person-route-assign']) !!}
        {!! Form::hidden('week_id', $week) !!}
        {!! Form::hidden('day_id', $day) !!}
        <div class="col-12">
            @include('partials.alert_box')
            {!! VuexyAdmin::selectTwoAjax('client_id', [], null, ['data-plugin-options' => '{"placeholder": "'.trans('route.placeholders.client').'", "ajax": {"url": "'.route('client.search').'", "type": "get"}}', 'id' => 'form-control-client_id-'.$item->getTable()], null) !!}
        </div>
    {!! Form::close() !!}
    @endif
</div>
<div class="modal-footer">
    <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.back') }}</button>
</div>

@if(can('create-route'))
<script id="person-client-route" type="text/x-custom-template">
    <tr id="row@{{ uid }}">
        <td>
            <span class="feather icon-move sortable-handle"></span> @{{ full_name }}
            <input name="ranks[]" type="hidden" value="@{{ client_id }}">
        </td>
        <td><span class="badge badge-info" data-route-rank>@{{ rank }}</span></td>
        <td class="td-actions">
            @if(can('edit-route'))
            <a data-action="@{{ action }}" class="delete-link" title="{{ trans('route.actions.delete') }}" data-toggle="tooltip" data-text="{{ trans('skeleton.delete_msg') }}" data-id="@{{ uid }}" data-callback="personClientRouteDeleted"><span class="feather icon-trash-2"></span></a>
            @endif
        </td>
    </tr>
</script>
@endif

<script>
    week_id = {{ $week }};
    day_id = '{{ $day }}';
    @if(can('create-route'))
    var clients = {{ json_encode($routes->pluck('client_id')->toArray()) }};
    function personClientRouteDeleted(response) {
        // Clients
        var index = clients.indexOf(response.data.client_id);
        if (index > -1) {
            clients.splice(index, 1);
        }
        // Route html
        route_html = response.route.td;
    }
    @endif
    $(document).ready(function () {
        App.tooltip();
        $('[data-person-routes]').sortable({
            group: 'data-person-routes',
            handle: ".sortable-handle",
            animation: 150,
            ghostClass: 'bg-light-badge',
            onSort: function (evt) {
                $('[data-person-route-nav]').removeClass('hidden');
            }
        });
        $('form.ajax-form-person-route').submit(function (e) {
            // Prevent default.
            e.preventDefault();
            // Parameters
            var $form = $(this);
            // Loader: Off
            loader_on();
            // Ajax
            $.ajax({
                method: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType : 'json',
                success: function (response) {
                    // Loader: Off
                    loader_off();
                    // Rank
                    $('[data-route-rank]').each(function (key, value) {
                        $(this).text(key + 1);
                    });
                    // Notification
                    notify(response.notification);
                    // Navigation
                    $('[data-person-route-nav]').addClass('hidden');
                },
                error: function (response) {
                    // Loader: Off
                    loader_off();
                    // Render error response
                    AjaxForm.renderErrors(response, $form);
                }
            });
        });
        @if(can('create-route'))
        var rank = {{ $routes->max('rank') }} + 1;
        select2ajax($('.{{ $form_class = 'ajax-form-person-route-assign' }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
            ajax: {
                data: function (params) {
                    return {
                        q: params.term,
                        e: clients.join('.')
                    };
                },
                cache: false
            }
        });
        $('select#form-control-client_id-persons').change(function (e) {
            // Select
            var $select = $(this);
            if (!$select.val()) {
                return;
            }
            // Parameters
            var $form = $('.{{ $form_class }}');
            var form = document.getElementsByClassName('{{ $form_class }}');
            var data = new FormData(form[0]);
            // Data: Append
            data.append('rank', rank);
            // Loader: Off
            loader_on();
            // Ajax
            $.ajax({
                method: $form.attr('method'),
                url: $form.attr('action'),
                data: data,
                dataType : 'json',
                contentType: false,
                processData: false,
                success: function (response) {
                    // Loader: Off
                    loader_off();
                    // Notification
                    notify(response.notification);
                    // Clients
                    clients.push(parseInt(response.route.client_id));
                    // Rank
                    rank++;
                    // Reset
                    $select.val(null).trigger('change');
                    // Template
                    var template = $('#person-client-route').html();
                    Mustache.parse(template);
                    $('[data-person-routes]').append(Mustache.render(template, response.route));
                    // Route html
                    route_html = response.route.td;
                },
                error: function (response) {
                    // Loader: Off
                    loader_off();
                    // Render error response
                    AjaxForm.renderErrors(response, $form);
                }
            });
        });
        @endif
    });
</script>
