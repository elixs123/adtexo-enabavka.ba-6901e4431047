<script>
    $(document).ready(function () {
        @can('edit-document')
        $('input[data-select-all]').change(function() {
            var checked = $(this).is(':checked');
            $('input[data-select-' + $(this).data('status') + ']').not(':disabled').each(function() {
                if ($(this).parent().parent().parent().is(':visible')) {
                    $(this).prop('checked', checked).trigger('change');
                } else {
                    $(this).prop('checked', false).trigger('change');
                }
            });
        });
            @if($status_actions)
        $('button[data-document-status], button[data-client-status]').click(function (e) {
            // Prevent default
            e.preventDefault();
            // Status
            $(this).parent().next('input[name="s"]').val($(this).data('status'));
            // Loader: On
            loader_on();
            // Request
            var $form = $(this).parent().parent();
            var type = $(this).data('type');
            HttpRequest.post($form.attr('action') + '?' + $form.serialize(), {}, function (response) {
                // Loader: Off
                loader_off();
                @if(request('status') == 'express_post')
                // PDF
                var d = [];
                $.each(response.items, function(key, uid) {
                    d.push('d[]=' + uid.replace('documents', ''))
                });
                if (response.items.length) {
                    $('button[data-document-pdf]').data('href', $('button[data-document-pdf]').data('url') + '?' + d.join('&')).trigger('click');
                }
                @endif
                // Remove
                $.each(response.items, function(key, uid) {
                    $('#' + type + uid).remove();
                });
                // Check
                if ($('tr[data-tr-status="' + $form.data('status') + '"]').length === 0) {
                    $('form.ajax-form-' + type + '-' + $form.data('status')).remove();
                    $('div[data-no-results="' + $form.data('status') + '"]').show();
                }
                // Failed
                if (response.failed && response.failed.length) {
                    var message = response.failed.join(' ');
                    notify({
                        type: 'error',
                        message: message,
                    });
                }
                // Redirect
                if (response.redirect) {
                    document.location = response.redirect;
                }
            });
        });
            @endif
        @endcan
    });
</script>
