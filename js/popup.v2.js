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
var queryString, videoDevices, multiStreamRecorder, agentId, remoteVideoSessions = 0, inCall = [];
var recordScreen, recordCamera, recordingTimer, videoDefault, videoCurrentId = 0, audioCurrentId = 0, audioOutputCurrentId = 0, videoCurrent, startNextCamera = false, audioSource, videoSource, testAudioTrack, testVideoTrack, videoSelect, audioInputSelect, audioOutputSelect;

var sourceBuffer, passRoom, requirePass = false, agentAvatar, visitorName, agentName, datetime, duration, token, room;
var disableVideo, disableAudio, disableScreenShare, disableWhiteboard, disableTransfer, autoAcceptVideo, autoAcceptAudio, timerVars = [], startedRecroding = false, is_widget_opened = false;
var translator;

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
        room = (currScript.getAttribute('data-room_id')) ? currScript.getAttribute('data-room_id') : queryString.room;

        lsRepUrl = currScript.getAttribute('data-source_path');
        agentAvatar = peer_avatar = (currScript.getAttribute('data-avatar')) ? currScript.getAttribute('data-avatar') : lsRepUrl + 'img/small-avatar.jpg';
        agentName = peer_name = (currScript.getAttribute('data-names')) ? currScript.getAttribute('data-names') : svConfigs.agentName;
        visitorName = (currScript.getAttribute('data-visitorName')) ? currScript.getAttribute('data-visitorName') : '';
        passRoom = (currScript.getAttribute('data-pass')) ? currScript.getAttribute('data-pass') : passRoom;
        datetime = (currScript.getAttribute('data-datetime')) ? currScript.getAttribute('data-datetime') : '';
        duration = (currScript.getAttribute('data-duration')) ? currScript.getAttribute('data-duration') : '';
        agentId = (currScript.getAttribute('data-agentId')) ? currScript.getAttribute('data-agentId') : '';
        comm_controller = new comController();
        comm_controller.init('popup', room);
        notify_handler = new notifyHandler();
        notify_handler.init();
        if (localStorage.getItem('prd')) {
            var callerInfo = localStorage.getItem('prd');
            callerInfo = JSON.parse(callerInfo);
            if (queryString.isAdmin) {
                agentName = (agentName) ? agentName : callerInfo.name;
            } else {
                visitorName = (visitorName) ? visitorName : callerInfo.name
            }
        } else {
            agentName = (agentName) ? agentName : '';
        }



        token = (queryString.token) ? queryString.token : '';
        disableVideo = (currScript.getAttribute('data-disableVideo')) ? true : false;
        disableAudio = (currScript.getAttribute('data-disableAudio')) ? true : false;
        disableScreenShare = (currScript.getAttribute('data-disableScreenShare')) ? true : false;
        disableWhiteboard = (currScript.getAttribute('data-disableWhiteboard')) ? true : false;
        disableTransfer = (currScript.getAttribute('data-disableTransfer')) ? true : false;
        autoAcceptVideo = (currScript.getAttribute('data-autoAcceptVideo')) ? true : false;
        autoAcceptAudio = (currScript.getAttribute('data-autoAcceptAudio')) ? true : false;
        if (conferenceStyle == 'conference') {
            widgetSize = {width: 800, height: 600};
        }

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

        $(document).on('AdminPopupOffline', function (e) {
            isOnline = false;
        });
        $(document).on('VisitorsRoom', function (e) {
            visitors = e.count;
        });

        $(window).on('unload', function () {
            hangupCall();
            console.log('close the call');
        });

        var joinedMessage = function (e) {
            var incomingText = smartVideoLocale.msgStore['incomingText'];
            if (incomingText && names[e.sessionId]) {
                $('#incoming_text').html(incomingText.replace('{{caller_name}}', names[e.sessionId].name));
            }
            if (e.sessionId !== comm_controller.getSessionId()) {
                var joinedText = smartVideoLocale.msgStore['joinedChat'];
                if (joinedText && names[e.sessionId].name.indexOf(svConfigs.anonVisitor) == -1) {
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
            if (!names[e.sessionId]) {
                names[e.sessionId] = {};
            }
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
                names[e.sessionId].priv = (e.callerInfo.priv);
                if (e.callerInfo.username) {
                    names[e.sessionId].username = e.callerInfo.username;
                }
            }
            names[e.sessionId].muted = e.callerInfo.muted;
            names[e.sessionId].video = e.callerInfo.video;
            names[e.sessionId].isAdmin = (e.callerInfo.isAdmin) ? e.callerInfo.isAdmin : false;
            $('.dw-chat-avatar').attr('src', avatar);
            $('.direct-chat-img left ' + e.callerInfo.name).attr('src', avatar);
            setOnlineVisitors();
        };

        var setOnlineVisitors = function () {
            if (conferenceStyle == 'conference') {
                $('#attendeesList').empty();

                var count = 0;
                for (var sess in names) {
                    var meVar = '';
                    if (sess == comm_controller.getSessionId()) {
                        meVar = ' (Me) ';
                    }
                    var micIcon = '', muteIcon = '', tvIcon = '';
                    var cListAttendees = $('ul#attendeesList');
                    if (names[sess].muted !== 'undefined') {
                        if (names[sess].muted == true) {
                            micIcon = '<i class="fa fa-microphone-slash"></i> ';
                            muteIcon = '';
                        } else {
                            muteIcon = '';
                            micIcon = '<i class="fa fa-microphone"></i> ';
                            if (sess !== comm_controller.getSessionId()) {
                                muteIcon = '<span class="user-action"><a data-typeid="' + sess + '" href="#" id="muteAttendee' + sess + '"><i class="fa fa-fw fa-volume-off"></i> Mute</a></span>';
                            }
                        }
                    }
                    if (names[sess].muted !== 'undefined' && names[sess].video) {
                        tvIcon = '<i class="fa fa-tv"></i> ';
                    }
                    if (sess !== comm_controller.getSessionId()) {
                        var privateChat = ' <span class="user-action"><a data-typeid="' + sess + '" href="#" id="private' + sess + '"><i class="fa fa-comment"></i> Private</a></span>';
                    } else {
                        privateChat = '';
                    }

                    if (queryString.isAdmin) {
                        var grantIcon = blockIcon = '';
                        if (queryString.broadcast) {
                            if (names[sess].priv) {
                                var txt = smartVideoLocale.msgStore['revoke'];
                                grantIcon = ' <span class="user-action"><a data-typeid="' + sess + '" href="#" id="revoke' + sess + '"><i class="fa fa-stop-circle-o"></i> ' + txt + '</a></span>';
                            } else {
                                if (names[sess].raiseHand) {
                                    txt = smartVideoLocale.msgStore['grant'];
                                    grantIcon = ' <span class="user-action"><a data-typeid="' + sess + '" href="#" id="grant' + sess + '"><i class="fa fa-hand-paper-o"></i> ' + txt + '</a></span>';
                                }
                            }
                            if ((svConfigs.serverSide.loginForm || svConfigs.serverSide.token) && sess !== comm_controller.getSessionId()) {
                                var txtBlock = smartVideoLocale.msgStore['block'];
                                blockIcon = ' <span class="user-action"><a data-typeid="' + sess + '" href="#" id="block' + sess + '"><i class="fa fa-stop"></i> ' + txtBlock + '</a></span>';
                            }
                        }

//                            $('<li><i class="fa ' + micIcon + '"></i><span class="mx-10">' + names[sess].name + '</span>' + tvIcon + ' <label class="switch custom-checkbox"> <input data-typeid="' + sess + '" id="presentUser' + sess + '" onchange="topbar.toggleFix(); " type="checkbox"> <span class="switch-indicator"></span> </label></li>').appendTo(cListAttendees);
                        $('<li>' + micIcon + tvIcon + '<span class="mx-10">' + names[sess].name + meVar + '</span>' + muteIcon + privateChat + grantIcon + blockIcon + '</li>').appendTo(cListAttendees);
                        $('#grant' + sess).on('click', function (e) {
                            for (var sid in names) {
                                if (names[sid].priv) {
                                    names[sid].priv = false;
                                    comm_controller.revokePriveleges(sid);
                                }
                            }
                            names[$(this).attr('data-typeid')].priv = true;
                            comm_controller.grantPriveleges($(this).attr('data-typeid'));
                        });
                        $('#block' + sess).on('click', function (e) {


                            var r = confirm(smartVideoLocale.msgStore['sureBlock']);
                            if (r == true) {
                                $.ajax({
                                    type: 'POST',
                                    url: lsRepUrl + '/server/script.php',
                                    data: {'type': 'blockuser', 'username': names[$(this).attr('data-typeid')].username}
                                })
                                        .done(function (data) {
                                            console.log('blocked');
                                        })
                                        .fail(function () {
                                            //
                                        });


                                delete names[$(this).attr('data-typeid')];
                                comm_controller.blockUser($(this).attr('data-typeid'));
                                setOnlineVisitors();
                                setTimeout(function () {
                                    ui_handler.toggleVisitors(false);
                                    setOnlineVisitors();
                                }, 500);
                            }
                        });
                        $('#revoke' + sess).on('click', function (e) {
                            names[$(this).attr('data-typeid')].priv = false;
                            comm_controller.revokePriveleges($(this).attr('data-typeid'));
                        });
                        $('#muteAttendee' + sess).on('click', function (e) {
                            comm_controller.setMute($(this).attr('data-typeid'));
                        });
                    } else {
                        $('<li>' + micIcon + tvIcon + '<span class="mx-10">' + names[sess].name + meVar + '</span>' + privateChat + '</li>').appendTo(cListAttendees);
                    }
                    $('#private' + sess).on('click', function (e) {

                        var typeid = $(this).attr('data-typeid');
                        $('#visitor_message').show();
                        $('#send_message_to').html(smartVideoLocale.msgStore['sendMessageTo'] + names[typeid].name);

                        $('#private_message_button').off();
                        $('#private_message_button').on('click', function () {
                            var msg = $('#private_message_small').val();
                            sendChatMessage(msg, true, typeid);
                            $('#private_message_small').val('');
                            $('#visitor_message').hide();
                            e.stopPropagation();
                        });
                        e.stopPropagation();
                    });
                    if (sess !== comm_controller.getSessionId()) {
                        peer_avatar = names[sess].avatar;
                        peer_name = names[sess].name;
                        peer_name_id = sess;
                    }
                    $('<li><hr/></li>').appendTo(cListAttendees);
                    count++;
                }


                if (count > 0) {
                    $('.dw-chat-avatar').show();
                    $('.dw-chat-avatar').attr('src', peer_avatar);
                    $('#peer_name_chat').html(peer_name);
                    $('#showProfile').show();
                } else {
                    $('.dw-chat-avatar').hide();
                    $('#peer_name_chat').html('');
                    $('#showProfile').hide();
                }
            } else {

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

                        if (queryString.isAdmin && queryString.broadcast) {
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

        var callEnded = function () {

            if (!comm_controller.getScreenStream() && !$('#remoteScreen').is(":visible") && !comm_controller.getStream()) {
                stopIncomingCall();
                stopRecording(true);


                if (conferenceStyle == 'conference') {
                    ui_handler.displayVideoOnly();
                } else {
                    inCall = [];
                    console.log('call ended');
                    $('#localVideo').hide();
                    $('#video_back').show();
                    ui_handler.toggleInstaChat();
                    setTimeout(function () {
                        endMeeting(false);
                    }, 500);
                }

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
//                    downloadRecording();
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
                        recordingTimer = setInterval(pauseRecording, 60000);
                    });

                });

            } else {
                if (queryString.isAdmin) {
                    if (svConfigs.recording.oneWay) {
                        var recorders = [];
                    } else {
                        recorders = [comm_controller.getStream()];
                    }
                    var recorders = [];
                    for (var sess in names) {
                        if (svConfigs.recording.oneWay) {
                            if (sess != comm_controller.getSessionId()) {
                                var rec = comm_controller.getRemoteStream(sess);
                                recorders.push(rec);
                            }

                        } else {
                            rec = comm_controller.getRemoteStream(sess);
                            recorders.push(rec);
                        }
                    }
                }
                if (queryString.broadcast && queryString.isAdmin) {
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
                    $('#recording_message').show();
                    $('.recordinglink').attr('href', url);
                    $('.recordinglink').click(function () {
//                    window.URL.revokeObjectURL(url);
                        $('#recording_message').hide();
                    });
                    $('.close-but-wd-small').on('click', function () {
                        window.URL.revokeObjectURL(url);
                        $('#recording_message').hide();
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

            if (conferenceStyle == 'conference') {
                ui_handler.displayVideoOnly();
            } else {
                ui_handler.toggleInstaChat();
            }

            if (comm_controller.getStream()) {
                stopRecording(true);
                comm_controller.handleCallTermination();
                hangUpScreenShare();
            }
            comm_controller.endCall('hang up call');
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
                        option.dataset.icon = 'fa fa-microphone mr-2';
//                        if (deviceInfo.label) {
                        audioInputSelect.appendChild(option);
//                        }
                    } else if (deviceInfo.kind === 'audiooutput') {
                        option.text = deviceInfo.label || 'speaker ' +
                                (audioOutputSelect.length + 1);
                        option.dataset.icon = 'fa fa-headphones mr-2';
//                        if (deviceInfo.label) {
                        audioOutputSelect.appendChild(option);
//                        }
                    } else if (deviceInfo.kind === 'videoinput') {
                        option.dataset.icon = 'fa fa-video-camera mr-2';
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
                    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).then(gotDevices).catch(handleError);
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

            if (conferenceStyle == 'simple') {
                ui_handler.toggleInstaChat();
                ui_handler.restoreVideoBox();
                $('#popup_widget_text_videos').show();
                ui_handler.toggleInstaVideo(4)
            }
        };

        var startVideoButton = function (autoaccept) {
            if (window.stream) {
                window.stream.getTracks().forEach(function (track) {
                    track.stop();
                });
            }
            ui_handler.restoreVideoBox();
            ui_handler.toggleInstaVideo(false);
            var type = (video_on) ? 'Video' : 'Audio';
            comm_controller.initCall(type, autoaccept, videoSource, comm_controller.getSessionId(), audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
        };

        var startVideo = function (autoaccept, videoDevice) {
            //hangUpScreenShare();
            if (isOnline) {
                setVideoIcon(comm_controller.getSessionId(), video_on);
                if (!queryString.broadcast && svConfigs.videoScreen.greenRoom && !getCookie('lsvGreenRoom')) {
                    startGreenRoom();
                    $('#startVideoButton').click(function () {
                        startVideoButton(autoaccept);
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
            if (conferenceStyle == 'conference') {
                if ($('#' + elem).val()) {
                    var msg = $('#' + elem).val();
                    sendChatMessage(msg, true);
                    $('#' + elem).val('');
                }
            } else {
                if ($('#' + elem).text()) {
                    var msg = $('#' + elem).text();
                    sendChatMessage(msg, true);
                    $('#' + elem).html('');
                }
            }
        };

        var screenShareUI = function (show) {
            if (show) {
                $('#wd-widget-content-video').show();
                $('#local_video_div').show();
                $('#localScreen').show();
                $('#localVideo').hide();
                $("#showHideVideo").addClass('disabled');
            } else {

                $('#remoteScreenChat').hide();
                $('#remoteScreen').hide();
                $('#localScreen').hide();
                if (comm_controller.getStream() || conferenceStyle == 'conference') {
                    $('#local_video_div').show();
                    $('#localVideo').show();
                } else {
                    $('#local_video_div').hide();
                    $('#localVideo').hide();
                }
                $("#showHideVideo").removeClass('disabled');
            }
            if (svConfigs.videoScreen && svConfigs.videoScreen.onlyAgentButtons == false || queryString.isAdmin) {
                ui_handler.setScreenButton(show);
            }
        };

        var hangUpScreenShare = function () {
            screenShareUI(false);
            comm_controller.handleScreenShareTermination();
            if (!comm_controller.getScreenStream() && !$('#remoteScreen').is(":visible") && !comm_controller.getStream()) {
                if (conferenceStyle == 'conference') {
                    ui_handler.displayVideoOnly();
                } else {
                    ui_handler.toggleInstaChat();
                }
            }
        };

        var startScreenShare = function () {
            if (comm_controller.getScreenStream()) {
                hangUpScreenShare();
            } else {
                ui_handler.restoreVideoBox();
                comm_controller.startScreenShare();
                screenShareUI(true);
            }
        };

        var startInitialScreen = function () {

            if (queryString.isAdmin) {
                isOnline = true;
            }
            audio_on = (!$(".muteAudio").is(':checked'));
            video_on = (!$(".muteVideo").is(':checked'));
            if (disableAudio) {
                audio_on = false;
            }
            if (disableVideo) {
                video_on = false;
            }

            names[comm_controller.getSessionId()].muted = !audio_on;
            names[comm_controller.getSessionId()].video = video_on;
            names[comm_controller.getSessionId()].name = (queryString.isAdmin) ? agentName : (visitorName) ? visitorName : guestName(comm_controller.getSessionId());
            names[comm_controller.getSessionId()].isAdmin = (queryString.isAdmin);
            names[comm_controller.getSessionId()].priv = (localStorage.getItem('hasPrivileges')) ? true : false;
            if ($('#ng_caller_name').val()) {
                if (queryString.isAdmin) {
                    agentName = $('#ng_caller_name').val();
                } else {
                    visitorName = $('#ng_caller_name').val();
                }
                names[comm_controller.getSessionId()].name = $('#ng_caller_name').val();

            }
            if (requirePass && localStorage.getItem('prd')) {
                var req = JSON.parse(localStorage.getItem('prd'));
                names[comm_controller.getSessionId()].password = req.password;
            } else {
                comm_controller.setCallerInfo(names[comm_controller.getSessionId()], (queryString.isAdmin));
            }
            localStorage.setItem('prd', JSON.stringify(names[comm_controller.getSessionId()]));

            if (svConfigs.videoScreen.greenRoom) {
                $('#wd-widget-content-greenroom').show();
                startGreenRoom();
            } else {

                setOnlineVisitors();
                ui_handler.setVideoButton();
                startVideo(true);
            }
        };

        var checkAppointment = function () {
            var currDate = new Date();
            var dateHalfHour = new Date(datetime);
            dateHalfHour.setMinutes(dateHalfHour.getMinutes() + parseInt(duration));

            if (currDate > new Date(datetime) && dateHalfHour > currDate) {
                return true;
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
                    var datePretty = getPrettyDate(new Date(datetime).getTime() / 1000);
                    var meetingInfo = info.replace('{{timemeeting}}', datePretty);
                    var meetingInfo = meetingInfo.replace('{{diffString}}', diffString);
                    toggleNotification(meetingInfo, true);

                } else {
                    toggleNotification(smartVideoLocale.msgStore['appointmentPast'], true);

                }
                ui_handler.displayChatOnly();
                ui_handler.setDisabled(true);
                return false;
            }

        }

        var openWidget = function (type) {
            toggleNotification('', false);
            if (conferenceStyle == 'conference') {
                is_widget_opened = ($('#wd-widget-content-greenroom').is(':visible') || $('#wd-widget-content-video-waiting').is(':visible')
                        || $('#wd-widget-content-video').is(':visible') || is_widget_opened);
            } else {
                is_widget_opened = ($('#nd_widget_content').is(':visible'));
            }
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

                                        var time = getPrettyDate(pid.date_created);
//                                        return time;
                                        showMessage(name, pid.message, time, pid.system, pid.avatar);
                                    });
                                }
                            })
                            .fail(function () {
                                console.log(false);
                            });
                }
            }
            if (conferenceStyle == 'conference') {

                fileInput = document.querySelector('input#filetransfer');
            } else {
                if (type === 'chat') {
                    ui_handler.toggleInstaChat();
                } else if (type === 'video') {
                    ui_handler.toggleInstaVideo();
                } else if (type === 4 && !is_widget_opened) {
                    ui_handler.toggleInstaVideo(4);
                }
                document.getElementById('newdev_chat_message1').focus();
            }

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

        var setOnline = function () {
            if (!names[comm_controller.getSessionId()]) {
                names[comm_controller.getSessionId()] = {};
            }
            if (queryString.broadcast) {

                if (svConfigs.serverSide.token) {
                    if (!is_widget_opened && (!comm_controller.getVideoSessions() && !comm_controller.getScreenStream() && !$('#remoteScreen').is(":visible") && !$('#invideo').is(":visible") && !$('#ng_info').is(":visible"))) {
                        is_widget_opened = true;
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
                                        names[comm_controller.getSessionId()] = {name: data.first_name + ' ' + data.last_name, avatar: (svConfigs.agentAvatar) ? lsRepUrl + svConfigs.agentAvatar : lsRepUrl + 'img/small-avatar.jpg', isAdmin: (queryString.isAdmin), username: data.username};
                                        names[comm_controller.getSessionId()].priv = (localStorage.getItem('hasPrivileges'));
                                        if (queryString.isAdmin) {
                                            agentName = names[comm_controller.getSessionId()].name;
                                        } else {
                                            visitorName = names[comm_controller.getSessionId()].name;
                                        }
                                        
                                        comm_controller.setCallerInfo(names[comm_controller.getSessionId()], true);
                                        data.name = data.first_name + ' ' + data.last_name;
                                        data.isAdmin = (queryString.isAdmin);
                                        delete data.password;

                                        localStorage.setItem('prd', JSON.stringify(data));
                                        toggleNotification('', false);
                                        ui_handler.setDisabled(false);
                                        setOnlineVisitors();
                                        if (conferenceStyle == 'conference') {
                                            isOnline = true;
                                            startInitialScreen();
                                        }



                                        return false;

                                    } else {
                                        toggleNotification(smartVideoLocale.msgStore['notValidToken'], true);
                                        localStorage.removeItem('prd');
                                        localStorage.removeItem('hasPrivileges');
                                        comm_controller.setClose(comm_controller.getSessionId());
                                        ui_handler.displayChatOnly();
                                        ui_handler.setDisabled(true);
                                        return false;
                                    }
                                })
                                .fail(function () {
                                    //
                                });
                    }
                    return false;
                }

                if (conferenceStyle !== 'conference') {
                    ui_handler.toggleWidget();
                    ui_handler.toggleInstaChat();
                    ui_handler.displayVideoOnly();
                    ui_handler.toggleHeaderChat();
                    if (queryString.broadcast) {
                        $('#raisehand_div').show();
                    }

                    $('#localVideo').hide();
                    $('.wd-avatar-agent').hide();
                    $('#video_container_chat').show();
                    $('#wd-widget-content-video').hide();
                    if (queryString.isAdmin) {
                        isOnline = true;
                        $("#callAudioButton_1").addClass('disabled');
                        $('.peer_avatar').hide();
                    }
                    return false;
                } else {
                    openWidget('chat');
                    if (queryString.isAdmin) {
                        isOnline = true;
                    }
                }

            }
            if (!comm_controller.getVideoSessions() && !comm_controller.getScreenStream() && !$('#remoteScreen').is(":visible") && !$('#invideo').is(":visible") && !$('#ng_info').is(":visible")) {
                openWidget('chat');
                if (!queryString.isAdmin && duration && datetime) {
                    var check = checkAppointment();
                    if (!check) {
                        return false;
                    }
                }

                if (!queryString.isAdmin && requirePass) {
                    toggleNotification('', false);

                    if (localStorage.getItem('prd')) {
                        svConfigs.entryForm.enabled = false;
                        var callerInfo = localStorage.getItem('prd');
                        comm_controller.setCallerInfo(JSON.parse(callerInfo), false);
                    } else {

                        $('#ng_info').show();
                        ui_handler.displayChatOnly();
                        ui_handler.setDisabled(true);
                        if (visitorName) {
                            $("#ng_caller_name").hide();
                        }
                        if (svConfigs.entryForm.showEmail) {
                            $("#ng_caller_email").show();
                        }
                        if (svConfigs.entryForm.showAvatar) {
                            $("#ng_caller_avatar").show();
                        }
                        $("#ng_password").show();

                        $('#continue-button').on('click', function () {
                            var req = {};
                            if (visitorName) {
                                req.name = visitorName;
                            } else {
                                req.name = $('#ng_caller_name').val();
                            }
                            if ($('#ng_caller_name').val()) {
                                req.email = $('#ng_caller_name').val();
                            }
                            if ($("#ng_caller_avatar").val()) {
                                req.avatar = $("#ng_caller_avatar").val();
                            }
                            req.password = $('#ng_password').val();
                            comm_controller.setCallerInfo(req, false);
                            localStorage.setItem('prdTmp', JSON.stringify(req));
                        });
                        return false;
                    }

                }

                if (visitorName && !queryString.isAdmin) {
                    svConfigs.entryForm.enabled = false;
                    var req = {'name': visitorName};
                    localStorage.setItem('prd', JSON.stringify(req));
                }

                if (localStorage.getItem('prd')) {
                    svConfigs.entryForm.enabled = false;
                    var callerInfo = localStorage.getItem('prd');
                    if (callerInfo) {
                        callerInfo = JSON.parse(callerInfo);
                    }
                    comm_controller.setCallerInfo(callerInfo, (queryString.isAdmin));
                    names[comm_controller.getSessionId()] = {name: (callerInfo) ? callerInfo.name : caller_name, avatar: (callerInfo) ? callerInfo.avatar : caller_avatar, email: (callerInfo) ? callerInfo.email : caller_email};
                }

                if (svConfigs.entryForm.enabled) {
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
                                            comm_controller.setCallerInfo(req, false);
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
                            comm_controller.setCallerInfo(req, (queryString.isAdmin));
                        }
                        if (conferenceStyle == 'conference') {
                            startInitialScreen();
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
                            if (!queryString.isAdmin) {
                                isOnline = false;
                                $("#ng_password").show();
                            }
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

                    if (conferenceStyle == 'conference') {
                        if (svConfigs.videoScreen.greenRoom) {
                            $('#wd-widget-content-greenroom').show();
                            startGreenRoom();
                            $('#startVideoButton').click(function () {
                                startVideoButton(false)
                            });
                        } else {
                            if (queryString.broadcast && !queryString.isAdmin) {
                                $('#cameraMicChoose').hide();
                            }
                            if (queryString.isAdmin) {
                                $('#ng_caller_name').val(agentName);
                            } else {
                                $('#ng_caller_name').val(visitorName);
                            }
                        }
                    }


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
                    if (queryString.isAdmin && agentName) {
                        svConfigs.entryForm.enabled = false;
                        names[comm_controller.getSessionId()] = {name: (svConfigs.agentName) ? svConfigs.agentName : '', avatar: (svConfigs.agentAvatar) ? svConfigs.agentAvatar : lsRepUrl + 'img/small-avatar.jpg'};
                        comm_controller.setCallerInfo(names[comm_controller.getSessionId()], true);
                        localStorage.setItem('prd', JSON.stringify(names[comm_controller.getSessionId()]));
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
                        $('#startVideoButton').on('click', function () {
                            startVideoButton(false);
                        });
                    }
                }
                var callerInfo = localStorage.getItem('prd');
                if (callerInfo) {
                    callerInfo = JSON.parse(callerInfo);
                }
                names[comm_controller.getSessionId()] = {name: (callerInfo) ? callerInfo.name : caller_name, avatar: (callerInfo) ? callerInfo.avatar : caller_avatar, email: (callerInfo) ? callerInfo.email : caller_email};
//                    localStorage.setItem('prd', JSON.stringify(names[comm_controller.getSessionId()]));

                if (svConfigs.entryForm.enabled == false && conferenceStyle == 'conference') {
                    isOnline = true;
                    ui_handler.setDisabled(false);
                    startInitialScreen();
                }

            } else {
                var callerInfo = localStorage.getItem('prd');
                comm_controller.setCallerInfo(JSON.parse(callerInfo), (queryString.isAdmin));
                if (comm_controller.getScreenStream()) {
                    comm_controller.addToJoinScreenShare();
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
                                localStorage.removeItem('hasPrivileges');
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
            if (comm_controller.getParticipants() > 0) {
                console.log('setOffline return');
                return;
            }
            if (!queryString.isAdmin && duration && datetime) {

                setTimeout(function () {
                    var check = checkAppointment();
                    if (!check) {
                        return false;
                    }
                }, 50);

            }

            if (!comm_controller.getVideoSessions() && !comm_controller.getScreenStream() && !$('#remoteScreenChat').is(":visible") && !$('#invideo').is(":visible")) {
                ui_handler.setDisabled(true);
                if (smartVideoLocale.msgStore['waitingOtherParty']) {
                    var waitingToCome = smartVideoLocale.msgStore['waitingOtherParty'];
                } else {
                    waitingToCome = 'Waiting for the other party to join';
                }
//                hangupCall();
                toggleNotification(waitingToCome, true);
            }
            setOnlineVisitors();
        };

        var setVideoIcon = function (id, show) {
            if (names[id]) {
                names[id].video = show;
            }
            setOnlineVisitors();
        };


        function loadHtml() {
            console.log('loadHtml');
            if (conferenceStyle == 'conference') {
                var widget_css = 'roomconference.css';

            } else {
                widget_css = 'room.css';
            }
            var rateit_css = 'rateit.css';
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

            $.get(lsRepUrl + 'pages/' + videoWidgetContainer + '?v=' + currVersion, function (widget_data) {
//                var widgetHtml = 'pages/widget.agent.new.html?v=' + currVersion;

//                $.get(lsRepUrl + widgetHtml, function (data) {
                $container.append(widget_data);
//                    $('#agent_widget').append(widget_data);
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
                                data = JSON.parse(data);
                                if (data.title) {
                                    document.title = data.title;
                                }
                            })
                            .fail(function () {
                                return;
                            });

                }
                if (conferenceStyle == 'conference') {
                    $('#showProfile').show();
                    if ((isChrome || isFirefox || isOpera || isSafariA) && (!isiPhone) && (queryString.room || queryString.broadcast || localStorage.getItem('hasPrivileges'))) {
                        $('#screenshareLi').show();
                    }
                    if (queryString.broadcast && !queryString.isAdmin && !localStorage.getItem('hasPrivileges')) {
                        $('#screenshareLi').hide();
                    }
                    if (svConfigs.recording.enabled && queryString.isAdmin) {
                        $('#recordingLi').show();
                    }
                    if (svConfigs.videoScreen.getSnapshot) {
                        $('#snapshotLi').show();
                    }
                }

                if (conferenceStyle == 'conference' && queryString.broadcast && !queryString.isAdmin) {
                    $('#showHideVideo').hide();
                    $('#showHideAudio').hide();
                    $('#raisehandLi').show();
                    $('#raisehandLi1').show();
                    $('#localVideo').hide();
                    $('#snapshotLi').hide();
                }

                if (localStorage.getItem('hasPrivileges')) {
                    $('#screenshareLi').show();
                    $('#whiteboardLi').show();
                }

                if (disableWhiteboard) {
                    $('#whiteboardLi').hide();
                }

                if (disableScreenShare) {
                    $('#screenshareLi').hide();
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
                    $('#login-conference-title').html(smartVideoLocale.msgStore['formConferenceTitle']);
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
                    $('#raisehand1').attr('title', smartVideoLocale.msgStore['raiseHand']);
                    $('#snapshot').attr('title', smartVideoLocale.msgStore['getSnapshot']);
                    $('.recordinglink').html(smartVideoLocale.msgStore['previewRecording']);
                    $('#snapshotLink').html(smartVideoLocale.msgStore['snapshotDownload']);
                    $('.acceptFile').html(smartVideoLocale.msgStore['acceptFile']);
                    $('.rejectFile').html(smartVideoLocale.msgStore['rejectFile']);
                    $('#cleanCanvas').attr('title', smartVideoLocale.msgStore['wb_clearall']);
                    var notSupportedText = (isiPhone) ? smartVideoLocale.msgStore['notSupportedIos'] : smartVideoLocale.msgStore['notSupportedError'];
                    $('#not_supported').html(notSupportedText);
                    $('#startVideoButton').html(smartVideoLocale.msgStore['continueToCall']);
                    $('#chooseVideoAudio').html(smartVideoLocale.msgStore['chooseOptions']);
                    $('.feedback-title').html(smartVideoLocale.msgStore['feedbackFieldForm']);
                    $('#feedback-button').html(smartVideoLocale.msgStore['feedbackButton']);
                    $('.muteMe').html(smartVideoLocale.msgStore['muteMe']);
                    $('.turnOffCamera').html(smartVideoLocale.msgStore['turnOffCamera']);
                    $('#startRecording').attr('title', smartVideoLocale.msgStore['startRecording']);
                    $('#end_meeting').attr('title', smartVideoLocale.msgStore['endMeeting']);
                });

                var options = {
                    lsRepUrl: lsRepUrl,
                    lang: svConfigs.smartVideoLanguage
                };

                smartVideoLocale.init(options, jQuery);


