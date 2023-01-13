<div class="form-group form-group-default form-group-default-select2">
    <label>{{ $label }}</label>
    {!! Form::select($name, $values, isset($item->$name) ? $item->$name : null, ['data-placeholder' => $placeholder, 'id' => str_slug($name), 'class' => 'full-width', 'data-init-plugin' => 'select2ajax', 'data-ajax-url' => $url, 'data-ajax-method' => isset($method) ? $method : 'get']); !!}
</div>