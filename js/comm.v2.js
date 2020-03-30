'use strict';
var publicRoomIdentifier, connection, videoConnection, screenConnection, tenant, role, sessionId, roomId, forceClose = false,
        autoReconnectInterval = 5 * 1000, socket, streamConstraints, recentFile, conversationPanel, tempStream, RMCMediaTrack, lsDesigner, popupNotifications = [], repeatStatInterval = 2000,
        visitorRinging = [], conferenceStyle, classVideo, videoElementContainer, videoWidgetContainer, roomLinkPage, requirePassComm = false;
var comController = function () {

    var self = this;
    this.init = function (att, room) {
        requirePassComm = (typeof passRoom !== 'undefined' && passRoom);
        roomId = room;

        role = att;
        sessionId = (getCookie('sessionId') && getCookie('sessionId') !== 'admin') ? getCookie('sessionId') : getGuid();
//        sessionId = getGuid();
        if (role === 'admin') {
            sessionId = 'admin';
        }
        if (queryString.isAdmin) {
            sessionId = sessionId + 'a';
        }
        setCookie('sessionId', sessionId, 1);
        window.enableAdapter = true;
        publicRoomIdentifier = 'dashboard';
        tenant = 'dashboard';
        connection = new RTCMultiConnection();
        connection.iceServers = svConfigs.iceServers.iceServers;

        connection.socketURL = svConfigs.appWss;

        conferenceStyle = (svConfigs.videoScreen && svConfigs.videoScreen.videoContainerStyle === 'conference') ? 'conference' : 'simple';
        classVideo = (conferenceStyle === 'conference') ? 'sourcevideo' : 'bigvideo';
        videoElementContainer = (conferenceStyle === 'conference') ? 'video_container_small' : 'video_container';
        videoWidgetContainer = (conferenceStyle === 'conference') ? 'w.html' : 'widget.html';
        roomLinkPage = (conferenceStyle == 'conference') ? 'r.html' : 'room.html';

        connection.publicRoomIdentifier = publicRoomIdentifier;

        connection.onbeforeunload = function () {
//
        };
        connection.session = {
            audio: false,
            video: false,
            data: true
        };
        if (typeof names !== 'undefined') {
            var name = (names[sessionId]) ? names[sessionId].name : guestName(sessionId);
        } else {
            name = guestName(sessionId);
        }
        connection.extra = {
            'role': role,
            'name': name,
            'tenant': tenant,
            'sessionId': sessionId,
            'ua': navigator.userAgent,
            'referrer': document.title,
            'roomId': roomId,
            'isAdmin': (queryString.isAdmin || (role == 'admin')) ? 1 : 0,
            'pass': requirePassComm,
            'callerInfo': (localStorage.getItem('prd')) ? JSON.parse(localStorage.getItem('prd')) : ''
        };

        connection.onopen = function (event) {
            console.log('You are connected with: ' + connection.getAllParticipants().join(', '));
            if (lsDesigner && lsDesigner.pointsLength <= 0) {
                // make sure that remote user gets all drawings synced.
                setTimeout(function () {
                    connection.send('plz-sync-points');
                }, 1000);
            }
            if (event.extra.role == 'popup') {
                if (event.extra.isAdmin) {
                    var e = jQEngager.Event('AdminPopupOnline', {sessionId: event.extra.sessionId, isAdmin: 1, pass: event.extra.pass});
                    jQEngager(document).trigger(e)

                } else {
                    if (event.extra.callerInfo) {
                        var event = {sessionId: event.extra.sessionId, isAdmin: event.extra.isAdmin, name: (event.extra.callerInfo) ? event.extra.callerInfo.name : event.extra.name, callerInfo: event.extra.callerInfo, pass: event.extra.pass};
                    } else {
                        event = jQEngager.Event('PopupOnline', {sessionId: event.extra.sessionId, isAdmin: event.extra.isAdmin, pass: event.extra.pass});
                    }
                    var e = jQEngager.Event('PopupOnline', event);
                    jQEngager(document).trigger(e);
                }

            }
            if (event.extra && event.extra.role == 'admin') {
                var e = jQEngager.Event('AdminOnline');
                jQEngager(document).trigger(e);
            }
        };
        connection.onclose = connection.onerror = connection.onleave = function (event) {
            var e = jQEngager.Event('PopupLeft', {sessionId: event.userid, isAdmin: 0});
            jQEngager(document).trigger(e);
//            if (self.getParticipants().length === 0) {
//                var e = jQEngager.Event('PopupOffline', {sessionId: event.sessionId, isAdmin: 0});
//                jQEngager(document).trigger(e);
//            }
            if (event.extra && event.extra.role == 'admin') {
                var e = jQEngager.Event('AdminOffline');
                jQEngager(document).trigger(e);
            }
            if (event.extra && event.extra.role == 'visitor') {
                jQEngager('#simpleButton' + event.extra.sessionId).hide();
                jQEngager('#chats-lsv-admin').remove('#simpleButton' + event.extra.sessionId);
            }
            console.log('On close', event);
        };

        connection.onUserStatusChanged = function (event) {
//            console.log('onUserStatusChanged', event);

            if (event.status == 'offline') {
                if (connection.getAllParticipants().length == 0) {
                    var e = jQEngager.Event('AdminPopupOffline');
                    jQEngager(document).trigger(e);
                    self.handleCallTermination();
                }
                var e = jQEngager.Event('PopupLeft', {sessionId: event.userid, isAdmin: 0});
                jQEngager(document).trigger(e);
            }
            if (event.status == 'online') {
                if (connection.getAllParticipants().length > 0 && event.extra && event.extra.sessionId != sessionId) {
                    if (event.extra.callerInfo) {
                        var event = {sessionId: event.extra.sessionId, isAdmin: event.extra.isAdmin, name: (event.extra.callerInfo) ? event.extra.callerInfo.name : event.extra.name, callerInfo: event.extra.callerInfo, pass: event.extra.pass};
                    } else {
                        event = {sessionId: event.extra.sessionId, isAdmin: event.extra.isAdmin, pass: event.extra.pass};
                    }
                    var e = jQEngager.Event('PopupOnline', event);
                    jQEngager(document).trigger(e);
                }
            }
        };

        connection.onPeerStateChanged = function (event) {
//            console.log('On PeerStateChanged', event);
            if (event.iceConnectionState == 'closed' && event.extra && event.extra.isAdmin) {
                var e = jQEngager.Event('AdminOffline');
                jQEngager(document).trigger(e);
                var e = jQEngager.Event('AdminPopupOffline');
                jQEngager(document).trigger(e);
                for (var i = 0; i < popupNotifications.length; i++) {
                    if (popupNotifications[i] == event.extra.sessionId) {
                        popupNotifications.splice(i, 1);
                    }
//                    if (event.extra.isAdmin) {
//                        var e = jQEngager.Event('AdminPopupOnline', {sessionId: event.sessionId, isAdmin: 1});
//                        jQEngager(document).trigger(e)
//                    } else {
//                        var e = jQEngager.Event('PopupOnline', {sessionId: event.sessionId, isAdmin: 0});
//                        jQEngager(document).trigger(e);
//                    }
                }
            }

        };

        this.onGettingWebRTCStats = function (stats, remoteUserId) {
            if (!videoConnection.peers[remoteUserId]) {
                stats.nomore();
            }
            var statsData = 'UserID: ' + remoteUserId + '\n';
            statsData += 'Bandwidth: ' + bytesToSize(stats.bandwidth.speed);
            statsData += '\n';
            statsData += 'Encryption: ' + stats.encryption;
            statsData += '\n';
            statsData += 'Codecs: ' + stats.audio.recv.codecs.concat(stats.video.recv.codecs).join(', ');
            statsData += '\n';
            statsData += 'Data: ' + bytesToSize(stats.audio.bytesReceived + stats.video.bytesReceived);
            statsData += '\n';
            statsData += 'ICE: ' + stats.connectionType.remote.candidateType.join(', ');
            statsData += '\n';
            statsData += 'Port: ' + stats.connectionType.remote.transport.join(', ');

            stats.results.forEach(function (item) {
                if (item.type === 'ssrc' && item.transportId === 'Channel-audio-1') {
                    var packetsLost = item.packetsLost;
                    var packetsSent = item.packetsSent;
                    var audioInputLevel = item.audioInputLevel;
                    var trackId = item.googTrackId; // media stream track id
                    var isAudio = item.mediaType === 'audio'; // audio or video
                    var isSending = item.id.indexOf('_send') !== -1; // sender or receiver

                    statsData += '\n';
                    statsData += 'packetsLost: ' + packetsLost;
                }
            });

//            console.log('getStats', statsData);
        };

        this.joinBroadcastLooper = function () {
            videoConnection.extra.broadcaster = false;
            videoConnection.dontCaptureUserMedia = true;
            videoConnection.session.oneway = true;
            // join-broadcast looper
            (function reCheckRoomPresence() {
                videoConnection.checkPresence(roomId + '_video', function (isRoomExist, roomid, extra) {
                    // note: last parametr on checkPresence will be changed in future
                    // it is expected to return "error" rather than "extra"
                    // so you can compare: if(error === connection.errors.ROOM_FULL) {}
                    if (extra._room) {
                        if (extra._room.isFull) {
                            alert('Room is full.');
                        }
                    }
                    if (isRoomExist) {
                        videoConnection.join(roomId + '_video', function (isRoomJoined, roomid, error) {
                            if (error) {
                                console.error('join', error, roomId + '_video');
                                return;
                            }
                        });
                        return;
                    }
                    setTimeout(reCheckRoomPresence, 5000);
                });
            })();
        };

        this.startScreenConnection = function () {
            screenConnection = new RTCMultiConnection();
            screenConnection.closeBeforeUnload = false;
            screenConnection.iceServers = svConfigs.iceServers.iceServers;
            screenConnection.socketURL = svConfigs.appWss;
            screenConnection.publicRoomIdentifier = publicRoomIdentifier;
            screenConnection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: false,
                OfferToReceiveVideo: true
            };

            var screen_constraints = {
                mandatory: {
                    maxWidth: screen.width > 1920 ? screen.width : 1920,
                    maxHeight: screen.height > 1080 ? screen.height : 1080
                },
                optional: []
            };
            screenConnection.mediaConstraints = screen_constraints;
            screenConnection.session = {
                screen: true,
                oneway: true
            };

            screenConnection.onstream = function (event) {
                if (event.type === 'local') {
                    var video = document.getElementById('localScreen');
                } else {
                    video = document.getElementById('remoteScreen');
                }

                try {
                    video.setAttributeNode(document.createAttribute('autoplay'));
                    video.setAttributeNode(document.createAttribute('playsinline'));
                } catch (e) {
                    video.setAttribute('autoplay', true);
                    video.setAttribute('playsinline', true);
                }
                if (event.type === 'local') {
                    video.volume = 0;
                    try {
                        video.setAttributeNode(document.createAttribute('muted'));
                    } catch (e) {
                        video.setAttribute('muted', true);
                    }
                } else {
                    var e = jQEngager.Event('RemoteScreenShareStarted');
                    jQEngager(document).trigger(e);
                }
                video.srcObject = event.stream;

            };
            screenConnection.onstreamended = function (event) {
                var e = jQEngager.Event('ScreenShareEnded');
                jQEngager(document).trigger(e);
                self.handleScreenShareTermination();

            };
            screenConnection.onMediaError = function (e) {
                if (e.message === 'Concurrent mic process limit.') {
                    if (DetectRTC.audioInputDevices.length <= 1) {
                        alert('Please select external microphone. Check github issue number 483.');
                        return;
                    }
                    var secondaryMic = DetectRTC.audioInputDevices[1].deviceId;
                    screenConnection.mediaConstraints.audio = {
                        deviceId: secondaryMic
                    };
//                connection.join(connection.sessionid);
                }
                if (e.message === 'Permission denied') {
                    var e = jQEngager.Event('ScreenShareEnded');
                    jQEngager(document).trigger(e);
                    self.handleScreenShareTermination();
                    self.startScreenConnection();
                }
            };
        };
        connection.onmessage = function (event) {
            //chat, canvas, etc.
//                console.log('message', event);
            if (event.data.typing === true) {
                var e = jQEngager.Event('SendTyping', {typing: true, sessionId: event.userid});
                jQEngager(document).trigger(e);
                return;
            }
            if (event.data.typing === false) {
                var e = jQEngager.Event('SendTyping', {typing: false});
                jQEngager(document).trigger(e);
                return;
            }
            if (event.data.chatMessage) {
                if (event.data.privateId) {
                    if (event.data.privateId == self.getSessionId()) {
                        var e = jQEngager.Event('ChatMessage', {msg: event.data.chatMessage, date: event.data.date, sessionId: event.data.sessionId, privateId: event.data.privateId});
                        jQEngager(document).trigger(e);
                    }
                    if (event.data.privateId && role === 'admin') {
                        jQEngager('#simpleButton' + event.data.privateId).show();
                        var msgerChat = $('#contentChatsimple' + event.data.privateId)[0];
                        var side = 'left';
                        var name = $('#nameChat' + event.data.privateId).val();
                        const msgHTML = `
                                <div class="msg-lsv ${side}-msg-lsv">
                                  <div class="msg-lsv-bubble">
                                    <div class="msg-lsv-info">
                                      <div class="msg-lsv-info-name">${name}</div>
                                      <div class="msg-lsv-info-time">${getPrettyDate(new Date().getTime() / 1000)}</div>
                                    </div>

                                    <div class="msg-lsv-text">${event.data.chatMessage}</div>
                                  </div>
                                </div>
                              `;

                        msgerChat.insertAdjacentHTML('beforeend', msgHTML);
                        msgerChat.scrollTop += 500;
                        playEnterRoom();
                    }
                } else {
                    var e = jQEngager.Event('ChatMessage', {msg: event.data.chatMessage, date: event.data.date, sessionId: event.data.sessionId});
                    jQEngager(document).trigger(e);
                }
                return;
            }
            if (event.data.translateMessage) {
                var e = jQEngager.Event('TranslateMessage', {msg: event.data.translateMessage, sessionId: event.data.sessionId});
                jQEngager(document).trigger(e);
                return;
            }
            if (event.data === 'voiceSpeaking') {
                var e = jQEngager.Event('VoiceSpeaking', {id: event.userid});
                jQEngager(document).trigger(e);
            }
            if (event.data === 'voiceSilence') {
                var e = jQEngager.Event('VoiceSilence', {id: event.userid});
                jQEngager(document).trigger(e);
            }
            if (lsDesigner && event.data === 'plz-sync-points') {
                lsDesigner.sync();
                return;
            }

            if (event.data.whiteboardData) {
                var e = jQEngager.Event('WhiteboardSync');
                jQEngager(document).trigger(e);
                if (!queryString.isAdmin && !localStorage.getItem('hasPrivileges')) {
                    self.startWhiteboard();
                }
                if (!lsDesigner) {
                    self.startWhiteboard();
                }
                if (event.data.whiteboardData) {
                    var points = event.data.whiteboardData;
                    var ratioWidth = screen.width / event.data.width;
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
                    lsDesigner.syncData(points);
                } else {
                    lsDesigner.clearCanvas();
                }
            }
        };

        if (queryString.room) {

            videoConnection = new RTCMultiConnection();
            videoConnection.iceServers = svConfigs.iceServers.iceServers;
            videoConnection.socketURL = svConfigs.appWss;
            videoConnection.publicRoomIdentifier = publicRoomIdentifier;
            if (queryString.broadcast) {
                videoConnection.sdpConstraints.mandatory = {
                    OfferToReceiveAudio: true,
                    OfferToReceiveVideo: (queryString.isAdmin) ? true : false,
                };
            } else {
                videoConnection.sdpConstraints.mandatory = {
                    OfferToReceiveAudio: true,
                    OfferToReceiveVideo: true
                };
            }
            videoConnection.extra = {
                sessionId: sessionId
            };

            videoConnection.onPeerStateChanged = function (event) {
//                if (event.iceConnectionState === 'connected' && event.signalingState === 'stable') {
//                    if (videoConnection.peers[event.userid].gettingStats === true) {
//                        return;
//                    }
//                    videoConnection.peers[event.userid].gettingStats = true;
//                    var peer = videoConnection.peers[event.userid].peer;
//                    if (DetectRTC.browser.name === 'Firefox') {
//                        getStats(peer, peer.getLocalStreams()[0].getTracks()[0], function (stats) {
//                            self.onGettingWebRTCStats(stats, event.userid);
//                        }, repeatStatInterval);
//                    } else {
//                        getStats(peer, function (stats) {
//                            self.onGettingWebRTCStats(stats, event.userid);
//                        }, repeatStatInterval);
//                    }
//                }
            };

            videoConnection.onspeaking = function (e) {
                if (audio_on && video_on && self.getStream()) {
                    connection.send('voiceSpeaking');
                }
            };

            videoConnection.onsilence = function (e) {
                connection.send('voiceSilence');
            };

            videoConnection.onMediaError = function (e) {
                if (e.message === 'Permission denied') {
                    var e = jQEngager.Event('TogglePermissionDenied');
                    jQEngager(document).trigger(e);
                    socket.emit(connection.socketCustomEvent, {
                        'type': 'endCall',
                        'role': role,
                        'tenant': tenant,
                        'sessionId': sessionId,
                        'roomId': roomId
                    });
                } else {
                    videoConnection.openOrJoin(roomId + '_video', function (isRoomExist, roomid, error) {
                        if (error) {
                            console.error('openOrJoin', error, roomid);
                            return;
                        }
                    });
                }
            };

            self.startScreenConnection();

            connection.chunkSize = 16000;
            connection.enableFileSharing = true;




            connection.onUserIdAlreadyTaken = function (useridAlreadyTaken, yourNewUserId) {
//            connection.join(useridAlreadyTaken);
            };

            connection.autoSaveToDisk = false;
            var progressHelper = {};
            connection.onFileProgress = function (chunk, uuid) {
                var helper = progressHelper[chunk.uuid];
                helper.progress[0].value = chunk.currentPosition || chunk.maxChunks || helper.progress[0].max;
            };

            connection.onFileStart = function (file) {

                if (file.userid === connection.userid) {
                    if (!recentFile.started) {
                        recentFile.started = true

                        var e = jQEngager.Event('IncomingFileTransfer', {name: file.name, sender: true, fileId: file.uuid});
                        jQEngager(document).trigger(e);
                    }
                } else {
                    var e = jQEngager.Event('IncomingFileTransfer', {name: file.name, sender: false, fileId: file.uuid});
                    jQEngager(document).trigger(e);
                }


                progressHelper[file.uuid] = {
                    progress: jQEngager('#progress' + file.uuid)
                };
                progressHelper[file.uuid].progress.max = file.maxChunks;
            };

            connection.onFileEnd = function (file) {
                var html = self.getFileHTML(file);

                if (file.userid === connection.userid) {

                    if (recentFile) {
                        recentFile.userIndex++;
                        var nextUserId = connection.getAllParticipants()[recentFile.userIndex];
                        if (nextUserId) {
                            connection.send(recentFile, nextUserId);
                        } else {
                            recentFile = null;
                        }
                    } else {
                        recentFile = null;
                    }
                } else {
                    jQEngager('#download' + file.uuid).html(html);
                }
            };


            videoConnection.onstream = function (event) {

                console.log('videoConnection.onstream', event);
                videoConnection.videosContainer = document.getElementById(videoElementContainer);
                if (event.type === 'local') {
                    //self.checkMediaDevices();
                    var e = jQEngager.Event('LocalVideoStarted');
                    jQEngager(document).trigger(e);
                    var video = document.getElementById('localVideo');
                    video.setAttribute('data-sessionId', event.extra.sessionId);
                    video.muted = true;
                    video.volume = 0;
                    video.srcObject = event.stream;
                } else {
                    if (typeof event.extra.sessionId === 'undefined') {
                        return;
                    }
                    $('.sourcevideo').each(function () {
                        if ($(this).attr('id') == event.extra.sessionId) {
                            return;
                        }
                    });
                    $('.bigvideo').each(function () {
                        if ($(this).attr('id') == event.extra.sessionId) {
                            return;
                        }
                    });

                    var span = document.getElementById('remoteVideoSpan' + event.extra.sessionId);
                    var video = document.getElementById(event.extra.sessionId);
                    if (span) {
                        span.remove();
                    }
                    if (video) {
                        video.remove();
                    }


                    event.mediaElement.controls = false;
                    event.mediaElement.removeAttribute('src');
                    event.mediaElement.removeAttribute('srcObject');

                    var mediaElement = document.createElement('video');
                    mediaElement.id = event.extra.sessionId;
                    var videoSessions = videoConnection.getAllParticipants().length;
                    mediaElement.setAttribute('class', classVideo);
                    if (conferenceStyle !== 'conference') {
                        var width = ((videoSessions) > 1) ? '49%' : '98%';
                        var position = (videoSessions > 1) ? 'relative' : 'absolute';
                        mediaElement.style.width = width;
                    }
                    try {
                        mediaElement.setAttributeNode(document.createAttribute('autoplay'));
                        mediaElement.setAttributeNode(document.createAttribute('playsinline'));
                        mediaElement.setAttributeNode(document.createAttribute('videoautoplay'));
                    } catch (e) {
                        mediaElement.playsinline = true;
                        mediaElement.autoplay = true;
                        mediaElement.videoautoplay = true;
                    }
                    var mediaStream = event.stream;
                    if ('srcObject' in mediaElement) {
                        mediaElement.srcObject = mediaStream;
                    } else {
                        mediaElement[!!navigator.mozGetUserMedia ? 'mozSrcObject' : 'src'] = !!navigator.mozGetUserMedia ? mediaStream : (window.URL || window.webkitURL).createObjectURL(mediaStream);
                    }

                    videoConnection.videosContainer.appendChild(mediaElement);

                    if (queryString.broadcast) {
                        var e = jQEngager.Event('RemoteVideoStarted');
                        jQEngager(document).trigger(e);
                        if (queryString.token) {
                            toggleNotification(smartVideoLocale.msgStore['welcomeBroadcast'], true);
                        } else {
                            toggleNotification(smartVideoLocale.msgStore['incomingBroadcast'], true);
                        }
                        var playPromise = mediaElement.play();
                        if (playPromise !== undefined) {

                            playPromise.then(function () {
                                toggleNotification('', false);
                                mediaElement.play();
                            }).catch(function (error) {

                                $('#incomingBroadcast').click(function () {
                                    toggleNotification('', false);
                                    mediaElement.play();

                                });
                            });
                        }
                    } else {
                        var e = jQEngager.Event('RemoteVideoStarted');
                        jQEngager(document).trigger(e);
                        mediaElement.play();
                    }
                    setTimeout(function () {
                        $('.' + classVideo).each(function () {
                            if ($(this).is(':visible')) {
                                $(this).css('width', width);
                                $(this).css('position', position);
                            }
                        });
                    }, 100);
                    setTimeout(function () {
                        $('.' + classVideo).each(function () {
                            if ($(this).is(':visible')) {
                                var id = $(this).attr('id');
                                $('#remoteVideoSpan' + id).remove();
                                var post = $(this).position();
                                var e = jQEngager.Event('RemoteSpanPosition', {sessionId: id, position: post});
                                jQEngager(document).trigger(e);
                            }
                        });
                    }, 1000);
                    visitorRinging = [];
                }
                if (queryString.broadcast) {
                    if (event.type === 'remote' && videoConnection.isInitiator) {
                        var participants = [];
                        videoConnection.getAllParticipants().forEach(function (pid) {
                            participants.push({
                                pid: pid,
                                broadcaster: videoConnection.peers[pid].extra.broadcaster === true
                            });
                        });
                        videoConnection.socket.emit(videoConnection.socketCustomEvent, {
                            participants: participants
                        });
                    } else if (event.type === 'remote' && !videoConnection.extra.broadcaster) {
                        videoConnection.socket.emit(videoConnection.socketCustomEvent, {
                            giveAllParticipants: true,
                            roomId: roomId
                        });
                    }
                }

                videoConnection.onUserStatusChanged(event);
                if (svConfigs.videoScreen.videoConference == false) {
                    setTimeout(function () {
                        connection.send('voiceSpeaking');
                    }, 1500);
                }
                self.initHark({
                    stream: event.stream,
                    streamedObject: event,
                    connection: videoConnection
                });
            };

            videoConnection.onstreamended = function (event) {
                if (!event.extra) {
                    return;
                }
                var video = document.getElementById(event.extra.sessionId);
                if (video) {
                    video.parentNode.removeChild(video);
                    $('#remoteVideoSpan' + event.extra.sessionId).remove();
                }

                var videoSessions = videoConnection.getAllParticipants().length;

                if (conferenceStyle !== 'conference') {
                    var width = ((videoSessions) > 1) ? '49%' : '98%';
                    var position = (videoSessions > 1) ? 'relative' : 'absolute';
                }

                setTimeout(function () {
                    $('.' + classVideo).each(function () {
                        if ($(this).is(':visible')) {
                            $(this).css('width', width);
                            $(this).css('position', position);
                        }
                    });
                }, 100);
                setTimeout(function () {
                    $('.' + classVideo).each(function () {
                        if ($(this).is(':visible')) {
                            var id = $(this).attr('id');
                            $('#remoteVideoSpan' + id).remove();
                            var post = $(this).position();
                            var e = jQEngager.Event('RemoteSpanPosition', {sessionId: id, position: post});
                            jQEngager(document).trigger(e);
                        }
                    });
                }, 1000);

                if (videoSessions === 0) {
                    self.handleCallTermination();
                }
                if (queryString.broadcast && !connection.isInitiator) {
                    toggleError(smartVideoLocale.msgStore['broadcastStopped'], 5000);
                    location.reload();

                }
            };

            videoConnection.onUserStatusChanged = function (event) {
                var names = [];
                connection.getAllParticipants().forEach(function (pid) {
                    names.push(self.getFullName(pid));
                });
                if (!names.length) {
                    names = ['Only You'];
                } else {
                    names = [connection.extra.userFullName || 'You'].concat(names);
                }
                console.log('<b>Active users:</b> ' + names.join(', '));
            };



            videoConnection.onopen = function () {
                console.log('You are connected with: ' + connection.getAllParticipants().join(', '));
            };
            videoConnection.onclose = function () {
                if (videoConnection.getAllParticipants().length) {
                }
            };
            videoConnection.onEntireSessionClosed = function (event) {

                if (videoConnection) {
                    videoConnection.leave();
                    videoConnection.streamEvents.selectAll({
                        local: true
                    }).forEach(function (streamEvent) {
                        streamEvent.stream.getAudioTracks()[0].stop();
                        streamEvent.stream.getVideoTracks()[0].stop();
                    });
                }
                var e = jQEngager.Event('CallEnded');
                jQEngager(document).trigger(e);
            };

            if (conferenceStyle == 'conference') {
                connection.autoCloseEntireSession = false;
                videoConnection.autoCloseEntireSession = false;
                screenConnection.autoCloseEntireSession = false;
            }

        }

        self.connect();
    };

    this.connect = function () {

        this.updateListOfRooms = function (rooms) {


//            connection.getAllParticipants().forEach(function (participantId) {
//                console.log(participantId);
//                var user = connection.peers[participantId];
////                console.log(user);
//            });
            jQEngager('#visitors').empty();
            rooms.forEach(function (room, idx) {
                room.participants.forEach(function (participant, idx) {

                    var user = connection.peers[participant];
                    if (!user) {
                        return;
                    }
                    var extraData = user.extra;
                    var divId = jQEngager('#visitors').find('#' + extraData.sessionId);
                    var ua = (extraData.ua) ? detect.parse(extraData.ua) : '';
                    var browerName = (ua) ? ua.browser.name : '';
                    var osName = (ua) ? ua.os.name : '';
                    var name = (extraData.callerInfo && extraData.callerInfo.name) ? extraData.callerInfo.name : guestName(extraData.sessionId);
                    var chat = '<a href="javascript:void(0);" id="chat' + extraData.sessionId + '">Start chat</a>';
                    var simpleChatUi = '<section class="msger-lsv" id="simpleButton' + extraData.sessionId + '" style="display: none;">\
                            <header class="msger-lsv-header">\
                                <div class="msger-lsv-header-title">\
                                        ' + name + '\
                                </div>\
                                <div class="msger-lsv-header-options">\
                                        <a href="javascript:void(0);" title="" class="close-but-wd-small" id="closeSimpleChat' + extraData.sessionId + '"><span></span></a>\
                                </div>\
                        </header>\
                        <main class="msger-lsv-chat" id="contentChatsimple' + extraData.sessionId + '">\
                        </main>\
                        <form class="msger-lsv-inputarea" id="form' + extraData.sessionId + '">\
                                <input type="text" id="input' + extraData.sessionId + '" class="msger-lsv-input" placeholder="Enter your message...">\
                                <input type="hidden" id="nameChat' + extraData.sessionId + '" value="' + name + '">\
                                <button type="submit" class="msger-lsv-send-btn">Send</button>\
                        </form>\
                </section>';
                    if (divId.length > 0) {
                        var newNode = divId[0];
                        newNode.innerHTML = '<div class="col-sm-10 col-xs-10">\
                                                                        <div class="messages msg_receive">\
                                                                                <p>' + name + ' ' + extraData.referrer + ' ' + chat + '<br/>' + osName + ' ' + browerName + '</p>\
                                                                                \
                                                                        </div>\
                                                                </div>';
                        jQEngager(divId[0]).replaceWith(newNode);

                    } else {
                        chat = '<a href="javascript:void(0);" id="chat' + extraData.sessionId + '">Start chat</a>';
                        newNode = document.createElement('div');
                        newNode.className = 'row msg_container base_receive';
                        newNode.id = extraData.sessionId;


                        newNode.innerHTML = '<div class="col-sm-10 col-xs-10">\
                                                                        <div class="messages msg_receive">\
                                                                                <p>' + name + ' ' + extraData.referrer + ' ' + chat + '<br/>' + osName + ' ' + browerName + '</p>\
                                                                                \
                                                                        </div>\
                                                                </div>';

                        jQEngager('#visitors').append(newNode);
                    }
                    $('#chat' + extraData.sessionId).off('click');
                    $('#chat' + extraData.sessionId).click(function () {
                        $('#simpleButton' + extraData.sessionId).show();
                    });
                    var chatId = jQEngager('#chats-lsv-admin').find('#simpleButton' + extraData.sessionId);
                    if (chatId.length == 0) {
                        jQEngager('#chats-lsv-admin').append(simpleChatUi);
                        $('#chat' + extraData.sessionId).click(function () {
                            $('#simpleButton' + extraData.sessionId).show();
                        });

                        $('#closeSimpleChat' + extraData.sessionId).click(function () {
                            $('#simpleButton' + extraData.sessionId).hide();
                        });
                        var appendMessage = function (name, side, text) {
                            if (!text) {
                                return;
                            }
                            var msgerChat = $('#contentChatsimple' + extraData.sessionId)[0];

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
                        $('#form' + extraData.sessionId).submit(function (event) {
                            event.preventDefault();
                            var msgerInput = $('#input' + extraData.sessionId);
                            var msgText = msgerInput.val();
                            appendMessage('Me', 'right', msgText);
                            msgerInput.val('');
                            self.addLocalChat(msgText, null, extraData.sessionId);
                        });
                    }

                    if (room.extra.role == 'popup') {
                        var element = jQEngager('#visitors').find('#' + extraData.sessionId);
                        var checkExists = jQEngager('#visitors').find('#room' + extraData.sessionId);
                        if (element.length > 0 && checkExists.length === 0) {


                            setTimeout(function () {
                                var divElement = element.children().children().children();
                                var str = {};
                                str.names = (svConfigs.agentName) ? svConfigs.agentName : guestName(extraData.sessionId);
                                if (lsRepUrl) {
                                    str.lsRepUrl = lsRepUrl;
                                }
                                if (agentId) {
                                    str.agentId = agentId;
                                }

                                var encodedString = window.btoa(JSON.stringify(str));
                                var roomLink = lsRepUrl + 'pages/' + roomLinkPage + '?room=' + room.extra.roomId + '&p=' + encodedString + '&isAdmin=1';
                                var newNode = document.createElement('span');
                                newNode.id = 'room' + extraData.sessionId;
                                newNode.innerHTML = ' <a href="' + roomLink + '" target="_blank">Enter Room</a>';
                                divElement.append(newNode);
                            }, 200);

                            if (jQEngager.inArray(extraData.sessionId, popupNotifications) == -1) {
                                playEnterRoom();
                                var e = jQEngager.Event('EnterPageNotification');
                                jQEngager(document).trigger(e, {name: name});
                                popupNotifications.push(extraData.sessionId);
                            }
                        }
                    }
                });

            });
        };

        this.looper = function () {
            connection.socket.emit('get-public-rooms', publicRoomIdentifier, function (listOfRooms) {
                self.updateListOfRooms(listOfRooms);
                setTimeout(self.looper, 3000);
            });
        }

        // keep room opened even if owner leaves
//        connection.autoCloseEntireSession = true;
        connection.connectSocket(function (skc) {
            socket = skc;
            connection.changeUserId(sessionId, null);
            self.showStatusBar('Connected to the chat server!', 5000);
//            connection.userid = sessionId;
            var e = jQEngager.Event('CommConnected');
            jQEngager(document).trigger(e);

            if (role === 'admin') {
                self.looper();
            }

            var callback = function (event) {
                if (event.type === 'initCall' && sessionId !== event.sessionId && event.roomId === roomId) {
                    connection.getAllParticipants().forEach(function (pid) {
                        visitorRinging.push(pid);
                    });
                    var e = jQEngager.Event('IncomingCall', {autoaccept: event.autoaccept, sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'startScreenShare' && sessionId !== event.sessionId && event.roomId === roomId && !screenConnection.isInitiator && screenConnection.extra.sessionId !== sessionId) {
                    screenConnection.openOrJoin(roomId + '_screen', function (isRoomJoined, roomid, error) {
                        console.log(isRoomJoined, roomid, error);
                        screenConnection.extra = {
                            sessionId: sessionId
                        };
                    });
                }
                if (event.type === 'endCall' && sessionId !== event.sessionId && event.roomId === roomId) {
                    setTimeout(function () {
                        var index = visitorRinging.indexOf(event.sessionId);
                        if (index !== -1) {
                            visitorRinging.splice(index, 1);
                        }
                        if (videoConnection.getAllParticipants().length == 0 && visitorRinging.length == 0) {
//                            self.handleCallTermination();
                            var e = jQEngager.Event('CallEnded');
                            jQEngager(document).trigger(e);
                        }
                    }, 200);
                }
                if (event.type === 'remoteVideoUnmuted' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RemoteVideoUnmuted', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'remoteVideoMuted' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RemoteVideoMuted', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'remoteAudioUnmuted' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RemoteAudioUnmuted', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'remoteAudioMuted' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RemoteAudioMuted', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'revokePriveleges' && sessionId == event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RevokePriveleges', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'grantPriveleges' && sessionId == event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('GrantPriveleges', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'blockUser' && event.roomId === roomId) {
                    var e = jQEngager.Event('BlockUser', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'forceAudioMuted' && event.roomId === roomId) {
                    var e = jQEngager.Event('ForceAudioMuted', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'forceDelete' && event.roomId === roomId) {
                    connection.leave();
                    var e = jQEngager.Event('ForceDelete', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'forceDeleteAll' && sessionId !== event.sessionId && event.roomId === roomId) {
                    connection.leave();
                    var e = jQEngager.Event('ForceDeleteAll');
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'setPresent' && event.roomId === roomId) {
                    var e = jQEngager.Event('SetPresent', {sessionId: event.sessionId, present: event.present});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'endMeeting' && event.roomId === roomId) {
                    var e = jQEngager.Event('EndMeeting', {sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'adminOnline' && event.roomId === roomId) {
                    var e = jQEngager.Event('AdminOnline');
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'toVideo' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('ToVideo');
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'startRecording' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RemoteStartRecording');
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'stopRecording' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('RemoteStopRecording');
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'rejectCall' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var index = visitorRinging.indexOf(event.sessionId);
                    if (index !== -1) {
                        visitorRinging.splice(index, 1);
                    }
                    if (visitorRinging.length == 0) {
                        var e = jQEngager.Event('CallEnded');
                        jQEngager(document).trigger(e);
                    }
                }
                if (event.type === 'setCallerInfo' && sessionId !== event.sessionId && event.roomId === roomId) {
                    var e = jQEngager.Event('CallerInfo', {sessionId: event.sessionId, callerInfo: event.callerInfo, isAdmin: event.isAdmin});
                    jQEngager(document).trigger(e);
                }
                if (event.type === 'clearCanvas' && sessionId !== event.sessionId && event.roomId === roomId) {
                    if (lsDesigner) {
                        lsDesigner.clearCanvas();
                    }
                }
                if (event.type === 'sendCallerBack' && event.roomId === roomId) {
                    var e = jQEngager.Event('SendCallerBack', {access: event.access, sessionId: event.sessionId});
                    jQEngager(document).trigger(e);
                }
                if (event.participants && videoConnection && !videoConnection.isInitiator && !videoConnection.extra.broadcaster) {
                    event.participants.forEach(function (participant) {
                        if (participant.pid === videoConnection.userid)
                            return;
                        if (videoConnection.getAllParticipants().indexOf(participant.pid) !== -1)
                            return;
                        if (!videoConnection.extra.broadcaster && participant.broadcaster === false)
                            return;
                        videoConnection.join(participant.pid, function (isRoomJoined, roomid, error) {
                            if (error) {
                                return;
                            }
                        });
                    });
                }
                if (event.giveAllParticipants && videoConnection && videoConnection.isInitiator && event.roomId == roomId) {
                    var participants = [];
                    videoConnection.getAllParticipants().forEach(function (pid) {
                        participants.push({
                            pid: pid,
                            broadcaster: videoConnection.peers[pid].extra.broadcaster === true
                        });
                    });
                    connection.socket.emit(connection.socketCustomEvent, {
                        participants: participants
                    });
                    toggleError(smartVideoLocale.msgStore['broadcastViewers'] + ' ' + participants.length, 5000);
                }
            };

            socket.on('connect', function (evt) {
                self.showStatusBar('Connected to the chat server!', 5000);
            });

            socket.on('disconnect', function (evt) {
                self.showStatusBar('Unable to connect to the chat server! Kindly refresh', 10000);
                //socket.off(connection.socketCustomEvent, callback);
                //self.reconnectWebsocket(evt);
                var e = jQEngager.Event('AdminOffline');
                jQEngager(document).trigger(e);
                jQEngager('#visitors').empty();
                location.reload();
            });

            socket.on(connection.socketCustomEvent, callback);

            connection.checkPresence(roomId, function (roomExist, roomid) {
                if (roomExist === true) {
                    connection.join(roomId, function (isRoomOpened, roomid, error) {
                        if (queryString.isAdmin) {
                            socket.emit(connection.socketCustomEvent, {
                                'type': 'adminOnline',
                                'role': 'popup',
                                'sessionId': sessionId,
                                'roomId': roomId
                            });
                        }
                        console.log('joined to ' + roomid);
                        if (error) {
                            location.reload();
                        }
                    });
                } else {
                    connection.open(roomId, function (isRoomOpened, roomid, error) {
                        if (queryString.isAdmin) {
                            socket.emit(connection.socketCustomEvent, {
                                'type': 'adminOnline',
                                'role': 'popup',
                                'sessionId': sessionId,
                                'roomId': roomId
                            });
                        }
                        console.log(isRoomOpened, roomid, error);
                        if (error) {
                            location.reload();
                        }
                    });
                }
                if (role === 'admin') {
                    connection.isInitiator = true;
                }
            });

        });
    };

    this.renegotiate = function (videoSource) {

//        navigator.mediaDevices.getUserMedia({
//            video: {
//                facingMode: 'application'
//            }
//        }).then(function (backCamera) {
//            replaceTrack(backCamera);
//        });

        navigator.mediaDevices.getUserMedia({
            video: {
                deviceId: {exact: videoSource}
            },
            audio: audio_on
        }).then(function (newDeviceStream) {
            replaceTrack(newDeviceStream);
        });
        videoConnection.streamEvents.selectAll({
            local: true
        }).forEach(function (streamEvent) {
            if (streamEvent.stream.isAudio) {
                streamEvent.stream.getAudioTracks()[0].stop();
            }
            if (streamEvent.stream.isVideo) {
                streamEvent.stream.getVideoTracks()[0].stop();
            }
        });
        videoConnection.attachStreams.forEach(function (stream) {
            stream.stop();
        })
        var videoCons = (videoSource) ? {deviceId: {exact: videoSource}} : {facingMode: "user"};
        streamConstraints = {video: videoCons, audio: audio_on};
        videoConnection.mediaConstraints = streamConstraints;
        videoConnection.session = {
            video: true,
            audio: audio_on
        };
        videoConnection.join(roomId + '_video');
        function replaceTrack(stream, isScreen) {
            if (!isScreen) {
                videoConnection.lastCamera = stream;
            } else if (!videoConnection.lastCamera) {
                videoConnection.lastCamera = videoConnection.attachStreams[0];
            }

            videoConnection.getAllParticipants().forEach(function (pid) {
                var peer = videoConnection.peers[pid].peer;
                if (!peer.getSenders)
                    return;

                var videoTrack = stream.clone().getVideoTracks()[0];
                var audioTrack = stream.clone().getAudioTracks()[0];

                peer.getSenders().forEach(function (sender) {
                    console.log(sender, sender.track);
                    if (!sender || !sender.track)
                        return;

                    if (sender.track.kind === 'video' && videoTrack) {
                        if (sender.track.id != videoTrack.id) {
                            sender.replaceTrack(videoTrack);
                        }
                        videoTrack = null;
                    } else if (sender.track.kind === 'audio' && audioTrack) {
                        if (sender.track.id != audioTrack.id) {
                            sender.replaceTrack(audioTrack);
                        }
                        audioTrack = null;
                    }
                });
            });


//            var video = document.getElementById('localVideo');
//            video.setAttribute('data-sessionId', sessionId);
//            video.src = '';
//            video.srcObject = null;
//            video.muted = true;
//            video.volume = 0;
//            video.srcObject = stream;
        }
    };

    this.initCall = function (type, autoaccept, videoSource, sessionId, audioSource, videoConstraint, audioConstraint) {
        videoConnection.extra.roomOwner = true;

        var callType = (type) ? type : (this.id === 'initVideo') ? 'Video' : 'Audio';
        var audioCons = (audioSource) ? {deviceId: {exact: audioSource}} : {};
        if (!audio_on) {
            audioCons = false;
        }
        if (audioConstraint) {
            jQEngager.extend(true, audioCons, audioConstraint);
        }
        var videoCons = (videoSource) ? {deviceId: {exact: videoSource}} : {facingMode: "user"};
        if (videoConstraint) {
            jQEngager.extend(true, videoCons, videoConstraint);
        }
        switch (callType) {
            case 'Video':
                var videoSession = true;
                streamConstraints = {video: videoCons, audio: audioCons};
                break;
            case 'Audio':
                videoSession = false;
                streamConstraints = {audio: audioCons, video: false};
                break;
            default:
                videoSession = true;
                streamConstraints = {video: videoCons, audio: audioCons};
                break;
        }
        if (queryString.broadcast) {

            videoConnection.session = {
                audio: true,
                video: (queryString.isAdmin) ? true : false,
                broadcast: true
            };

            videoConnection.mediaConstraints = {
                audio: true,
                video: (queryString.isAdmin) ? true : false
            };
            if (queryString.isAdmin || localStorage.getItem('hasPrivileges')) {
                videoConnection.extra.broadcaster = true;
                videoConnection.openOrJoin(roomId + '_video', function (isRoomExist, roomid, error) {
                    if (error) {
                        console.error('openOrJoin', error, roomid);
                        return;
                    }
                });
            } else {
                videoConnection.extra.roomOwner = false;
                self.joinBroadcastLooper();
            }


        } else {
            var checkVideoPresence = function () {
                streamConstraints.video = (video_on) ? streamConstraints.video : streamConstraints.video = false;
                streamConstraints.audio = (audio_on) ? streamConstraints.audio : streamConstraints.audio = false;
                videoConnection.mediaConstraints = streamConstraints;
                videoConnection.session = {
                    video: (video_on) ? videoSession : false,
                    audio: audio_on
                };
                videoConnection.checkPresence(roomId + '_video', function (roomExist, roomid) {
                    if (roomExist === true) {
                        videoConnection.join(roomId + '_video', function (isRoomOpened, roomid, error) {

                            if (error) {
                                console.log(error);
                                self.handleCallTermination();
                                videoConnection.closeEntireSession(function () {
                                    videoConnection.openOrJoin(roomId + '_video');
                                });
                            }

                        });
                    } else {
                        videoConnection.open(roomId + '_video', function (isRoomOpened, roomid, error) {
                            if (error) {
                                console.log(error);
                                self.handleCallTermination();
                                videoConnection.closeEntireSession(function () {
                                    videoConnection.openOrJoin(roomId + '_video');
                                });
                            }
                        });
                    }

                    if (!video_on) {
                        var e = jQEngager.Event('LocalVideoStarted');
                        jQEngager(document).trigger(e);
                    }
                    if (conferenceStyle == 'simple') {
                        socket.emit(connection.socketCustomEvent, {
                            'type': 'initCall',
                            'role': role,
                            'tenant': tenant,
                            'autoaccept': autoaccept,
                            'sessionId': sessionId,
                            'roomId': roomId
                        });
                    }

                    connection.getAllParticipants().forEach(function (pid) {
                        visitorRinging.push(pid);
                    });

                });
            };

            videoConnection.DetectRTC.load(function () {
                var options = [];
                videoConnection.DetectRTC.videoInputDevices.forEach(function (device) {
                    var option = {}
                    option.value = device.id;
                    option.text = device.label;
                    options.push(option);
                });

                if (videoConnection.DetectRTC.videoInputDevices.length === 0) {
                    var e = jQEngager.Event('VideoRemoved');
                    jQEngager(document).trigger(e);
                    socket.emit(connection.socketCustomEvent, {
                        'type': 'remoteVideoMuted',
                        'role': role,
                        'tenant': tenant,
                        'sessionId': sessionId,
                        'roomId': roomId
                    });

                }

                if (videoConnection.DetectRTC.audioInputDevices.length === 0) {
                    var e = jQEngager.Event('AudioRemoved');
                    jQEngager(document).trigger(e);
                    socket.emit(connection.socketCustomEvent, {
                        'type': 'remoteAudioMuted',
                        'role': role,
                        'tenant': tenant,
                        'sessionId': sessionId,
                        'roomId': roomId
                    });
                }

                if (videoConnection.DetectRTC.videoInputDevices) {
                    var e = jQEngager.Event('MediaDevices', {devices: options});
                    jQEngager(document).trigger(e);
                }
                checkVideoPresence();
            });



        }
//
//            getStats(videoConnection, function (result) {
//                result.connectionType.remote.ipAddress;
//                result.connectionType.remote.candidateType;
//                result.connectionType.transport;
//
//                result.bandwidth.speed; // bandwidth download speed (bytes per second)
//                console.log(result.bandwidth.speed);
//                // to access native "results" array
//                result.results.forEach(function (item) {
//                    if (item.type === 'ssrc' && item.transportId === 'Channel-audio-1') {
//                        var packetsLost = item.packetsLost;
//                        var packetsSent = item.packetsSent;
//                        var audioInputLevel = item.audioInputLevel;
//                        var trackId = item.googTrackId; // media stream track id
//                        var isAudio = item.mediaType === 'audio'; // audio or video
//                        var isSending = item.id.indexOf('_send') !== -1; // sender or receiver
//
//                        console.log('SendRecv type', item.id.split('_send').pop());
//                        console.log('MediaStream track type', item.mediaType);
//                    }
//                });
//            }, repeatStatInterval);



    };

    this.leave = function () {
        connection.getAllParticipants().forEach(function (participantId) {
            connection.disconnectWith(participantId);
        });
    };

    this.toggleAudio = function () {

        var audio = 1;
        videoConnection.attachStreams.forEach(function (stream) {
            stream.getAudioTracks().forEach(function (str) {
                audio = str.enabled = !str.enabled;
            });
        });
        if (audio == 1) {
            videoConnection.mediaConstraints.audio = true;
            videoConnection.addStream({
                audio: true
            });
        }
        var muted = (audio) ? 'AudioUnmuted' : 'AudioMuted';
        var remotemuted = (audio) ? 'remoteAudioUnmuted' : 'remoteAudioMuted';

        var e = jQEngager.Event(muted);
        jQEngager(document).trigger(e);

        socket.emit(connection.socketCustomEvent, {
            'type': remotemuted,
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.toggleVideo = function () {
        var video = 1;
        videoConnection.attachStreams.forEach(function (stream) {
            stream.getVideoTracks().forEach(function (str) {
                video = str.enabled = !str.enabled;
            });
        });

        if (video == 1) {
            videoConnection.mediaConstraints.video = true;
            videoConnection.addStream({
                video: true
            });
        }

        var muted = (video) ? 'VideoUnmuted' : 'VideoMuted';
        var remotemuted = (video) ? 'remoteVideoUnmuted' : 'remoteVideoMuted';

        var e = jQEngager.Event(muted);
        jQEngager(document).trigger(e);

        socket.emit(connection.socketCustomEvent, {
            'type': remotemuted,
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.answerCall = function (video, join, videoSource, audioSource, videoConstraint, audioConstraint) {

        var audioCons = (audioSource) ? {deviceId: {exact: audioSource}} : {};
        if (audioConstraint) {
            jQEngager.extend(true, audioCons, audioConstraint);
        }
        if (video) {
            var videoCons = (videoSource) ? {deviceId: {exact: videoSource}} : {facingMode: "user"};
        } else {
            videoCons = false;
        }
        if (videoConstraint) {
            jQEngager.extend(true, videoCons, videoConstraint);
        }
        streamConstraints = {video: videoCons, audio: audioCons};
        videoConnection.session = {
            video: video,
            audio: true
        };
        videoConnection.mediaConstraints = streamConstraints;

        if (!join) {
            return;
        }

        setTimeout(function () {
            videoConnection.openOrJoin(roomId + '_video', function (isRoomOpened, roomid, error) {
                if (error) {
                    console.log(error);
                    self.handleCallTermination();
                    location.reload();
                }
            });
        }, 200);
    };

    this.forceStopCall = function (videoSource) {
        videoConnection.streamEvents.selectAll({
            local: true
        }).forEach(function (streamEvent) {
            if (streamEvent.stream.isAudio) {
                streamEvent.stream.getAudioTracks()[0].stop();
            }
            if (streamEvent.stream.isVideo) {
                streamEvent.stream.getVideoTracks()[0].stop();
            }
        });
        videoConnection.attachStreams.forEach(function (stream) {
            stream.stop();
        });
        videoConnection.leave();
        videoConnection.closeSocket();
        var videoCons = (videoSource) ? {deviceId: {exact: videoSource}} : {facingMode: "user"};
        streamConstraints = {video: videoCons, audio: audio_on};
        videoConnection.mediaConstraints = streamConstraints;
        videoConnection.session = {
            video: video_on,
            audio: audio_on
        };
        videoConnection.join(roomId + '_video', function (isRoomJoined, roomid, error) {
            setTimeout(function () {
                videoConnection.getAllParticipants().forEach(function (pid) {
                    var peer = videoConnection.peers[pid].peer;
                    if (!peer.getSenders) {
                        return;
                    }
                    var videoTrack, audioTrack;
                    videoConnection.streamEvents.selectAll({
                        local: true
                    }).forEach(function (streamEvent) {
                        if (streamEvent.stream.isAudio) {
                            audioTrack = streamEvent.stream.getAudioTracks()[0];
                        }
                        if (streamEvent.stream.isVideo) {
                            videoTrack = streamEvent.stream.getVideoTracks()[0];
                        }
                    });

                    peer.getSenders().forEach(function (sender) {
                        if (!sender || !sender.track) {
                            return;
                        }

                        if (sender.track.kind === 'video' && videoTrack) {
                            if (sender.track.id != videoTrack.id) {
                                sender.replaceTrack(videoTrack);
                            }
                            videoTrack = null;
                        } else if (sender.track.kind === 'audio' && audioTrack) {
                            if (sender.track.id != audioTrack.id) {
                                sender.replaceTrack(audioTrack);
                            }
                            audioTrack = null;
                        }
                    });
                });
            }, 1000);

        });
    };

    this.handleCallTermination = function () {
        if (!videoConnection) {
            return;
        }

        if (conferenceStyle == 'conference') {
            return;
        }

        videoConnection.streamEvents.selectAll({
            local: true
        }).forEach(function (streamEvent) {
            if (streamEvent.stream.isAudio) {
                streamEvent.stream.getAudioTracks()[0].stop();
            }
            if (streamEvent.stream.isVideo) {
                streamEvent.stream.getVideoTracks()[0].stop();
            }
        });
        videoConnection.attachStreams.forEach(function (stream) {
            stream.stop();
        })

        if (videoConnection.getAllParticipants().length == 0) {
            videoConnection.closeSocket();
        } else {
            videoConnection.leave();
        }

    };

    this.reconnectWebsocket = function (e) {
        if (!forceClose) {
            console.log('WebSocketClient reconnecting in ' + autoReconnectInterval, e);
            setTimeout(function () {
                console.log("WebSocketClient: reconnecting...");
                self.connect();
            }, autoReconnectInterval);
        }
    };

    this.getRoomId = function () {
        return roomId;
    };


    this.checkMediaDevices = function () {

        videoConnection.DetectRTC.load(function () {
            var options = [];
            videoConnection.DetectRTC.videoInputDevices.forEach(function (device) {
                var option = {}
                option.value = device.id;
                option.text = device.label;
                options.push(option);
            });
            var e = jQEngager.Event('MediaDevices', {devices: options});
            jQEngager(document).trigger(e);
        });
    };

    this.getParticipants = function () {
        return connection.getAllParticipants();
    };

    this.getVideoSessions = function () {
        if (videoConnection) {
            return videoConnection.getAllParticipants().length;
        } else {
            return 0;
        }
    };

    this.addStreamStopListener = function (stream, callback) {
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
    };

    this.addToJoinScreenShare = function () {
        if (screenConnection && screenConnection.isInitiator) {
            socket.emit(connection.socketCustomEvent, {
                'type': 'startScreenShare',
                'role': role,
                'tenant': tenant,
                'sessionId': sessionId,
                'roomId': roomId
            });
        }
    };

    this.startScreenShare = function () {
        screenConnection.openOrJoin(roomId + '_screen', function (isRoomOpened, roomid, error) {
            screenConnection.isInitiator = true;
            screenConnection.extra = {
                sessionId: sessionId
            };
            socket.emit(connection.socketCustomEvent, {
                'type': 'startScreenShare',
                'role': role,
                'tenant': tenant,
                'sessionId': sessionId,
                'roomId': roomId
            });
        });
    };

    this.rejectCall = function () {
        socket.emit(connection.socketCustomEvent, {
            'type': 'rejectCall',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.stopRecording = function () {
        //
    };

    this.getSessionId = function () {
        return sessionId;
    };

    this.getRemoteSessionId = function () {
        return '';
    };

    this.getVideoStream = function () {
        var ret = false;
        if (videoConnection) {
            videoConnection.attachStreams.forEach(function (stream) {
                stream.getVideoTracks().forEach(function (str) {
                    ret = (str.enabled);
                });
            });
        } else {
            ret = false;
        }
        return ret;
    };

    this.getStream = function () {
        if (videoConnection) {
            return (videoConnection.getAllParticipants().length > 0);
        } else {
            return false;
        }
    };

    this.getRemoteStream = function (id) {
        var stream;
        if (videoConnection) {
            Object.keys(videoConnection.streamEvents).forEach(function (streamid) {
                var event = videoConnection.streamEvents[streamid];
                if (event.stream && event.extra.sessionId == id) {
                    stream = event.stream;
                    return false;
                }
            });
        }
        return stream;
    };

    this.endCall = function (msg) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'endCall',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId,
            'msg': msg
        });
        if (videoConnection) {

            videoConnection.streamEvents.selectAll({
                local: true
            }).forEach(function (streamEvent) {
                if (streamEvent.stream.isAudio) {
                    streamEvent.stream.getAudioTracks()[0].stop();
                }
                if (streamEvent.stream.isVideo) {
                    streamEvent.stream.getVideoTracks()[0].stop();
                }
            });

            videoConnection.leave();
        }

    };

    this.setMute = function (id) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'forceAudioMuted',
            'role': role,
            'tenant': tenant,
            'sessionId': id,
            'roomId': roomId
        });
    };


    this.forceAudioMute = function () {
        var localStream = videoConnection.attachStreams[0];
        if (localStream) {
            localStream.mute('audio');
        }

        var e = jQEngager.Event('AudioMuted');
        jQEngager(document).trigger(e);

        socket.emit(connection.socketCustomEvent, {
            'type': 'remoteAudioMuted',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.setDelete = function (id) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'forceDelete',
            'role': role,
            'tenant': tenant,
            'sessionId': id,
            'roomId': roomId
        });
    };

    this.setClose = function () {
        forceClose = true;
        connection.closeEntireSession();
    };

    this.setPresentUser = function (id, present) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'setPresent',
            'role': role,
            'tenant': tenant,
            'present': present,
            'sessionId': id,
            'roomId': roomId
        });
    };

    this.setDeleteAll = function (id) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'forceDeleteAll',
            'role': role,
            'tenant': tenant,
            'sessionId': id,
            'roomId': roomId
        });
    };

    this.revokePriveleges = function (id) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'revokePriveleges',
            'role': role,
            'tenant': tenant,
            'sessionId': id,
            'roomId': roomId
        });
    };

    this.blockUser = function (id) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'blockUser',
            'role': role,
            'tenant': tenant,
            'sessionId': id,
            'roomId': roomId
        });
    };

    this.endMeeting = function (id) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'endMeeting',
            'role': role,
            'tenant': tenant,
            'sessionId': id,
            'roomId': roomId
        });
    };

    this.toVideo = function () {
        socket.emit(connection.socketCustomEvent, {
            'type': 'toVideo',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.grantPriveleges = function (sessionId) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'grantPriveleges',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.getScreenStream = function () {
        if (screenConnection) {
            return (screenConnection.attachStreams.length > 0);
        } else {
            return false;
        }
    };

    this.startRecording = function () {
        socket.emit(connection.socketCustomEvent, {
            'type': 'startRecording',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };

    this.stopRecording = function () {
        socket.emit(connection.socketCustomEvent, {
            'type': 'stopRecording',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId
        });
    };


    this.setCallerInfo = function (callerInfo, isAdmin) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'setCallerInfo',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId,
            'callerInfo': callerInfo,
            'isAdmin': isAdmin
        });
        connection.extra = {
            'role': role,
            'name': name,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId,
            'isAdmin': (queryString.isAdmin || (role == 'admin')) ? 1 : 0,
            'pass': requirePassComm,
            'callerInfo': callerInfo
        };
    };

    this.sendCallerBack = function (access, sessionId) {
        socket.emit(connection.socketCustomEvent, {
            'type': 'sendCallerBack',
            'role': role,
            'tenant': tenant,
            'sessionId': sessionId,
            'roomId': roomId,
            'access': access
        });
    };

    this.handleScreenShareTermination = function () {
        if (!screenConnection) {
            return;
        }
        if (screenConnection) {
            screenConnection.streamEvents.selectAll({
                local: true
            }).forEach(function (streamEvent) {
//                streamEvent.stream.getAudioTracks()[0].stop();
                streamEvent.stream.getVideoTracks()[0].stop();
            });
        }

        screenConnection.attachStreams.forEach(function (stream) {
            stream.stop();
        })
        screenConnection.isInitiator = false;
        screenConnection.extra = {
            sessionId: null
        };
        screenConnection.closeSocket();
    };

    this.addLocalChat = function (chatMessage, date, privateId) {
        if (!chatMessage || !chatMessage.replace(/ /g, '').length)
            return;
        connection.send({
            chatMessage: chatMessage,
            privateId: privateId,
            date: date,
            sessionId: sessionId
        });
        connection.send({
            typing: false
        });
    };

    this.sendTranslateMessage = function (translateMessage) {
        if (!translateMessage || !translateMessage.replace(/ /g, '').length)
            return;
        connection.send({
            translateMessage: translateMessage,
            sessionId: sessionId
        });
    };

    this.sendTyping = function (type) {
        if (type) {
            connection.send({
                typing: true
            });
        } else {
            connection.send({
                typing: false
            });
        }
    };


    this.getFileHTML = function (file) {
        var url = file.url || URL.createObjectURL(file);
        var attachment = '<a href="' + url + '" target="_blank" download="' + file.name + '">Download: <b>' + file.name + '</b></a>';
//        if (file.name.match(/\.jpg|\.png|\.jpeg|\.gif/gi)) {
//            attachment += '<br><img crossOrigin="anonymous" src="' + url + '">';
//        } else if (file.name.match(/\.wav|\.mp3/gi)) {
//            attachment += '<br><audio src="' + url + '" controls></audio>';
//        } else if (file.name.match(/\.pdf|\.js|\.txt|\.sh/gi)) {
//            attachment += '<iframe class="inline-iframe" src="' + url + '"></iframe></a>';
//        }
        return attachment;
    }

    this.getFullName = function (userid) {
        var _userFullName = userid;
        if (connection.peers[userid] && connection.peers[userid].extra && connection.peers[userid].extra.userFullName) {
            _userFullName = connection.peers[userid].extra.userFullName;
        }
        return _userFullName;
    };

    this.sendFile = function (file) {
        recentFile = file;
        if (connection.getAllParticipants().length >= 1) {
            recentFile.userIndex = 0;
            connection.send(recentFile, connection.getAllParticipants()[recentFile.userIndex]);
        }
    };

    this.setWhiteboardTools = function () {
        if (lsDesigner) {
            lsDesigner.destroy();
            lsDesigner = null;
        }
        self.startWhiteboard();

    };

    this.whiteboardTools = function () {
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
    };

    this.initHark = function (args) {
        if (!window.hark) {
            throw 'Please link hark.js';
            return;
        }

        var connection = args.connection;
        var streamedObject = args.streamedObject;
        var stream = args.stream;

        var options = {};
        var speechEvents = hark(stream, options);

        speechEvents.on('speaking', function () {
            connection.onspeaking(streamedObject);
        });

        speechEvents.on('stopped_speaking', function () {
            connection.onsilence(streamedObject);
        });
    }

    this.startWhiteboard = function () {
        if (!lsDesigner) {
            lsDesigner = new CanvasDesigner();
            lsDesigner.widgetHtmlURL = lsRepUrl + 'pages/whiteboard.html';
            lsDesigner.widgetJsURL = lsRepUrl + 'js/whiteboard.widget.js';

            if (queryString.isAdmin || svConfigs.whiteboard.allowAnonymous || localStorage.getItem('hasPrivileges')) {
                self.whiteboardTools();
                lsDesigner.addSyncListener(function (data) {
                    connection.send({
                        width: screen.width,
                        whiteboardData: data
                    });
                });
            } else {
                lsDesigner.setTools({});
                lsDesigner.setSelected('');

            }
            if (lsDesigner.pointsLength <= 0) {
                // make sure that remote user gets all drawings synced.
                setTimeout(function () {
                    connection.send('plz-sync-points');
                }, 1000);
            }
            var elem = document.getElementById('whiteboard_canvas');
            lsDesigner.appendTo(elem);
        }
        setTimeout(function () {
            lsDesigner.sync();
        }, 2000);
    };

    this.clearCanvas = function () {
        if (lsDesigner) {
            lsDesigner.clearCanvas();
            socket.emit(connection.socketCustomEvent, {
                'type': 'clearCanvas',
                'role': role,
                'tenant': tenant,
                'sessionId': sessionId,
                'roomId': roomId
            });
//            lsDesigner.destroy();
//            lsDesigner = null;
//            self.startWhiteboard();
        }
    };

    this.showStatusBar = function (msg, displayTime) {
        console.log('showStatusBar', msg);
        jQEngager('#statusbar').html(msg);
        jQEngager('#statusbar').show();

        setTimeout(function () {
            jQEngager('#statusbar').hide();
        }, displayTime);
    };
};