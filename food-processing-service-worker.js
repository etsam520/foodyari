
self.addEventListener('install', event => {
    console.log('Food Processing Service Worker installing.');
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    console.log('Food Processing Service Worker activating.');
    event.waitUntil(self.clients.claim());
});

self.addEventListener('message', event => {
    if (event.data.action === 'startTimer') {
        startProcessingTimer(event.data.orderId, event.data.duration);
    }
});

function startProcessingTimer(orderId, duration) {
    setTimeout(() => {
        showNotification(orderId);
        playBeep();
    }, duration);
}

function showNotification(orderId) {
    self.registration.showNotification('Food Ready', {
        body: `Order ${orderId} is ready now!`,
        icon: '	http://localhost:8080/foodyari_etsam/public/assets/images/icons/food.svg' // Provide a valid path to an icon
    });
}

function playBeep() {
    self.clients.matchAll().then(clients => {
        clients.forEach(client => {
            client.postMessage({ action: 'playBeep' });
        });
    });
}

