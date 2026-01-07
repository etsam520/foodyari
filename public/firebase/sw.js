try {
    importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
    importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');
} catch (e) {
    console.error('Error loading Firebase scripts:', e);
}

firebaseConfig = {
        apiKey: "AIzaSyDX-2US5QR3lfYeO5XSh-3yLQ3xHutIUoo",
        authDomain: "hellonofi-933e4.firebaseapp.com",
        projectId: "hellonofi-933e4",
        storageBucket: "hellonofi-933e4.appspot.com",
        messagingSenderId: "897535676579",
        appId: "1:897535676579:web:9a06491f258626be89ef16",
        measurementId: "G-H9SLHBL835"
    };
    
//     try {
//         const app = firebase.initializeApp(firebaseConfig);
//         const messaging = firebase.messaging();

//         messaging.onMessage((payload) => {
//             console.log('Message received. ', JSON.stringify(payload));
//         });
//     } catch (e) {
//         console.error('Error initializing Firebase:', e);
//     }
// console.log("initialized");
if (firebase && firebase.messaging) {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    console.log(messaging.notification)

    // Handle push notifications
    self.addEventListener('push', event => {
        const data = event.data.json();
        const title = data.notification.title;
        const options = {
            body: data.notification.body,
            icon: data.notification.icon
        };
        event.waitUntil(self.registration.showNotification(title, options));
    });

    // Handle notification click event
    self.addEventListener('notificationclick', event => {
        event.notification.close();
        event.waitUntil(clients.openWindow(event.notification.data.url));
    });
} else {
    console.error('Firebase or Firebase Messaging not available.');
}

