var plugin_controller = function () {


    var extensionId;

    this.init = function (extensionId, agentContainer) {

        extensionId = extensionId;
        this.getChromeExtensionStatus(extensionId, function(status) {
            if (status === 'installed-enabled' || status === 'not-chrome') {
                var e = jQuery.Event("PluginDetected");
                jQuery(agentContainer).trigger(e);
            } else {
                var e = jQuery.Event("PluginNotDetected");
                jQuery(agentContainer).trigger(e);
            }
        });

    };

    window.addEventListener('message', function (event) {
        if (event.origin != window.location.origin) {
            return;
        }

        onMessageCallback(event.data);
    });

// and the function that handles received messages

    function onMessageCallback(data) {
        // "cancel" button is clicked
        if (data == 'PermissionDeniedError') {
            chromeMediaSource = 'PermissionDeniedError';
            if (screenCallback)
                return screenCallback('PermissionDeniedError');
            else
                throw new Error('PermissionDeniedError');
        }

        // extension notified his presence
        if (data == 'rtcmulticonnection-extension-loaded') {
            chromeMediaSource = 'desktop';
        }

        // extension shared temp sourceId
        if (data.sourceId && screenCallback) {
            screenCallback(sourceId = data.sourceId, data.canRequestAudioTrack === true);
        }
    }

// global variables
    var chromeMediaSource = 'screen';
    var sourceId;
    var screenCallback;

// this method can be used to check if chrome extension is installed & enabled.
    this.isChromeExtensionAvailable = function(callback) {
        if (!callback)
            return;

        if (chromeMediaSource == 'desktop')
            return callback(true);

        // ask extension if it is available
        window.postMessage('are-you-there', '*');

        setTimeout(function () {
            if (chromeMediaSource == 'screen') {
                callback(false);
            } else
                callback(true);
        }, 2000);
    }

// this function can be used to get "source-id" from the extension
    function getSourceId(callback) {
        if (!callback)
            throw '"callback" parameter is mandatory.';
        if (sourceId)
            return callback(sourceId);

        screenCallback = callback;
        window.postMessage('get-sourceId', '*');
    }

// this function can be used to get "source-id" from the extension
    function getCustomSourceId(arr, callback) {
        if (!arr || !arr.forEach)
            throw '"arr" parameter is mandatory and it must be an array.';
        if (!callback)
            throw '"callback" parameter is mandatory.';

        if (sourceId)
            return callback(sourceId);

        screenCallback = callback;
        window.postMessage({
            'get-custom-sourceId': arr
        }, '*');
    }

// this function can be used to get "source-id" from the extension
    function getSourceIdWithAudio(callback) {
        if (!callback)
            throw '"callback" parameter is mandatory.';
        if (sourceId)
            return callback(sourceId);

        screenCallback = callback;
        window.postMessage('audio-plus-tab', '*');
    }

    this.getChromeExtensionStatus = function (extensionid, callback) {
        if (isFirefox || isEdge || isOpera || (isChrome && getChromeVersion() >= 72))
            return callback('not-chrome');

        if (arguments.length != 2) {
            callback = extensionid;
            extensionId = 'lnccibcicldllmjjphpacjplnpmjnmab'; // default extension-id
        }

        var image = document.createElement('img');
        image.src = 'chrome-extension://' + extensionid + '/icon16.png';
        image.onload = function () {
            chromeMediaSource = 'screen';
            window.postMessage('are-you-there', '*');
            setTimeout(function () {
                if (chromeMediaSource == 'screen') {
                    callback('installed-disabled');
                } else
                    callback('installed-enabled');
            }, 2000);
        };
        image.onerror = function () {
            callback('not-installed');
        };
    }

    function getScreenConstraintsWithAudio(callback) {
        this.getScreenConstraints(callback, true);
    }

// this function explains how to use above methods/objects
    this.getScreenConstraints = function(callback, captureSourceIdWithAudio, constraint) {
        sourceId = null;
        var firefoxScreenConstraints = {
            mozMediaSource: 'window',
            mediaSource: 'window'
        };
        var androidScreenConstraints = {
            video: true
        };
        var edgeScreenConstraints = {
                video: true
            };

        if (isFirefox || isOpera) {
            return callback(null, firefoxScreenConstraints);
        }
        if (isAndroid) {
            return callback(null, androidScreenConstraints);
        }
        if (isEdge) {
            return callback(null, edgeScreenConstraints);
        }
        // this statement defines getUserMedia constraints
        // that will be used to capture content of screen
        var maxHeight = (constraint) ? constraint.maxHeight : 1080;
        var maxWidth  = (constraint) ? constraint.maxWidth : 1920;
        var screen_constraints = {
            mandatory: {
                chromeMediaSource: 'desktop',
                maxWidth: screen.width > maxWidth ? screen.width : maxWidth,
                maxHeight: screen.height > maxHeight ? screen.height : maxHeight
            },
            optional: []
        };

        // this statement verifies chrome extension availability
        // if installed and available then it will invoke extension API
        // otherwise it will fallback to command-line based screen capturing API
        if (chromeMediaSource == 'desktop' && !sourceId) {
            if (captureSourceIdWithAudio) {
                getSourceIdWithAudio(function (sourceId, canRequestAudioTrack) {
                    screen_constraints.mandatory.chromeMediaSourceId = sourceId;

                    if (canRequestAudioTrack) {
                        screen_constraints.canRequestAudioTrack = true;
                    }
                    callback(sourceId == 'PermissionDeniedError' ? sourceId : null, screen_constraints);
                });
            } else {
                getSourceId(function (sourceId) {
                    screen_constraints.mandatory.chromeMediaSourceId = sourceId;
                    callback(sourceId == 'PermissionDeniedError' ? sourceId : null, screen_constraints);
                });
            }
            return;
        }

        // this statement sets gets 'sourceId" and sets "chromeMediaSourceId" 
        if (chromeMediaSource == 'desktop') {
            screen_constraints.mandatory.chromeMediaSourceId = sourceId;
        }

        // now invoking native getUserMedia API
        callback(null, screen_constraints);
    }

};
