const messaging = firebase.messaging();
const service_worker_url = $('#url').val()+'/hrm/firebase/sw-js';
// console.log(service_worker_url);
navigator.serviceWorker.register(service_worker_url)
    .then((registration) => {
        messaging.useServiceWorker(registration);
        messaging.requestPermission()
            .then(function () {
                $('#__index_ltn').length > 0 &&  getRegToken();
            })
            .catch(function (err) {
                console.log('Unable to get permission to notify.', err);
            });
        messaging.onMessage(function (payload) {
            notificationTitle = payload.data.title;
            notificationOptions = {
                body: payload.data.body,
                icon: payload.data.icon,
                image: payload.data.image,
            };
            var notification = new Notification(notificationTitle, notificationOptions);
        });
    });

function getRegToken(argument) {
    messaging.getToken().then(function (currentToken) {
        if (currentToken) {
            saveToken(currentToken);
        } else {
        }
    })
        .catch(function (err) {
        });
}

function saveToken(currentToken) {
    $.ajax({
        type: "POST", 
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            firebase_token: currentToken,
            user_id : $('#fire_base_authenticate').val()
        },
        url: $('meta[name="base-url"]').attr("content") + '/hrm/firebase-token/assign',
        success: function (data) {
        },
        error: function (err) {
          
        }
    });
}