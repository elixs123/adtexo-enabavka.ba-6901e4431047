<div class="row">
    <div class="col-12 col-lg-6">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($tabs as $tab_key => $tab)
            <li class="nav-item">
                <a class="nav-link{{ ($tab_key == $tab_active) ? ' active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['tab' => $tab_key, 'status' => null])) }}" data-loader>{{ $tab }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="col-12 col-lg-6">
        {!! Form::open(['url' => route('dashboard'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'form-dates-range']) !!}
        {!! VuexyAdmin::dateRange('start', 'end', $dates_data['start_date'], $dates_data['end_date'], []) !!}
        {!! Form::hidden('tab', $tab_active) !!}
        {!! Form::close() !!}
    </div>
    @if(in_array($tab_active, ['in_warehouse', 'express_post', 'personal_takeover']))
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($status as $status_code => $status_name)
            <li class="nav-item">
                <a class="nav-link{{ ($status_code == $query['status']) ? ' active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['tab' => $tab_active, 'status' => $status_code])) }}" data-loader>{{ $status_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<!-- start: warehouse documents -->
<div class="row">
    <div class="col-12">
        @if($query['status'] == 'for_invoicing')
        @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => 'for_invoicing', 'type' => 'order', 'payment' => 'cash_payment'])
        @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => 'for_invoicing', 'type' => 'order', 'payment' => 'other_payment'])
        @else
        @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => $query['status'], 'type' => 'order', 'warehouse_tab' => $tab_active])
        @endif
    </div>
</div>
<!-- end: warehouse documents -->

@section('script_inline')
    @parent
    @include('homepage.document._script', ['status_actions' => !in_array($query['status'], ['invoiced'])])
    <script>
        $(document).ready(function () {
            $('input[name="start"], input[name="end"]').change(function () {
                loader_on();
                $('form.form-dates-range').submit();
            });
            @if(in_array($query['status'], ['invoiced']))
            var $btn_express_post = $('button[data-document-express-post]');
            $('input[data-select-order-{{ $query['status'] }}]').change(function() {
                var checked = [];
                $('input[data-select-order-{{ $query['status'] }}]').each(function() {
                    if ($(this).is(':checked')) {
                        checked.push('d[]=' + $(this).val())
                    }
                });
    
                $btn_express_post.data('href', $btn_express_post.data('url') + '?' + checked.join('&'));
            });
            @endif
        });
        function warehouseRemoveDocuments(response) {
            var $btn_express_post = $('button[data-document-express-post]');
            
            $.each(response.items, function(key, uid) {
                $('#document' + uid).remove();
            });
    
            var type = 'document';
            if ($('tr[data-tr-status="' + $btn_express_post.data('status') + '"]').length === 0) {
                $('form.ajax-form-' + type + '-' + $btn_express_post.data('status')).remove();
                $('div[data-no-results="' + $btn_express_post.data('status') + '"]').show();
            }
    
            if (response.items.length) {
                $btn_express_post.data('href', $btn_express_post.data('url'));
            }
    
            // Failed
            if (response.failed && response.failed.length) {
                var message = response.failed.join(' ');
                notify({
                    type: 'error',
                    message: message,
                });
            }
        }
    </script>
@endsection
