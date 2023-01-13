const messaging = firebase.messaging();

var firebaseToken = '';

messaging.usePublicVapidKey("BLLdTVLe0ywnmlQkQX8L_hZ-zRRTJx3rrlTDTZaNvEAs2AwuibUhH55xbRpQuQTfmZYwdoHB3wfsA-6b1ujJ6BA");

messaging.onTokenRefresh(() => {
    messaging.getToken().then((refreshedToken) => {
        consoleLog('Token refreshed.');
        // Indicate that the new Instance ID token has not yet been sent to the app server.
        setTokenSentToServer(false);
        // Send Instance ID token to app server.
        sendTokenToServer(refreshedToken);
    }).catch((err) => {
        console.error('Unable to retrieve refreshed token ', err);
    });
});

messaging.onMessage((payload) => {
    consoleLog('Message received. ');
    consoleLog(payload);
    add_notification(payload.data);
    const {title, ...options} = payload.notification;
    navigator.serviceWorker.ready.then(registration => {
        registration.showNotification(title, options);
    });
});

function resetUI() {
    showToken('loading...');
    // Get Instance ID token. Initially this makes a network call, once retrieved subsequent calls to getToken will return from cache.
    messaging.getToken().then((currentToken) => {
        if (currentToken) {
            sendTokenToServer(currentToken);
            updateUIForPushEnabled(currentToken);
        } else {
            // Show permission request.
            consoleLog('No Instance ID token available. Request permission to generate one.');
            // Show permission UI.
            setTokenSentToServer(false);
            requestPermission();
        }
    }).catch((err) => {
        console.error('An error occurred while retrieving token. ', err);
        setTokenSentToServer(false);
    });
}

function showToken(currentToken) {
    consoleLog(currentToken);
}

function sendTokenToServer(currentToken) {
    firebaseToken = currentToken;
    if (!isTokenSentToServer()) {
        consoleLog('Sending token to server...');
        
        $.ajax({
            method: 'POST',
            url: '/firebase/token',
            dataType : 'json',
            data : 'token=' + currentToken,
            success: function (response) {
                setTokenSentToServer(true);
                
                notify(response.notification);
    
                consoleLog(response.notification.message);
            },
            error: function (response) {
                console.error(response);
            }
        });
    } else {
        consoleLog('Token already sent to server so won\'t send it again ' +
            'unless it changes');
    }
}

function isTokenSentToServer() {
    return window.localStorage.getItem('sentToServer') === '1';
}

function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? '1' : '0');
}

function requestPermission() {
    consoleLog('Requesting permission...');
    
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            consoleLog('Notification permission granted.');
            // In many cases once an app has been granted notification permission, it should update its UI reflecting this.
            resetUI();
        } else {
            consoleLog('Unable to get permission to notify.');
        }
    });
}

function denyPermission() {
    Notification.permission = 'denied';
    consoleLog('Notification permission denied.');
}

function deleteToken(href) {
    loader_on();
    
    messaging.getToken().then((currentToken) => {
        messaging.deleteToken(currentToken).then(() => {
            $.ajax({
                method: 'POST',
                url: '/firebase/token/delete',
                dataType : 'json',
                data : 'token=' + currentToken,
                success: function (response) {
                    consoleLog('Token deleted.');
    
                    setTokenSentToServer(false);
    
                    document.location = href;
                    //resetUI();
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }).catch((err) => {
            console.error('Unable to delete token. ', err);
        });
        // [END delete_token]
    }).catch((err) => {
        console.error('Error retrieving Instance ID token. ', err);
    });
}

function updateUIForPushEnabled(currentToken) {
    showToken(currentToken);
}

function updateUIForPushPermissionRequired() {
    //
}

function consoleLog(log) {
    console.log(log);
}
