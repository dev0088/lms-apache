
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

var useragent = (navigator.userAgent || navigator.vendor || window.opera).toLowerCase();
var isiPhone = /iPhone|iPad|iPod/i.test(useragent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
var isAndroid = /android/i.test(useragent);
var isWindowsPhone = /windows phone/i.test(useragent);
var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
var isFirefox = typeof InstallTrigger !== 'undefined';
var queryString = QueryString();
var isSafariA = (queryString.isSafari && queryString.isSafari == 'false') ? false : Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || (function (p) {
    return p.toString() === "[object SafariRemoteNotification]";
})(!window['safari'] || safari.pushNotification);
var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
var isIEA = (queryString.isIE && queryString.isIE == 'false') ? false : /*@cc_on!@*/false || !!document.documentMode; // At least IE6
var isEdge = (queryString.isEdge && queryString.isEdge == 'false') ? false : navigator.userAgent.indexOf('Edge') > -1;

function getChromeVersion() {
    var raw = navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./);

    return raw ? parseInt(raw[2], 10) : false;
}

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
    }

    script.onerror = function (e) {
        setTimeout(function () {
            loadScript(url, callback);
        }, 5000);
    };

    script.src = url + '?v=' + currVersion;
    document.getElementsByTagName("head")[0].appendChild(script);
}

function stopFullScreenPopup() {
    if (document.getElementById("exitFullscreenButton"))
        document.getElementById("exitFullscreenButton").style.display = "none";
    if (document.getElementById("fullscreenButton"))
        document.getElementById("fullscreenButton").style.display = "block";

    window.resizeTo(widgetSize.width + (window.outerWidth - window.innerWidth), widgetSize.height + (window.outerHeight - window.innerHeight));
    window.moveTo(((screen.width - widgetSize.width) / 2), ((screen.height - widgetSize.height) / 2));
}
;

function toggleFullScreen() {

    if (!document.msFullscreenElement && !document.fullscreen && !document.mozFullScreen && !document.webkitIsFullScreen) {

        showFullScreen();
    } else {

        stopFullScreen();
    }
}
;

document.addEventListener("fullscreenchange", function () {
    if (!document.fullscreen) {
        stopFullScreen();
    }
    ;
}, false);

document.addEventListener("mozfullscreenchange", function () {
    if (!document.mozFullScreen) {
        stopFullScreen();
    }
}, false);

document.addEventListener("MSFullscreenChange", function () {
    if (!document.msFullscreenElement) {
        stopFullScreen();
    }
}, false);

document.addEventListener("webkitfullscreenchange", function () {
    if (!document.webkitIsFullScreen) {
        stopFullScreen();
    }
}, false);


function showFullScreen() {
    var videoElement = document.getElementById("video_container");
    if (videoElement.requestFullscreen) {
        videoElement.requestFullscreen();
    } else if (videoElement.msRequestFullscreen) {
        videoElement.msRequestFullscreen();
    } else if (videoElement.mozRequestFullScreen) {
        videoElement.mozRequestFullScreen();
    } else if (videoElement.webkitRequestFullscreen) {
        videoElement.webkitRequestFullscreen();
    }
    document.getElementById("exitFullscreenButton").style.display = "block";
}
;

function stopFullScreen() {
    if ((document.fullscreen) && document.exitFullscreen) {
        document.exitFullscreen();
    } else if ((document.fullscreen) && document.msExitFullscreen) {
        document.msExitFullscreen();
    } else if ((document.fullscreen) && document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if ((document.fullscreen) && document.webkitCancelFullScreen) {
        document.webkitCancelFullScreen();
    }
    if (document.getElementById("exitFullscreenButton")) {
        document.getElementById("exitFullscreenButton").style.display = "none";
    }
}
;

function changeToUrl(msg) {
    var x = msg;
    var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
    var regex = new RegExp(expression);
    var split = x.split(" ");
    for (var i = 0; i < split.length; i++) {
        if (split[i].match(regex)) {
//                        var text = split[i].split(".").slice(1).join(".").split("/")[0];

            split[i] = '<a target=\"_blank\" href=\"' + split[i] + '\">' + split[i] + '</a>';
        }
    }
    return split.join(" ");
}

var estimateDif = function (elapsed) {
    var sec_num = parseInt(elapsed / 1000, 10);
    var hours = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours || minutes || seconds) {
        var hoursText = (hours == 1) ? ' hour ' : ' hours ';
        hours = (hours) ? hours + hoursText : '';
        var minutesText = (minutes == 1) ? ' minute ' : ' minutes ';
        minutes = (minutes) ? minutes + minutesText : ''
        var secondsText = (seconds == 1) ? ' second ' : ' seconds ';
        seconds = (seconds) ? seconds + secondsText : '';
        return  hours + minutes + seconds;
    } else {
        return null;
    }
};

