@if(config('services.firebase.enabled'))
    <script type="text/javascript" src="https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/firebasejs/6.3.3/firebase-messaging.js"></script>
    <script type="text/javascript">
        const firebaseConfig = {!! json_encode(config('services.firebase.config'), JSON_UNESCAPED_SLASHES) !!}
        firebase.initializeApp(firebaseConfig);
    </script>
    <script type="text/javascript">
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js?v=20191017', {scope: '/firebase-cloud-messaging-push-scope'}).then(function (registration) {
                //console.log('Firebase ServiceWorker registration successful with scope: ', registration.scope);
                const messaging = firebase.messaging();
                messaging.useServiceWorker(registration);
                resetUI();
            }).catch(function (err) {
                console.log('Firebase ServiceWorker registration failed: ', err);
            });
        }
    </script>
    <script src="{{ asset('assets/js/firebase.js').assetVersion() }}" type="text/javascript"></script>
@endif