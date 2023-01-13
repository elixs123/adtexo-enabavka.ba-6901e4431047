@if(config('broadcasting.connections.pusher.enabled'))
<script type="text/javascript" src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script type="text/javascript">
    Pusher.logToConsole = @if(config('app.env') == 'local'){{ 'false' }}@else{{ 'false' }}@endif;
        @php
            $options = config('broadcasting.connections.pusher.options');
            $options['auth']['headers']['X-CSRF-Token'] = csrf_token();
        @endphp
    var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {!! json_encode($options, JSON_UNESCAPED_SLASHES) !!});
    var auth_hash = '{{ hash('sha1', auth()->id()) }}';
    var pusher_me = null;
    var socket_id = null;
</script>
<script src="{{ asset('assets/js/pusher.js').assetVersion() }}" type="text/javascript"></script>
@endif