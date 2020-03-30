var notifyHandler = function () {
    var self = this;

    this.init = function () {
        if (!self.isNotificationSupported()) {
            console.log('Your browser does not support Notifications. Use Latest Chrome/Safari to save the world.');
            return;
        }
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();

        }

    };

    jQuery(document).on('EnterPageNotification', function (e) {
        if (!document.hasFocus()) {
            var guest = (e.name) ? e.name : 'Visitor'
            self.showNotification(guest + ' has requested a chat.');
        }
    });

    jQuery(document).on('IncomingCall', function (e) {
        if (!document.hasFocus()) {
            self.showNotification('Visitor is calling you.');
        }
    });


    this.showNotification = function (title) {
        if (!self.isNotificationSupported()) {
            console.log('Your browser does not support Notifications. Use Latest Chrome/Safari to save the world.');
            return;
        }
        if (Notification.permission === 'granted') {
            var notification = new Notification(title, {
                icon: lsRepUrl + '/img/logo.png',
                body: 'Click to open the page.',
                vibrate: [500, 110, 500, 110, 500]
            });

            //add some listeners.
            notification.onclick = function () {
                try {
                    window.focus();
                } catch (ex) {
                    console.log(ex);
                }
            };
            setTimeout(notification.close.bind(notification), 10000);
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(function (permission) {
                // If the user accepts, let's create a notification
                if (permission === 'granted') {
                    notification = new Notification('Hi there!');
                }
            });
        }

    };

    //request permission.
    this.requestPermissions = function () {

        if (Notification.permission !== 'granted') {
            Notification.requestPermission();

        }
    };

    this.isNotificationSupported = function () {
        return ('Notification' in window);
    };
};
