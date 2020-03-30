var ms = Date.now();
var currVersion = ms;
var queryString, svConfigs;
var lsRepUrl = window.location.protocol + '//' + window.location.host + '/';
var autoReconnectInterval = 5 * 1000;
var configName = 'config.json';
function loadScript(url, callback) {
    var script = document.createElement("script")
    script.type = "text/javascript";
    if (script.readyState) {  //IE
        script.onreadystatechange = function () {
            if (script.readyState == "loaded" ||
                    script.readyState == "complete") {
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
                loadScript(url, callback)
            }, autoReconnectInterval);

        };
    }

    script.src = url + '?v=' + currVersion;
    document.getElementsByTagName("head")[0].appendChild(script);
}

function sendPopupMessage(message) {
    $('#homepage').postMessage(message, '*');
}

var QueryString = function () {
    // This function is anonymous, is executed immediately and
    // the return value is assigned to QueryString!
    var query_string = {};
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined") {
            query_string[pair[0]] = pair[1];
            // If second entry with this name
        } else if (typeof query_string[pair[0]] === "string") {
            var arr = [query_string[pair[0]], pair[1]];
            query_string[pair[0]] = arr;
            // If third or later entry with this name
        } else {
            query_string[pair[0]].push(pair[1]);
        }
    }
    return query_string;
};

$(function () {
    var checkParams = function (param, s) {
        for (var key in param) {
            var obj = param[key];
            if (obj) {
                if (s) {
                    s.setAttribute("data-" + key, obj);
                } else {
                    if (key === 'lsRepUrl') {
                        lsRepUrl = obj;
                    }
                    if (key === 'config') {
                        configName = obj;
                    }
                }
            }
        }
    };

    var loadBundle = function () {
        var s = document.getElementById('newdev-embed-script');
        s.src = lsRepUrl + 'js/bundle.client.js?v=' + currVersion;
        s.id = 'newdev-embed-script';
        s.setAttribute('data-source_path', lsRepUrl);
        s.setAttribute('async', true);
        s.setAttribute('data-is_popup', true);
        var params = (queryString.p !== undefined) ? JSON.parse(window.atob(queryString.p)) : null;
        if (!queryString.isAdmin) {
            if (svConfigs.agentName) {
                s.setAttribute('data-names', svConfigs.agentName);
            }
            if (svConfigs.agentAvatar) {
                s.setAttribute('data-avatar', svConfigs.agentAvatar);
            }
        }
        checkParams(params, s);
    };

    var loadPopup = function () {

        var s = document.getElementById('newdev-embed-script');
        s.src = lsRepUrl + 'js/popup.v2.js?v=' + currVersion;
        s.id = 'newdev-embed-script';
        s.setAttribute('data-source_path', lsRepUrl);
        s.setAttribute('async', true);
        s.setAttribute('data-is_popup', true);
        var params = (queryString.p !== undefined) ? JSON.parse(window.atob(queryString.p)) : null;
        if (!queryString.isAdmin) {
            if (svConfigs.agentName) {
                s.setAttribute('data-names', svConfigs.agentName);
            }
            if (svConfigs.agentAvatar) {
                s.setAttribute('data-avatar', svConfigs.agentAvatar);
            }
        }
        checkParams(params, s);
    };

    var startPopup = function () {
        queryString = QueryString();
        var params = (queryString.p !== undefined) ? JSON.parse(window.atob(queryString.p)) : null;
        checkParams(params);
        $.get(lsRepUrl + 'pages/version.txt?v=' + ms, function (data) {
            currVersion = data;


            var loadNotify = function () {
                loadScript(lsRepUrl + 'js/notify_handler.js', loadPopup);
            };

            var loadLocale = function () {
                loadScript(lsRepUrl + 'js/ui_handler.js', loadNotify);
            };

            var loadComm = function () {
                loadScript(lsRepUrl + 'js/i18n.js', loadLocale);
            };

            var loadBuffer = function () {
                loadScript(lsRepUrl + 'js/comm.v2.js', loadComm);
            };

            var loadRmc = function () {
                loadScript(lsRepUrl + 'js/FileBufferReader.js', loadBuffer);
            };

            var loadStats = function () {
                loadScript(lsRepUrl + 'js/rmc.js', loadRmc);
            };

            var loadHark = function () {
                loadScript(lsRepUrl + 'js/getStats.min.js', loadStats);
            };

            var loadCommon = function () {
                loadScript(lsRepUrl + 'js/hark.js', loadHark);
            };

            var loadSocket = function () {
                loadScript(lsRepUrl + 'js/common.js', loadCommon);
            };

            var loadConfig = function () {
                loadScript(svConfigs.appWss + 'socket.io/socket.io.js', loadSocket);
            };

            var loadConfig1 = function () {
                loadScript(svConfigs.appWss + 'socket.io/socket.io.js', loadBundle);
            };

            $.ajax({
                url: lsRepUrl + 'config/' + configName + '?v=' + currVersion,
                type: 'GET',
                dataType: 'json',
                beforeSend: function (x) {
                    if (x && x.overrideMimeType) {
                        x.overrideMimeType('application/j-son;charset=UTF-8');
                    }
                },
                success: function (data) {
                    svConfigs = data;
                    loadConfig();
                }
            });
            /*
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
             */
        });
    };
    startPopup();
});