var generateLink = function (isBroadcast) {
    sessionId = Math.random().toString(36).slice(2).substring(0, 15);

    var str = {};
    if (lsRepUrl) {
        str.lsRepUrl = lsRepUrl;
    }
    if ($('#roomName').val()) {
        sessionId = $('#roomName').val();
    }
    if ($('#names').val()) {
        str.names = $('#names').val();
    }
    if (agentId) {
        str.agentId = agentId;
    }
    if ($('#visitorName').val()) {
        str.visitorName = $('#visitorName').val();
    }
    if ($('#shortvisitor').val()) {
        shortVisitorUrl = $('#shortvisitor').val();
        shortVisitorUrl_broadcast = $('#shortvisitor').val() + '_b';
    } else {
        shortVisitorUrl = Math.random().toString(36).slice(2).substring(0, 6);
        shortVisitorUrl_broadcast = Math.random().toString(36).slice(2).substring(0, 6);
    }
    if ($('#shortagent').val()) {
        shortAgentUrl = $('#shortagent').val();
        shortAgentUrl_broadcast = $('#shortagent').val() + '_b';
    } else {
        shortAgentUrl = Math.random().toString(36).slice(2).substring(0, 6);
        shortAgentUrl_broadcast = Math.random().toString(36).slice(2).substring(0, 6);
    }
    if ($('#datetime').val()) {
        var datetime = new Date($('#datetime').val()).toISOString();
        str.datetime = datetime;
    }
    if ($('#duration').val()) {
        str.duration = $('#duration').val();
    }
    if ($("#disableVideo").is(':checked')) {
        str.disableVideo = 1;
    }
    if ($("#disableAudio").is(':checked')) {
        str.disableAudio = 1;
    }
    if ($("#disableScreenShare").is(':checked')) {
        str.disableScreenShare = 1;
    }
    if ($("#disableWhiteboard").is(':checked')) {
        str.disableWhiteboard = 1;
    }
    if ($("#disableTransfer").is(':checked')) {
        str.disableTransfer = 1;
    }
    if ($("#autoAcceptVideo").is(':checked')) {
        str.autoAcceptVideo = 1;
    }
    if ($("#autoAcceptAudio").is(':checked')) {
        str.autoAcceptAudio = 1;
    }
    var param = '';
    if (isBroadcast) {
        param = '&broadcast=1';
    }
    var encodedString = window.btoa(JSON.stringify(str));


    visitorUrl = lsRepUrl + 'pages/' + roomLinkPage + '?room=' + sessionId + '&p=' + encodedString + param;
    viewerBroadcastLink = lsRepUrl + 'pages/' + roomLinkPage + '?room=' + sessionId + '&p=' + encodedString + param;
    var aux = document.createElement("input");
    aux.setAttribute("value", visitorUrl);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
    if ($('#roomPass').val()) {
        str.pass = $('#roomPass').val();
    }
    delete str['visitorName'];
    encodedString = window.btoa(JSON.stringify(str));
    agentUrl = lsRepUrl + 'pages/' + roomLinkPage + '?room=' + sessionId + '&p=' + encodedString + '&isAdmin=1' + param;
    agentBroadcastUrl = lsRepUrl + 'pages/' + roomLinkPage + '?room=' + sessionId + '&p=' + encodedString + '&isAdmin=1' + param;
};

var getCurrentTime = function () {
    var timeStampt = convertTimestamp(new Date().getTime(), true);
    return timeStampt;
}

