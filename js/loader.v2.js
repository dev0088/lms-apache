var jQEngager;
var ms = Date.now();
var currVersion = ms;
var notify_handler;
var scripts = document.getElementsByTagName('script');
var index = scripts.length - 1;
currScript = scripts[index];
var lsRepUrl = currScript.getAttribute('data-source_path');
var autoReconnectInterval = 5 * 1000;
var initVideoLoader = function () {


    var startComm = function () {
        var comm = new comController();
        if (agentId) {
            comm.init('admin', 'dashboard' + agentId);
        } else {
            comm.init('admin', 'dashboard');
        }
        notify_handler = new notifyHandler();
        notify_handler.init();
    };

    var loadBundle = function () {
        loadScript(lsRepUrl + 'js/bundle.js', startComm);
    };

    var loadComm = function () {
        loadScript(lsRepUrl + 'js/comm.v2.js', startComm);
    };

    var loadRmc = function () {
        loadScript(lsRepUrl + 'js/rmc.js', loadComm);
    };

    var loadLocale = function () {
        loadScript(lsRepUrl + 'js/i18n.js', loadRmc);
    };

    var loadNotify = function () {
        loadScript(lsRepUrl + 'js/notify_handler.js', loadLocale);
    };

    var loadCommon = function () {
        loadScript(lsRepUrl + 'js/common.js', loadNotify);
    };

    var loadSocket = function () {
        loadScript(svConfigs.appWss + 'socket.io/socket.io.js', loadCommon);
    };

    var loadSocket1 = function () {
        loadScript(svConfigs.appWss + 'socket.io/socket.io.js', loadBundle);
    };

    var loadConfig = function () {
        $.ajax({
            url: lsRepUrl + 'config/config.json?v=' + currVersion,
            type: 'GET',
            dataType: 'json',
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType('application/j-son;charset=UTF-8');
                }
            },
            success: function (data) {
                svConfigs = data;
                loadSocket();
            }, 
            error: function (e) {
                console.log(e);
            } 
        });
    };


    loadConfig();
};


function loadScript(url, callback) {
    var script = document.createElement('script')
    script.type = 'text/javascript';
    if (script.readyState) {  //IE
        script.onreadystatechange = function () {
            if (script.readyState == 'loaded' ||
                    script.readyState == 'complete') {
                script.onreadystatechange = null;
                if (callback) {
                    callback();
                }
            }
        };
    } else {  //Others
        script.onload = function () {
            if (callback) {
                callback();
            }
        };
        script.onerror = function () {

            setTimeout(function () {
                jQEngager('#statusbar').html('Connection failed. Reconnecting...');
                jQEngager('#statusbar').show();
                loadScript(url, callback)
            }, autoReconnectInterval);

        };
    }

    script.src = url + '?v=' + currVersion;
    document.getElementsByTagName('head')[0].appendChild(script);
}

var ms = Date.now();
if ((typeof jQuery === 'undefined') || parseInt(jQuery.fn.jquery.split('.')[1]) < 11) {
    loadScript('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', function () {
        jQEngager = jQuery.noConflict(true);
        jQEngager.get(lsRepUrl + 'pages/version.txt?v=' + ms, function (data) {
            currVersion = data;
            jQEngager(document).ready(new initVideoLoader());
        });

    });
} else {
    jQEngager = jQuery;
    jQEngager.get(lsRepUrl + 'pages/version.txt?v=' + ms, function (data) {
        currVersion = data;
        jQEngager(document).ready(new initVideoLoader());
    });
}
