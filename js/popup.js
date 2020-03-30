/******** Our main function ********/
var popup_instance = null;
var caller_name;
var caller_phone;
var caller_avatar;
var caller_email;
var peer_name, names = [];
var peer_phone;
var peer_avatar;
var peer_logo;
var peer_background;
var peer_email;
var popup_message = '';
var lsRepUrl;
var widgetSize = {width: 750, height: 564};
var ui_handler, notify_handler, video_on = true, audio_on = true;
var jQEngager, isOnline = false, pluginInstalled, pluginController, comm_controller, visitors;
var queryString, videoDevices, multiStreamRecorder, roomId, agentId, remoteVideoSessions = 0, screenshareStream = null, inCall = [];
var recordScreen, recordCamera, recordingTimer, videoDefault, videoCurrentId = 0, audioCurrentId = 0, audioOutputCurrentId = 0, videoCurrent, startNextCamera = false, audioSource, videoSource, testAudioTrack, testVideoTrack, videoSelect, audioInputSelect, audioOutputSelect;

var fileInput;
var downloadAnchor;
var progressBar;

var receiveBuffer = [];
var receivedSize = 0;
var sourceBuffer, passRoom, requirePass = false, lsDesigner, agentAvatar, visitorName, agentName, datetime, duration, token, room;
var disableVideo, disableAudio, disableScreenShare, disableWhiteboard, disableTransfer, autoAcceptVideo, autoAcceptAudio, startedRecroding;

var main = function () {
    jQEngager = jQuery;

    var currScript = document.currentScript || (function () {
        var scripts = document.getElementById('newdev-embed-script');
        return scripts;
    })();

    if (currScript == null || currScript == undefined) {
        var scripts = document.getElementsByTagName('script');
        var index = scripts.length - 1;
        currScript = scripts[index];
    }

    jQuery(document).ready(function ($) {
        lsRepUrl = currScript.getAttribute('data-source_path');
        agentAvatar = peer_avatar = (currScript.getAttribute('data-avatar')) ? currScript.getAttribute('data-avatar') : lsRepUrl + 'img/small-avatar.jpg';
        agentName = peer_name = (currScript.getAttribute('data-names')) ? currScript.getAttribute('data-names') : guestName(getGuid());
        passRoom = (currScript.getAttribute('data-pass')) ? currScript.getAttribute('data-pass') : passRoom;
        visitorName = (currScript.getAttribute('data-visitorName')) ? currScript.getAttribute('data-visitorName') : '';
        datetime = (currScript.getAttribute('data-datetime')) ? currScript.getAttribute('data-datetime') : '';
        duration = (currScript.getAttribute('data-duration')) ? currScript.getAttribute('data-duration') : '';
        agentId = (currScript.getAttribute('data-agentId')) ? currScript.getAttribute('data-agentId') : '';
        token = (queryString.token) ? queryString.token : '';
        disableVideo = (currScript.getAttribute('data-disableVideo')) ? true : false;
        disableAudio = (currScript.getAttribute('data-disableAudio')) ? true : false;
        disableScreenShare = (currScript.getAttribute('data-disableScreenShare')) ? true : false;
        disableWhiteboard = (currScript.getAttribute('data-disableWhiteboard')) ? true : false;
        disableTransfer = (currScript.getAttribute('data-disableTransfer')) ? true : false;
        autoAcceptVideo = (currScript.getAttribute('data-autoAcceptVideo')) ? true : false;
        autoAcceptAudio = (currScript.getAttribute('data-autoAcceptAudio')) ? true : false;
        names[0] = {avatar: agentAvatar, name: agentName};
        roomId = (currScript.getAttribute('data-room_id')) ? currScript.getAttribute('data-room_id') : '';
        room = ((queryString.broadcast || queryString.addbroadcast)) ? queryString.broadcast || queryString.addbroadcast : queryString.room;

        if (svConfigs.serverSide && svConfigs.serverSide.agentInfo && agentId) {
            $.ajax({
                type: 'POST',
                url: lsRepUrl + '/server/script.php',
                data: {'type': 'getagent', 'tenant': agentId}
            })
                    .done(function (data) {
                        if (data) {
                            data = JSON.parse(data);
                            agentName = data.first_name + ' ' + data.last_name;
                        }
                    })
                    .fail(function () {
                        //
                    });

        }

        if (isiPhone || isAndroid) {
            svConfigs.videoScreen.chat = false;
        }
        if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
            widgetSize.width = 1052;
        }

        var $container = $('<div>', {class: 'nd-widget-container_lead', id: 'newdev-widget'});
        var widget_css = 'room.css';
        var rateit_css = 'rateit.css';

        $(document).on('AdminPopupOnline', function (e) {
            delete names[0];
            names[e.sessionId] = {avatar: agentAvatar, name: agentName};
            requirePass = (e.pass != undefined && e.pass);
            isOnline = true;
            setOnlineVisitors();
        });
        $(document).on('PopupOnline', function (e) {
            if (!names[e.sessionId]) {
                names[e.sessionId] = {avatar: (e.avatar) ? e.avatar : lsRepUrl + 'img/small-avatar.jpg', name: (e.name) ? e.name : guestName(e.sessionId)};
            }
            isOnline = true;
            setOnlineVisitors();
        });
        $(document).on('AdminPopupOffline', function (e) {
            isOnline = false;
        });
        $(document).on('VisitorsRoom', function (e) {
            visitors = e.count;
        });
        comm_controller = new comController();
        comm_controller.init((passRoom));
        notify_handler = new notifyHandler();
        notify_handler.init();
        pluginController = new plugin_controller();

        $(window).on('unload', function () {
            hangupCall();
            console.log('close the call');
        });

        var joinedMessage = function (e) {
            var incomingText = smartVideoLocale.msgStore['incomingText'];
            if (incomingText && names[e.sessionId]) {
                $('#incoming_text').html(incomingText.replace('{{caller_name}}', names[e.sessionId].name));
            }
            if (e.sessionId !== comm_controller.getSessionId() && queryString.isAdmin) {
                var joinedText = smartVideoLocale.msgStore['joinedChat'];
                if (joinedText) {
                    var joinedMessage = joinedText.replace('{{caller_name}}', names[e.sessionId].name);
                    if (svConfigs.videoScreen && svConfigs.videoScreen.waitingRoom) {
                        toggleError(joinedMessage, 5000);
                    } else {
                        showMessage('', joinedMessage, null, 'joinedChat');
                        if (svConfigs.serverSide.chatHistory) {
                            saveChat(joinedMessage, '', 'joinedChat', agentId, '', names);
                        }
                    }
                }
            }
        };

        var updateInfo = function (e) {
            if (e.callerInfo.name && names[e.sessionId] && names[e.sessionId].name !== e.callerInfo.name) {
                if (names[e.sessionId].username) {
                    var username = names[e.sessionId].username;
                }
                names[e.sessionId] = {name: e.callerInfo.name, email: e.callerInfo.email};
                if (username) {
                    names[e.sessionId].username = username;
                }

                $('#peer_name_chat').text(names[e.sessionId].name);
                joinedMessage(e);
            }

            var avatar = (e.callerInfo.avatar) ? e.callerInfo.avatar : lsRepUrl + 'img/small-avatar.jpg';
            if (names[e.sessionId]) {
                names[e.sessionId].avatar = avatar;
                if (e.callerInfo.priv) {
                    names[e.sessionId].priv = e.callerInfo.priv;
                }
                if (e.callerInfo.username) {
                    names[e.sessionId].username = e.callerInfo.username;
                }
            }
            $('.dw-chat-avatar').attr('src', avatar);
            $('.direct-chat-img left ' + e.callerInfo.name).attr('src', avatar);
            setOnlineVisitors();
        };

        var setOnlineVisitors = function () {
            $('#visitors').empty();
            var count = 0;
            for (var sess in names) {
                if (sess !== comm_controller.getSessionId()) {
                    var cList = $('ul#visitors');
                    var li = $('<li/>')
                            .addClass('ui-menu-item')
                            .attr('role', 'menuitem')
                            .appendTo(cList);

                    var aaa = $('<a/>')
                            .addClass('ui-all')
                            .attr('typeid', sess)
                            .text(names[sess].name)
                            .attr('title', smartVideoLocale.msgStore['sendMessageTo'] + names[sess].name)
                            .appendTo(li);
                    aaa.click(function () {
                        if ($(this).attr('typeid') != comm_controller.getSessionId()) {
                            sendPrivateChat($(this).attr('typeid'));
                        }
                    });

                    if (queryString.isAdmin && queryString.addbroadcast) {
                        if (names[sess].priv) {
                            var txt = smartVideoLocale.msgStore['revoke'];
                            var src = lsRepUrl + 'img/revoke.png';
                        } else {
                            if (names[sess].raiseHand) {
                                txt = smartVideoLocale.msgStore['grant'];
                                src = lsRepUrl + 'img/grant.png';
                            } else {
                                txt = '';
                            }
                        }
                        if (txt) {
                            var grant = $('<img/>')
                                    .attr('typeid', sess)
                                    .addClass('centerImg')
                                    .attr('src', src)
                                    .attr('title', txt + ' ' + names[sess].name)
                                    .appendTo(li);
                            grant.click(function () {
                                if ($(this).attr('typeid') != comm_controller.getSessionId()) {
                                    if (names[$(this).attr('typeid')].priv) {
                                        names[$(this).attr('typeid')].priv = false;
                                        comm_controller.revokePriveleges($(this).attr('typeid'));
                                    } else {
                                        names[$(this).attr('typeid')].priv = true;
                                        comm_controller.grantPriveleges($(this).attr('typeid'));
                                    }
                                    setOnlineVisitors();
                                }
                                setTimeout(function () {
                                    ui_handler.toggleVisitors(false);
                                    setOnlineVisitors();
                                }, 500);
                            });
                        }
                    }
                    if (queryString.isAdmin && (svConfigs.serverSide.loginForm || svConfigs.serverSide.token)) {
                        var txtBlock = smartVideoLocale.msgStore['block'];
                        var block = $('<img/>')
                                .addClass('centerImg')
                                .attr('typeid', sess)
                                .attr('title', txtBlock + ' ' + names[sess].name)
                                .attr('src', lsRepUrl + 'img/block.png')
                                .appendTo(li);
                        block.click(function () {
                            var r = confirm(smartVideoLocale.msgStore['sureBlock']);
                            if (r == true) {
                                $.ajax({
                                    type: 'POST',
                                    url: lsRepUrl + '/server/script.php',
                                    data: {'type': 'blockuser', 'username': names[$(this).attr('typeid')].username}
                                })
                                        .done(function (data) {
                                            console.log('blocked');
                                        })
                                        .fail(function () {
                                            //
                                        });


                                delete names[$(this).attr('typeid')];
                                comm_controller.blockUser($(this).attr('typeid'));
                                setOnlineVisitors();
                                setTimeout(function () {
                                    ui_handler.toggleVisitors(false);
                                    setOnlineVisitors();
                                }, 500);
                            }

                        });
                    }
                }
                if (sess !== comm_controller.getSessionId()) {
                    peer_avatar = names[sess].avatar;
                    peer_name = names[sess].name;
                }
                count++;
            }
            function sendPrivateChat(typeid) {

                $('#visitor_message').show();
                $('#send_message_to').html(smartVideoLocale.msgStore['sendMessageTo'] + names[typeid].name);

                $('#private_message_button').off();
                $('#private_message_button').on('click', function () {
                    var msg = $('#private_message_small').text();
                    sendChatMessage(msg, true, typeid);
                    $('#visitor_message').hide();
                    setTimeout(function () {
                        ui_handler.toggleVisitors(false);
                    }, 500);

                    $('#private_message_small').text('');
                });

            }
            if (count > 2) {
                $('.dw-chat-avatar').attr('src', lsRepUrl + 'img/listusers.png');
                $('#peer_name_chat').html('...');
            } else {
                $('.dw-chat-avatar').attr('src', peer_avatar);
                $('#peer_name_chat').html(peer_name);
            }

        };

        $(document).on('CallerInfo', function (e) {
            updateInfo(e);
        });

        var callEnded = function (sessionId) {
            remoteVideoSessions = 0;
            stopIncomingCall();
            stopRecording(true);
            inCall = [];
            console.log('call ended');

            var remoteVideo = document.querySelector('video#remoteVideo' + sessionId);
            var localVideo = document.querySelector('video#localVideo');
            if (remoteVideo) {
                remoteVideo.src = '';
                remoteVideo.srcObject = null;
                $('#remoteVideo' + sessionId).remove();
                $('#remoteVideoSpan' + sessionId).remove();
                endMeeting(false);
            }
            setVideoScreens();

            if (!comm_controller.getVideoSessions()) {
                comm_controller.stopMediaStream(sessionId);
                if (localVideo) {
                    localVideo.src = '';
                    localVideo.srcObject = null;
                }

                $('#localVideo').hide();
                $('#remoteVideo' + sessionId).remove();
                $('#remoteVideoSpan' + sessionId).remove();
                $('#video_back').show();

                ui_handler.toggleInstaChat();
            }
            if (!comm_controller.getScreenStream() && !$('#remoteScreenChat').is(":visible")) {
                comm_controller.handleScreenShareTermination();
            }



        };


        function addStreamStopListener(stream, callback) {
            stream.addEventListener('ended', function () {
                callback();
                callback = function () {};
            }, false);
            stream.addEventListener('inactive', function () {
                callback();
                callback = function () {};
            }, false);
            stream.getTracks().forEach(function (track) {
                track.addEventListener('ended', function () {
                    callback();
                    callback = function () {};
                }, false);
                track.addEventListener('inactive', function () {
                    callback();
                    callback = function () {};
                }, false);
            });
        }

        function invokeGetDisplayMedia(success, error) {
            var displaymediastreamconstraints = {
                video: {
                    displaySurface: 'monitor', // monitor, window, application, browser
                    logicalSurface: true,
                    cursor: 'always' // never, always, motion
                }
            };
            // above constraints are NOT supported YET
            // that's why overridnig them
            displaymediastreamconstraints = {
                video: true
            };
            if (navigator.mediaDevices.getDisplayMedia) {
                navigator.mediaDevices.getDisplayMedia(displaymediastreamconstraints).then(success).catch(error);
            } else {
                navigator.getDisplayMedia(displaymediastreamconstraints).then(success).catch(error);
            }
        }

        function captureScreen(callback) {
            invokeGetDisplayMedia(function (screen) {
                addStreamStopListener(screen, function () {
                    stopRecording(true);
                    ui_handler.setRecordingUi(false);
                    comm_controller.stopRecording();
                    downloadRecording();
                });
                callback(screen);
            }, function (error) {
                console.error(error);
                alert('Unable to capture your screen. Please check console logs.\n' + error);
            });

        }

        function captureCamera(cb) {
            navigator.mediaDevices.getUserMedia({audio: true, video: true}).then(cb);
        }

        function keepStreamActive(stream) {
            var video = document.createElement('video');
            video.autoplay = true;
            video.muted = true;
            video.srcObject = stream;
            video.style.display = 'none';
            (document.body || document.documentElement).appendChild(video);
        }


        var pauseRecording = function () {
            stopRecording(false);
            comm_controller.startRecording();
            ui_handler.setRecordingUi(true);

        };

        var startRecording = function () {
            startedRecroding = true;
            comm_controller.startRecording();
            ui_handler.setRecordingUi(true);
            if (svConfigs.recording.screen) {

                captureScreen(function (screen) {
                    recordScreen = screen;

                    keepStreamActive(screen);
                    captureCamera(function (camera) {
                        recordCamera = camera;
                        keepStreamActive(camera);
                        screen.width = window.screen.width;
                        screen.height = window.screen.height;
                        screen.fullcanvas = true;

                        camera.width = 320;
                        camera.height = 240;
                        camera.top = screen.height - camera.height;
                        camera.left = screen.width - camera.width;

                        multiStreamRecorder = RecordRTC([screen, camera], {
                            type: 'video',
                            mimeType: 'video/webm'
                        });
                        multiStreamRecorder.startRecording();
                        //recordingTimer = setInterval(pauseRecording, 60000);
                    });

                });

            } else {
                if (queryString.isAdmin) {
                    if (svConfigs.recording.oneWay) {
                        var recorders = [];
                    } else {
                        recorders = [comm_controller.getStream()];
                    }

                    for (var sess in names) {
                        if (sess != comm_controller.getSessionId()) {
                            recorders.push(comm_controller.getRemoteStream(sess));
                        }
                    }
                }
                if (queryString.addbroadcast) {
                    recorders = [];
                    $('.broadcastvideo').each(function (index) {
                        if ($(this).is(":visible")) {
                            var id = $(this)[index].id;
                            recorders.push(comm_controller.getBroadcastStream(id));
                        }
                    });
                }
                multiStreamRecorder = RecordRTC(recorders, {
                    type: 'video',
                    mimeType: 'video/webm',
                    disableLogs: true
                });
                multiStreamRecorder.startRecording();
            }
        };

        var stopRecording = function (stop) {
            ui_handler.setRecordingUi(false);
            comm_controller.stopRecording();
            if (multiStreamRecorder) {
                multiStreamRecorder.stopRecording(function () {
                    downloadRecording(stop);
                    if (stop && svConfigs.recording.screen) {
                        clearInterval(recordingTimer);
                        [recordScreen, recordCamera].forEach(function (stream) {
                            stream.getTracks().forEach(function (track) {
                                track.stop();
                            });
                        });
                    }
                });

            }
        };

        var downloadRecording = function (show) {
            if (startedRecroding) {
                var url = URL.createObjectURL(multiStreamRecorder.getBlob());
                if (svConfigs.recording.download) {
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'record_' + getCurrentDateFormatted() + '.webm';
                    document.body.appendChild(a);
                    a.click();
                    setTimeout(function () {
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    }, 100);

                }
                if (svConfigs.recording.saveServer) {
                    var fileType = 'video';
                    var fileName = 'record_' + room + '_' + getCurrentDateFormatted() + '.webm';

                    var formData = new FormData();
                    formData.append(fileType + '-filename', fileName);
                    formData.append(fileType + '-blob', multiStreamRecorder.getBlob());
                    formData.append('room', room);
                    formData.append('agentName', agentName);
                    formData.append('agentId', agentId);

                    xhr(lsRepUrl + '/server/saverecord.php', formData);

                    function xhr(url, data) {
                        var request = new XMLHttpRequest();
                        request.onreadystatechange = function () {
                            if (request.readyState == 4 && request.status == 200) {
                                if (!show) {
                                    multiStreamRecorder.startRecording();
                                } else {
                                    multiStreamRecorder.destroy();
                                    multiStreamRecorder = null;
                                }
                            }
                        };
                        request.open('POST', url);
                        request.send(data);
                    }
                } else {
                    $('.control-recording').show();
                    $('.recordinglink').attr('href', url);
                    $('.recordinglink').click(function () {
//                    window.URL.revokeObjectURL(url);
                        $('.control-recording').hide();
                    });
                    $('.close-but-wd-small').on('click', function () {
                        window.URL.revokeObjectURL(url);
                        $('.control-recording').hide();
                    });
                }
                startedRecroding = false;
            }
//            multiStreamRecorder.screen.stop();
//            multiStreamRecorder.destroy();
//            multiStreamRecorder = null;
        };

        var hangupCall = function () {
            stopIncomingCall();
            if (comm_controller.getStream(comm_controller.getRemoteSessionId())) {
                stopRecording(true);
                ui_handler.toggleInstaChat();
                comm_controller.endCall('hang up call', comm_controller.getSessionId());
                comm_controller.handleCallTermination();
                hangUpScreenShare();
                $('.sourcevideo').each(function () {
                    if ($(this).is(":visible")) {
                        var id = $(this).data('id');
                        $('#remoteVideoSpan' + id).remove();
                        $(this).remove();
                    }
                });
            }
            var localVideo = document.querySelector('video#localVideo');

            if (localVideo) {
                localVideo.src = '';
                localVideo.srcObject = null;
            }
            if ((queryString.broadcast || queryString.addbroadcast) && svConfigs.broadcastUrl) {
                $('.wd-video-c').removeClass('disabled');
                var remoteVideo = $(this);

                if (remoteVideo) {
                    remoteVideo.src = '';
                    remoteVideo.srcObject = null;
                    $(this).remove();
                }
                toggleNotification(smartVideoLocale.msgStore['broadcastStopped'], true);
                ui_handler.displayVideoOnly();
            }
        };

        var startGreenRoom = function () {
            setCookie('lsvGreenRoom', '1');
            var cloud_css_link = $('<link>', {
                rel: 'stylesheet',
                type: 'text/css',
                href: lsRepUrl + 'css/cloud.css?v=' + currVersion
            });
            cloud_css_link.appendTo('head');
            var sky_css_link = $('<link>', {
                rel: 'stylesheet',
                type: 'text/css',
                href: lsRepUrl + 'css/sky-forms.css?v=' + currVersion
            });
            sky_css_link.appendTo('head');
            var videoElement = $('#videoPreview')[0];
            audioInputSelect = document.querySelector('select#audioSource');
            audioOutputSelect = document.querySelector('select#audioOutput');
            videoSelect = document.querySelector('select#videoSource');
            var selectors = [audioInputSelect, audioOutputSelect, videoSelect];

            audioCurrentId = (localStorage.getItem('audioCurrentId') > 0) ? parseInt(localStorage.getItem('audioCurrentId')) : 0;
            videoCurrentId = (localStorage.getItem('videoCurrentId') > 0) ? parseInt(localStorage.getItem('videoCurrentId')) : 0;
            videoSource = (localStorage.getItem('videoSource')) ? localStorage.getItem('videoSource') : undefined;
            audioOutputCurrentId = (localStorage.getItem('audioOutputCurrentId') > 0) ? localStorage.getItem('audioOutputCurrentId') : 0;

            function gotDevices(deviceInfos) {
                // Handles being called several times to update labels. Preserve values.
                var values = selectors.map(function (select) {
                    return select.value;
                });
                selectors.forEach(function (select) {
                    while (select.firstChild) {
                        select.removeChild(select.firstChild);
                    }
                });

                for (var i = 0; i !== deviceInfos.length; ++i) {
                    var deviceInfo = deviceInfos[i];
                    var option = document.createElement('option');
                    option.value = deviceInfo.deviceId;
                    if (deviceInfo.kind === 'audioinput') {
                        option.text = deviceInfo.label ||
                                'microphone ' + (audioInputSelect.length + 1);
//                        if (deviceInfo.label) {
                        audioInputSelect.appendChild(option);
//                        }
                    } else if (deviceInfo.kind === 'audiooutput') {
                        option.text = deviceInfo.label || 'speaker ' +
                                (audioOutputSelect.length + 1);
//                        if (deviceInfo.label) {
                        audioOutputSelect.appendChild(option);
//                        }
                    } else if (deviceInfo.kind === 'videoinput') {
                        option.text = deviceInfo.label || 'camera ' + (videoSelect.length + 1);
                        videoSelect.appendChild(option);
                    }
                }
                selectors.forEach(function (select, selectorIndex) {
                    if (Array.prototype.slice.call(select.childNodes).some(function (n) {
                        return n.value === values[selectorIndex];
                    })) {
                        select.value = values[selectorIndex];
                    }
                });


                var audioElement = document.getElementById('audioSource');
                audioElement.selectedIndex = audioCurrentId;
                var audioOutputElement = document.getElementById('audioOutput');
                audioOutputElement.selectedIndex = audioOutputCurrentId;
                var videoElement = document.getElementById('videoSource');
                videoElement.selectedIndex = videoCurrentId;
            }

            var videoCons = (videoSource) ? {deviceId: videoSource ? {exact: videoSource} : undefined} : true;
            if (isIEA) {
                videoCons = true;
            }
            var constraints = {
                audio: true,
                video: videoCons
            };

            function handleMediaDevice(e) {
                if (e.name === 'NotReadableError' && audio_on) {
                    audio_on = false;

                } else if (e.name === 'OverconstrainedError') {
                    localStorage.removeItem('videoSource');
                    localStorage.removeItem('videoCurrentId');
                    localStorage.removeItem('audioCurrentId');

                    var constraints = {
                        audio: true,
                        video: true
                    };
                } else {
                    return;
                }
                if (typeof Promise === 'undefined') {
                    //navigator.getUserMedia(constraints, gotStream, handleError);
                } else {
                    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).then(function () {
                        navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);
                    }).catch(handleMediaDevice);
                }
            }

            if (typeof Promise === 'undefined') {
                //navigator.getUserMedia(constraints, gotStream, handleError);
            } else {
                navigator.mediaDevices.getUserMedia(constraints).then(gotStream).then(function () {
                    navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);
                }).catch(handleMediaDevice);
            }

