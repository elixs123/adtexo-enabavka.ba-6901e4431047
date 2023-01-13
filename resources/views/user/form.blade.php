{!! Form::model($item, ['url' => $form_url, 'method' => $method, 'files' => true, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-'.$item->getTable())]) !!}
    @include('partials.alert_box')
    <div class="modal-body">
        <p><strong class="text-uppercase">{{ $form_title }}</strong></p>
        <hr>
        <div class="row">
            <div class="col-12">
                {!! VuexyAdmin::email('email', null, ['maxlength' => 64, 'required'], trans('user.data.email')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::password('password', ['maxlength' => 32, 'required' => is_null($item->email)], trans('user.data.password')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::password('password_confirmation', ['maxlength' => 32, 'required' => is_null($item->email)], trans('user.data.password_confirmation')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::selectTwo('status', get_codebook_opts('status')->pluck('name', 'code')->toArray(), null, ['data-plugin-options' => '{"minimumResultsForSearch": -1}', 'id' => 'form-control-status-'.$item->getTable(), 'required'], trans('skeleton.data.status')) !!}
            </div>
            <div class="col-6">
                {!! VuexyAdmin::selectTwo('role_id', $roles->pluck('label', 'id')->toArray(), isset($current_user_roles) ? $current_user_roles->first()->id : null, ['data-plugin-options' => '{}', 'required'], trans('user.data.role')) !!}
            </div>
            <div class="col-12">
                {!! VuexyAdmin::file('photo', ['path' => config('picture.user_path').'/medium_'], trans('user.data.photo'), trans('skeleton.allowed_extensions', ['ext' => 'JPG'])) !!}
            </div>
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
        select2init($('.{{ $form_class }}'), {
            dropdownParent: $('.{{ $form_class }}').parent(),
        });
    });
</script>