var guestName = function (token) {
    token.charCodeAt(0) + token.charCodeAt(token.length - 1);
    var s = 0;
    for (var i = 0; i < token.length; i++) {
        s += token.charCodeAt(i);
    }
    var numb = s % 100;
    return 'Visitor-' + parseInt(numb + 1);
};

var getCurrentDateFormatted = function () {
    var currentdate = new Date();
    return (currentdate.getDate() + "_"
            + (currentdate.getMonth() + 1) + "_"
            + currentdate.getFullYear() + "_"
            + currentdate.getHours() + currentdate.getMinutes() + currentdate.getSeconds());
};

var getPrettyDate = function (timestamp) {
    var today = new Date();
    var ddn = String(today.getDate()).padStart(2, '0');
    var mmn = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyyn = today.getFullYear();

    var d = new Date(timestamp * 1000),
            yyyy = d.getFullYear(),
            mm = ('0' + (d.getMonth() + 1)).slice(-2),
            dd = ('0' + d.getDate()).slice(-2), // Add leading 0.
            hh = d.getHours(),
            h = ('0' + hh).slice(-2),
            min = ('0' + d.getMinutes()).slice(-2), // Add leading 0.
            time;
    if (ddn == dd && mmn == mm && yyyyn == yyyy) {
        return h + ':' + min;
    } else {
        return  dd + '.' + mm + '.' + yyyy + ' ' + h + ':' + min;
    }
};

var convertTimestamp = function (timestamp, timeonly) {

    var curr = new Date(), d = new Date(timestamp),
            yyyy = (curr.getFullYear() !== d.getFullYear()) ? ', ' + d.getFullYear() : '',
            mm = ('0' + (d.getMonth() + 1)).slice(-2),
            dd = ('0' + d.getDate()).slice(-2), // Add leading 0.
            hh = d.getHours(),
            minutes = d.getMinutes(),
            ampm = hh >= 12 ? 'pm' : 'am';
    var hours = hh % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;


    if (timeonly) {
        time = hours + ':' + minutes + ' ' + ampm;
    } else {
        //time = dt_to + yyyy;
        time = hours + ':' + minutes + ' ' + ampm;
    }
    return time;
};

var compareDates = function (timestamp1, timestamp2) {
    var d1 = new Date(timestamp1);
    d1.setHours(0, 0, 0, 0);
    var d2 = new Date(timestamp2);
    d2.setHours(0, 0, 0, 0);
    if (d1.getTime() !== d2.getTime()) {
        return false;
    } else {
        return true;
    }
};