// Attach audio output device to video element using device/sink ID.
            function attachSinkId(element, sinkId) {
                if (typeof element.sinkId !== 'undefined') {
                    element.setSinkId(sinkId)
                            .then(function () {
                                //
                            })
                            .catch(function (error) {
                                var errorMessage = error;
                                if (error.name === 'SecurityError') {
                                    errorMessage = 'You need to use HTTPS for selecting audio output ' +
                                            'device: ' + error;
                                }
                                console.error(errorMessage);
                                // Jump back to first output device in the list as it's the default.
                                audioOutputSelect.selectedIndex = 0;
                            });
                } else {
                    console.warn('Browser does not support output device selection.');
                }
            }

            function changeAudioDestination() {
                audioOutputCurrentId = audioOutputSelect.selectedIndex;
                if (audioOutputCurrentId > 0) {
                    localStorage.setItem('audioOutputCurrentId', audioOutputCurrentId);
                }
                var audioDestination = audioOutputSelect.value;
                //stop this temporarily
                attachSinkId(videoElement, audioDestination);
            }

            function gotStream(stream) {
                testAudioTrack = stream.getAudioTracks()[0];
                testVideoTrack = stream.getVideoTracks()[0];
                testAudioTrack.enabled = false;
                window.stream = stream; // make stream available to console
                videoElement.srcObject = stream;
                // Refresh button list in case labels have become available
                if (!isIEA) {
                    videoElement.srcObject = stream;
                    return navigator.mediaDevices.enumerateDevices();
                }
            }

            function start() {
                if (window.stream) {
                    window.stream.getTracks().forEach(function (track) {
                        track.stop();
                    });
                }

                audioSource = audioInputSelect.value;
                videoSource = videoSelect.value;
                videoCurrentId = videoSelect.selectedIndex;
                audioCurrentId = audioInputSelect.selectedIndex;

                localStorage.setItem('videoCurrentId', videoCurrentId);
                localStorage.setItem('videoSource', videoSource);
                localStorage.setItem('audioCurrentId', audioCurrentId);
                var constraints = {
                    audio: audio_on ? {deviceId: audioSource ? {exact: audioSource} : undefined} : false,
                    video: {deviceId: videoSource ? {exact: videoSource} : undefined}
                };

                if (typeof Promise === 'undefined') {
                    navigator.getUserMedia(constraints, gotStream, handleError);
                } else {
                    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).catch(handleError);
                }
            }
            if (audioInputSelect) {
                audioInputSelect.onchange = start;
            }
            if (audioOutputSelect) {
                audioOutputSelect.onchange = changeAudioDestination;
            }
            if (videoSelect) {
                videoSelect.onchange = start;
            }

            function handleError(error) {
                console.log('navigator.getUserMedia error: ', error);
            }
            ui_handler.toggleInstaChat();
            ui_handler.restoreVideoBox();
            $('#popup_widget_text_videos').show();
            ui_handler.toggleInstaVideo(4)
        };

        var startVideo = function (autoaccept, videoDevice) {
            //hangUpScreenShare();
            if (isOnline) {
                if (svConfigs.videoScreen.greenRoom && !getCookie('lsvGreenRoom')) {
                    startGreenRoom();
                    $('#startVideoButton').click(function () {
                        if (window.stream) {
                            window.stream.getTracks().forEach(function (track) {
                                track.stop();
                            });
                        }
                        ui_handler.restoreVideoBox();
                        ui_handler.toggleInstaVideo(false);
                        var type = (video_on) ? 'Video' : 'Audio';
                        comm_controller.initCall(type, autoaccept, videoSource, comm_controller.getSessionId(), audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                    });

                } else {
                    ui_handler.restoreVideoBox();
                    ui_handler.toggleInstaVideo(false);
                    var type = (video_on) ? 'Video' : 'Audio';
                    comm_controller.initCall(type, autoaccept, videoDevice, comm_controller.getSessionId(), audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                }
            } else {
                setOffline();
            }
        };

        var sendChatMessage = function (msg, showMyMessage, typeid) {
            msg = escapeHtmlEntities(msg);
            if (typeid) {
                msg = '<small id="private">' + smartVideoLocale.msgStore['private'] + '</small> ' + msg;
            }
            if (showMyMessage) {
                showMessage('Me', msg);
            }

            var date = new Date().toLocaleTimeString();
            comm_controller.addLocalChat(msg, date, typeid);
            if (svConfigs.serverSide.chatHistory) {
                saveChat(msg, names[comm_controller.getSessionId()].name, '', agentId, names[comm_controller.getSessionId()].avatar, names);
            }
        };

        var closeWidget = function () {
            window.close();
        };

        var sendChatWindowMessage = function (elem) {
            if ($('#' + elem).text()) {
                var msg = $('#' + elem).text();
                sendChatMessage(msg, true);
                $('#' + elem).html('');
            }
        };

        var screenShareUI = function (show) {
            if (show) {
                $('#wd-widget-content-video').show();
                if (!video_on) {
                    $('#local_video_div').show();
                }
                $('#localScreen').show();
                $('#localVideo').hide();
                $("#showHideVideo").addClass('disabled');
            } else {

                $('#remoteScreenChat').hide();
                $('#remoteScreen').hide();
                $('#localScreen').hide();
                if (video_on) {
                    $('#localVideo').show();
                } else {
                    $('#local_video_div').hide();
                }
                $("#showHideVideo").removeClass('disabled');
            }
            if (svConfigs.videoScreen && svConfigs.videoScreen.onlyAgentButtons == false || queryString.isAdmin) {
                ui_handler.setScreenButton(show);
            }
        };

        var hangUpScreenShare = function () {
            screenshareStream = null;
            screenShareUI(false);
            comm_controller.handleScreenShareTermination();
        };

        var startScreenShare = function () {

            pluginController.getScreenConstraints(function (error, screen_constraints) {
                if (error) {
                    return console.log(error);
                }

                var video = document.querySelector('video#localScreen');
                if (isEdge) {
                    navigator.getDisplayMedia(screen_constraints).then(function (stream) {
                        video.srcObject = stream;
                        screenshareStream = stream;
                        screenShareUI(true);

                        for (var sess in names) {
                            if (sess !== comm_controller.getSessionId()) {
                                comm_controller.startScreenShare(stream, true, sess);
                                comm_controller.addStreamStopListener(stream, function () {
                                    hangUpScreenShare();
                                });
                            }
                        }


                    }).catch(function (error) {
                        console.log('Edge SS errors', JSON.stringify(error, null, '\t'));
                    });
                } else if (isChrome && getChromeVersion() >= 72 && !isAndroid) {
                    navigator.mediaDevices.getDisplayMedia(screen_constraints).then(
                            function (stream) {
                                video.srcObject = stream;
                                screenshareStream = stream;
                                screenShareUI(true);

                                for (var sess in names) {
                                    if (sess !== comm_controller.getSessionId()) {
                                        comm_controller.startScreenShare(stream, true, sess);
                                        comm_controller.addStreamStopListener(stream, function () {
                                            hangUpScreenShare();
                                        });
                                    }
                                }


                            }).catch(function (error) {
                        console.log(JSON.stringify(error, null, '\t'));
                    });
//                            
//                            stream => {
//                        video.srcObject = stream;
//                        screenShareUI(true);
//                        comm_controller.startScreenShare(stream, true);
//                        comm_controller.addStreamStopListener(stream, function () {
//                            hangUpScreenShare();
//                        });
//                    }, error => {
//                        alert('Please make sure to use Edge 17 or higher.');
//                    }
                } else if (isChrome && getChromeVersion() >= 72 && isAndroid) {
                    navigator.mediaDevices.getDisplayMedia(screen_constraints).then(
                            function (stream) {
                                video.srcObject = stream;
                                screenshareStream = stream;
                                screenShareUI(true);

                                for (var sess in names) {
                                    if (sess !== comm_controller.getSessionId()) {
                                        comm_controller.startScreenShare(stream, true, sess);
                                        comm_controller.addStreamStopListener(stream, function () {
                                            hangUpScreenShare();
                                        });
                                    }
                                }


                            }).catch(function (error) {
                        console.log(JSON.stringify(error, null, '\t'));
                    });
//                            
//                            stream => {
//                        video.srcObject = stream;
//                        screenShareUI(true);
//                        comm_controller.startScreenShare(stream, true);
//                        comm_controller.addStreamStopListener(stream, function () {
//                            hangUpScreenShare();
//                        });
//                    }, error => {
//                        alert('Please make sure to use Edge 17 or higher.');
//                    }
                } else {
                    navigator.mediaDevices.getUserMedia({
                        audio: false,
                        video: screen_constraints
                    }).then(function (stream) {
                        screenshareStream = stream;
                        comm_controller.initScreen(true);

                        //                    video.src = URL.createObjectURL(stream);
                        video.srcObject = stream;
                        video.autoplay = true;
                        video.muted = true;
                        screenShareUI(true);
                        for (var sess in names) {
                            if (sess !== comm_controller.getSessionId()) {
                                comm_controller.startScreenShare(stream, true, sess);
                                comm_controller.addStreamStopListener(stream, function () {
                                    hangUpScreenShare();
                                });
                            }
                        }
                    }).catch(function (error) {
                        console.log(JSON.stringify(error, null, '\t'));
                    });
                }
            }, true, svConfigs.videoScreen.screenConstraint);
        };

        var openWidget = function (type) {
            toggleNotification('', false);
            var is_widget_opened = ($('#nd_widget_content').is(':visible'));
            console.log('openWidget', is_widget_opened, type);
            caller_name = (caller_name) ? caller_name : '';
            caller_email = (caller_email) ? caller_email : '';
            caller_phone = (caller_phone) ? caller_phone : '';

            if (!is_widget_opened) {
                ui_handler.toggleWidget();
                if (type === 'chat' && svConfigs.serverSide.chatHistory) {
                    $.ajax({
                        type: 'POST',
                        url: lsRepUrl + '/server/script.php',
                        data: {'type': 'getchat', 'roomId': room, 'sessionId': comm_controller.getSessionId(), 'agentId': agentId}
                    })
                            .done(function (data) {
                                if (data) {
                                    var result = JSON.parse(data);
                                    result.forEach(function (pid) {
                                        if (names[comm_controller.getSessionId()] && pid.from == names[comm_controller.getSessionId()].name) {
                                            var name = 'Me';
                                        } else {
                                            name = pid.from;
                                        }
                                        if (!queryString.isAdmin && pid.system) {
                                            return;
                                        }
                                        showMessage(name, pid.message, pid.date_created, pid.system, pid.avatar);
                                    });
                                }
                            })
                            .fail(function () {
                                console.log(false);
                            });
                }
            }
            if (type === 'chat') {
                ui_handler.toggleInstaChat();
            } else if (type === 'video') {
                ui_handler.toggleInstaVideo();
            } else if (type === 4 && !is_widget_opened) {
                ui_handler.toggleInstaVideo(4);
            }
            document.getElementById('newdev_chat_message1').focus();
            fileInput = document.querySelector('input#filetransfer');
            setOnlineVisitors();
        };



        var slideScreen = function (start) {
            $('.new_chat_badge_container').hide();
            if ($('.wd-chat-box').is(':visible')) {
                ui_handler.displayVideoOnly();
                if (start === true) {
                    startVideo(false);
                }
            } else {
                ui_handler.displayChatOnly();
            }
        };

        var setOnline = function (sess) {
            if ((queryString.broadcast || queryString.addbroadcast) && svConfigs.broadcastUrl) {

                if (!queryString.isAdmin && duration && datetime) {
                    var currDate = new Date();
                    var dateHalfHour = new Date(datetime);
                    dateHalfHour.setMinutes(dateHalfHour.getMinutes() + parseInt(duration));

                    if (currDate > new Date(datetime) && dateHalfHour > currDate) {
                    } else {
                        var date1 = new Date();
                        var date2 = new Date(datetime);
                        var delta = Math.abs(date2 - date1) / 1000;
                        var days = Math.floor(delta / 86400);
                        delta -= days * 86400;
                        var hours = Math.floor(delta / 3600) % 24;
                        delta -= hours * 3600;
                        var minutes = Math.floor(delta / 60) % 60;
                        delta -= minutes * 60;

                        diffString = '';
                        console.log(smartVideoLocale.msgStore);
                        if (days > 0) {
                            var pl = (days > 1) ? smartVideoLocale.msgStore['days'] : smartVideoLocale.msgStore['day'];
                            diffString = days + ' ' + pl;
                        }
                        if (days === 0 && hours > 0) {
                            var plh = (hours > 1) ? smartVideoLocale.msgStore['hours'] : smartVideoLocale.msgStore['hour'];
                            diffString = hours + ' ' + plh;
                        }
                        if (days === 0 && hours == 0 && minutes > 0) {
                            diffString = minutes + ' ' + smartVideoLocale.msgStore['minutes'];
                        }
                        if (diffString && new Date(datetime) > currDate) {
                            var info = smartVideoLocale.msgStore['notexactAppointment'];
                            var meetingInfo = info.replace('{{timemeeting}}', datetime);
                            var meetingInfo = meetingInfo.replace('{{diffString}}', diffString);
                            toggleNotification(meetingInfo, true);

                        } else {
                            toggleNotification(smartVideoLocale.msgStore['appointmentPast'], true);

                        }
                        ui_handler.displayChatOnly();
                        ui_handler.setDisabled(true);
                        return;
                    }
                }

                if (svConfigs.serverSide.token) {
                    ui_handler.setDisabled(true);
                    $.ajax({
                        type: 'POST',
                        url: lsRepUrl + 'server/script.php',
                        data: {'type': 'logintoken', 'isAdmin': (queryString.isAdmin), 'token': token, 'roomId': room}
                    })
                            .done(function (data) {
                                if (data) {
                                    data = JSON.parse(data);
                                    ui_handler.setDisabled(false);
                                    if (queryString.isAdmin) {
                                        delete names[0];
                                    }
                                    names[comm_controller.getSessionId()] = {name: data.first_name + ' ' + data.last_name, avatar: (svConfigs.agentAvatar) ? lsRepUrl + svConfigs.agentAvatar : lsRepUrl + 'img/small-avatar.jpg', isAdmin: (queryString.isAdmin), username: data.username};
                                    names[comm_controller.getSessionId()].priv = (localStorage.getItem('hasPrivileges'));
                                    comm_controller.setCallerInfo(comm_controller.getSessionId(), names[comm_controller.getSessionId()], true);
                                    data.name = data.first_name + ' ' + data.last_name;
                                    data.isAdmin = (queryString.isAdmin);
                                    delete data.password;

                                    if (queryString.token && queryString.broadcast && !localStorage.getItem('prd')) {
                                        setTimeout(function () {
                                            toggleNotification(smartVideoLocale.msgStore['welcomeBroadcast'], true);
                                            $('#welcomeBroadcast').click(function () {
                                                toggleNotification('', false);
                                            });
                                        }, 100);
                                    }


                                    localStorage.setItem('prd', JSON.stringify(data));
                                    toggleNotification('', false);
                                    ui_handler.setDisabled(false);
                                    setOnlineVisitors();

                                } else {
                                    svConfigs.broadcastUrl = '';
                                    toggleNotification(smartVideoLocale.msgStore['notValidToken'], true);
                                    localStorage.removeItem('prd');
                                    comm_controller.setClose(comm_controller.getSessionId());
                                    ui_handler.displayChatOnly();
                                    ui_handler.setDisabled(true);
                                    return;
                                }
                            })
                            .fail(function () {
                                //
                            });

                } else {
                    delete names[0];
                    if (localStorage.getItem('prd')) {
                        var callerInfo = localStorage.getItem('prd');
                        if (callerInfo) {
                            callerInfo = JSON.parse(callerInfo);
                        }
                        names[comm_controller.getSessionId()] = {name: (callerInfo) ? callerInfo.name : caller_name, avatar: (callerInfo) ? callerInfo.avatar : caller_avatar, email: (callerInfo) ? callerInfo.email : caller_email};
                        comm_controller.setCallerInfo(comm_controller.getSessionId(), names[comm_controller.getSessionId()], true);
                    } else {
                        if (queryString.isAdmin) {
                            names[comm_controller.getSessionId()] = {name: (svConfigs.agentName) ? svConfigs.agentName : '', avatar: (svConfigs.agentAvatar) ? svConfigs.agentAvatar : lsRepUrl + 'img/small-avatar.jpg'};
                        } else {
                            names[comm_controller.getSessionId()] = {name: guestName(comm_controller.getSessionId()), avatar: lsRepUrl + 'img/small-avatar.jpg'};
                            comm_controller.setCallerInfo(comm_controller.getSessionId(), names[comm_controller.getSessionId()], false);
                        }
                    }
                    localStorage.setItem('prd', JSON.stringify(names[comm_controller.getSessionId()]));
                    toggleNotification('', false);
                    ui_handler.setDisabled(false);
                    setOnlineVisitors();
                }

                return;
            }
            if (!comm_controller.getVideoSessions() && !comm_controller.getScreenStream() && !$('#remoteScreenChat').is(":visible")) {
                openWidget('chat');
                if (!queryString.isAdmin && duration && datetime) {
                    var currDate = new Date();
                    var dateHalfHour = new Date(datetime);
                    dateHalfHour.setMinutes(dateHalfHour.getMinutes() + parseInt(duration));

                    if (currDate > new Date(datetime) && dateHalfHour > currDate) {
                    } else {
                        var date1 = new Date();
                        var date2 = new Date(datetime);
                        var delta = Math.abs(date2 - date1) / 1000;
                        var days = Math.floor(delta / 86400);
                        delta -= days * 86400;
                        var hours = Math.floor(delta / 3600) % 24;
                        delta -= hours * 3600;
                        var minutes = Math.floor(delta / 60) % 60;
                        delta -= minutes * 60;

                        diffString = '';
                        console.log(smartVideoLocale.msgStore);
                        if (days > 0) {
                            var pl = (days > 1) ? smartVideoLocale.msgStore['days'] : smartVideoLocale.msgStore['day'];
                            diffString = days + ' ' + pl;
                        }
                        if (days === 0 && hours > 0) {
                            var plh = (hours > 1) ? smartVideoLocale.msgStore['hours'] : smartVideoLocale.msgStore['hour'];
                            diffString = hours + ' ' + plh;
                        }
                        if (days === 0 && hours == 0 && minutes > 0) {
                            diffString = minutes + ' ' + smartVideoLocale.msgStore['minutes'];
                        }
                        if (diffString && new Date(datetime) > currDate) {
                            var info = smartVideoLocale.msgStore['notexactAppointment'];
                            var meetingInfo = info.replace('{{timemeeting}}', datetime);
                            var meetingInfo = meetingInfo.replace('{{diffString}}', diffString);
                            toggleNotification(meetingInfo, true);

                        } else {
                            toggleNotification(smartVideoLocale.msgStore['appointmentPast'], true);

                        }
                        ui_handler.displayChatOnly();
                        ui_handler.setDisabled(true);
                        return;
                    }
                }

                if (!queryString.isAdmin && requirePass) {
                    toggleNotification('', false);

                    if (localStorage.getItem('prd')) {
                        svConfigs.entryForm.enabled = false;
                        var callerInfo = localStorage.getItem('prd')
                        comm_controller.setCallerInfo(comm_controller.getSessionId(), JSON.parse(callerInfo), false);
                    } else {

                        $('#ng_info').show();
                        ui_handler.displayChatOnly();
                        ui_handler.setDisabled(true);
                        if (visitorName) {
                            $("#ng_caller_name").hide();
                        }
                        $("#ng_password").show();

                        $('#continue-button').on('click', function () {
                            var req = {};
                            if (visitorName) {
                                req.name = visitorName;
                            } else {
                                req.name = $('#ng_caller_name').val();
                            }
                            req.password = $('#ng_password').val();
                            comm_controller.setCallerInfo(comm_controller.getSessionId(), req, false);
                            localStorage.setItem('prdTmp', JSON.stringify(req));
                        });
                        return;
                    }

                }

                if (visitorName && !queryString.isAdmin) {
                    svConfigs.entryForm.enabled = false;
                    var req = {'name': visitorName};
                    localStorage.setItem('prd', JSON.stringify(req));
                }

                if (localStorage.getItem('prd')) {
                    svConfigs.entryForm.enabled = false;
                    var callerInfo = localStorage.getItem('prd')
                    comm_controller.setCallerInfo(comm_controller.getSessionId(), JSON.parse(callerInfo), false);
                }

                if ((svConfigs.entryForm.enabled) && !queryString.isAdmin) {
                    var buttonClick = function () {
                        caller_name = $('#ng_caller_name').val();
                        caller_email = $('#ng_caller_email').val();
                        caller_avatar = $('#ng_caller_avatar').val();
                        toggleNotification('', false);
                        var name = (caller_name) ? caller_name : '';
                        var email = (caller_email) ? ' (' + caller_email + ')' : '';
                        var req = {'name': caller_name, 'email': caller_email, 'avatar': caller_avatar};

                        if (svConfigs.serverSide && svConfigs.serverSide.loginForm) {
                            ui_handler.setDisabled(true);
                            $('#ng_info').show();
                            $.ajax({
                                type: 'POST',
                                url: lsRepUrl + 'server/script.php',
                                data: {'type': 'login', 'email': $('#ng_caller_email').val(), 'password': $('#ng_password').val()}
                            })
                                    .done(function (data) {
                                        if (data) {
                                            ui_handler.setDisabled(false);
                                            $('#ng_info').hide();
                                            comm_controller.setCallerInfo(comm_controller.getSessionId(), req, false);
                                            localStorage.setItem('prd', JSON.stringify(req));
                                        } else {
                                            toggleNotification(smartVideoLocale.msgStore['notValidPassword'], true);
                                        }
                                    })
                                    .fail(function () {
                                        //
                                    });

                        } else {
                            localStorage.setItem('prd', JSON.stringify(req));
                            if (svConfigs.entryForm.private && requirePass) {
                                req.password = $('#ng_password').val();
                            } else {
                                $('#ng_info').hide();
                                $('#continue-button').off();
                                ui_handler.setDisabled(false);
                            }
                            comm_controller.setCallerInfo(comm_controller.getSessionId(), req, false);
                        }
                    };
                    if (svConfigs.entryForm.required || svConfigs.entryForm.private) {
                        var validateFields = function () {
                            var validateClicks = function (e) {
                                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                if (e.keyCode == 13 && $("#ng_caller_name").val() !== '' && $("#ng_caller_email").val() !== '' && filter.test($("#ng_caller_email").val())) {
                                    buttonClick();
                                }

                                var additionEmail = (svConfigs.entryForm.showEmail) ? ($("#ng_caller_email").val() !== '' && filter.test($("#ng_caller_email").val())) : true;
                                var additionAvatar = (svConfigs.entryForm.showAvatar) ? ($("#ng_caller_avatar").val() !== '') : true;
                                if ($("#ng_caller_name").val() !== '' && additionEmail && additionAvatar) {
                                    $('#continue-button').removeClass('disabled');
                                } else {
                                    $('#continue-button').addClass('disabled');
                                }
                            };
                            $("#ng_caller_name").keyup(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_caller_email").keyup(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_password").keyup(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_caller_avatar").keyup(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_caller_name").blur(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_caller_email").blur(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_password").blur(function (e) {
                                validateClicks(e);
                            });
                            $("#ng_caller_avatar").blur(function (e) {
                                validateClicks(e);
                            });
                        };
                        if ((svConfigs.entryForm.private && requirePass) || svConfigs.serverSide.loginForm) {
                            isOnline = false;
                            $("#ng_password").show();
                        }
                        if (svConfigs.entryForm.showEmail) {
                            $("#ng_caller_email").show();
                        }
                        if (svConfigs.entryForm.showAvatar) {
                            $("#ng_caller_avatar").show();
                        }
                        validateFields();
                        $('#continue-button').addClass('disabled');

                    }
                    ui_handler.displayChatOnly();
                    ui_handler.setDisabled(true);
                    $('#ng_info').show();
                    setTimeout(function () {
                        $('#ng_caller_name').focus();
                    }, 500);

                    $('#continue-button').off();

                    $('#continue-button').on('click', function () {
                        buttonClick();
                    });
                    svConfigs.showEntryForm = false;

                } else {
                    if (queryString.isAdmin) {
                        delete names[0];
                        names[comm_controller.getSessionId()] = {name: (svConfigs.agentName) ? svConfigs.agentName : '', avatar: (svConfigs.agentAvatar) ? svConfigs.agentAvatar : lsRepUrl + 'img/small-avatar.jpg'};
                        comm_controller.setCallerInfo(comm_controller.getSessionId(), names[comm_controller.getSessionId()], true);
                    }
                    if (!isOnline) {
                        ui_handler.setDisabled(true);
                    } else {
                        ui_handler.displayChatOnly();
                        toggleNotification('', false);
                        ui_handler.setDisabled(false);
                    }
                    if (svConfigs.videoScreen.greenRoom) {
                        startGreenRoom();
                        $('#startVideoButton').off();
                        $('#startVideoButton').click(function () {
                            if (window.stream) {
                                window.stream.getTracks().forEach(function (track) {
                                    track.stop();
                                });
                            }
                            ui_handler.restoreVideoBox();
                            ui_handler.toggleInstaVideo(false);
                            var type = (video_on) ? 'Video' : 'Audio';
                            comm_controller.initCall(type, false, videoSource, comm_controller.getSessionId(), audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                        });
                    }
                }
                if (!queryString.isAdmin) {
                    var callerInfo = localStorage.getItem('prd');
                    if (callerInfo) {
                        callerInfo = JSON.parse(callerInfo);
                    }
                    names[comm_controller.getSessionId()] = {name: (callerInfo) ? callerInfo.name : caller_name, avatar: (callerInfo) ? callerInfo.avatar : caller_avatar, email: (callerInfo) ? callerInfo.email : caller_email};
                }

            } else {
                if (comm_controller.getScreenStream()) {
                    comm_controller.startScreenShare(screenshareStream, true, sess);
                    comm_controller.addStreamStopListener(screenshareStream, function () {
                        hangUpScreenShare();
                    });
                }
            }
            setOnlineVisitors();
        };


        var endMeeting = function (update) {

            if (update) {
                $.ajax({
                    type: 'POST',
                    url: lsRepUrl + 'server/script.php',
                    data: {'type': 'endmeeting', 'agentId': agentId, 'roomId': room}
                })
                        .done(function (data) {
                            setTimeout(function () {
                                toggleNotification(smartVideoLocale.msgStore['meetingHasEnded'], true);
                                localStorage.removeItem('prd');
                                comm_controller.setClose(comm_controller.getSessionId());
                                ui_handler.displayChatOnly();
                                ui_handler.setDisabled(true);
                                isOnline = false;
                            }, 150);
                        })
                        .fail(function () {
                            //
                        });
            } else {
                if (svConfigs.serverSide.feedback == true && !queryString.isAdmin && !getCookie('lsvRateRoom') && !comm_controller.getVideoSessions()) {
                    $('#feedback_form').show();
                    $('#feedback-button').click(function () {
                        $('#feedback_form').hide();
                        if (token) {
                            var userId = token;
                        } else {
                            userId = (names[comm_controller.getSessionId()].name) ? names[comm_controller.getSessionId()].name : guestName(comm_controller.getSessionId());
                        }
                        $.ajax({
                            type: 'POST',
                            url: lsRepUrl + 'server/script.php',
                            data: {'type': 'feedback', 'roomId': room, 'sessionId': comm_controller.getSessionId(), 'rate': $('#ratestars').rateit('value'), 'text': $('#feedback_message').val(), 'userId': userId}
                        })
                                .done(function (data) {
                                    if (data) {
                                        setCookie('lsvRateRoom', '1', 240);
                                    }

                                })
                                .complete(function () {
                                    setCookie('lsvRateRoom', '1', 240);
                                })
                                .fail(function () {
                                    console.log(false);
                                });

                    });
                }
            }
            return;
        };

        var setOffline = function () {
            if (!comm_controller.getVideoSessions() && !comm_controller.getScreenStream() && !$('#remoteScreenChat').is(":visible")) {
                ui_handler.setDisabled(true);
                if (smartVideoLocale.msgStore['waitingOtherParty']) {
                    var waitingToCome = smartVideoLocale.msgStore['waitingOtherParty'];
                } else {
                    waitingToCome = 'Waiting for the other party to join';
                }
                toggleNotification(waitingToCome, true);
            }
            setOnlineVisitors();
        };

        var remoteVideoScreens = function (hide) {
            $('.sourcevideo').each(function () {
                var id = $(this).data('id');
                if (hide) {
                    $('#remoteVideo' + id).hide();
                    $('#remoteVideoSpan' + id).hide();
                } else {
                    $('#remoteVideo' + id).show();
                    $('#remoteVideoSpan' + id).show();
                }
            });
        };

        var setVideoScreens = function () {

            var countVisibleVideo = $('.sourcevideo:visible').length;
            var percents = (countVisibleVideo > 1) ? '50' : '100';
            if (countVisibleVideo > 0) {
                $('.sourcevideo').each(function () {
                    if ($(this).is(":visible")) {
                        $(this).css('position', 'static');
                        $(this).css('width', percents + '%');
                        $(this).css('float', 'left');
                        $(this).css('max-height', $('.wd-video').css('height'));
                        var post = $(this).position();
                        var id = $(this).data('id');
                        $('#remoteVideoSpan' + id).css({top: 10 + post.top + 'px', left: 15 + post.left + 'px'});
                    }
                });
            } else {
                $('.sourcevideo').each(function () {
                    if ($(this).is(":visible")) {
                        var id = $(this).data('id');
                        $('#remoteVideoSpan' + id).remove();
                        $(this).remove();
                    }
                });
            }
        };

        var whiteboardTools = function () {
            lsDesigner.setSelected('pencil');

            lsDesigner.setTools({
                line: true,
                arrow: true,
                pencil: true,
                marker: true,
                dragSingle: true,
                dragMultiple: true,
                eraser: true,
                pdf: true,
                rectangle: true,
                arc: true,
                text: true,
                image: true,
                zoom: true,
                lineWidth: true,
                colorsPicker: true,
                extraOptions: true,
                undo: true
            });
            lsDesigner.icons = {
                pencil: lsRepUrl + 'img/whiteboard/pencil.png',
                marker: lsRepUrl + 'img/whiteboard/brush.png',
                eraser: lsRepUrl + 'img/whiteboard/eraser.png',
                text: lsRepUrl + 'img/whiteboard/text.png',
                image: lsRepUrl + 'img/whiteboard/image.png',
                dragSingle: lsRepUrl + 'img/whiteboard/dragSingle.png',
                dragMultiple: lsRepUrl + 'img/whiteboard/dragMultiple.png',
                line: lsRepUrl + 'img/whiteboard/line.png',
                arrow: lsRepUrl + 'img/whiteboard/arrow.png',
                pdf: lsRepUrl + 'img/whiteboard/pdf.png',
                zoom_in: lsRepUrl + 'img/whiteboard/zoom_in.png',
                zoom_out: lsRepUrl + 'img/whiteboard/zoom_out.png',
                arc: lsRepUrl + 'img/whiteboard/arc.png',
                rectangle: lsRepUrl + 'img/whiteboard/rectangle.png',
                lineWidth: lsRepUrl + 'img/whiteboard/lineWidth.png',
                undo: lsRepUrl + 'img/whiteboard/undo.png',
                colorsPicker: lsRepUrl + 'img/whiteboard/colorsPicker.png',
                extraOptions: lsRepUrl + 'img/whiteboard/extraOptions.png'
            };
            lsDesigner.addSyncListener(function (data) {
                comm_controller.sendWhiteboardData(data);

                if (queryString.token) {
                    $.ajax({
                        type: 'POST',
                        url: lsRepUrl + 'server/script.php',
                        data: {'type': 'adddrawing', 'drawing': JSON.stringify(data), 'roomId': room}
                    })
                            .done(function (data) {
//                                console.log(data);
                            })
                            .fail(function () {
                                return;
                            });
                }
            });
        };

        function loadHtml() {
            console.log('loadHtml');

            var widget_css_link = $('<link>', {
                rel: 'stylesheet',
                type: 'text/css',
                href: lsRepUrl + 'css/' + widget_css + '?v=' + currVersion
            });
            $(document.body).append($container);

            widget_css_link.appendTo('head');

            if (svConfigs.serverSide.feedback == true) {
                var rateit_css_link = $('<link>', {
                    rel: 'stylesheet',
                    type: 'text/css',
                    href: lsRepUrl + 'css/' + rateit_css + '?v=' + currVersion
                });
                rateit_css_link.appendTo('head');
            }

            $.get(lsRepUrl + 'pages/widget.html?v=' + currVersion, function (widget_data) {
                var widgetHtml = 'pages/widget.agent.new.html?v=' + currVersion;

                $.get(lsRepUrl + widgetHtml, function (data) {
                    $container.append(widget_data);
                    $('#agent_widget').append(data);
                    ui_handler.setWidgetValues();


                    if (svConfigs.serverSide.checkRoom) {
                        $.ajax({
                            type: 'POST',
                            url: lsRepUrl + 'server/script.php',
                            data: {'type': 'getroom', 'token': token, 'isAdmin': (queryString.isAdmin), 'roomId': room}
                        })
                                .done(function (data) {
                                    if (!data) {
                                        endMeeting(true);
                                        return;
                                    }
                                })
                                .fail(function () {
                                    return;
                                });

                    }


                    $(document).on('LSLocaleUpdated', function (e) {
                        $('#cancel_call_button span').html(smartVideoLocale.msgStore['Cancel']);
                        $('#waitingToConnect').html(smartVideoLocale.msgStore['waitingToConnect']);
                        $('#answer_audiocall_button').attr('title', smartVideoLocale.msgStore['answerWithAudio']);
                        $('#answer_audiocall_button1').attr('title', smartVideoLocale.msgStore['answerWithAudio']);
                        $('#callAudioButton_4').attr('title', smartVideoLocale.msgStore['callWithAudio']);
                        $('#callAudioButton_1').attr('title', smartVideoLocale.msgStore['callWithAudio']);
                        $('#call_audio').attr('title', smartVideoLocale.msgStore['callWithAudio']);
                        $('#answer_call_button1').attr('title', smartVideoLocale.msgStore['answerWithVideo']);
                        $('#answer_call_button').attr('title', smartVideoLocale.msgStore['answerWithVideo']);
                        $('#reject_call_button').attr('title', smartVideoLocale.msgStore['rejectCall']);
                        var incomingText = smartVideoLocale.msgStore['incomingText'];
                        $('#incoming_text').html(incomingText.replace('{{caller_name}}', peer_name));
                        $('#callButton_4').attr('title', smartVideoLocale.msgStore['callWithVideo']);
                        $('#callButton_1').attr('title', smartVideoLocale.msgStore['callWithVideo']);
                        $('#call_video').attr('title', smartVideoLocale.msgStore['callWithVideo']);
                        $('#exit_meeting').attr('title', smartVideoLocale.msgStore['exitMeeting']);
                        $('#file_transfer').attr('title', smartVideoLocale.msgStore['fileTransfer']);
                        $('#showHideVideo').attr('title', smartVideoLocale.msgStore['showHideVideo']);
                        $('#showHideAudio').attr('title', smartVideoLocale.msgStore['showHideAudio']);
                        $('.wd-v-share').attr('title', smartVideoLocale.msgStore['startShare']);
                        $('#startscreenshare').attr('title', smartVideoLocale.msgStore['startShare']);
                        $('#stopscreenshare').attr('title', smartVideoLocale.msgStore['stopShare']);
                        $('.wd-v-stopshare').attr('title', smartVideoLocale.msgStore['stopShare']);
                        $('#cameraSwitch').attr('title', smartVideoLocale.msgStore['cameraSwitch']);
                        $('#hangupButton').attr('title', smartVideoLocale.msgStore['hangupButton']);
                        $('#enableScreenShare').html(smartVideoLocale.msgStore['enableScreenShare']);
                        $('#screensharelink').attr('src', 'https://chrome.google.com/webstore/detail/' + svConfigs.chromePluginId);
                        $('.swipe_text_video').html(smartVideoLocale.msgStore['videoScreen']);
                        $('.swipe_text').html(smartVideoLocale.msgStore['chatScreen']);
                        $('.login-wd-title').html(smartVideoLocale.msgStore['nameFieldForm']);
                        $('#continue-button').html(smartVideoLocale.msgStore['continueButton']);
                        $('#ng_caller_name').attr('placeholder', smartVideoLocale.msgStore['namePlaceholder']);
                        $('#ng_caller_avatar').attr('placeholder', smartVideoLocale.msgStore['avatarPlaceholder']);
                        $('#ng_password').attr('placeholder', smartVideoLocale.msgStore['passwordPlaceholder']);
                        $('#answer_audiocall_button span').html(smartVideoLocale.msgStore['audio']);
                        $('#answer_call_button span').html(smartVideoLocale.msgStore['video']);
                        $('#reject_call_button span').html(smartVideoLocale.msgStore['reject']);
                        $('.wd-v-recording recording-on').attr('title', smartVideoLocale.msgStore['stopRecording']);
                        $('.wd-v-recording recording-off').attr('title', smartVideoLocale.msgStore['startRecording']);
                        $('.recordingIcon').attr('title', smartVideoLocale.msgStore['recording']);
                        $('#whiteboard').attr('title', smartVideoLocale.msgStore['whiteboard']);
                        $('#raisehand').attr('title', smartVideoLocale.msgStore['raiseHand']);
                        $('.recordinglink').html(smartVideoLocale.msgStore['previewRecording']);
                        $('.acceptFile').html(smartVideoLocale.msgStore['acceptFile']);
                        $('.rejectFile').html(smartVideoLocale.msgStore['rejectFile']);
                        $('#cleanCanvas').attr('title', smartVideoLocale.msgStore['wb_clearall']);
                        var notSupportedText = (isiPhone) ? smartVideoLocale.msgStore['notSupportedIos'] : smartVideoLocale.msgStore['notSupportedError'];
                        $('#not_supported').html(notSupportedText);
                        $('#startVideoButton').html(smartVideoLocale.msgStore['continueToCall']);
                        $('#chooseVideoAudio').html(smartVideoLocale.msgStore['chooseOptions']);
                        $('.feedback-title').html(smartVideoLocale.msgStore['feedbackFieldForm']);
                        $('#feedback-button').html(smartVideoLocale.msgStore['feedbackButton']);
                    });

                    var options = {
                        lsRepUrl: lsRepUrl,
                        lang: svConfigs.smartVideoLanguage
                    };

                    smartVideoLocale.init(options, jQuery);

                    if ((queryString.broadcast || queryString.addbroadcast) && svConfigs.broadcastUrl) {
                        ui_handler.toggleWidget();
                        ui_handler.toggleInstaChat();
                        ui_handler.displayVideoOnly();
                        ui_handler.toggleHeaderChat();
                        if (queryString.broadcast) {
                            $('#raisehand_div').show();
                        }


                        var startBroadcast = function () {
                            loadScript(svConfigs.broadcastUrl + 'socket.io/socket.io.js', function () {
                                var startBroadcastLocal = function (roomId) {
                                    remoteVideoSessions = 1;
                                    connection.extra.broadcaster = true;
                                    DetectRTC.load(function () {

                                        connection.openOrJoin(roomId, function (isRoomExist, roomid, error) {
                                            if (error) {
                                                console.error('openOrJoin', error, roomid);
                                                return;
                                            }
                                            afterConnectingSocket();
                                            if (!connection.isInitiator) {
                                                var initialStatus = connection.dontCaptureUserMedia;
                                                connection.dontCaptureUserMedia = true;
                                                // each user must create a separate room as well!
                                                connection.open(connection.userid, function (isRoomOpened, roomid, error) {
                                                    if (error) {
                                                        console.error('open', error, roomid);
                                                        return;
                                                    }
                                                    connection.dontCaptureUserMedia = initialStatus;
                                                    connection.isInitiator = false;
                                                });
                                            }
                                        });
                                        if (queryString.isAdmin && (isChrome || isFirefox) && (!isiPhone && !isAndroid) && svConfigs.recording.enabled == true && svConfigs.recording.autoStart == false) {
                                            $('.wd-v-recording').show();
                                        }
                                        $('#callButton_1').addClass('disabled');
                                        $('#hangupBroadcastButton').click(function () {
                                            stopRecording(true);
                                            connection.onbeforeunload();
//                                            connection.disconnect();
                                            connection.close();
                                            ui_handler.toggleInstaChat();
                                            ui_handler.displayVideoOnly();
                                            $('#callButton_1').removeClass('disabled');
                                            toggleNotification('', false);
                                            $("#hangupBroadcastButton").hide();
                                            if (localStorage.getItem('hasPrivileges')) {
                                                localStorage.removeItem('hasPrivileges');
                                                location.reload();
                                            }

                                        });
                                    });
                                };

                                var connection = new RTCMultiConnection();
                                connection.autoCloseEntireSession = true;
                                connection.socketURL = svConfigs.broadcastUrl;
                                connection.socketMessageEvent = 'multi-broadcasters-demo';
//                                connection.socketMessageEvent = 'scalable-media-broadcast-demo';
                                connection.session = {
                                    audio: true,
                                    video: (queryString.isAdmin) ? true : false,
                                    broadcast: true
                                };
                                connection.sdpConstraints.mandatory = {
                                    OfferToReceiveAudio: true,
                                    OfferToReceiveVideo: (queryString.isAdmin) ? true : false,
                                };
                                connection.mediaConstraints = {
                                    audio: true,
                                    video: (queryString.isAdmin) ? true : false
                                };
                                connection.videosContainer = document.getElementById('video_container_chat');
                                connection.onstream = function (event) {
                                    if ($('#' + event.userid).length === 0 && event.stream.isVideo == true) {
                                        var mediaElement = document.createElement('video');
                                        mediaElement.id = event.userid;
                                        mediaElement.setAttribute('class', 'broadcastvideo');
                                        try {
                                            mediaElement.setAttributeNode(document.createAttribute('autoplay'));
                                            mediaElement.setAttributeNode(document.createAttribute('playsinline'));
                                            mediaElement.setAttributeNode(document.createAttribute('videoautoplay'));
                                        } catch (e) {
                                            mediaElement.playsinline = true;
                                            mediaElement.autoplay = true;
                                            mediaElement.videoautoplay = true;
                                        }
                                        if (event.type === 'local') {
                                            mediaElement.muted = true;
                                        }
                                        var mediaStream = event.stream;
                                        if ('srcObject' in mediaElement) {
                                            mediaElement.srcObject = mediaStream;
                                        } else {
                                            mediaElement[!!navigator.mozGetUserMedia ? 'mozSrcObject' : 'src'] = !!navigator.mozGetUserMedia ? mediaStream : (window.URL || window.webkitURL).createObjectURL(mediaStream);
                                        }
                                        comm_controller.addBroadcastStream(event.userid, mediaStream);
                                        connection.videosContainer.appendChild(mediaElement);
                                        var percents = ($('.broadcastvideo').length > 1) ? '50' : '100';
                                        $('.broadcastvideo').each(function () {
                                            if ($(this).is(":visible")) {
                                                $(this).css('position', 'static');
                                                $(this).css('width', percents + '%');
                                                $(this).css('float', 'left');
                                                $(this).css('max-height', $('.wd-video-box').css('height'));
                                            }
                                        });
                                    }

                                    var playPromise = mediaElement.play();
                                    if (playPromise !== undefined) {
                                        playPromise.then(function () {
                                            //console.log('playing');
                                        }).catch(function (error) {

                                            toggleNotification(smartVideoLocale.msgStore['incomingBroadcast'], true);
                                            $('#incomingBroadcast').click(function () {
                                                toggleNotification('', false);
                                                mediaElement.play();
                                            });
                                        });
                                    }

                                    if (event.type === 'remote' && connection.isInitiator) {
                                        var participants = [];
                                        connection.getAllParticipants().forEach(function (pid) {
                                            participants.push({
                                                pid: pid,
                                                broadcaster: connection.peers[pid].extra.broadcaster === true
                                            });
                                        });
                                        connection.socket.emit(connection.socketCustomEvent, {
                                            participants: participants
                                        });
                                    } else if (event.type === 'remote' && !connection.extra.broadcaster) {
                                        connection.socket.emit(connection.socketCustomEvent, {
                                            giveAllParticipants: true
                                        });
                                    }
                                };

                                var afterConnectingSocket = function () {
                                    connection.socket.on(connection.socketCustomEvent, function (message) {
                                        if (message.participants && !connection.isInitiator && !connection.extra.broadcaster) {
                                            message.participants.forEach(function (participant) {
                                                if (participant.pid === connection.userid)
                                                    return;
                                                if (connection.getAllParticipants().indexOf(participant.pid) !== -1)
                                                    return;
                                                if (!connection.extra.broadcaster && participant.broadcaster === false)
                                                    return;
                                                connection.join(participant.pid, function (isRoomJoined, roomid, error) {
                                                    if (error) {
                                                        return;
                                                    }
                                                });
                                            });
                                        }
                                        if (message.giveAllParticipants && connection.isInitiator) {
                                            var participants = [];
                                            connection.getAllParticipants().forEach(function (pid) {
                                                participants.push({
                                                    pid: pid,
                                                    broadcaster: connection.peers[pid].extra.broadcaster === true
                                                });
                                            });
                                            connection.socket.emit(connection.socketCustomEvent, {
                                                participants: participants
                                            });
                                            toggleError(smartVideoLocale.msgStore['broadcastViewers'] + ' ' + participants.length, 5000);
                                        }
                                    });
                                };

                                connection.onleave = function () {
                                    console.log('connection.onleave');
                                };

                                connection.onstreamended = function (event) {
                                    var mediaElement = document.getElementById(event.userid);
                                    if (mediaElement) {
                                        mediaElement.parentNode.removeChild(mediaElement);
                                    }
                                    if (!connection.isInitiator) {
                                        if ($('.broadcastvideo').length == 0) {
                                            toggleError(smartVideoLocale.msgStore['broadcastStopped'], 5000);
                                            location.reload();
                                        } else {
                                            var percents = ($('.broadcastvideo').length > 1) ? '50' : '100';
                                            $('.broadcastvideo').each(function () {
                                                if ($(this).is(":visible")) {
                                                    $(this).css('position', 'static');
                                                    $(this).css('width', percents + '%');
                                                    $(this).css('float', 'left');
                                                    $(this).css('max-height', $('.wd-video').css('height'));
                                                }
                                            });
                                        }
                                    }
                                };

                                var joinBroadcastLooper = function (roomid) {
                                    connection.extra.broadcaster = false;
                                    connection.dontCaptureUserMedia = true;
                                    connection.session.oneway = true;
                                    // join-broadcast looper
                                    (function reCheckRoomPresence() {
                                        connection.checkPresence(roomid, function (isRoomExist, roomid, extra) {
                                            // note: last parametr on checkPresence will be changed in future
                                            // it is expected to return "error" rather than "extra"
                                            // so you can compare: if(error === connection.errors.ROOM_FULL) {}
                                            if (extra._room) {
                                                if (extra._room.isFull) {
                                                    alert('Room is full.');
                                                }
                                            }
                                            if (isRoomExist) {
                                                connection.join(roomid, function (isRoomJoined, roomid, error) {
                                                    if (error) {
                                                        console.error('join', error, roomid);
                                                        return;
                                                    }
                                                    afterConnectingSocket();
                                                });
                                                return;
                                            }
                                            setTimeout(reCheckRoomPresence, 5000);
                                        });
                                    })();
                                    $('.wd-video-c').addClass('disabled');
                                };


                                if (queryString.addbroadcast) {

                                    $('#callButton_1').click(function () {
                                        $('#video_container_chat').show();
                                        startBroadcastLocal(queryString.addbroadcast);
                                        $("#hangupBroadcastButton").show();
                                    });
                                    $("#callAudioButton_1").addClass('disabled');
                                    $('.peer_avatar').hide();

                                }

                                if (queryString.broadcast && !localStorage.getItem('hasPrivileges')) {
                                    localStorage.setItem(connection.socketMessageEvent, queryString.broadcast);
                                    joinBroadcastLooper(queryString.broadcast);
                                }

                                if (queryString.broadcast && localStorage.getItem('hasPrivileges')) {
                                    //$('#video_container_chat').show();
                                    $("#callButton_1").addClass('disabled');
                                    $("#callAudioButton_1").addClass('disabled');
                                    $('.peer_avatar').hide();
                                    $("#hangupBroadcastButton").show();
                                    startBroadcastLocal(queryString.broadcast);
                                    names[comm_controller.getSessionId()].priv = true;
                                    comm_controller.setCallerInfo(comm_controller.getSessionId(), names[comm_controller.getSessionId()], true);
                                }

                                $(document).on('blockUser', function (e) {
                                    if (comm_controller.getSessionId() == e.sessionId) {
                                        hangupCall();
                                        localStorage.removeItem('prd');
                                        comm_controller.setClose(comm_controller.getSessionId());
                                        delete names[e.sessionId];
                                        location.reload();
                                    }
                                });

                                $(document).on('RevokePriveleges', function (e) {
                                    names[e.sessionId].priv = false;
                                    if (comm_controller.getSessionId() == e.sessionId) {
                                        localStorage.removeItem('hasPrivileges');
                                        $("#hangupBroadcastButton").trigger("click");
                                        location.reload();

                                    }
                                });

                                $(document).on('GrantPriveleges', function (e) {
                                    names[e.sessionId].priv = true;
                                    if (comm_controller.getSessionId() == e.sessionId) {
                                        localStorage.setItem('hasPrivileges', true);
                                        location.reload();
                                    }
                                });

                            });

                        };

                        loadScript('../js/rmc.js', function () {
//                        loadScript('../js/RTCMultiConnection.js', function () {
                            $('#localVideo').hide();
                            $('.wd-avatar-agent').hide();
                            $('#video_container_chat').show();
                            $('#wd-widget-content-video').hide();
                            startBroadcast();
                        });

                    } else {
                        comm_controller.checkMediaDevices();
                        $(document).on('RemoteVideoSessions', function (e) {
                            console.log('RemoteVideoSessions', e.count);
                            remoteVideoSessions = e.count;
                        });

                        $(document).on('IncomingCall', function (e) {
                            console.log('IncomingCall', e.sessionId);
                            ui_handler.setWidgetValues();
                            ui_handler.onIncomingVideo();
                            if (autoAcceptVideo || autoAcceptAudio
                                    || svConfigs.videoScreen.autoAcceptVideo || svConfigs.videoScreen.autoAcceptAudio
                                    || e.autoaccept || comm_controller.getVideoSessions()) {
                                setTimeout(function () {
                                    if (svConfigs.videoScreen.greenRoom && !getCookie('lsvGreenRoom')) {
                                        startGreenRoom();
                                        $('#startVideoButton').click(function () {
                                            if (window.stream) {
                                                window.stream.getTracks().forEach(function (track) {
                                                    track.stop();
                                                });
                                            }
                                            inCall.push(e.sessionId);
                                            comm_controller.answerCall(video_on, e.sessionId, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                        });
                                    } else {
                                        if (svConfigs.videoScreen.autoAcceptAudio || autoAcceptAudio) {
                                            video_on = false;
                                        }
                                        if (svConfigs.videoScreen.autoAcceptVideo || autoAcceptVideo) {
                                            video_on = true;
                                        }
                                        ui_handler.setMobileChatOnly();
                                        ui_handler.displayVideoOnly();
                                        inCall.push(e.sessionId);
                                        comm_controller.answerCall(video_on, e.sessionId, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                    }
                                }, 1000);
                            } else {
                                playIncomingCall();
                                ui_handler.toggleRinging(function (accepted) {
                                    if (accepted) {
                                        if (remoteVideoSessions > 0) {
                                            $('#wd-widget-content-video-waiting').show();
                                            setTimeout(function () {
                                                startVideo(true);
                                            }, 1000);
                                            if (!inCall.includes(e.sessionId)) {
//                                            comm_controller.rejectCall();
                                                inCall.push(e.sessionId);
                                                comm_controller.answerCall(video_on, e.sessionId, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);

                                            }
                                        } else {
                                            if (svConfigs.videoScreen.greenRoom && !getCookie('lsvGreenRoom')) {
                                                startGreenRoom();
                                                $('#startVideoButton').click(function () {
                                                    if (window.stream) {
                                                        window.stream.getTracks().forEach(function (track) {
                                                            track.stop();
                                                        });
                                                    }
                                                    inCall.push(e.sessionId);
                                                    comm_controller.answerCall(video_on, e.sessionId, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                                });
                                            } else {
                                                inCall.push(e.sessionId);
                                                comm_controller.answerCall(video_on, e.sessionId, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                            }
                                        }
                                    } else {
                                        comm_controller.rejectCall();
                                    }
                                });
                            }
                        });

                        $('.wd-v-hangup').on('click', function () {
                            hangupCall();
                        });

                        $(document).off('LocalVideoStarted')
                        $(document).on('LocalVideoStarted', function (e) {
                            $('#localVideo').show();
//                        ui_handler.togglePermissionWidget(true);
                            ui_handler.toggleVideoBox(true);
                            try {
                                if (isIEA) {
                                    attachMediaStream(document.querySelector('video#localVideo'), e.stream);
                                } else {
                                    var localVideo = document.querySelector('video#localVideo');
                                    localVideo.srcObject = e.stream;
                                    localVideo.autoplay = true;
                                    localVideo.muted = true;
                                }
//                            $('#localVideo').attr('srcObject', e.stream);
                            } catch (error) {
                                $('#localVideo').attr('src', window.URL.createObjectURL(e.stream));
                            }
                        });


                        $('#cancel_call_button').on('click', function () {
                            hangupCall();
                        });

                        $('#callButton_1').on('click', function () {
                            video_on = video_iphone_on = true;
                            ui_handler.setVideoButton();
                            startVideo(false);
                        });

                        $('#callAudioButton_1').on('click', function () {
                            ringBackStart = true;
                            video_on = false;
                            ui_handler.setVideoButton();
                            startVideo(false);
                        });

                        $(document).on('NotSupportedBrowser', function (e) {
                            comm_controller.rejectCall();
                            ui_handler.setVideoBoxOff('video');
                        });

                        $(document).off('RemoteVideoStarted');
                        $(document).on('RemoteVideoStarted', function (e) {
                            console.log('RemoteVideoStarted');
                            if ((isChrome || isFirefox) && (!isiPhone && !isAndroid) && queryString.isAdmin && svConfigs.recording.enabled == true && svConfigs.recording.autoStart == false) {
                                $('.wd-v-recording').show();
                            }
                            $('#permission_div').hide();
                            $('#video_back').hide();
                            stopIncomingCall();
                            try {
                                if (!$('#remoteVideo' + e.sessionId).length) {
                                    if (e.stream.getVideoTracks().length > 0 || isiPhone) {
                                        var span = $('<span />', {
                                            id: 'remoteVideoSpan' + e.sessionId,
                                            class: 'sourcevideospan'
                                        });
                                        span.appendTo($('#video_container'));
                                        var name = (names[e.sessionId]) ? names[e.sessionId].name : peer_name;
                                        $('#remoteVideoSpan' + e.sessionId).html(name);
                                        var videoClass = 'sourcevideo';
                                    } else {
                                        videoClass = 'sourcevideosmall';
                                    }
                                    var video = $('<video />', {
                                        id: 'remoteVideo' + e.sessionId,
                                        class: videoClass,
                                        videoautoplay: true,
                                        playsinline: true,
                                        muted: false
                                    });
                                    video.appendTo($('#video_container'));
                                    $('#remoteVideo' + e.sessionId).data('id', e.sessionId);

                                    ui_handler.toggleVideoBox(true);
                                    if (isIEA) {
                                        attachMediaStream(document.querySelector('video#remoteVideo' + e.sessionId), e.stream);
                                    } else {
                                        var remoteVideo = document.querySelector('video#remoteVideo' + e.sessionId);
                                        remoteVideo.srcObject = e.stream;
                                        remoteVideo.autoplay = true;
                                        remoteVideo.muted = false;
                                        comm_controller.addStreamStopListener(e.stream, function () {

                                        });
                                    }
                                    setVideoScreens();
                                }
                                if ((isChrome || isFirefox) && (!isiPhone && !isAndroid) && queryString.isAdmin && svConfigs.recording.enabled && svConfigs.recording.autoStart) {
                                    startRecording();
                                }

//                            $('#remoteVideo').attr('srcObject', e.stream);
                            } catch (error) {
                                console.log(error)
                            }

                        });

                        $(document).on('VideoMuted', function (e) {
                            console.log('VideoMuted');
                            video_on = false;
                            ui_handler.setVideoButton();
                        });

                        $(document).on('VideoUnmuted', function (e) {
                            video_on = true;
                            ui_handler.setVideoButton();
                        });

                        $(document).on('AudioMuted', function (e) {
                            audio_on = false;
                            ui_handler.setMuteButton();
                        });

                        $(document).on('AudioUnmuted', function (e) {
                            console.log('AudioUnmuted');
                            audio_on = true;
                            ui_handler.setMuteButton();
                        });

                        $(document).on('RemoteVideoMuted', function (e) {
                            console.log('RemoteVideoMuted');
                            $('#remoteVideo' + e.sessionId).hide();
                        });

                        $(document).on('RemoteVideoUnmuted', function (e) {
                            console.log('RemoteVideoMuted');
                            $('#remoteVideo' + e.sessionId).show();
                        });

                        $(document).on('RemoteAudioMuted', function (e) {
                            console.log('RemoteAudioMuted');
                        });

                        $(document).on('RemoteAudioUnmuted', function (e) {
                            console.log('RemoteAudioUnmuted');
                        });

                        $('#fullscreenButton').on('click', function () {
                            toggleFullScreen();
                        });

                        $('#exitFullscreenButton').on('click', function () {
                            toggleFullScreen();
                        });
                        $('#call_video').off();
                        $('#call_audio').off();

                        $('#call_video').on('click', function () {
                            video_on = true;
                            slideScreen(true);
                        });

                        $('#call_audio').on('click', function () {
                            if (isiPhone) {
                                video_on = true;
                                video_iphone_on = false;
                            } else {
                                video_on = false;
                            }
                            slideScreen(true);

                        });

                        $('.wd-v-text').on('click', function () {
                            slideScreen(false);
                        });


                        $('#slide_video').on('click', function () {
                            slideScreen(false);
                        });

                        if (isAndroid || isiPhone) {
                            $('#mainleft_div').on('swipe', function () {
                                slideScreen(false);
                            });

                            $('.wd-chat-box').on('swipe', function () {
                                slideScreen(false);
                            });
                        }

                        $('.wd-v-nosound').on('click', function () {
                            comm_controller.toggleAudio();
                        });

                        $('.wd-v-sound').on('click', function () {
                            comm_controller.toggleAudio();
                        });

                        $('.wd-v-novideo').on('click', function () {
                            comm_controller.toggleVideo();
                        });

                        $('.wd-v-video').on('click', function () {
                            comm_controller.toggleVideo();
                        });

                        $(document).on('RemoteVideoStopped', function (e) {
                            remoteVideoScreens(true);
                            $('#video_back').show();
                        });

                        $(document).on('MediaDevices', function (e) {
                            videoDevices = e.devices;

                            if (videoDevices.length > 1) {
                                $('#cameraSwitch').show();
                                $('#cameraSwitch').off();
                                $('#cameraSwitch').click(function () {
                                    videoCurrentId = videoCurrentId + 1;
                                    if (videoCurrentId === videoDevices.length) {
                                        videoCurrentId = 0;
                                    }
                                    hangupCall();
                                    video_on = true;
                                    ui_handler.displayVideoOnly();

                                    setTimeout(function () {
                                        startVideo(true, videoDevices[videoCurrentId].value);
                                    }, 1000);
                                });
                            }

                        });

                        $(document).on('RestartVideo', function (e) {
                            hangupCall();
                            video_on = true;
                            ui_handler.displayVideoOnly();
                            setTimeout(function () {
                                startVideo(true);
                            }, 1000);

                        });

                        $(document).on('CallAccepted', function (e) {
                            stopIncomingCall();
                        });

                        $(document).on('CallRejected', function (e) {
                            stopIncomingCall();
                        });

                        $(document).on('CallFailed', function (e) {
                            stopIncomingCall();
                        });
                        $(document).on('LocalVideoStopped', function (e) {
                        });

                        $(document).on('ChatRejected', function (e) {
                        });

                        $(document).off('CallEnded');
                        $(document).on('CallEnded', function (e) {
                            callEnded(e.sessionId);
                        });

                        $(document).on('CheckPopup', function (e) {
                            var name = (names[comm_controller.getSessionId()] && names[comm_controller.getSessionId()].name) ? names[comm_controller.getSessionId()].name : visitorName;
                            comm_controller.setPing(comm_controller.getSessionId(), name);
                        });



                        if (queryString.isAdmin) {
                            comm_controller.adminOnline();
                        }


                    }
                    $(document).off('CallerInfo');
                    $(document).on('CallerInfo', function (e) {
                        if (e.callerInfo.name && names[e.sessionId]) {
                            if (passRoom || e.callerInfo.password) {
                                if (queryString.isAdmin) {
                                    comm_controller.sendCallerBack((e.callerInfo.password == passRoom), e.sessionId);
                                }
                                if (e.callerInfo.password == passRoom) {
                                    updateInfo(e);
                                }
                            } else {
                                if (!svConfigs.serverSide.loginForm && queryString.isAdmin) {
                                    comm_controller.sendCallerBack(true, e.sessionId);
                                }
                                updateInfo(e);
                            }
                        }

                    });

                    $(document).off('AdminPopupOffline');
                    $(document).on('AdminPopupOffline', function (e) {
                        isOnline = false;
                        setOffline();
                    });

                    $(document).off('PopupOffline');
                    $(document).on('PopupOffline', function (e) {
                        isOnline = false;
                        setOffline();
                    });

                    $(document).off('PopupLeft');
                    $(document).on('PopupLeft', function (e) {
                        if (names[e.sessionId] && names[e.sessionId].name && queryString.isAdmin) {
                            var leftText = smartVideoLocale.msgStore['leftChat'];
                            var leftMessage = leftText.replace('{{caller_name}}', names[e.sessionId].name);
                            if (svConfigs.videoScreen && svConfigs.videoScreen.waitingRoom) {
                                toggleError(leftMessage, 5000);
                            } else {
                                showMessage('', leftMessage, null, 'leftChat');
                                if (svConfigs.serverSide.chatHistory) {
                                    saveChat(leftMessage, '', 'leftChat', agentId, '', names);
                                }
                            }
                        }
                        delete names[e.sessionId];
                        setOnlineVisitors();
                    });

                    $('.box-title').on('click', function () {
                        ui_handler.toggleVisitors(true);
                    });

                    $('#toVideo').on('click', function () {
                        if (queryString.isAdmin && queryString.addbroadcast) {
                            ui_handler.displayBroadcast();
                            comm_controller.broadcastSync();
                        } else {
                            ui_handler.displayVideoOnly();
                        }
                    });

                    $('#cleanCanvas').on('click', function () {
                        if (lsDesigner) {
                            lsDesigner.clearCanvas();
                            lsDesigner.sync();
                            comm_controller.sendWhiteboardData();
                            if (queryString.token) {
                                $.ajax({
                                    type: 'POST',
                                    url: lsRepUrl + 'server/script.php',
                                    data: {'type': 'adddrawing', 'drawing': null, 'roomId': room}
                                })
                                        .done(function (data) {
//                                console.log(data);
                                        })
                                        .fail(function () {
                                            return;
                                        });
                            }
                        }
                    });

                    $(document).on('BroadcastSync', function (e) {
                        ui_handler.displayBroadcast();
                    });

                    var reestimateWhiteboard = function (points, width) {
                        var ratioWidth = screen.width / width;
                        points.points.forEach(function (point) {
                            if (point[0] == 'text') {
                                point[1][1] = point[1][1] * ratioWidth;
                                point[1][2] = point[1][2] * ratioWidth;
                            } else if (point[0] == 'image' || point[0] == 'pdf') {
                                point[1][1] = point[1][1] * ratioWidth;
                                point[1][2] = point[1][2] * ratioWidth;
                                point[1][3] = point[1][3] * ratioWidth;
                                point[1][4] = point[1][4] * ratioWidth;
                            } else {
                                point[1][0] = point[1][0] * ratioWidth;
                                point[1][1] = point[1][1] * ratioWidth;
                                point[1][2] = point[1][2] * ratioWidth;
                                point[1][3] = point[1][3] * ratioWidth;
                            }
                        });
                        return points;
                    };

                    $(document).on('WhiteboardSync', function (e) {
                        ui_handler.toggleInstaWhiteboard();
                        var newComer = false;
                        if (!lsDesigner) {
                            newComer = true;
                            lsDesigner = new CanvasDesigner();
                            lsDesigner.widgetHtmlURL = lsRepUrl + 'pages/whiteboard.html';
                            lsDesigner.widgetJsURL = lsRepUrl + 'js/whiteboard.widget.js';

                            if (queryString.isAdmin || svConfigs.whiteboard.allowAnonymous || localStorage.getItem('hasPrivileges')) {
                                whiteboardTools();
                            } else {
                                lsDesigner.setTools({});
                                lsDesigner.setSelected('');
                            }
                            var elem = document.getElementById('whiteboard_canvas')
                            lsDesigner.appendTo(elem);
                        }

                        if (queryString.token && newComer) {
                            setTimeout(function () {
                                $('#tool-box').hide();
                                $.ajax({
                                    type: 'POST',
                                    url: lsRepUrl + 'server/script.php',
                                    data: {'type': 'getdrawing', 'roomId': room}
                                })
                                        .done(function (data) {
                                            var result = JSON.parse(data);
                                            $.each(result, function (i, item) {
                                                var points = JSON.parse(item.drawing);
                                                points = reestimateWhiteboard(points, e.width);
                                                lsDesigner.syncData(points);
                                            });
                                        })
                                        .fail(function () {
                                            return;
                                        });
                            }, 1000);
                        } else {
                            if (newComer) {
                                setTimeout(function () {
                                    var points = JSON.parse(e.whiteboardData);
                                    points = reestimateWhiteboard(points, e.width);
                                    lsDesigner.syncData(points);
                                }, 1000);
                            } else {
                                if (e.whiteboardData) {
                                    var points = JSON.parse(e.whiteboardData);
                                    points = reestimateWhiteboard(points, e.width);
                                    lsDesigner.syncData(points);
                                } else {
                                    lsDesigner.clearCanvas();
                                    lsDesigner.syncData();
                                }

                            }
                        }
                    });

                    $('#mainleft_div').hover(
                            function () {
                                $('.wd-video-c').delay(200).show();
                            },
                            function () {
                                $('.wd-video-c').delay(200).hide();
                            }
                    );


                    $('#whiteboard').off();

                    $('#whiteboard').on('click', function () {
                        ui_handler.toggleInstaWhiteboard();
                        $('#cleanCanvas').show();
                        if ((queryString.isAdmin || localStorage.getItem('hasPrivileges')) && !lsDesigner) {
                            lsDesigner = new CanvasDesigner();
                            lsDesigner.widgetHtmlURL = lsRepUrl + 'pages/whiteboard.html';
                            lsDesigner.widgetJsURL = lsRepUrl + 'js/whiteboard.widget.js';

                            whiteboardTools();

                            var elem = document.getElementById('whiteboard_canvas');
                            lsDesigner.appendTo(elem);
                            comm_controller.sendWhiteboardData('initial');

                            setTimeout(function () {
                                $.ajax({
                                    type: 'POST',
                                    url: lsRepUrl + 'server/script.php',
                                    data: {'type': 'getdrawing', 'roomId': room}
                                })
                                        .done(function (data) {
                                            var result = JSON.parse(data);
                                            $.each(result, function (i, item) {
                                                lsDesigner.syncData(JSON.parse(item.drawing));
                                            });
                                        })
                                        .fail(function () {
                                            return;
                                        });
                            }, 1000);


                        }
                        setTimeout(function () {
                            lsDesigner.sync();
                        }, 2000);
                    });

                    $('#raisehand').off();
                    $('#raisehand').on('click', function () {
                        sendChatMessage('raiseHand');
                        var raiseHandText = smartVideoLocale.msgStore['raiseHandText'];
                        var raiseHand = raiseHandText.replace('{{caller_name}}', names[comm_controller.getSessionId()].name);
                        showMessage('', raiseHand, null, 'raiseHand');
                    });

                    if (svConfigs.whiteboard.enabled == true) {
                        if (queryString.isAdmin || localStorage.getItem('hasPrivileges')) {
                            $('#whiteboard_div').show();
                        }
                        loadScript('../js/canvas-designer-widget.js', function () {

                        });
                    }
                    if (svConfigs.serverSide.roomInfo == true) {
                        if (queryString.isAdmin) {
                            $('#exitmeeting_div').show();
                        }
                    }
                    if (svConfigs.serverSide.feedback == true) {
                        loadScript('../js/jquery.rateit.js', function () {});
                    }

                    $(document).off('ChatMessage');
                    $(document).on('ChatMessage', function (e) {
                        var name = (names[e.sessionId]) ? names[e.sessionId].name : peer_name;
                        var avatar = (names[e.sessionId]) ? names[e.sessionId].avatar : peer_avatar;
                        if (e.msg == 'raiseHand') {
                            var raiseHandText = smartVideoLocale.msgStore['raiseHandText'];
                            var raiseHand = raiseHandText.replace('{{caller_name}}', names[e.sessionId].name);
                            showMessage('', raiseHand, null, 'raiseHand');
                            names[e.sessionId].raiseHand = true;
                            setOnlineVisitors();
                            setTimeout(function () {
                                names[e.sessionId].raiseHand = false;
                                setOnlineVisitors();
                            }, 150 * 1000);
                        } else {
                            showMessage(name, e.msg, null, null, avatar);
                        }
                        if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
                            $('.wd-v-text').hide();
                        } else {
                            $('.new_chat_badge_container').show();
                        }
                    });

                    $('#newdev_chat_message1').keyup(function (e) {
                        if (e.keyCode == 13 && $('#newdev_chat_message1').text()) {
                            var msg = $('#newdev_chat_message1').text();
                            user_act = true;
                            sendChatMessage(msg, true);
                            $('#newdev_chat_message1').html('');
                        }
                    });

                    if ((svConfigs.videoScreen && svConfigs.videoScreen.waitingRoom) || isOnline || queryString.isAdmin) {
                        setTimeout(function () {
                            setOnline();
                        }, 100);
                    } else {
                        setOffline();
                    }

                    $(document).on('FileAccepted', function (e) {
                        comm_controller.createFileConnection();
                    });

                    $(document).on('FileRejected', function (e) {
                        $('li[data-system-attribue="fileTransfer"]').remove();
                        var senderName = (e.sessionId) ? names[e.sessionId].name : peer_name;
                        var rejectedFile = smartVideoLocale.msgStore['rejectedFile'];
                        rejectedFile = rejectedFile.replace('{{caller_name}}', senderName);
                        showMessage('', rejectedFile, null, 'fileTransfer');
                        if (svConfigs.serverSide.chatHistory) {
                            saveChat(rejectedFile, '', 'fileTransfer', agentId, '', names);
                        }
                    });

                    $(document).off('IncomingFileTransfer');
                    $(document).on('IncomingFileTransfer', function (e) {

                        var id = getGuid();
                        var senderName = (e.sessionId) ? names[e.sessionId].name : peer_name;
                        var incomingFile = smartVideoLocale.msgStore['incomingFile'];
                        incomingFile = incomingFile.replace('{{caller_name}}', senderName);
                        showMessage('', incomingFile + e.name + '<br/><a href="javascript:acceptFile();" class="acceptFile">Accept</a> | <a href="javascript:rejectFile();" class="rejectFile">Reject</a>', null, 'acceptReject');
                        if (svConfigs.serverSide.chatHistory) {
                            saveChat(incomingFile + e.name, '', 'acceptReject', agentId, '', names);
                        }
                        window.acceptFile = function () {
                            var data = {name: e.name, size: e.size};
                            $('li[data-system-attribue="acceptReject"]').remove();
                            var txt = smartVideoLocale.msgStore['receivingFile'] + e.name + '<br/><div class="progress"><progress id="progress' + id + '" max="0" value="0"></progress></div><a id="download' + id + '"></a>';
                            showMessage('', txt, null, 'fileTransfer');
                            if (svConfigs.serverSide.chatHistory) {
                                saveChat(smartVideoLocale.msgStore['receivingFile'] + e.name, '', 'fileTransfer', agentId, '', names);
                            }
                            downloadAnchor = document.querySelector('a#download' + id);
                            progressBar = document.querySelector('progress#progress' + id);
                            comm_controller.fileAccepted(data);
                        };

                        window.rejectFile = function () {
                            $('li[data-system-attribue="acceptReject"]').remove();
                            comm_controller.fileRejected(id);
                            comm_controller.closeDataChannels();
                        };
                    });

                    $("#file_transfer").off();
                    $("#file_transfer").on('click', function (e) {
                        $("#filetransfer").click();
                    });

                    $("#filetransfer").off('change', function (e) {

                    });

                    $("#filetransfer").on('change', function (e) {
                        if ($('input#filetransfer').val()) {
                            var id = getGuid();
                            var file = fileInput.files[0];
                            var sendingFile = smartVideoLocale.msgStore['sendingFile'];
                            showMessage('', sendingFile + file.name + '<br/><div class="progress"><progress id="progress' + id + '" max="0" value="0"></progress></div><a id="download' + id + '"></a>', null, 'fileTransfer');
                            downloadAnchor = document.querySelector('a#download' + id);
                            progressBar = document.querySelector('progress#progress' + id);
                            comm_controller.sendFile();
                        }
                    });

                    $(document).off('SendCallerBack');
                    $(document).on('SendCallerBack', function (e) {
                        if (queryString.isAdmin || e.sessionId != comm_controller.getSessionId()) {
                            return;
                        }
                        toggleNotification('', false);
                        if (e.access) {
                            isOnline = true;
                            ui_handler.setDisabled(false);
                            $('#ng_info').hide();
                            $('#continue-button').off();
                            localStorage.setItem('prd', localStorage.getItem('prdTmp'));
                            localStorage.removeItem('prdTmp');
                        } else {
                            isOnline = false;
                            ui_handler.setDisabled(true);
                            toggleNotification(smartVideoLocale.msgStore['notValidPassword'], true);
                            $('#ng_info').show();
                        }
                    });

                    $('#newdev_chat_button1').click(function (e) {
                        sendChatWindowMessage('newdev_chat_message1');
                    });

                    $(document).off('AdminPopupOnline');
                    $(document).on('AdminPopupOnline', function (e) {
                        isOnline = true;
                        delete names[0];
                        names[e.sessionId] = {avatar: agentAvatar, name: agentName};
                        requirePass = (e.pass != undefined && e.pass);
                        setOnline(e.sessionId);
                    });

                    $(document).off('PopupOnline');
                    $(document).on('PopupOnline', function (e) {
                        if (!names[e.sessionId]) {
                            names[e.sessionId] = {avatar: (e.avatar) ? e.avatar : lsRepUrl + 'img/small-avatar.jpg', name: (e.name) ? e.name : guestName(e.sessionId)};
                        }
                        isOnline = true;
                        setOnline(e.sessionId);
                    });

                    $('.wd-v-recording').on('click', function () {
                        if (multiStreamRecorder && multiStreamRecorder.getState() == 'recording') {
                            stopRecording(true);
                        } else {
                            startRecording();
                        }
                    });

                    $(document).on('RemoteStartRecording', function (e) {
                        $('.recordingIcon').show();
                    });

                    $(document).on('RemoteStopRecording', function (e) {
                        $('.recordingIcon').hide();
                    });

                    $(document).off('RemoteScreenShareStarted');
                    $(document).on('RemoteScreenShareStarted', function (e) {
                        console.log('RemoteScreenShareStarted');
                        if (!comm_controller.getStream(comm_controller.getRemoteSessionId())) {
                            ui_handler.restoreVideoBox();
                            ui_handler.displayVideoOnly();
                            ui_handler.toggleInstaChatScreen();
                            $('#remoteScreenChat').show();
                        } else {
                            remoteVideoScreens(true);
                            $('#remoteScreen').show();
                        }
                        try {
                            ui_handler.setScreenDisabled(true);
                            if (comm_controller.getStream(comm_controller.getRemoteSessionId())) {
                                if (isIEA) {
                                    attachMediaStream(document.querySelector('video#remoteScreen'), e.stream);
                                } else {
                                    var remoteVideo = document.querySelector('video#remoteScreen');
                                    remoteVideo.srcObject = e.stream;
                                    remoteVideo.autoplay = true;
                                    remoteVideo.muted = false;
                                }

                            } else {
                                setTimeout(function () {
                                    if (isIEA) {
                                        attachMediaStream(document.querySelector('video#remoteScreenChat'), e.stream);
                                    } else {
                                        var remoteScreenChat = document.querySelector('video#remoteScreenChat');
                                        remoteScreenChat.srcObject = e.stream;
                                        remoteScreenChat.autoplay = true;
                                        remoteScreenChat.muted = false;
                                    }
                                }, 4000);

                            }
//                            $('#remoteVideo').attr('srcObject', e.stream);
                        } catch (error) {
                            if (comm_controller.getStream(comm_controller.getRemoteSessionId())) {
                                $('#remoteScreen').attr('src', window.URL.createObjectURL(e.stream));
                            } else {
                                $('#remoteScreenChat').attr('src', window.URL.createObjectURL(e.stream));
                            }
                        }
                    });

                    $(document).on('IncomingScreenShare', function (e) {
                        console.log('IncomingScreenShare', e);
                    });

                    $(document).off('ScreenShareEnded');
                    $(document).on('ScreenShareEnded', function (e) {
                        console.log('ScreenShareEnded');
                        ui_handler.setScreenDisabled(false);
                        if (comm_controller.getStream(comm_controller.getRemoteSessionId())) {
                            $('#remoteScreen').hide();
                            remoteVideoScreens(false);
                            var remoteScreen = document.querySelector('video#remoteScreen');
                            if (remoteScreen) {
                                remoteScreen.src = '';
                                remoteScreen.srcObject = null;
                            }
                        }
                        var remoteScreenChat = document.querySelector('video#remoteScreenChat');
                        if (remoteScreenChat) {
                            remoteScreenChat.src = '';
                            remoteScreenChat.srcObject = null;
                        }
                        if ($('#remoteScreenChat').is(":visible")) {
                            $('#remoteScreenChat').hide();
                            screenShareUI(false);
                        }
                    });

                    $(document).on('ScreenShareFailed', function (e) {
                        var message = 'Screen Share failed';
                        toggleError(message);
                        screenShareUI(false);
                    });

                    $(document).on('EndMeeting', function (e) {
                        endMeeting(false);
                    });

                    $('#exit_meeting').on('click', function () {
                        $("#hangupBroadcastButton").trigger("click");
                        hangupCall();
                        comm_controller.endMeeting();
                        endMeeting(true);
                    });

                    if ((isChrome || isFirefox || isOpera) && (!isiPhone) && (queryString.room || queryString.addbroadcast || localStorage.getItem('hasPrivileges'))) {
                        $('.wd-v-share').show();
                        $('#screenshare_div').show();
                        $(document).on('PluginDetected', function (e) {
                            pluginInstalled = true;
                        });
                        $(document).on('PluginNotDetected', function (e) {
                            pluginInstalled = false;
                        });
                        pluginController.init(svConfigs.chromePluginId, document);
                        $('.control-ss > a').click(function () {
                            $('.control-ss').hide();
                        });

                    }

                    var startScreenFromButton = function () {
                        pluginController.getChromeExtensionStatus(svConfigs.chromePluginId, function (status) {
                            if (status == 'installed-enabled' || status == 'not-chrome') {
                                pluginInstalled = true;
                                $('.control-ss').hide();
                                console.log('start screen share');
                                if (comm_controller.getScreenStream()) {
                                    hangUpScreenShare();
                                }

                                startScreenShare();

                            } else {
                                pluginInstalled = false;
                                $('.control-ss').show();
                                $('.close-but-wd-small').on('click', function () {
                                    $('.control-ss').hide();
                                });
                            }
                        });
                    };

                    $('.wd-v-share').on('click', function () {
                        startScreenFromButton();
                    });

                    $('#startscreenshare').off('click', function () {

                    });

                    $('#startscreenshare').on('click', function () {
                        if (isOnline) {
                            ui_handler.displayVideoOnly();
                            startScreenFromButton();
                        } else {
                            setOffline();
                        }
                    });

                    $('.wd-v-stopshare').on('click', function () {
                        hangUpScreenShare();
                    });

                    $('#stopscreenshare').on('click', function () {
                        hangUpScreenShare();
                    });

                    if ((queryString.isAdmin || queryString.addbroadcast) && (isChrome || isFirefox) && (!isiPhone && !isAndroid) && svConfigs.recording.enabled == true) {
                        loadScript(lsRepUrl + 'js/msr.js', function () {});
                    }

                    if (svConfigs.videoScreen && svConfigs.videoScreen.onlyAgentButtons && !queryString.isAdmin) {
                        ui_handler.setAgentOnlyButtons();
                    }

                    if (disableVideo) {
                        ui_handler.disableVideo();
                    }

                    if (disableAudio) {
                        ui_handler.disableAudio();
                    }

                    if (disableScreenShare) {
                        ui_handler.disableScreenShare();
                    }

                    if (disableWhiteboard) {
                        ui_handler.disableWhiteboard();
                    }
                    if (disableTransfer) {
                        ui_handler.disableTransfer();
                    }

                    $(document).mouseup(function (e) {
                        var container = $('#nd_widget_visitors');

                        // If the target of the click isn't the container
                        if (!container.is(e.target) && container.has(e.target).length === 0) {
                            ui_handler.toggleVisitors(false);
                        }
                        var container = $('#feedback_form');

                        // If the target of the click isn't the container
                        if (!container.is(e.target) && container.has(e.target).length === 0) {
                            $('#feedback_form').hide();
                        }
                    });
                    setOnlineVisitors();
                    loadScript(lsRepUrl + 'js/additional.js', function () {});
                });
            });
        }
        ;

        ui_handler = new uiHandler();
        ui_handler.init(jQuery, $container, comm_controller);

        jQuery(document).on('CommConnected', function (e) {
            function ntfParent() {
                deleteCookie('lsvGreenRoom');
                hangupCall();
                if (window.opener) {
                    if (queryString.isAdmin) {
                        isOnline = false;
                        comm_controller.adminOffline();
                    }
                    window.opener.postMessage({type: 'popupClosed'}, '*');
                }
            }

            if (window.addEventListener) {
                window.addEventListener('beforeunload', ntfParent, false);
            } else {
                window.attachEvent('onbeforeunload', ntfParent);
            }
            if (isAndroid || isiPhone) {
                var loadHtmlScript = function () {
                    loadScript('https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js', loadHtml());
                };
                loadScript('https://webrtc.github.io/adapter/adapter-latest.js', loadHtmlScript());

            } else if (isIEA) {
                loadScript('https://cdn.temasys.com.sg/adapterjs/0.15.x/adapter.screenshare.js', loadHtml());
            } else {
                loadScript('https://webrtc.github.io/adapter/adapter-latest.js', loadHtml());
            }

        });


    });

};

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

    script.src = url + '?v=' + currVersion;
    document.getElementsByTagName("head")[0].appendChild(script);
}
var init = new main();