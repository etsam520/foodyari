import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-messaging.js";

import {setCookie, getCookie} from "./cookie-helper.js";

const CheckFcm = getCookie("My_FCM_Token");
if(CheckFcm == null){

    const firebaseConfig = {
        apiKey: "AIzaSyDX-2US5QR3lfYeO5XSh-3yLQ3xHutIUoo",
        authDomain: "hellonofi-933e4.firebaseapp.com",
        projectId: "hellonofi-933e4",
        storageBucket: "hellonofi-933e4.appspot.com",
        messagingSenderId: "897535676579",
        appId: "1:897535676579:web:9a06491f258626be89ef16",
        measurementId: "G-H9SLHBL835"
    };
    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    async function initializeMessaging() {
        try {
            // await navigator.serviceWorker.register('/firebase-messaging-sw.js');
            const registration = await navigator.serviceWorker.getRegistration('/firebase-messaging-sw.js');

            if (registration) {
                console.log('Service worker is already registered, updating...');
                // Update the existing service worker
                registration.update();
            } else {
                console.log('No service worker found, registering a new one...');
                await navigator.serviceWorker.register('/firebase-messaging-sw.js');
            }

            const currentToken = await getToken(messaging, {
                vapidKey: 'BAHlcT7_oQpraeqYHfTMNSXWIExB_6sq1dhIjhK-xlE_yXgP4ybFDDpnzRy51PChe62bAtsPFwGEzwOcR5vNTI8'
            });

            if (currentToken) {
                setCookie("My_FCM_Token", currentToken, 2);
            } else {
                console.log('No registration token available. Request permission to generate one.');
            }
        } catch (error) {
            console.error('An error occurred while retrieving token:', error);
        }
    }

    // Service Worker event listener for push notifications
    self.addEventListener('push', event => {
        const data = event.data.json();
        const title = data.notification.title;
        const options = {
            body: data.notification.body,
            icon: data.notification.icon
        };
        log('Test log for sms');
        event.waitUntil(self.registration.showNotification(title, options));
    });

    // Firebase messaging onMessage listener
    onMessage(messaging, payload => {
        console.log('Message received:', JSON.stringify(payload));
        // Handle the message payload as needed
    });

    async function checkNotificationPermission() {
        if ('Notification' in window) {
            try {
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    new Notification("Notifications enabled!", {
                        body: "Thank you for enabling notifications.",
                        icon: "path-to-icon.png"
                    });
                    // Optional: Reload the page if needed
                    window.location.reload();
                } else {
                    // toastr.info('Enable Notifications');
                }
            } catch (error) {
                console.error('Error requesting notification permission:', error);
                toastr.error('Error requesting notification permission.');
            }
        } else {
            alert("This browser does not support notifications.");
        }
    }

    window.onload = function() {
        if (Notification.permission !== 'granted') {
            setTimeout(checkNotificationPermission, 2000);
        }
    };

    // Initialize Firebase messaging
    initializeMessaging();

}