//                comm_controller.checkMediaDevices();
                $(document).on('RemoteVideoSessions', function (e) {
                    console.log('RemoteVideoSessions', e.count);
                    remoteVideoSessions = e.count;
                });

                $(document).on('IncomingCall', function (e) {
                    console.log('IncomingCall', e.sessionId);
                    if (conferenceStyle == 'conference') {
                        ui_handler.setWidgetValues();
                        ui_handler.onIncomingVideo();
                        if (e.autoaccept || comm_controller.getVideoSessions()) {
                            setTimeout(function () {
                                comm_controller.answerCall(video_on, true);
                            }, 1000);
                        } else {
                            playIncomingCall();
                            ui_handler.toggleRinging(function (accepted) {
                                if (accepted) {
                                    comm_controller.answerCall(video_on, true);
                                } else {
                                    comm_controller.rejectCall();
                                }
                            });
                        }
                    } else {
                        ui_handler.setWidgetValues();
                        ui_handler.onIncomingVideo();
                        var incomingText = smartVideoLocale.msgStore['incomingText'];
                        $('#incoming_text').html(incomingText.replace('{{caller_name}}', peer_name));
                        if (autoAcceptVideo || autoAcceptAudio
                                || svConfigs.videoScreen.autoAcceptVideo || svConfigs.videoScreen.autoAcceptAudio
                                || e.autoaccept || comm_controller.getVideoSessions()) {

                            if (queryString.isAdmin) {
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
                                            comm_controller.answerCall(video_on, false, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
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
                                        comm_controller.answerCall(video_on, false, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                    }
                                }, 1000);
                            }
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
                                            comm_controller.answerCall(video_on, true, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);

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
                                                comm_controller.answerCall(video_on, true, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                            });
                                        } else {
                                            inCall.push(e.sessionId);
                                            comm_controller.answerCall(video_on, true, videoSource, audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                                        }
                                    }
                                } else {
                                    comm_controller.rejectCall();
                                }
                            });
                        }
                    }
                });

                $('.wd-v-hangup').on('click', function () {
                    hangupCall();
                });

                $(document).off('LocalVideoStarted')
                $(document).on('LocalVideoStarted', function (e) {
                    $('#localVideo').show();
                    if (conferenceStyle == 'conference' || queryString.broadcast) {
                        ui_handler.toggleVideoBox(true);
                        setVideoIcon(comm_controller.getSessionId(), video_on);
                    }
                    var constraint = false;
                    var direction = (svConfigs.transcribe) ? svConfigs.transcribe.direction : '';
                    if (direction == 'agent') {
                        constraint = (isChrome && queryString.isAdmin && svConfigs.transcribe && svConfigs.transcribe.enabled == true);
                    } else if (direction == 'visitor') {
                        constraint = (isChrome && !queryString.isAdmin && svConfigs.transcribe && svConfigs.transcribe.enabled == true);
                    } else if (direction == 'both') {
                        constraint = (isChrome && svConfigs.transcribe && svConfigs.transcribe.enabled == true);
                    }

                    if (constraint) {
                        loadScript('../js/translator.js', function () {
                            translator = new Translator();
                            var langFrom = (currScript.getAttribute('data-langFrom')) ? currScript.getAttribute('data-langFrom') : svConfigs.transcribe.language;
                            var langTo = (currScript.getAttribute('data-langTo')) ? currScript.getAttribute('data-langTo') : svConfigs.transcribe.languageTo;
                            if (direction == 'both' && !queryString.isAdmin) {
                                langFrom = (currScript.getAttribute('data-langTo')) ? currScript.getAttribute('data-langTo') : svConfigs.transcribe.languageTo;
                                langTo = (currScript.getAttribute('data-langFrom')) ? currScript.getAttribute('data-langFrom') : svConfigs.transcribe.language;
                            }
                            translator.voiceToText(function (text) {
                                if (langTo) {
                                    translator.translateLanguage(text, {
                                        from: langFrom,
                                        to: langTo,
                                        callback: function (translatedText) {
                                            comm_controller.sendTranslateMessage(translatedText, false);
                                        },
                                        api_key: svConfigs.transcribe.apiKey
                                    });
                                } else {
                                    comm_controller.sendTranslateMessage(text, false);
                                }
                            }, langFrom);
                        });
                    }

                });

                $('#end_meeting').on('click', function () {
                    hangupCall();
                    localStorage.removeItem('prd');
                    localStorage.removeItem('hasPrivileges');
                    if (queryString.isAdmin) {
                        comm_controller.setDeleteAll();
                        $('#invideo').hide();
                        $('#ng_info').show();
                        $('#continue-button').on('click', function () {
                            caller_name = $('#ng_caller_name').val();
                            var req = {'name': caller_name, 'email': caller_email, 'avatar': caller_avatar};
                            localStorage.setItem('prd', JSON.stringify(req));
                            comm_controller.setCallerInfo(req, (queryString.isAdmin));
                            $('#ng_info').hide();
                            startInitialScreen();
                        });

                    } else {
                        if (svConfigs.entryForm.enabled) {
                            localStorage.removeItem('prd');
                            localStorage.removeItem('hasPrivileges');
                            location.reload();
                        } else {
                            location.href = '/';
                        }
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

                $(document).off('RemoteSpanPosition');
                $(document).on('RemoteSpanPosition', function (e) {
                    var name = (names[e.sessionId]) ? names[e.sessionId].name : peer_name;
                    var span = $('<h2 />', {
                        id: 'remoteVideoSpan' + e.sessionId,
                        class: 'sourcevideospan'
                    });
                    span.css('postion', 'absolute');
                    span.css('top', e.position.top + 'px');
                    span.css('left', e.position.left + 'px');
                    span.appendTo($('#' + videoElementContainer));
                    span.html(name);
                });

                var makeAllSmall = function () {
                    $('.bigvideo').each(function () {
                        if ($(this).is(':visible') && svConfigs.videoScreen.videoContainerStyle === 'conference') {
                            var id = $(this).attr('id');
                            $('#' + id).detach().appendTo('#video_container_small');
                            $('#' + id).removeClass('bigvideo');
                            $('#' + id).removeClass('bigvideoadd');
                            $('#' + id).addClass('sourcevideo');
                            var post = $($('#' + id)).position();

                            $('#remoteVideoSpan' + id).remove();
                            var name = (names[id]) ? names[id].name : peer_name;
                            var span = $('<h2 />', {
                                id: 'remoteVideoSpan' + id,
                                class: 'sourcevideospan'
                            });
                            span.css('left', post.left);
                            span.css('top', post.top);
                            span.appendTo($('#video_container_small'));
                            span.html(name);

                        }
                    });
                };

                $(document).off('VoiceSpeaking');
                $(document).on('VoiceSpeaking', function (e) {
                    var id = e.id;
                    if ($('#' + id).is(':visible') && !$('#wd-widget-content-whiteboard').is(':visible') && !$('#remoteScreen').is(':visible')) {
                        clearTimeout(timerVars[id]);
                        if (svConfigs.videoScreen && svConfigs.videoScreen.videoContainerStyle === 'conference' && jQEngager('#' + id)) {

                            makeAllSmall();
                            $('#fullScreen').show();
                            $('#' + id).detach().appendTo('#video_container');
                            $('#' + id).removeClass('sourcevideo');
                            $('#' + id).addClass('bigvideo');
                            $('#' + id).addClass('bigvideoadd');
                            var post = $('#' + id).position();
                            if (!post) {
                                return;
                            }
                            $('#remoteVideoSpan' + id).remove();
                            var name = (names[id]) ? names[id].name : peer_name;
                            var span = $('<h2 />', {
                                id: 'remoteVideoSpan' + id,
                                class: 'sourcevideospan'
                            });
                            if (conferenceStyle == 'conference') {
                                span.css('left', post.left);
                            } else {
                                span.css('left', post.left);
                            }
                            span.css('top', post.top);
                            span.appendTo($('#video_container'));
                            span.html(name);
                        } else {
                            jQEngager('#' + id).css('border', '1px solid #484d75');
                        }
                    }
                });

                $(document).off('VoiceSilence');
                $(document).on('VoiceSilence', function (e) {
                    if (!svConfigs.videoScreen.videoConference) {
                        return;
                    }
                    var id = e.id;
                    if ($('#' + id).is(':visible')) {
                        timerVars[id] = setTimeout(function () {
                            if (svConfigs.videoScreen && svConfigs.videoScreen.videoContainerStyle === 'conference' && jQEngager('#' + id)) {
//                                jQEngager('#' + id).detach().appendTo('#video_container_small');
//                                jQEngager('#' + id).removeClass('bigvideo');
//                                jQEngager('#' + id).removeClass('bigvideoadd');
//                                jQEngager('#' + id).addClass('sourcevideo');
//                                var post = $(jQEngager('#' + id)).position();
//                                if (!post) {
//                                    return;
//                                }
//                                $('#remoteVideoSpan' + id).remove();
//                                var name = (names[id]) ? names[id].name : peer_name;
//                                var span = $('<h2 />', {
//                                    id: 'remoteVideoSpan' + id,
//                                    class: 'sourcevideospan'
//                                });
//                                span.css('left', post.left);
//                                span.css('top', post.top);
//                                span.appendTo($('#video_container_small'));
//                                span.html(name);
//                                $('#fullScreen').hide();
                            } else {
                                jQEngager('#' + id).css('border', '1px solid #fff');
                            }
                            $('#translate_message').hide();
                        }, 5000);
                    }
                });
                var clearphoto = function () {
                    canvasData = null;
                    $('#snapshotData').hide();
                    $('.recordinglink').attr('href', '');
                };

                if (svConfigs.videoScreen.getSnapshot) {
                    var video, canvas, width, height, canvasData;

                    $('#snapshotLi').click(function () {
                        video = $('.bigvideo.bigvideoadd')[0];
                        canvas = $('#snapshotCanvas')[0];
                        width = 500;
                        if (video) {
                            height = video.videoHeight / (video.videoWidth / width);
                            if (isNaN(height)) {
                                height = width / (4 / 3);
                            }

                            var context = canvas.getContext('2d');
                            if (width && height) {
                                canvas.width = width;
                                canvas.height = height;
                                context.drawImage(video, 0, 0, width, height);
                                canvasData = canvas.toDataURL('image/png');
                                $('#snapshotData').show();
                                $('#snapshotLink').attr('href', canvasData);
                                $('#snapshotLink').attr('download', peer_name + '.png');
                                $('#snapshotLink').click(function () {
                                    clearphoto();
                                });
                                $('.close-but-wd-small').on('click', function () {
                                    clearphoto();
                                });
                            } else {
                                clearphoto();
                            }
                        }
                    });
                }
                $(document).off('RemoteVideoStarted');
                $(document).on('RemoteVideoStarted', function (e) {

                    var previewVideo = document.querySelector('video#videoPreview');
                    if (previewVideo) {
                        previewVideo.src = '';
                        previewVideo.srcObject = null;
                    }
                    if (conferenceStyle == 'conference') {
                        console.log('RemoteVideoStarted');
                        if ((isChrome || isFirefox) && (!isiPhone && !isAndroid) && queryString.isAdmin && svConfigs.recording.enabled == true && svConfigs.recording.autoStart == false) {
                            $('.fa-circle').show();
                        }
                        $('#permission_div').hide();
                        $('#video_back').hide();
                        stopIncomingCall();
                        if (!$('#wd-widget-content-whiteboard').is(':visible')) {
                            ui_handler.toggleVideoBox(true);
                        }
                        setVideoIcon(comm_controller.getSessionId(), video_on);
//                            ui_handler.toggleVideoBox(true);
                    } else {


                        if ((isChrome || isFirefox) && (!isiPhone && !isAndroid) && queryString.isAdmin && svConfigs.recording.enabled == true && svConfigs.recording.autoStart == false) {
                            $('.wd-v-recording').show();
                        }
                        $('#permission_div').hide();
                        $('#video_back').hide();
                        stopIncomingCall();
                        try {
                            ui_handler.toggleVideoBox(true);
                            if ((isChrome || isFirefox) && (!isiPhone && !isAndroid) && queryString.isAdmin && svConfigs.recording.enabled && svConfigs.recording.autoStart) {
                                startRecording();
                            }

//                            $('#remoteVideo').attr('srcObject', e.stream);
                        } catch (error) {
                            console.log(error)
                        }
                    }
                    clearphoto();
                });

                $(document).on('VideoRemoved', function (e) {
                    console.log('VideoRemoved');
                    video_on = false;
                    ui_handler.setVideoButton();
                    setVideoIcon(comm_controller.getSessionId(), false);

                    names[comm_controller.getSessionId()].video = false;
                    localStorage.setItem('prd', JSON.stringify(names[comm_controller.getSessionId()]));

                });

                $(document).on('AudioRemoved', function (e) {
                    ui_handler.disableAudio();
                    audio_on = false;
                    ui_handler.setMuteButton();
                    if (names[comm_controller.getSessionId()]) {
                        names[comm_controller.getSessionId()].muted = true;
                        localStorage.setItem('prd', JSON.stringify(names[comm_controller.getSessionId()]));
                    }
                    setOnlineVisitors();
                });

                $(document).on('VideoMuted', function (e) {
                    ui_handler.disableVideo();
                    console.log('VideoMuted');
                    video_on = false;
                    ui_handler.setVideoButton();
                    setVideoIcon(comm_controller.getSessionId(), false);
                });

                $(document).on('VideoUnmuted', function (e) {
                    video_on = true;
                    ui_handler.setVideoButton();
                    setVideoIcon(comm_controller.getSessionId(), true);
                });
                $(document).on('AudioMuted', function (e) {
                    audio_on = false;
                    ui_handler.setMuteButton();
                    if (names[comm_controller.getSessionId()]) {
                        names[comm_controller.getSessionId()].muted = true;
                    }
                    setOnlineVisitors();
                });

                $(document).on('AudioUnmuted', function (e) {
                    audio_on = true;
                    ui_handler.setMuteButton();
                    if (names[comm_controller.getSessionId()]) {
                        names[comm_controller.getSessionId()].muted = false;
                    }
                    setOnlineVisitors();
                });

                $(document).on('RemoteVideoMuted', function (e) {
                    console.log('RemoteVideoMuted');
                    $('#' + e.sessionId).hide();
                    $('#remoteVideoSpan' + e.sessionId).hide();
                    setVideoIcon(e.sessionId, false);
                });

                $(document).on('BlockUser', function (e) {
                    if (comm_controller.getSessionId() == e.sessionId) {
                        hangupCall();
                        localStorage.removeItem('prd');
                        localStorage.removeItem('hasPrivileges');
                        comm_controller.setClose(comm_controller.getSessionId());
                        delete names[e.sessionId];
                        location.reload();
                    }
                });

                $(document).on('RevokePriveleges', function (e) {
                    names[e.sessionId].priv = false;
                    if (comm_controller.getSessionId() == e.sessionId) {
                        $('.fa-hand-paper-o').closest('a').removeClass('active');
                        localStorage.removeItem('hasPrivileges');
                        $("#hangupBroadcastButton").trigger("click");
                        location.reload();

                    }
                });

                $(document).on('GrantPriveleges', function (e) {
                    names[e.sessionId].priv = true;
                    if (comm_controller.getSessionId() == e.sessionId) {
                        $('.fa-hand-paper-o').closest('a').removeClass('active');
                        localStorage.setItem('hasPrivileges', true);
                        location.reload();
                    }
                });

                $(document).on('RemoteVideoUnmuted', function (e) {
                    console.log('RemoteVideoUnmuted');
                    $('#' + e.sessionId).show();
                    $('#remoteVideoSpan' + e.sessionId).show();
                    setVideoIcon(e.sessionId, true);
                });

                $(document).on('RemoteAudioMuted', function (e) {
                    if (names[e.sessionId]) {
                        names[e.sessionId].muted = true;
                    }
                    setOnlineVisitors();
                });

                $(document).on('RemoteAudioUnmuted', function (e) {
                    if (names[e.sessionId]) {
                        names[e.sessionId].muted = false;
                    }
                    setOnlineVisitors();
                });


                $(document).on('ForceAudioMuted', function (e) {
                    audio_on = false;
                    comm_controller.forceAudioMute();
                });

                $(document).on('ForceDelete', function (e) {
                    if (e.sessionId == comm_controller.getSessionId()) {
                        hangupCall();
                        $('#invideo').hide();
                        localStorage.removeItem('prd');
                        localStorage.removeItem('hasPrivileges');
                        location.reload();
                    }
                });

                $(document).on('ForceDeleteAll', function (e) {
                    hangupCall();
                    is_widget_opened = false;
                    $('#invideo').hide();
                    localStorage.removeItem('prd');
                    localStorage.removeItem('hasPrivileges');
                    location.reload();
                });

                $('#fullscreenButton').on('click', function () {
                    $('#fullScreen').hide();
                    $('#exitFullScreen').show();
                    toggleFullScreen();
                });

                $('#exitFullscreenButton').on('click', function () {
                    $('#fullScreen').show();
                    $('#exitFullScreen').hide();
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

                $('#showHideAudio').on('click', function () {
                    if (!audio_on && !video_on) {
                        comm_controller.initCall('Audio', false, videoSource, comm_controller.getSessionId(), audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                    }
                    audio_on = !audio_on;
                    comm_controller.toggleAudio();
                });

                $('#showHideVideo').on('click', function () {
                    if (!audio_on && !video_on) {
                        comm_controller.initCall('Video', false, videoSource, comm_controller.getSessionId(), audioSource, svConfigs.videoScreen.videoConstraint, svConfigs.videoScreen.audioConstraint);
                    }
                    video_on = !video_on;
                    comm_controller.toggleVideo();
                });

                $('#muteAttendee').on('click', function () {
                    comm_controller.setMute(peer_name_id);
                });

                $(document).on('RemoteVideoStopped', function (e) {
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
                            video_on = true;

                            if (isiPhone) {
                                comm_controller.forceStopCall(videoDevices[videoCurrentId].value);
                            } else {
                                comm_controller.renegotiate(videoDevices[videoCurrentId].value);
                            }
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

                $(document).off('TogglePermissionDenied');
                $(document).on('TogglePermissionDenied', function (e) {
                    callEnded(e.sessionId);
                    ui_handler.togglePermissionWidget(false);
                });

                $(document).on('CheckPopup', function (e) {
                    comm_controller.setPing(comm_controller.getSessionId());
                });

                $(document).off('CallerInfo');
                $(document).on('CallerInfo', function (e) {
                    if (e.callerInfo && e.callerInfo.name) {
                        if (passRoom || e.callerInfo.password) {
                            if (queryString.isAdmin) {
                                comm_controller.sendCallerBack((e.callerInfo.password == passRoom), e.sessionId);
                            }
                            if (e.callerInfo.password == passRoom) {
                                updateInfo(e);
                            }
                        } else {
                            updateInfo(e);
                        }
                    }

                });

                $(document).off('AdminPopupOffline');
                $(document).on('AdminPopupOffline', function (e) {
                    if (conferenceStyle != 'conference') {
                        isOnline = false;
                        setOffline();
                    }
                });

                $(document).off('PopupOffline');
                $(document).on('PopupOffline', function (e) {
                    if (conferenceStyle != 'conference') {
                        isOnline = false;
                        setOffline();
                    }
                });

                $(document).off('PopupLeft');
                $(document).on('PopupLeft', function (e) {
                    if (names[e.sessionId] && names[e.sessionId].name) {
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
                    var video = document.getElementById(e.sessionId);
                    if (video) {
                        video.parentNode.removeChild(video);
                        $('#remoteVideoSpan' + e.sessionId).remove();
                    }
                    delete names[e.sessionId];
                    setOnlineVisitors();
                });

                $('.box-title').on('click', function () {
                    ui_handler.toggleVisitors(true);
                });

                $('#toVideo').on('click', function () {
                    if (comm_controller.getStream() || conferenceStyle == 'conference') {
                        ui_handler.displayVideoOnly();
                        comm_controller.toVideo();
                    } else {
                        ui_handler.toggleInstaChat();
                    }
                });

                $(document).on('ToVideo', function (e) {
                    ui_handler.displayVideoOnly();
                });

                $('#cleanCanvas').on('click', function () {
                    comm_controller.clearCanvas();
                });

                $(document).on('WhiteboardSync', function (e) {
                    makeAllSmall();
                    ui_handler.toggleInstaWhiteboard();
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
                    if ((queryString.isAdmin || localStorage.getItem('hasPrivileges'))) {
                        comm_controller.startWhiteboard();
                    }
                });

                $('#raisehand').off();
                $('#raisehand').on('click', function () {
                    sendChatMessage('raiseHand');
                    var raiseHandText = smartVideoLocale.msgStore['raiseHandText'];
                    var raiseHand = raiseHandText.replace('{{caller_name}}', names[comm_controller.getSessionId()].name);
                    $('.fa-hand-paper-o').closest('a').addClass('active');
                    showMessage('', raiseHand, null, 'raiseHand');
                });

                $('#raisehand1').off();
                $('#raisehand1').on('click', function () {
                    sendChatMessage('raiseHand');
                    var raiseHandText = smartVideoLocale.msgStore['raiseHandText'];
                    var raiseHand = raiseHandText.replace('{{caller_name}}', names[comm_controller.getSessionId()].name);
                    $('.fa-hand-paper-o').closest('a').addClass('active');
                    showMessage('', raiseHand, null, 'raiseHand');
                });

                if (svConfigs.whiteboard.enabled == true) {
                    if (queryString.isAdmin || localStorage.getItem('hasPrivileges')) {
                        $('#whiteboard_div').show();
                        $('#whiteboardLi').show();
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
                    } else if (e.msg == 'sendFile') {
                        var senderName = (e.sessionId) ? names[e.sessionId].name : peer_name;
                        var incomingFile = smartVideoLocale.msgStore['incomingFile'];
                        incomingFile = incomingFile.replace('{{caller_name}}', senderName);
                        showMessage('', incomingFile, null, 'sendFile');
                    } else {
                        showMessage(name, e.msg, null, null, avatar);
                    }
                    if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
                        $('.wd-v-text').hide();
                    } else {
                        if (conferenceStyle == 'conference') {
                            if (!$("#formito_chat").hasClass('is-open')) {
                                $('.new_chat_badge_container').show();
                            } else {
                                $('.new_chat_badge_container').hide();
                            }
                        } else {
                            $('.new_chat_badge_container').show();
                        }
                    }
                });

                $(document).off('TranslateMessage');
                $(document).on('TranslateMessage', function (e) {
                    var name = (names[e.sessionId]) ? names[e.sessionId].name : peer_name;
                    var avatar = (names[e.sessionId]) ? names[e.sessionId].avatar : peer_avatar;
                    ui_handler.showTranslateMessage(name + ': ' + e.msg);
                });

                $(document).off('SendTyping');
                $(document).on('SendTyping', function (e) {
                    if (e.typing) {
                        $('li[data-system-attribue="chatTyping"]').remove();
                        $('div[data-system-attribue="chatTyping"]').remove();
                        var name = (names[e.sessionId]) ? names[e.sessionId].name : guestName(e.sessionId);
                        var chatTyping = name + ' is typing';
                        showMessage('', chatTyping, null, 'chatTyping');
                    } else {
                        $('li[data-system-attribue="chatTyping"]').remove();
                        $('div[data-system-attribue="chatTyping"]').remove();
                    }

                });

                var keyPressTimer;
                var numberOfKeys = 0;
                $('#newdev_chat_message1').keyup(function (e) {
                    clearTimeout(keyPressTimer);
                    numberOfKeys++;
                    if (numberOfKeys % 3 === 0) {
                        comm_controller.sendTyping(true);
                    }
                    keyPressTimer = setTimeout(function () {
                        comm_controller.sendTyping(false);
                    }, 1200);
                    if (conferenceStyle == 'conference') {
                        var chatmessageValue = $('#newdev_chat_message1').val();
                    } else {
                        chatmessageValue = $('#newdev_chat_message1').text();
                    }
                    if (e.keyCode == 13 && chatmessageValue) {
                        var msg = chatmessageValue;
                        user_act = true;
                        sendChatMessage(msg, true);
                        $('#newdev_chat_message1').html('');
                        $('#newdev_chat_message1').val('');
                        comm_controller.sendTyping(false);
                    }
                });

                if ((svConfigs.videoScreen && svConfigs.videoScreen.waitingRoom) || isOnline || queryString.isAdmin) {
                    setTimeout(function () {
                        setOnline();
                    }, 100);
                } else {
                    setOffline();
                }

                $(document).off('IncomingFileTransfer');
                $(document).on('IncomingFileTransfer', function (e) {
                    if (e.sender) {
                        var sendingFile = smartVideoLocale.msgStore['sendingFile'];
                        showMessage('', sendingFile + e.name + '<br/><div class="progress"><progress id="progress' + e.fileId + '" max="0" value="0"></progress></div>', null, 'fileTransfer');
                    } else {
                        var id = e.fileId;
                        var txt = e.name + '<br/><div class="progress"><progress id="progress' + id + '" max="0" value="0"></progress></div><span id="download' + id + '"></span>';
                        showMessage('', txt, null, 'fileTransfer');
                        if (svConfigs.serverSide.chatHistory) {
                            saveChat(smartVideoLocale.msgStore['receivingFile'] + e.name, '', 'fileTransfer', agentId, '', names);
                        }
                    }

                });

                $("#file_transfer").off();
                $("#file_transfer").on('click', function (e) {
                    var file = new FileSelector();
                    file.selectSingleFile(function (file) {
                        sendChatMessage('sendFile');
                        comm_controller.sendFile(file);
                    });
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
                        var callerInfo = localStorage.getItem('prd');
                        names[comm_controller.getSessionId()] = {name: (callerInfo) ? callerInfo.name : caller_name, avatar: (callerInfo) ? callerInfo.avatar : caller_avatar, email: (callerInfo) ? callerInfo.email : caller_email};
                        if (conferenceStyle == 'conference') {
                            startInitialScreen();
                        }
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
                    requirePass = (e.pass != undefined && e.pass);
                    setOnline(e.sessionId);
                });

                $(document).off('PopupOnline');
                $(document).on('PopupOnline', function (e) {
                    if (e.sessionId && e.sessionId !== 'visitor') {
                        if (!names[e.sessionId]) {
                            names[e.sessionId] = {avatar: (e.avatar) ? e.avatar : lsRepUrl + 'img/small-avatar.jpg', name: (e.name) ? e.name : guestName(e.sessionId)};
                            if (e.callerInfo) {
                                updateInfo(e);
                            }
                        }

                        isOnline = true;
                        requirePass = (e.pass != undefined && e.pass);
                        setOnline(e.sessionId);
                    }
                });

                $('.wd-v-recording').on('click', function () {
                    if (multiStreamRecorder && multiStreamRecorder.getState() == 'recording') {
                        stopRecording(true);
                    } else {
                        startRecording();
                    }
                });

                $('#startRecording').on('click', function () {
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
                    ui_handler.displayScreenShare();
                    $('#remoteScreen').show();
                    ui_handler.setScreenDisabled(true);

                });

                $(document).on('IncomingScreenShare', function (e) {
                    ui_handler.syncVideoChatPanelsPos();
                    console.log('IncomingScreenShare', e);
                });

                $(document).off('ScreenShareEnded');
                $(document).on('ScreenShareEnded', function (e) {
                    console.log('ScreenShareEnded');
                    ui_handler.setScreenDisabled(false);
                    screenShareUI(false);
                    if (!comm_controller.getScreenStream() && !$('#remoteScreen').is(":visible") && !comm_controller.getStream()) {
                        if (conferenceStyle == 'conference') {
                            ui_handler.displayVideoOnly();
                        } else {
                            ui_handler.toggleInstaChat();
                        }
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

                if ((isChrome || isFirefox || isOpera || isSafariA) && (!isiPhone) && (queryString.room || queryString.broadcast || localStorage.getItem('hasPrivileges'))) {
                    $('.wd-v-share').show();
                    $('#screenshare_div').show();
                    $(document).on('PluginDetected', function (e) {
                        pluginInstalled = true;
                    });
                    $(document).on('PluginNotDetected', function (e) {
                        pluginInstalled = false;
                    });
//                        pluginController.init(svConfigs.chromePluginId, document);
                    $('.control-ss > a').click(function () {
                        $('.control-ss').hide();
                    });

                }

                var startScreenFromButton = function () {
                    startScreenShare();
                };

                $('.wd-v-share').on('click', function () {
                    startScreenFromButton();
                });

                $('#startscreenshare').off('click', function () {

                });

                $('#startscreenshare').on('click', function () {
                    if (isOnline) {
                        if (comm_controller.getScreenStream()) {
                            hangUpScreenShare();
                        } else {
                            ui_handler.displayVideoOnly();
                            startScreenFromButton();
                        }

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

                if ((queryString.isAdmin || queryString.broadcast) && (isChrome || isFirefox || isOpera) && (!isiPhone) && svConfigs.recording.enabled == true) {
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


                $(document).on('click', '.closebtn', function () {
                    $('body').removeClass('menuopen');
                    $('.menu-link').removeClass('active');

                });
                $(document).on('click', '.fo-icon', function () {
                    $('.formito-launcher').toggleClass('is-open');
                    $('.new_chat_badge_container').hide();
                });
                $(document).on('click', '.fa-users', function (e) {
                    $('#attendees').toggle();
                    e.stopPropagation();
                });
                $(document.body).on('click', function (e) {
                    var id = e.target.id;
                    if (id !== 'private_message_small' && id !== 'fausers') {
                        $('#attendees').hide();
                        $('#visitor_message').hide();
                    }
                });

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
//            });
        }
        ;

        ui_handler = new uiHandler();
        ui_handler.init(jQuery, $container, comm_controller);

        jQuery(document).on('CommConnected', function (e) {
            function ntfParent() {
                deleteCookie('lsvGreenRoom');
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