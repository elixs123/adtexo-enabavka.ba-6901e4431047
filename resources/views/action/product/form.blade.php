{!! Form::open(['url' => $form_url, 'method' => $method, 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable()), 'data-callback' => request('callback')]) !!}
    {!! Form::hidden('user_id') !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong> <span class="badge badge-info text-uppercase">{{ $action->rType->name }}</span></p>
        <hr>
        <div class="row">
            <div class="col-6">
                {!! VuexyAdmin::text('stock_type_value', trans('action.vars.stock_types')[$action->stock_type], ['disabled'], trans('action.data.stock_type')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::text('action_qty', $action->qty, ['maxlength' => 10, 'required'], trans('action.data.stock')) !!}
            </div>
        </div>
        <hr>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="action-tab" data-toggle="tab" href="#action-tab-content" role="tab" aria-selected="true">{{ trans('action.vars.tabs.action') }}</a>
            </li>
            @if($action->isGratis())
            <li class="nav-item">
                <a class="nav-link" id="gratis-tab" data-toggle="tab" href="#gratis-tab-content" role="tab" aria-selected="false">{{ trans('action.vars.tabs.gratis') }}</a>
            </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="action-tab-content" role="tabpanel">
                @include('action.product.form.action_products')
            </div>
            @if($action->isGratis())
            <div class="tab-pane" id="gratis-tab-content" role="tabpanel">
                @include('action.product.form.action_gratis')
            </div>
            @endif
        </div>
        <div class="row">
            @if($action->stock_type == 'unlimited' && false)
            <div class="col-12">
                <hr>
                {!! VuexyAdmin::checkbox('change_qty', 1, empty($action_products), [], 'Promjeni zalihu akcije') !!}
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('skeleton.actions.cancel') }}</button>
        <button class="btn btn-success" type="submit">{{ trans('skeleton.actions.submit') }}</button>
    </div>
{!! Form::close() !!}

<script>
    $(document).ready(function () {
        App.validate('.{{ $form_class }}', {
            submitHandler: function(form) {
                AjaxForm.init('.{{ $form_class }}');
            }
        });
        autoNumericInit($('.{{ $form_class }}'), {});
    });
</script>

@yield('modal-scripts')
