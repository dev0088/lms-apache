/******** Our main function ********/
var currScript, currVersion = 15;
var isOnline = false;
var popupInstance = null;
var jQEngager, peer_avatar, peer_name, comm_controller, roomIdw, agentId, iframeId, offlineEmail, offlinePage, offlineNotify, openTab = false;
var autoReconnectInterval = 5 * 1000, configType;
currScript = document.currentScript || (function () {
    var scripts = document.getElementById('newdev-embed-script');
    return scripts;
})();

if (currScript == null || currScript == undefined) {
    var scripts = document.getElementsByTagName('script');
    var index = scripts.length - 1;
    currScript = scripts[index];
}

var lsRepUrl = currScript.getAttribute('data-source_path');

var initVideoNewDev = function () {

    var button_css = (currScript.getAttribute('data-button-css')) ? currScript.getAttribute('data-button-css') : 'button.css';
    var chat_css = 'simplechat.css';
    var buttonMessage = (currScript.getAttribute('data-message')) ? currScript.getAttribute('data-message') : 'Start Video Chat';
    peer_avatar = (currScript.getAttribute('data-avatar')) ? currScript.getAttribute('data-avatar') : '';
    peer_name = (currScript.getAttribute('data-names')) ? currScript.getAttribute('data-names') : '';
    roomIdw = (currScript.getAttribute('data-room_id')) ? currScript.getAttribute('data-room_id') : Math.random().toString(36).slice(2).substring(0, 15);
    agentId = (currScript.getAttribute('data-agent_id')) ? currScript.getAttribute('data-agent_id') : '';
    iframeId = (currScript.getAttribute('data-iframe_id')) ? currScript.getAttribute('data-iframe_id') : '';
    offlineEmail = (currScript.getAttribute('data-offline_email')) ? currScript.getAttribute('data-offline_email') : '';
    offlinePage = (currScript.getAttribute('data-offline_page')) ? currScript.getAttribute('data-offline_page') : '';
    offlineNotify = (currScript.getAttribute('data-offline_message')) ? currScript.getAttribute('data-offline_message') : '';
    openTab = (currScript.getAttribute('data-intab')) ? currScript.getAttribute('data-intab') : false;
    configType = (currScript.getAttribute('data-config')) ? currScript.getAttribute('data-config') : false;
    var openPopup = function () {
        if (popupInstance && !popupInstance.closed) {
            popupInstance.focus();
        } else {
            if (roomId.indexOf('dashboard') === -1) {
                comm_controller.leave();
            }
            var wSizeWidth = 350;
            var wSizeHeight = 445;
            var left = (screen.width / 2) - (wSizeWidth / 2);
            var top = (screen.height / 2) - (wSizeHeight / 2);
            var str = {};
            if (peer_avatar) {
                str.avatar = peer_avatar;
            }
            if (peer_name) {
                str.names = peer_name;
            }
            if (lsRepUrl) {
                str.lsRepUrl = lsRepUrl;
            }
            if (agentId) {
                str.agentId = agentId;
            }

            if (configType) {
                str.config = configType;
            }

            var encodedString = window.btoa(JSON.stringify(str));
            if (openTab) {
                var properties = '';
            } else {
                properties = 'width=' + wSizeWidth + ', height=' + wSizeHeight + ', left=' + left + ', top=' + top + ', location=no, menubar=no, resizable=yes, scrollbars=no, status=no, titlebar=no, toolbar = no';
            }
            var url = lsRepUrl + 'pages/' + roomLinkPage + '?room=' + roomIdw + '&p=' + encodedString;
            if (iframeId) {
                jQEngager('#' + iframeId).attr('src', url);
            } else {
                popupInstance = window.open('', 'popup_instance', properties);
                try {
                    if (popupInstance.location.href === 'about:blank') {
                        popupInstance.location.href = url;
                    }
                } catch (ex) {

                }
                popupInstance.focus();
            }

            if (window.addEventListener) {
                window.addEventListener('message', messageHandler, false);
            } else {
                window.attachEvent('onmessage', messageHandler);
            }
        }
    };

    var messageHandler = function (e) {
        if (e.data && e.data.type === 'popupClosed') {
            comm_controller.popupClosed(roomIdw);
        }
    };


    var loadConfig = function () {
        jQEngager.ajax({
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
            }
        });
    };

    var loadBundle = function () {
        loadScript(lsRepUrl + 'js/bundle.js', loadHtml);
    };

    var loadSocket = function () {
        loadScript(svConfigs.appWss + 'socket.io/socket.io.js', loadBundle);
    };


    var loadHtml = function () {
        comm_controller = new comController();
        comm_controller.init('visitor', 'dashboard');
        if (agentId) {
            setTimeout(function () {
                comm_controller.init('visitor', 'dashboard' + agentId);
            }, 500);
        }
        if (currScript.getAttribute('data-room_id')) {
            setTimeout(function () {
                comm_controller.init('visitor', currScript.getAttribute('data-room_id'));
            }, 1000);
        }
        var button_css_link = jQEngager('<link>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: lsRepUrl + 'css/' + button_css + '?v=' + currVersion
        });

        button_css_link.appendTo('head');
        var chat_css_link = jQEngager('<link>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: lsRepUrl + 'css/' + chat_css + '?v=' + currVersion
        });

        chat_css_link.appendTo('head');
        $container = jQEngager('#nd-widget-container');
        loadButton($container);

    };

    var setOnlineStatus = function () {
        var elmId = '#widget_but_span';
        if (!isOnline) {
            jQEngager(elmId).attr('class', 'offline-bnt');
        } else {
            jQEngager(elmId).attr('class', 'online-bnt');
        }
    };

    var loadButton = function ($container) {
        jQEngager.get(lsRepUrl + 'pages/button.html', function (button_data) {
            $container.append(button_data);
            console.log('loadButton');

            jQEngager(document).on('AdminOnline', function (e) {
                isOnline = true;
                setOnlineStatus();
            });

            jQEngager(document).on('AdminOffline', function (e) {
                isOnline = false;
                setOnlineStatus();
            });

            jQEngager('#newdev_widget_msg').html(buttonMessage);

            jQEngager('#widget-but').on('click', function (event) {
                if (isOnline) {
                    openPopup();

                } else {
                    if (offlineEmail) {
                        jQEngager('#widget_tooltip_container').show();
                        jQEngager('#widget_tooltip_container').on('click', function (event) {
                            jQEngager('#widget_tooltip_container').hide();
                            event.stopPropagation()
                        });
                        jQEngager('#offline_message').on('click', function (event) {
                            event.stopPropagation()
                        });
                        jQEngager('#sendEmail').on('click', function (event) {
                            jQEngager('#widget_tooltip_container').hide();
                            var offlineMessage = jQEngager('#offline_message').val()
                            document.location.href = "mailto:" + offlineEmail + '?subject=Offline request&body=' + offlineMessage;
                        });
                    } else if (offlinePage) {
                        if (offlinePage.indexOf('http') === -1) {
                            offlinePage = 'http://' + offlinePage;
                        }
                        window.open(offlinePage);
                    }
                }
            });
            jQEngager(document).off('ChatMessage');
            jQEngager(document).on('ChatMessage', function (e) {
                if (e.privateId == sessionId) {
                    if (localStorage.getItem('noMoreLsvChat')) {
                        var msgText = 'User has closed the chat!';
                        comm_controller.addLocalChat(msgText, null, sessionId);
                    } else {
                        jQEngager(".msger-lsv-widget").css('height', '420px');
                        jQEngager(".msger-lsv-chat").show();
                        jQEngager("#minimizeSimpleChat").show();
                        jQEngager(".msger-lsv-inputarea").show();
                        jQEngager("#maximizeSimpleChat").hide();
                        jQEngager("#simpleButton").show();
                        appendMessage('Agent', 'left', e.msg);
                    }
                }
            });


            var appendMessage = function (name, side, text) {
                if (!text) {
                    return;
                }
                var msgerChat = jQEngager('.msger-lsv-chat')[0];
                const msgHTML = `
    <div class="msg-lsv ${side}-msg-lsv">
      <div class="msg-lsv-bubble">
        <div class="msg-lsv-info">
          <div class="msg-lsv-info-name">${name}</div>
          <div class="msg-lsv-info-time">${getPrettyDate(new Date().getTime() / 1000)}</div>
        </div>

        <div class="msg-lsv-text">${text}</div>
      </div>
    </div>
  `;

                msgerChat.insertAdjacentHTML('beforeend', msgHTML);
                msgerChat.scrollTop += 500;
            };

            jQEngager('.msger-lsv-inputarea').submit(function (event) {
                event.preventDefault();
                var msgerInput = jQEngager('.msger-lsv-input');
                var msgText = msgerInput.val();
                appendMessage('Me', 'right', msgText);
                msgerInput.val('');
                comm_controller.addLocalChat(msgText, null, sessionId);
            });

            jQEngager('#closeSimpleChat').click(function () {
                var msgText = 'User closed the chat!';
                comm_controller.addLocalChat(msgText, null, sessionId);
                localStorage.setItem('noMoreLsvChat', 1);
                jQEngager("#simpleButton").hide();
            });

            jQEngager('#minimizeSimpleChat').click(function (event) {
                event.stopPropagation();
                jQEngager(".msger-lsv-widget").css('height', '40px');
                jQEngager(".msger-lsv-chat").hide();
                jQEngager(".msger-lsv-inputarea").hide();
                jQEngager("#minimizeSimpleChat").hide();
                jQEngager("#maximizeSimpleChat").show();
            });

            jQEngager('.msger-lsv-header').click(function (event) {
                event.stopPropagation();
                jQEngager(".msger-lsv-widget").css('height', '420px');
                jQEngager(".msger-lsv-chat").show();
                jQEngager(".msger-lsv-inputarea").show();
                jQEngager("#minimizeSimpleChat").show();
                jQEngager("#maximizeSimpleChat").hide();
            });
            jQEngager('#minimizeLsvImage').attr('src', lsRepUrl + '/img/minimize.png');
            jQEngager('#maximizeLsvImage').attr('src', lsRepUrl + '/img/maximize.png');
            jQEngager('#closeLsvImage').attr('src', lsRepUrl + '/img/close.png');
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
                loadScript(url, callback);
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
            jQEngager(document).ready(new initVideoNewDev());
        });

    });
} else {
    jQEngager = jQuery;
    jQEngager.get(lsRepUrl + 'pages/version.txt?v=' + ms, function (data) {
        currVersion = data;
        jQEngager(document).ready(new initVideoNewDev());
    });

}