var escapeHtmlEntities = function (str) {
    if (typeof jQuery !== 'undefined') {
        return jQuery('<div/>').text(str).html();
    }

    // No jQuery, so use string replace.
    return str
            .replace(/&/g, '&amp;')
            .replace(/>/g, '&gt;')
            .replace(/</g, '&lt;')
            .replace(/"/g, '&quot;');
}
var prevId, prevMsgP, prevBody;
var showMessage = function (id, msg, time, system, avatar, notsave) {
    if (!msg) {
        return;
    }

    var timeStampt = getPrettyDate(new Date().getTime() / 1000);
    time = (time !== '' && time !== null && time !== 'undefined' && time !== undefined) ? time : (id === '') ? '' : timeStampt;


    if (conferenceStyle == 'conference') {
        if (id === 'Me') {
            var image_class = 'avatar';
            id = smartVideoLocale.msgStore['me'];
            className = 'media media-chat media-chat-reverse';
        } else if (id === '') {
            className = 'media media-meta-day';
        } else {
            $('#peer_name_chat').text(id);
            playIncomingMessage();
            if (id === 'undefined') {
                id = 'Guest';
            }

            className = 'p-10 media-chat';
            if (!avatar) {
                avatar = lsRepUrl + 'img/small-avatar.jpg';
            }
            img_src = avatar;
            image_class = 'avatar ' + id;
        }
        system = (system) ? system : '';
        if (!prevId || id != prevId) {

            var msgP = $('<div />', {
                class: className
            });
            msgP.attr('data-system-attribue', system);
            if (id && id !== 'Me') {
                var nameNode = $('<h6 />', {
                });
                nameNode.appendTo(msgP);
                nameNode.html(id);
            }
            var body = $('<div />', {
                class: 'media-body'
            });
            body.appendTo(msgP);


        } else {
            msgP = prevMsgP;
            body = prevBody;
        }

        var p = $('<p />');
        p.html(msg);
        p.appendTo(body);
        var p = $('<p />', {
            class: 'meta'
        });
        p.html('<time datetime="2018">' + time + '</time>');
        p.appendTo(body);



        msgP.appendTo($('#chat-content'));
        $('#typing').html('');
        prevId = id;
        prevMsgP = msgP;
        prevBody = body;

        var chatFrame = document.getElementById('chat-content');
        chatFrame.scrollTop = 999999;
    } else {

//    msg = changeToUrl(msg);
        var msgP = document.createElement("li");
        var imageClass = 'left', nameClass = '', imageSpan;
        var img_src = '';
        if (id === 'Me' || id.substring(0, 3) == 'Me~') {
            imageClass = 'right';
            var rightImageClass = '';
            if (id.substring(0, 3) == 'Me~') {
                id = id.substring(3, 300);
                rightImageClass = ' right-image'
                if (avatar !== 'img/small-avatar.jpg' && avatar) {
                    img_src = '<img class="direct-chat-img ' + imageClass + '" src="' + avatar + '" alt="" />';
                } else {
                    var matches = id.match(/\b(\w)/g);
                    img_src = matches.join('').toUpperCase();
                    if (img_src) {
                        img_src = '<span class="acronym-right">' + img_src + '</span>';
                    } else {
                        img_src = '<img class="direct-chat-img ' + imageClass + '" src="img/small-avatar.jpg" alt="" />';
                    }
                }
            }
            id = smartVideoLocale.msgStore['me'];
            className = 'wd-right-bubble' + rightImageClass;
        } else if (id === '') {
            var divClass = '';
            if (system === 'divider') {
                divClass = ' divider';
            }
            className = 'wd-system-bubble' + divClass;
        } else {
            playIncomingMessage();
            if (id === 'undefined') {
                id = 'Guest';
            }

            nameClass = 'wd-chat-name';
            imageSpan = 'wd-chat-avatar';
            className = 'wd-left-bubble';
            if (!avatar) {
                avatar = '/img/small-avatar.jpg';
            }
            img_src = '<img class="direct-chat-img ' + imageClass + ' ' + id + '" src="' + avatar + '" alt="" />';
            if (id.substring(0, 3) == 'He~') {
                id = id.substring(3, 500);
                if (avatar !== '/img/small-avatar.jpg' && avatar) {
                    img_src = '<img class="direct-chat-img ' + imageClass + ' ' + id + '" src="' + avatar + '" alt="" />';
                } else {
                    var matches = id.match(/\b(\w)/g);
                    img_src = matches.join('').toUpperCase();
                    var svg = svg1 + img_src + svg2;
                    image = 'data:image/svg+xml;base64,' + btoa(svg);

                    if (img_src) {
                        img_src = '<img class="direct-chat-img ' + imageClass + ' ' + id + '" src="' + image + '" alt="" />';
                    } else {
                        img_src = '<img class="direct-chat-img ' + imageClass + ' ' + id + '" src="/img/small-avatar.jpg" alt="" />';
                    }
                }
            }
        }
        system = (system) ? system : '';
        msgP.setAttribute("data-system-attribue", system);
        msgP.innerHTML = '<div class="' + className + '">' + img_src +
//            '<span class="direct-chat-img '+imageClass+'" >NH</span>' +
                '<span class="' + nameClass + '">' + id + '</span><span class="timestamp">' + time + '</span><div>' + msg + '</div>';
        var chatFrame = document.getElementById('newdev_chat_ul1');
        chatFrame.appendChild(msgP);
        chatFrame.scrollTop = 999999;
    }
};

var saveChat = function (msg, id, system, agentId, avatar, names) {

    var agentName = (queryString.names) ? queryString.names : svConfigs.agentName;
    var datetime = new Date().toISOString();
    $.ajax({
        type: 'POST',
        url: lsRepUrl + '/server/script.php',
        data: {'type': 'addchat', 'roomId': (roomId) ? roomId : queryString.room, 'message': msg, 'agent': agentName, 'agentId': agentId, 'from': id, 'participants': Object.keys(names).toString(), 'system': system, 'avatar': avatar, 'datetime': datetime}
    })
            .done(function (data) {
                if (data == '200') {
                    //data saved
                } else {
                    //data failed to save
                }
            })
            .fail(function () {
                console.log(false);
            });
};

var ERROR_TIMER = 10000, errorTimer;
var toggleError = function (txt, secs) {
    jQuery('#error_message').show();
    jQuery('#error_message_text').html(txt);
    clearTimeout(errorTimer);
    errorTimer = setTimeout(function () {
        jQuery('#error_message').hide();
        jQuery('#error_message_text').html('');
    }, (secs) ? secs : ERROR_TIMER);
};

var toggleNotification = function (txt, show) {
    jQuery('#error_message').toggle(show);
    jQuery('#error_message_text').html(txt);
};

var getCookie = function (name) {
    var pattern = RegExp(name + "=.[^;]*");
    var matched = document.cookie.match(pattern);
    if (matched) {
        var cookie = matched[0].split('=');
        var cooki = cookie[1];//decodeURIComponent(cookie[1]).replace(/"/g, "");
        return cooki;
    }
    return null;
};

var deleteCookie = function (name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;;path=/';
};


var setCookie = function (name, value, hour) {
    var cookieName = name;
    var cookieValue = value;
    var d = new Date();
    var time = d.getTime();
    var expireTime = time + 1000 * 60 * 60 * parseInt(hour);
    d.setTime(expireTime);
    if (hour) {
        document.cookie = cookieName + '=' + cookieValue + ';expires=' + d.toGMTString() + ';path=/';
    } else {
        document.cookie = cookieName + '=' + cookieValue + ';path=/';
    }
};

var getGuid = function () {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
};

var incomingAudio;
var incomingMessage;
var enterRoom;

function playIncomingCall() {
    if (document.hasFocus()) {
        return;
    }
    incomingAudio = new Audio();
    incomingAudio.preload = "auto";
    incomingAudio.autoplay = true;
    incomingAudio.loop = true;
    incomingAudio.src = lsRepUrl + "/media/ringtone.mp3";
    var playPromise = incomingAudio.play();
    if (!isIEA) {
        if (playPromise !== undefined) {
            playPromise.then(function () {
                setTimeout(function () {
                    if (incomingAudio) {
                        incomingAudio.pause();
                    }
                }, 10000);
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
}
function playIncomingMessage() {
    if (document.hasFocus()) {
        return;
    }
    incomingMessage = new Audio();
    incomingMessage.preload = "auto";
    incomingMessage.autoplay = true;
    incomingMessage.loop = false;
    incomingMessage.src = lsRepUrl + "/media/msgtone.mp3";
    var playPromise = incomingMessage.play();
    if (!isIEA) {
        if (playPromise !== undefined) {
            playPromise.then(function () {
                setTimeout(function () {
                    if (incomingMessage) {
                        incomingMessage.pause();
                    }
                }, 1000);
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
}

function playEnterRoom() {
    if (document.hasFocus()) {
        return;
    }
    enterRoom = new Audio();
    enterRoom.preload = "auto";
    enterRoom.autoplay = true;
    enterRoom.loop = false;

    enterRoom.src = lsRepUrl + "/media/msgtone.mp3";
    enterRoom.play();

    var playPromise = enterRoom.play();

    if (playPromise !== undefined) {
        playPromise.then(function () {
            setTimeout(function () {
                if (enterRoom) {
                    enterRoom.pause();
                }
            }, 1000);
        }).catch(function (error) {
            console.log(error);
        });
    }
}

function stopIncomingCall() {
    if (isIEA) {
        if (incomingAudio) {
            incomingAudio.pause();
            incomingAudio.src = '';
        }
        return true;
    } else {
        if (incomingAudio) {
            var playPromise = incomingAudio.pause();

            if (playPromise !== undefined) {
                playPromise.then(function () {
                    //
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
}

function bytesToSize(bytes) {
    var k = 1000;
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0) {
        return '0 Bytes';
    }
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(k)), 10);
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}