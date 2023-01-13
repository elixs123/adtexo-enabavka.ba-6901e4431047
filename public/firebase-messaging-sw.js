importScripts('https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/6.3.3/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyBfMylXLUWVcBybZvzA-Q5WBJBGnNyKU7M",
    authDomain: "petrusicba.firebaseapp.com",
    databaseURL: "https://petrusicba.firebaseio.com",
    projectId: "petrusicba",
    storageBucket: "petrusicba.appspot.com",
    messagingSenderId: "870551583052",
    appId: "1:870551583052:web:efd79971c907761c"
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const {title, ...options} = payload.notification;
    
    if (typeof add_notification === "function") {
        add_notification(payload.data);
    }
    
    return self.registration.showNotification(title, options);
});
