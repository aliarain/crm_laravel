importScripts('//www.gstatic.com/firebasejs/8.7.1/firebase-app.js');
importScripts('//www.gstatic.com/firebasejs/8.7.1/firebase-messaging.js');

@include('vendor.notifications.init_firebase')

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    // Customize notification here
    const notificationTitle = payload.data.title;
    const notificationOptions = {
    body: payload.data.body,
    icon: payload.data.icon,
    image: payload.data.image,
};

return self.registration.showNotification(notificationTitle,notificationOptions);
});
