try {
  importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
  importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');
} catch (e) {
  console.error('Error loading Firebase scripts:', e);
}

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
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Messaging
let messaging;
try {
  messaging = firebase.messaging.isSupported() ? firebase.messaging() : null;
} catch (err) {
  console.error('Failed to initialize Firebase Messaging', err);
}

// Foreground notification handling
if (messaging) {
  try {
      messaging.onMessage((payload) => {
          console.log('Received foreground message: ', payload);
          const notificationTitle = payload.notification.title;
          const notificationOptions = {
              body: payload.notification.body,
              icon: payload.notification?.image || 'default-icon.png', // Fallback icon
              tag: notificationTitle, // to override the notification with the latest update
              data: {
                  url: payload?.data?.openUrl // URL to open when clicked
              }
          };

          // Show notification manually in the foreground
          if (Notification.permission === 'granted') {
              new Notification(notificationTitle, notificationOptions);
          }
      });
  } catch (err) {
      console.log(err);
  }
}

// Background notification handling (Service Worker)
if (messaging) {
  try {
      messaging.onBackgroundMessage((payload) => {
          console.log('Received background message: ', payload);

          const notificationTitle = payload.notification.title;
          const notificationOptions = {
              body: payload.notification.body,
              icon: payload.notification?.image || 'default-icon.png', // Fallback icon
              tag: notificationTitle, // to override the notification with the latest update
              data: {
                  url: payload?.data?.openUrl // URL to open when clicked
              }
          };

          // Display the notification when the browser is in the background
          self.registration.showNotification(notificationTitle, notificationOptions);
      });
  } catch (err) {
      console.log(err);
  }
}

self.addEventListener('push', function(event) {
  console.log('PUSH:: Received background message:', event);

  const data = event.data.json();
  event.waitUntil(
    self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clients) {
      console.log('PUSH:: Clients matched:', clients);

      let focusedClient = null;

      // Find the focused client (tab)
      for (let client of clients) {
        console.log('PUSH:: Checking client:', client.id, 'Focused:', client.visibilityState == 'visible');
        if (client.visibilityState == 'visible') {
          focusedClient = client;
          break;
        }
      }

      // If no focused client, find the most recently focused one
      if (!focusedClient && clients.length > 0) {
        focusedClient = clients[0]; // Default to the first available client
        console.log('PUSH:: No focused client found. Defaulting to the most recent client:', focusedClient.id);
      }

      // Send the message to the focused or most recent client
      if (focusedClient) {
        console.log('PUSH:: Sending message to client:', focusedClient.id);
        focusedClient.postMessage({
          action: 'playAudio',
          notificationData: data
        });
      } else {
        console.log('PUSH:: No clients available to receive the message.');
      }
    }).catch(function(error) {
      console.error('PUSH:: Error matching clients:', error);
    })
  );
});
