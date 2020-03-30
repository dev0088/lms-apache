var jQEngager;
var ms = Date.now();
var currVersion = ms;
var notify_handler;
var scripts = document.getElementsByTagName('script');
var index = scripts.length - 1;
currScript = scripts[index];
var lsRepUrl = currScript.getAttribute('data-source_path');
var initVideoLoader = function () {


    var startComm = function () {
        var comm = new comController();
        comm.init();
        notify_handler = new notifyHandler();
        notify_handler.init();
    };
/*
    var loadBundle = function () {
        loadScript('js/bundle.js', startComm);
    };

    var loadConfig = function () {
        $.ajax({
            url: lsRepUrl + 'config/config.ini?v=' + currVersion,
            type: 'GET',
            dataType: 'json',
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType('application/j-son;charset=UTF-8');
                }
            },
            success: function (data) {
                svConfigs = data;
                loadBundle();
            }
        });
    };
*/

    var loadComm = function () {
        loadScript('js/comm.js', startComm);
    };

    var loadLocale = function () {
        loadScript('js/i18n.js', loadComm);
    };

    var loadNotify = function () {
        loadScript('js/notify_handler.js', loadLocale);
    };

    var loadCommon = function () {
        loadScript('js/common.js', loadNotify);
    };

    var loadConfig = function () {
        $.ajax({
            url: lsRepUrl + 'config/config.ini?v=' + currVersion,
            type: 'GET',
            dataType: 'json',
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType('application/j-son;charset=UTF-8');
                }
            },
            success: function (data) {
                svConfigs = data;
                loadCommon();
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
    }

    script.src = lsRepUrl + url + '?v=' + currVersion;
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
