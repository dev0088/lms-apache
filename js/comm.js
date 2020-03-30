'use strict';
var callPC = [], myMediaStream, remoteStream = [], broadcaststream = [], screenPC = [];
var comController = function () {

    var self = this;
    var roomVisitors;
    var awaitingResponse;
    var streamConstraints, offerOptions = {
        offerToReceiveAudio: 1,
        offerToReceiveVideo: 1
    };
    var screenShareInstance, isCaller, pcSessionId;
    var queryString = QueryString();
    var wsChat;
    var iniRoom = 'admin';
//const wsChat = new WebSocket('wss://new-dev.com:41414/comm');

    var room = (queryString.room || queryString.addbroadcast || queryString.broadcast) || iniRoom;
    var admin = (typeof isAdmin !== 'undefined');
    var adminPopup = (queryString.isAdmin);
    var visitorId, sessionId;

    var autoReconnectInterval = 5 * 1000;

    //file
    var sending, pcConstraint, sendChannel, receiveChannel;
    var localConnection;
    var remoteConnection;
    var receiveBuffer = [];
    var receivedSize = 0;

    var bytesPrev = 0;
    var timestampPrev = 0;
    var timestampStart;
    var statsInterval = null;
    var bitrateMax = 0;
    var fileType = '', receivedFile, requirePass = false, timerVars = [], endEdgeIce = true, forceClose = false;

    this.init = function (pass) {

        var initCallElems = document.getElementsByClassName('initCall');
        requirePass = pass;

        for (var i = 0; i < initCallElems.length; i++) {
            initCallElems[i].addEventListener('click', this.initCall);
        }
        self.openSocket();


        if (room != iniRoom) {

            $('#chatInput').on('keyup', function () {
                var msg = this.value.trim();

                if (msg) {
                    wsChat.send(JSON.stringify({
                        action: 'typingStatus',
                        status: true,
                        room: room
                    }));
                }

                //if no text in input
                else {
                    wsChat.send(JSON.stringify({
                        action: 'typingStatus',
                        status: false,
                        room: room
                    }));
                }

            });
        }
    };

    this.reconnectWebsocket = function (e) {
        if (!forceClose) {
            console.log('WebSocketClient reconnecting in ' + autoReconnectInterval, e);
            setTimeout(function () {
                console.log("WebSocketClient: reconnecting...");
                self.openSocket();
            }, autoReconnectInterval);
        }
    };

    this.openSocket = function () {
        wsChat = new WebSocket(svConfigs.appWss);
        wsChat.onclose = function (e) {
            var e = jQEngager.Event('AdminOffline');
            jQEngager(document).trigger(e);
            jQEngager('#visitors').empty();
            switch (e.code) {
                case 1000:	// CLOSE_NORMAL
                    console.log("WebSocket: closed");
                    break;
                default:	// Abnormal closure
                    self.reconnectWebsocket(e);
                    break;
            }
        };

        wsChat.onopen = function () {

            console.log('wsChat started!');
            visitorId = (getCookie('visitorId')) ? getCookie('visitorId') : Math.random().toString(36).slice(2).substring(0, 15);
            sessionId = (getCookie('sessionId')) ? getCookie('sessionId') : getGuid();
            if (admin || adminPopup) {
                visitorId = 'admin';
            }
            setCookie('visitorId', visitorId, 1);
            setCookie('sessionId', sessionId, 1);
            if (agentId) {
                setCookie('agentId', agentId, 1);
            }

            if (room !== iniRoom) {
                wsChat.send(JSON.stringify({
                    action: 'subscribe',
                    referrer: document.title,
                    ua: navigator.userAgent,
                    visitorId: visitorId,
                    sessionId: sessionId,
                    room: room,
                    isAdmin: adminPopup,
                    agentId: agentId
                }));
                wsChat.send(JSON.stringify({
                    action: 'ping',
                    room: iniRoom,
                    visitorId: room,
                    agentId: agentId,
                    sessionId: sessionId,
                    isAdmin: adminPopup,
                    name: visitorName
                }));
            } else {
                //subscribe to room
                wsChat.send(JSON.stringify({
                    action: 'subscribe',
                    referrer: document.title,
                    ua: navigator.userAgent,
                    visitorId: visitorId,
                    agentId: agentId,
                    sessionId: sessionId,
                    room: iniRoom,
                    isAdmin: adminPopup
                }));
                wsChat.send(JSON.stringify({
                    action: 'online',
                    referrer: document.title,
                    ua: navigator.userAgent,
                    visitorId: visitorId,
                    sessionId: sessionId,
                    room: iniRoom,
                    isAdmin: adminPopup,
                    agentId: agentId
                }));
            }

            wsChat.send(JSON.stringify({
                action: 'giveOnline',
                room: room
            }));

            wsChat.send(JSON.stringify({
                action: 'giveCountOnline',
                room: room
            }));

            var e = jQEngager.Event('CommConnected');
            jQEngager(document).trigger(e);
            self.showStatusBar('Connected to the chat server!', 5000);
        };

        wsChat.onerror = function () {
            self.showStatusBar('Unable to connect to the chat server! Kindly refresh', 20000);
        };

        wsChat.onmessage = function (e) {
            var data = JSON.parse(e.data);
            console.log('onmessage', JSON.stringify(data), data.action, room, data.visitorId);

            if (data.action === 'imOnline') {
                if (data.visitorId === 'admin') {
                    console.log('admin is online');
                    var e = jQEngager.Event('AdminOnline', {sessionId: data.sessionId});
                    jQEngager(document).trigger(e);
                    var e = jQEngager.Event('AdminPopupOnline', {sessionId: data.sessionId, pass: data.pass});
                    jQEngager(document).trigger(e);

                } else {
                    var e = jQEngager.Event('PopupOnline', {sessionId: data.sessionId, isAdmin: data.isAdmin});
                    jQEngager(document).trigger(e);
                }
//                if (data.room !== iniRoom) {
//                    var e = jQEngager.Event('AdminPopupOnline', {sessionId: data.sessionId});
//                    jQEngager(document).trigger(e);
//                }
            }
            if (data.action === 'whoIsroom') {

                roomVisitors = data.count;
                var e = jQEngager.Event('VisitorsRoom', {count: data.count});
                jQEngager(document).trigger(e);
            }
            if (data.action === 'imOffline') {
                if (data.visitors) {
                    if (data.visitors.visitorId === 'admin') {
                        console.log('admin is offline');
                        var e = jQEngager.Event('AdminOffline');
                        jQEngager(document).trigger(e);
                        if (data.room !== iniRoom) {
                            var e = jQEngager.Event('AdminPopupOffline');
                            jQEngager(document).trigger(e);
                        }
                    } else {
                        var e = jQEngager.Event('PopupLeft', {sessionId: data.visitors.sessionId});
                        jQEngager(document).trigger(e);
                    }
                }
                if (data.visitorId && data.visitorId === 'admin') {
                    console.log('admin is offline');
                    var e = jQEngager.Event('AdminOffline');
                    jQEngager(document).trigger(e);
                    if (data.room !== iniRoom) {
                        var e = jQEngager.Event('AdminPopupOffline');
                        jQEngager(document).trigger(e);
                    }
                }
            }
            if (data.room === room) {
                //above check is not necessary since all messages coming to this user are for the user's current room
                //but just to be on the safe side
                switch (data.action) {
                    case 'initCall':
                        //launch modal to show that user has a call with options to accept or deny and start ringtone
                        //start ringtone here
                        var e = jQEngager.Event('IncomingCall', {autoaccept: data.autoaccept, sessionId: data.sessionId});
                        jQEngager(document).trigger(e);

                        break;
                    case 'initScreenShare':
                        //launch modal to show that user has a call with options to accept or deny and start ringtone
                        //start ringtone here
                        var e = jQEngager.Event('IncomingScreenShare', {autoaccept: data.autoaccept});
                        jQEngager(document).trigger(e);
                        break;

                    case 'callRejected':
                        var e = jQEngager.Event('CallEnded');
                        jQEngager(document).trigger(e);
                        break;

                    case 'endScreenShare':
                        var e = jQEngager.Event('ScreenShareEnded');
                        jQEngager(document).trigger(e);
                        break;

                    case 'endCall':
                        self.handleCallTermination(data.sessionId);
                        break;
                    case 'callAccepted':
                        if (data.sessionId in callPC) {
                            return;
                        }
                        if (data.remoteSessionId == self.getSessionId()) {

                            if (self.getStream()) {
                                self.startCall(true, data.sessionId, self.getStream());
                                clearTimeout(awaitingResponse);//clear timeout
                                var e = jQEngager.Event('CallAccepted');
                                jQEngager(document).trigger(e);
                            } else {
                                setTimeout(function () {
                                    self.startCall(true, data.sessionId, self.getStream());
                                    clearTimeout(awaitingResponse);//clear timeout
                                    var e = jQEngager.Event('CallAccepted');
                                    jQEngager(document).trigger(e);
                                }, 5000);
                            }
                        }
                        var countVideo = self.getCountSessions();
                        self.sendRemoteVideoSessions(countVideo);
                        break;

                    case 'startFileTransfer':
                        var e = jQEngager.Event('IncomingFileTransfer', {name: data.name, size: data.size, sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;

                    case 'fileAccepted':
                        var e = jQEngager.Event('FileAccepted');
                        jQEngager(document).trigger(e);
                        break;

                    case 'whiteboardSync':
                        var e = jQEngager.Event('WhiteboardSync', {whiteboardData: data.data, width: data.width, sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'broadcastSync':
                        var e = jQEngager.Event('BroadcastSync', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;

                    case 'fileRejected':
                        var e = jQEngager.Event('FileRejected', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;

                    case 'candidate':
                        if (data.candidate) {
                            //message is iceCandidate
                            callPC[data.sessionId] ? callPC[data.sessionId].addIceCandidate(new RTCIceCandidate(JSON.parse(data.candidate)),
                                    function () {
                                        console.log('onAddIceCandidateSuccess');
                                        if (isEdge && endEdgeIce) {
                                            endEdgeIce = false;
                                            setTimeout(function () {
                                                callPC[data.sessionId].addIceCandidate(null)
                                            }, 3000);
                                        }
                                    },
                                    function (err) {
                                        console.log('onAddIceCandidateFailed', err);
                                    }) : '';
                        }

                        break;
                    case 'ssCandidate':
                        if (data.candidate) {
                            //message is iceCandidate
                            screenPC[data.sessionId] ? screenPC[data.sessionId].addIceCandidate(new RTCIceCandidate(JSON.parse(data.candidate)),
                                    function () {
                                        console.log('onAddIceCandidateSuccess');
                                        if (isEdge && endEdgeIce) {
                                            endEdgeIce = false;
                                            setTimeout(function () {
                                                screenPC[data.sessionId].addIceCandidate(null)
                                            }, 3000);
                                        }
                                    },
                                    function (err) {
                                        console.log('onAddIceCandidateFailed', err);
                                    }) : '';
                        }

                        break;

                    case 'fileLocalCandidate':
                        if (data.candidate) {
                            //message is iceCandidate

                            localConnection.addIceCandidate(new RTCIceCandidate(JSON.parse(data.candidate)),
                                    function () {
                                        console.log('onAddIceCandidateSuccess');
                                        if (isEdge && endEdgeIce) {
                                            endEdgeIce = false;
                                            setTimeout(function () {
                                                localConnection.addIceCandidate(null)
                                            }, 3000);
                                        }
                                    },
                                    function (err) {
                                        console.log('onAddIceCandidateFailed', err);
                                    });

                            //localConnection.addIceCandidate(data.candidate, self.onAddIceCandidateSuccess, self.onAddIceCandidateError);
                        }

                        break;

                    case 'fileCandidate':
                        if (data.candidate) {
                            //message is iceCandidate
                            remoteConnection.addIceCandidate(new RTCIceCandidate(JSON.parse(data.candidate)),
                                    function () {
                                        console.log('onAddIceCandidateSuccess');
                                        if (isEdge && endEdgeIce) {
                                            endEdgeIce = false;
                                            setTimeout(function () {
                                                localConnection.addIceCandidate(null)
                                            }, 3000);
                                        }
                                    },
                                    function (err) {
                                        console.log('onAddIceCandidateFailed', err);
                                    });
                        }

                        break;

                    case 'remoteDescription':
                        //message is signal description
                        if (callPC[data.sessionId] && data.remoteSessionId == self.getSessionId()) {
                            if (!callPC[data.sessionId].remoteDescription) {
                                callPC[data.sessionId].setRemoteDescription(new RTCSessionDescription(data.sdp));
                            }
                            callPC[data.sessionId].createAnswer(
                                    function (desc) {
                                        callPC[data.sessionId].setLocalDescription(desc);
                                        var e = jQEngager.Event('LocalVideoStarted', {stream: self.getStream()});
                                        jQEngager(document).trigger(e);
                                        wsChat.send(JSON.stringify({
                                            action: 'localDescription',
                                            sdp: desc,
                                            room: room,
                                            sessionId: self.getSessionId(),
                                            remoteSessionId: data.sessionId
                                        }));
                                    }
                            , self.showErrors);
                        }
                        break;
                    case 'remoteScreenDescriptionLsv':
                        //message is signal description
                        self.startScreenShare(null, false, data.sessionId);
                        if (screenPC[data.sessionId] && data.sessionId == self.getSessionId()) {
                            screenPC[data.sessionId].setRemoteDescription(new RTCSessionDescription(data.sdp));
                            screenPC[data.sessionId].createAnswer(function (desc) {
                                desc.sdp = self.useH264Codec(desc.sdp);
                                screenPC[data.sessionId].setLocalDescription(desc);
                                var e = jQEngager.Event('LocalScreenStarted', {stream: self.getStream()});
                                jQEngager(document).trigger(e);
                                wsChat.send(JSON.stringify({
                                    action: 'localScreenDescriptionLsv',
                                    sdp: desc,
                                    room: room,
                                    sessionId: data.sessionId,
                                }));
                            }, self.showErrors);
                        }
                        break;

                    case 'localScreenDescriptionLsv':
                        //message is signal description
                        var sessionDescription = screenPC[data.sessionId].remoteDescription;
                        if (!sessionDescription) {
                            screenPC[data.sessionId] ? screenPC[data.sessionId].setRemoteDescription(new RTCSessionDescription(data.sdp)) : '';
                        }

                        break;

                    case 'localDescription':
                        //message is signal description
                        if (data.remoteSessionId == self.getSessionId()) {
                            var sessionDescription = callPC[data.sessionId].remoteDescription;
                            if (!sessionDescription) {
                                callPC[data.sessionId] ? callPC[data.sessionId].setRemoteDescription(new RTCSessionDescription(data.sdp)) : '';
                            }
                        }
                        break;

                    case 'fileRemoteDescription':
                        remoteConnection.setRemoteDescription(data.sdp);
                        remoteConnection.createAnswer(self.gotDescription2, self.showErrors);
                        break;

                    case 'fileLocalDescription':
                        localConnection.setRemoteDescription(data.sdp);
                        break;

                    case 'txt':
                        //it is a text chat
                        self.addRemoteChat(data.msg, data.date, data.sessionId, data.privateId);
                        break;

                    case 'typingStatus':
                        break;

                    case 'newSub':
                        if ((!roomId || (roomId && roomId == data.visitorId)) &&
                                !agentId || (agentId && agentId == data.agentId)) {
                            self.setRemoteStatus('online', data.sessionId);

                            //once the other user joined and current user has been notified, current user should also send a signal
                            //that he is online
                            wsChat.send(JSON.stringify({
                                action: 'imOnline',
                                visitorId: visitorId,
                                agentId: agentId,
                                room: room,
                                pass: requirePass,
                                count: data.count,
                                sessionId: self.getSessionId()
                            }));
                            if (admin) {
                                self.showStatusBar('Remote entered room', 10000);
                            }
                        }
                        break;

                    case 'imOffline':
                        if (data.count == 1) {
                            self.setRemoteStatus('offline', data.sessionId);
                        }
                        if (admin && data.visitors) {
                            timerVars[data.visitors.visitorId] = setTimeout(function () {
                                jQEngager('#visitors').find('#' + data.visitors.visitorId).remove();
                                delete timerVars[data.visitors.visitorId];
                            }, 2000);

                        }
                        if (data.visitors && data.visitors.visitorId === 'admin') {
                            var e = jQEngager.Event('AdminOffline');
                            jQEngager(document).trigger(e);
                        }

                        break;
                    case 'popupClosed':
                        var e = jQEngager.Event('PopupClosed');
                        jQEngager(document).trigger(e);
                        jQEngager('#visitors').find('#room' + data.visitorId).remove();
                        break;
                    case 'RemoteVideoUnmuted':
                        var e = jQEngager.Event('RemoteVideoUnmuted', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'RemoteVideoMuted':
                        var e = jQEngager.Event('RemoteVideoMuted', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'revokePriveleges':
                        var e = jQEngager.Event('RevokePriveleges', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'grantPriveleges':
                        var e = jQEngager.Event('GrantPriveleges', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'blockUser':
                        var e = jQEngager.Event('blockUser', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'startRecording':
                        var e = jQEngager.Event('RemoteStartRecording');
                        jQEngager(document).trigger(e);
                        break;
                    case 'stopRecording':
                        var e = jQEngager.Event('RemoteStopRecording');
                        jQEngager(document).trigger(e);
                        break;
                    case 'remoteVideoSession':
                        var e = jQEngager.Event('RemoteVideoSessions', {count: data.count});
                        jQEngager(document).trigger(e);
                        break;
                    case 'endMeeting':
                        var e = jQEngager.Event('EndMeeting', {sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'ping':
                        if (admin) {
                            var element = jQEngager('#visitors').find('#' + data.visitorId);
                            var checkExists = jQEngager('#visitors').find('#room' + data.visitorId);
                            if (element.length > 0 && checkExists.length === 0) {
                                playEnterRoom();
                                var divElement = element.children().children().children();
                                var str = {};
                                str.names = (data.name) ? data.name : guestName(data.sessionId);
                                if (lsRepUrl) {
                                    str.lsRepUrl = lsRepUrl;
                                }
                                if (agentId) {
                                    str.agentId = agentId;
                                }

                                var encodedString = window.btoa(JSON.stringify(str));
                                var roomLink = lsRepUrl + 'pages/room.html?room=' + data.visitorId + '&p=' + encodedString + '&isAdmin=1';
                                var newNode = document.createElement('span');
                                newNode.id = 'room' + data.visitorId;
                                newNode.innerHTML = ' <a href="' + roomLink + '" >Enter Room</a>';
                                divElement.append(newNode);
                                jQEngager('#name' + data.visitorId).text(str.names);
                                var e = jQEngager.Event('EnterPageNotification', {name: str.names});
                                jQEngager(document).trigger(e);
                            }
                        } else {
                            if (data.visitorId === visitorId && data.room === 'admin') {
                                var e = jQEngager.Event('AdminPopupOnline', {sessionId: data.sessionId, pass: data.pass});
                                jQEngager(document).trigger(e);
                            }
                            if (data.visitorId === room) {
                                var e = jQEngager.Event('PopupOnline', {sessionId: data.sessionId, pass: data.pass});
                                jQEngager(document).trigger(e);
                            }
                        }
                        break;
                    case 'online':
                        if (admin) {
                            if ((!roomId || (roomId && roomId == data.visitorId)) &&
                                    (!agentId || (agentId && agentId == data.agentId))) {
//                                jQEngager('#visitors').find('#' + data.visitorId).remove();
                                var divId = jQEngager('#visitors').find('#' + data.visitorId);
                                var ua = (data.ua) ? detect.parse(data.ua) : '';
                                var browerName = (ua) ? ua.browser.name : '';
                                var osName = (ua) ? ua.os.name : '';
                                if (divId.length > 0) {
                                    var newNode = divId[0];
                                    newNode.innerHTML = '<div class="col-sm-10 col-xs-10">\
                                                                        <div class="messages msg_receive">\
                                                                                <p><span id="name' + data.visitorId + '">' + guestName(data.sessionId) + '</span> ' + data.referrer + '<br/>' + osName + ' ' + browerName + '</p>\
                                                                                \
                                                                        </div>\
                                                                </div>';
                                    jQEngager(divId[0]).replaceWith(newNode);
                                    clearTimeout(timerVars[data.visitorId]);
                                } else {
                                    newNode = document.createElement('div');
                                    newNode.className = 'row msg_container base_receive';
                                    newNode.id = data.visitorId;


                                    newNode.innerHTML = '<div class="col-sm-10 col-xs-10">\
                                                                        <div class="messages msg_receive">\
                                                                                <p><span id="name' + data.visitorId + '">' + guestName(data.sessionId) + '</span> ' + data.referrer + '<br/>' + osName + ' ' + browerName + '</p>\
                                                                                \
                                                                        </div>\
                                                                </div>';

                                    jQEngager('#visitors').append(newNode);
                                }
                                delete timerVars[data.visitorId];
                            }
                        }
                        if ((!roomId || (roomId && roomId == data.visitorId)) &&
                                (!agentId || (agentId && agentId == data.agentId)) && data.visitorId === 'admin') {
                            var e = jQEngager.Event('AdminOnline', {sessionId: data.sessionId});
                            jQEngager(document).trigger(e);
                        }
                        break;
                    case 'checkPopup':
                        var e = jQEngager.Event('CheckPopup');
                        jQEngager(document).trigger(e);
                        break;
                    case 'setCallerInfo':
                        var e = jQEngager.Event('CallerInfo', {sessionId: data.sessionId, callerInfo: data.callerInfo, isAdmin: data.isAdmin});
                        jQEngager(document).trigger(e);
                        break;
                    case 'sendCallerBack':
                        var e = jQEngager.Event('SendCallerBack', {access: data.access, sessionId: data.sessionId});
                        jQEngager(document).trigger(e);
                        break;
                    case 'whoIsonline':
                        var visitors = data.visitors;
                        for (var key in visitors) {
                            if (visitors[key]) {
                                var visitor = visitors[key]['visitorId'];
                                if (admin && visitor !== visitorId) {
                                    if ((!roomId || (roomId && roomId == visitor)) &&
                                            !agentId || (agentId && agentId == visitors[key]['agentId'])) {

                                        var divId = jQEngager('#visitors').find('#' + visitor);

                                        var ua = (visitors[key]['ua']) ? detect.parse(visitors[key]['ua']) : '';
                                        var browerName = (ua) ? ua.browser.name : '';
                                        var osName = (ua) ? ua.os.name : '';
                                        if (divId.length > 0) {
                                            var newNode = divId[0];
                                            jQEngager(newNode).html('<div class="col-sm-10 col-xs-10">\
                                                                                <div class="messages msg_receive">\
                                                                                        <p><span id="name' + visitor + '">' + guestName(visitors[key]['sessionId']) + '</span> ' + visitors[key]['pageRef'] + '<br/>' + osName + ' ' + browerName + '</p>\
                                                                                        \
                                                                                </div>\
                                                                        </div>');
                                            jQEngager(divId[0]).replaceWith(newNode);
                                            clearTimeout(timerVars[visitor]);
                                        } else {
                                            newNode = document.createElement('div');
                                            newNode.className = 'row msg_container base_receive';
                                            newNode.id = visitor;
                                            newNode.innerHTML = '<div class="col-sm-10 col-xs-10">\
                                                                                <div class="messages msg_receive">\
                                                                                        <p><span id="name' + visitor + '">' + guestName(visitors[key]['sessionId']) + '</span> ' + visitors[key]['pageRef'] + '<br/>' + osName + ' ' + browerName + '</p>\
                                                                                        \
                                                                                </div>\
                                                                        </div>';
                                            jQEngager('#visitors').append(newNode);
                                        }
                                        delete timerVars[visitor];
                                        wsChat.send(JSON.stringify({
                                            action: 'checkPopup',
                                            visitorId: 'admin',
                                            room: visitor
                                        }));
                                    }
                                }
                                if (visitors[key] === 'admin') {
                                    var e = jQEngager.Event('AdminPopupOnline', {sessionId: visitors[key]['sessionId'], name: (visitors[key]['name']) ? visitors[key]['name'] : ''});
                                    jQEngager(document).trigger(e);
                                }

                                if (visitors[key]['visitorId'] !== visitorId) {
                                    if (visitors[key]['isAdmin'] === 1) {
                                        var e = jQEngager.Event('AdminPopupOnline', {sessionId: visitors[key]['sessionId'], name: (visitors[key]['name']) ? visitors[key]['name'] : ''});
                                        jQEngager(document).trigger(e);
                                    } else {
                                        var e = jQEngager.Event('PopupOnline', {sessionId: visitors[key]['sessionId'], name: (visitors[key]['name']) ? visitors[key]['name'] : ''});
                                        jQEngager(document).trigger(e);
                                    }
                                }
                            }
                        }

                        break;
                }
            } else if (data.action === 'subRejected') {
                //subscription on this device rejected cos user has subscribed on another device/browser
                self.showStatusBar('Maximum users in a room reached. Communication disallowed', 5000);
            }
        };
    };

    this.popupClosed = function (id) {
        wsChat.send(JSON.stringify({
            action: 'popupClosed',
            room: room,
            visitorId: id
        }));
    };

    this.getConstraint = function (type, videoSource, audioSource, videoConstraint, audioConstraint) {
        var callType = (type) ? type : (this.id === 'initVideo') ? 'Video' : 'Audio';
        var audioCons = (audioSource) ? {deviceId: {exact: audioSource}} : {};
        if (audioConstraint) {
            jQEngager.extend(true, audioCons, audioConstraint);
        }
        var videoCons = (videoSource) ? {deviceId: {exact: videoSource}} : {facingMode: "user"};
        if (videoConstraint) {
            jQEngager.extend(true, videoCons, videoConstraint);
        }
        switch (callType) {
            case 'Video':
                streamConstraints = {video: videoCons, audio: audioCons};
                break;
            case 'Audio':
                streamConstraints = {audio: audioCons, video: false};
                break;
            default:
                streamConstraints = {video: videoCons, audio: audioCons};
                break;
        }
        console.log('getConstraint', streamConstraints);
    };

    this.startRecording = function () {
        wsChat.send(JSON.stringify({
            action: 'startRecording',
            room: room,
            sessionId: self.getSessionId()
        }));
    };

    this.stopRecording = function () {
        wsChat.send(JSON.stringify({
            action: 'stopRecording',
            room: room,
            sessionId: self.getSessionId()
        }));
    };

    this.initCall = function (type, autoaccept, videoSource, sessionId, audioSource, videoConstraint, audioConstraint) {
        if (self.checkUserMediaSupport()) {
            isCaller = true;
            self.getConstraint(type, videoSource, audioSource, videoConstraint, audioConstraint);
            console.log('start call');
            self.setLocalMedia(sessionId);
            wsChat.send(JSON.stringify({
                action: 'initCall',
                room: room,
                autoaccept: autoaccept,
                sessionId: sessionId
            }));
            //wait for response for 30secs
            awaitingResponse = setTimeout(function () {
                var e = jQEngager.Event('CallEnded');
                jQEngager(document).trigger(e);
                self.endCall('Call ended due to lack of response', self.getSessionId());
            }, 60000);

        } else {
            var e = jQEngager.Event('NotSupportedBrowser');
            jQEngager(document).trigger(e);
        }
    };

    this.initScreen = function (autoaccept) {
        isCaller = true;
        if (self.checkUserMediaSupport()) {
            console.log('initScreenShare');
            wsChat.send(JSON.stringify({
                action: 'initScreenShare',
                room: room,
                autoaccept: autoaccept
            }));
        } else {
            var e = jQEngager.Event('NotSupportedBrowser');
            jQEngager(document).trigger(e);
        }
    };

    this.rejectCall = function () {
        wsChat.send(JSON.stringify({
            action: 'callRejected',
            msg: 'Call rejected by Remote',
            room: room
        }));
    };

    this.answerCall = function (video, sessionId, videoSource, audioSource, videoConstraint, audioConstraint) {
        if (self.checkUserMediaSupport()) {
            isCaller = false;
            self.getConstraint(video, videoSource, audioSource, videoConstraint, audioConstraint);
            self.setLocalMedia(sessionId);
        } else {
            wsChat.send(JSON.stringify({
                action: 'callRejected',
                msg: 'Remote\'s device does not have the necessary requirements to make call',
                room: room,
                sessionId: self.getSessionId(),
                remoteSessionId: sessionId
            }));
            var e = jQEngager.Event('NotSupportedBrowser');
            jQEngager(document).trigger(e);
        }
    };

    this.startScreenShare = function (stream, isCaller, sessionId) {
        endEdgeIce = true;
        self.setScreenStream(stream);
        screenPC[sessionId] = new RTCPeerConnection(svConfigs.iceServers);

        screenPC[sessionId].onicecandidate = function (e) {

            if (e.candidate) {
                var c = {
                    candidate: e.candidate.candidate,
                    sdpMLineIndex: e.candidate.sdpMLineIndex,
                    sdpMid: e.candidate.sdpMid
                };
                if (isIEA) {
                    var cand = c;
                } else {
                    cand = e.candidate;
                }

                //send my candidate to peer
                wsChat.send(JSON.stringify({
                    action: 'ssCandidate',
                    candidate: JSON.stringify(cand),
                    room: room,
                    sessionId: sessionId
                }));
            }
        };

        if (isIEA) {
            screenPC[sessionId].onaddstream = function (e) {
                console.log('onaddstream');
                var e = jQEngager.Event('RemoteScreenShareStarted', {stream: e.stream, sessionId: sessionId});
                jQEngager(document).trigger(e);
            };
        } else {
            screenPC[sessionId].ontrack = function (e) {
                console.log('ontrack');
                var e = jQEngager.Event('RemoteScreenShareStarted', {stream: e.streams[0], sessionId: sessionId});
                jQEngager(document).trigger(e);
            };
        }

        if (isCaller) {

            if (isIEA) {
                screenPC[sessionId].addStream(stream);
            } else {
//                stream.getTracks().forEach(track => callPC[sessionId].addTrack(track, stream));
                stream.getTracks().forEach(function (track) {
                    screenPC[sessionId].addTrack(track, stream);
                });
            }

            screenPC[sessionId].createOffer(
                    function (desc) {
                        desc.sdp = self.useH264Codec(desc.sdp);
                        screenPC[sessionId].setLocalDescription(desc);
                        var e = jQEngager.Event('LocalScreenStarted', {stream: self.getStream(), sessionId: sessionId});
                        jQEngager(document).trigger(e);
                        wsChat.send(JSON.stringify({
                            action: 'remoteScreenDescriptionLsv',
                            sdp: desc,
                            room: room,
                            sessionId: sessionId
                        }));
                    }, self.showErrors, offerOptions);
        }
    };

    this.startCall = function (caller, sessionId, stream) {
        endEdgeIce = true;
        isCaller = caller;
        callPC[sessionId] = new RTCPeerConnection(svConfigs.iceServers);

        //When my ice candidates become available
        callPC[sessionId].onicecandidate = function (e) {

            if (e.candidate) {
                var c = {
                    candidate: e.candidate.candidate,
                    sdpMLineIndex: e.candidate.sdpMLineIndex,
                    sdpMid: e.candidate.sdpMid
                };
                if (isIEA) {
                    var cand = c;
                } else {
                    cand = e.candidate;
                }

                //send my candidate to peer
                wsChat.send(JSON.stringify({
                    action: 'candidate',
                    candidate: JSON.stringify(cand),
                    room: room,
                    sessionId: self.getSessionId()
                }));
            }
        };

        //when remote connection state and ice agent is closed
        callPC[sessionId].oniceconnectionstatechange = function () {
            if (callPC[sessionId]) {
                switch (callPC[sessionId].iceConnectionState) {
                    case 'disconnected':
                    case 'failed':
                        self.handleCallTermination(sessionId);
                        console.log('Ice connection state is failed/disconnected');
                        self.showStatusBar('Call connection problem', 15000);
                        break;

                    case 'closed':
                        self.handleCallTermination(sessionId);
                        console.log('Ice connection state is \'closed\'');
                        self.showStatusBar('Call connection closed', 15000);
                        break;
                }
            }
        };

        //WHEN REMOTE CLOSES CONNECTION
        callPC[sessionId].onsignalingstatechange = function () {
            switch (callPC[sessionId].signalingState) {
                case 'closed':
                    console.log('Signalling state is \'closed\'');
                    self.showStatusBar('Signal lost', 15000);
                    break;
            }
        };

        if (isIEA) {
            callPC[sessionId].onaddstream = function (e) {
                remoteStream[sessionId] = e.stream;
                var e = jQEngager.Event('RemoteVideoStarted', {stream: e.stream, sessionId: sessionId});
                jQEngager(document).trigger(e);
            };
            callPC[sessionId].addStream(stream);
        } else {
            callPC[sessionId].ontrack = function (e) {
                console.log('ontrack');
                if (!remoteStream[sessionId]) {
                    var ev = jQEngager.Event('RemoteVideoStarted', {stream: e.streams[0], sessionId: sessionId});
                    jQEngager(document).trigger(ev);
                }
                remoteStream[sessionId] = e.streams[0];
            };
//            callPC[sessionId].addTrack(self.getStream(sessionId));
//            stream.getTracks().forEach(track => callPC[sessionId].addTrack(track, stream));
            stream.getTracks().forEach(function (track) {
                callPC[sessionId].addTrack(track, stream);
            });
        }


        if (isCaller) {
            callPC[sessionId].createOffer(
                    function (desc) {
                        callPC[sessionId].setLocalDescription(desc);
                        var e = jQEngager.Event('LocalVideoStarted', {stream: stream});
                        jQEngager(document).trigger(e);
                        wsChat.send(JSON.stringify({
                            action: 'remoteDescription',
                            sdp: desc,
                            room: room,
                            sessionId: self.getSessionId(),
                            remoteSessionId: sessionId
                        }));
                    }, self.showErrors, offerOptions);
        }
    };

    this.checkUserMediaSupport = function () {
        return !!(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
    };

    this.showErrors = function (e) {
        console.log('showErrors', e);
        switch (e.name) {
            case 'SecurityError':
                console.log(e.message);

                self.showStatusBar('Media sources usage is not supported on this browser/device', 10000);
                break;

            case 'NotAllowedError':
                console.log(e.message);

                self.showStatusBar('We do not have access to your audio/video sources', 10000);
                break;

            case 'NotFoundError':
                console.log(e.message);

                self.showStatusBar('The requested audio/video source cannot be found', 10000);
                break;

            case 'NotReadableError':
            case 'AbortError':
                console.log(e.message);
                self.showStatusBar('Unable to use your media sources', 10000);
                break;
        }
    };

    this.setStream = function (stream) {
        myMediaStream = stream;
    };

    this.gotDevices = function (deviceInfos) {
        // Handles being called several times to update labels. Preserve values.
        var options = [];
        for (var i = 0; i !== deviceInfos.length; ++i) {
            var deviceInfo = deviceInfos[i];
            var option = {}
            if (deviceInfo.kind === 'videoinput') {
                option.value = deviceInfo.deviceId;
                option.text = deviceInfo.label;
                options.push(option);
            }
        }

        var e = jQEngager.Event('MediaDevices', {devices: options});
        jQEngager(document).trigger(e);
    };

    this.handleError = function (error) {
        console.log('navigator.getUserMedia error: ', error);
    };

    this.checkMediaDevices = function () {
        if (isiPhone || isAndroid) {
            navigator.mediaDevices.enumerateDevices().then(self.gotDevices).catch(self.handleError);

        }
    };

    this.onCreateSessionDescriptionError = function (e) {
        self.showErrors(e);
    };

    this.gumFailed = function (e) {
        self.showErrors(e);
    };

    this.setLocalMedia = function (sessionId) {
        pcSessionId = sessionId;
        if (typeof Promise === 'undefined') {
            navigator.getUserMedia(streamConstraints,
                    function (stream) {
                        self.setStream(stream);
                        if (!isCaller) {
                            self.startCall(false, sessionId, stream);
                            wsChat.send(JSON.stringify({
                                action: 'callAccepted',
                                room: room,
                                sessionId: self.getSessionId(),
                                remoteSessionId: sessionId
                            }));
                        }
                    }

            , self.gumFailed);
        } else {
            navigator.mediaDevices.getUserMedia(streamConstraints)
                    .then(
                            function (stream) {
                                self.setStream(stream);
                                if (!isCaller) {
                                    self.startCall(false, sessionId, stream);
                                    wsChat.send(JSON.stringify({
                                        action: 'callAccepted',
                                        room: room,
                                        sessionId: self.getSessionId(),
                                        remoteSessionId: sessionId
                                    }));
                                }
                            }

                    )
                    .catch(self.gumFailed);
        }
    };

    /**
     * 
     * @param {type} msg
     * @param {type} date
     * @returns {undefined}
     */
    this.addRemoteChat = function (msg, date, sessionId, privateId) {
        if (privateId) {
            if (privateId == self.getSessionId()) {
                var e = jQEngager.Event('ChatMessage', {msg: msg, date: date, sessionId: sessionId});
                jQEngager(document).trigger(e);
            }
        } else {
            var e = jQEngager.Event('ChatMessage', {msg: msg, date: date, sessionId: sessionId});
            jQEngager(document).trigger(e);
        }
    };

    this.revokePriveleges = function (sessionId) {
        wsChat.send(JSON.stringify({
            action: 'revokePriveleges',
            room: room,
            sessionId: sessionId
        }));
    };

    this.blockUser = function (sessionId) {
        wsChat.send(JSON.stringify({
            action: 'blockUser',
            room: room,
            sessionId: sessionId
        }));
    };

    this.grantPriveleges = function (sessionId) {
        wsChat.send(JSON.stringify({
            action: 'grantPriveleges',
            room: room,
            sessionId: sessionId
        }));
    };

    /**
     * 
     * @param {type} msg
     * @param {type} date
     * @param {type} sendToPartner
     * @returns {undefined}
     */
    this.addLocalChat = function (msg, date, privateId) {
        var msgId = self.randomString(5);//this will be used to change the sent status once it is sent (applicable if we're saving to db)

        self.sendChatToSocket(msg, date, privateId);

    };

    this.setCallerInfo = function (sessionId, callerInfo, isAdmin) {
        wsChat.send(JSON.stringify({
            action: 'setCallerInfo',
            visitorId: visitorId,
            sessionId: sessionId,
            agentId: agentId,
            room: room,
            callerInfo: callerInfo,
            isAdmin: isAdmin
        }));
    };

    this.sendCallerBack = function (access, sessionId) {
        wsChat.send(JSON.stringify({
            action: 'sendCallerBack',
            visitorId: visitorId,
            agentId: agentId,
            room: room,
            access: access,
            sessionId: sessionId
        }));
    };

    this.useH264Codec = function (sdp) {
        var updated_sdp;
        if (isFirefox) {
            updated_sdp = sdp.replace("m=video 9 UDP/TLS/RTP/SAVPF 120 126 97\r\n", "m=video 9 UDP/TLS/RTP/SAVPF 126 120 97\r\n");
        } else {
            updated_sdp = sdp.replace("m=video 9 UDP/TLS/RTP/SAVPF 100 101 107 116 117 96 97 99 98\r\n", "m=video 9 UDP/TLS/RTP/SAVPF 107 101 100 116 117 96 97 99 98\r\n");
        }
        return updated_sdp;
    };



    /**
     * 
     * @param {type} msg
     * @param {type} setTimeOut
     * @returns {undefined}
     */
    this.endCall = function (msg, sessionId) {
        wsChat.send(JSON.stringify({
            action: 'endCall',
            msg: msg,
            room: room,
            sessionId: sessionId
        }));
        clearTimeout(awaitingResponse);
    };

    this.toggleVideo = function () {
        var videoTracks = self.getStream().getVideoTracks();
        if (videoTracks.length === 0) {
            var e = jQEngager.Event('RestartVideo');
            jQEngager(document).trigger(e);
            return;
        }
        for (var i = 0; i < videoTracks.length; ++i) {
            videoTracks[i].enabled = !videoTracks[i].enabled;
        }
        var muted = (videoTracks[0].enabled) ? 'VideoUnmuted' : 'VideoMuted';
        var remotemuted = (videoTracks[0].enabled) ? 'RemoteVideoUnmuted' : 'RemoteVideoMuted';
        var e = jQEngager.Event(muted);
        jQEngager(document).trigger(e);

        wsChat.send(JSON.stringify({
            action: remotemuted,
            room: room,
            sessionId: self.getSessionId()
        }));
    };

    this.toggleAudio = function () {
        var audioTracks = self.getStream().getAudioTracks();
        if (audioTracks.length === 0) {
            console.log('No local audio available.');
            return;
        }
        for (var i = 0; i < audioTracks.length; ++i) {
            audioTracks[i].enabled = !audioTracks[i].enabled;
        }
        console.log('Audio ' + (audioTracks[0].enabled ? 'unmuted.' : 'muted.'));
        var muted = (audioTracks[0].enabled) ? 'AudioUnmuted' : 'AudioMuted';
        var remotemuted = (audioTracks[0].enabled) ? 'RemoteAudioUnmuted' : 'RemoteAudioMuted';
        var e = jQEngager.Event(muted);
        jQEngager(document).trigger(e);

        wsChat.send(JSON.stringify({
            action: remotemuted,
            room: room,
            sessionId: sessionId
        }));
    };

    this.adminOnline = function () {
        wsChat.send(JSON.stringify({
            action: 'imOnline',
            visitorId: 'admin',
            agentId: agentId,
            room: room,
            pass: requirePass,
            sessionId: self.getSessionId()
        }));
    };

    this.adminOffline = function () {
        wsChat.send(JSON.stringify({
            action: 'imOffline',
            visitorId: 'admin',
            agentId: agentId,
            room: room
        }));
    };

    this.sendChatToSocket = function (msg, date, privateId) {
        wsChat.send(JSON.stringify({
            action: 'txt',
            msg: msg,
            date: date,
            room: room,
            sessionId: self.getSessionId(),
            privateId: privateId
        }));

    };

    this.sendWhiteboardData = function (data) {
        wsChat.send(JSON.stringify({
            action: 'whiteboardSync',
            data: JSON.stringify(data),
            width: screen.width,
            room: room,
            sessionId: self.getSessionId()
        }));
    };

    this.broadcastSync = function () {
        wsChat.send(JSON.stringify({
            action: 'broadcastSync',
            room: room,
            sessionId: self.getSessionId()
        }));
    };

    this.addStreamStopListener = function (stream, callback) {
        var streamEndedEvent = 'ended';

        if ('oninactive' in stream) {
            streamEndedEvent = 'inactive';
        }

        stream.addEventListener(streamEndedEvent, function () {
            callback();
            callback = function () {};
        }, false);

        stream.getAudioTracks().forEach(function (track) {
            track.addEventListener(streamEndedEvent, function () {
                callback();
                callback = function () {};
            }, false);
        });

        stream.getVideoTracks().forEach(function (track) {
            track.addEventListener(streamEndedEvent, function () {
                callback();
                callback = function () {};
            }, false);
        });
    };

    this.stopCall = function (sessionId) {
        if (callPC[sessionId]) {
            callPC[sessionId].onicecandidate = null;
            callPC[sessionId].onsignalingstatechange = null;
            callPC[sessionId].ontrack = null;
            callPC[sessionId].oniceconnectionstatechange = null;
            callPC[sessionId].close();
            callPC[sessionId] = null;
            delete remoteStream[sessionId];
            delete callPC[sessionId];
        }
        //remove streams and free media devices

        var e = jQEngager.Event('CallEnded', {sessionId: sessionId});
        jQEngager(document).trigger(e);
    };

    this.handleCallTermination = function (sessionId) {
        console.log('handleCallTermination', sessionId)
        if (sessionId) {
            self.stopCall(sessionId);
            if (!self.getVideoSessions()) {
                self.stopMediaStream();
            }
        } else {
            for (var key in callPC) {
                self.stopCall(key);
            }
            self.stopMediaStream();
        }
    };

    this.handleScreenShareTermination = function () {
        wsChat.send(JSON.stringify({
            action: 'endScreenShare',
            msg: 'ScreenShare ended',
            room: room
        }));
        //remove streams and free media devices
        self.stopScreenShareStream();
    };

    this.setPing = function (sessionId, name) {
        wsChat.send(JSON.stringify({
            action: 'ping',
            room: iniRoom,
            pass: requirePass,
            visitorId: visitorId,
            agentId: agentId,
            sessionId: sessionId,
            name: name
        }));
    };

    this.setClose = function () {
        forceClose = true;
        wsChat.close();
    };

    this.endMeeting = function () {
        wsChat.send(JSON.stringify({
            action: 'endMeeting',
            room: room,
            sessionId: self.getSessionId()
        }));
    };

//set the status of remote (online or offline)
    this.setRemoteStatus = function (status, sessionId) {
        if (status === 'online') {
            var e = jQEngager.Event('PopupOnline', {sessionId: sessionId});
            jQEngager(document).trigger(e);
        } else {
            var e = jQEngager.Event('CallEnded', {sessionId: sessionId});
            jQEngager(document).trigger(e);
            var e = jQEngager.Event('PopupOffline', {sessionId: sessionId});
            jQEngager(document).trigger(e);
        }
    };

    this.addBroadcastStream = function (id, stream) {
        broadcaststream[id] = stream;
    };

    this.getBroadcastStream = function (id) {
        return broadcaststream[id];
    };

    this.stopMediaStream = function () {
        if (self.getStream()) {
            self.getStream().getTracks().forEach(function (track) {
                track.stop();
            });
        }
        self.setStream(null);
        myMediaStream = null;
    };

    this.stopScreenShareStream = function () {
        if (self.getScreenStream()) {
            self.getScreenStream().getTracks().forEach(function (track) {
                track.stop();
            });
        }
        self.setScreenStream(null);
    };

    this.showStatusBar = function (msg, displayTime) {
        console.log('showStatusBar', msg);
        jQEngager('#statusbar').html(msg);
        jQEngager('#statusbar').show();

        setTimeout(function () {
            jQEngager('#statusbar').hide();
        }, displayTime);
    };

    /**
     * 
     * @param {type} length
     * @returns {String}
     */
    this.randomString = function (length) {
        var rand = Math.random().toString(36).slice(2).substring(0, length);

        return rand;
    };

    /**
     * 
     * @returns {String}
     */
    this.getStream = function () {
        return myMediaStream;
    };

    /**
     * 
     * @returns {String}
     */
    this.getRemoteStreams = function () {
        return remoteStream;
    };

    /**
     * 
     * @returns {String}
     */
    this.getRemoteStream = function (sessionId) {
        return remoteStream[sessionId];
    };

    /**
     * 
     * @returns {String}
     */
    this.getVideoSessions = function () {
        return (Object.keys(callPC).length > 0);
    };

    /**
     * 
     * @returns {String}
     */
    this.getCountSessions = function () {
        return Object.keys(callPC).length;
    };

    /**
     * 
     * @returns {String}
     */
    this.sendRemoteVideoSessions = function (count) {
        wsChat.send(JSON.stringify({
            action: 'remoteVideoSession',
            room: room,
            count: count,
            visitorId: visitorId
        }));
    };

    /**
     * 
     * @returns {String}
     */
    this.getScreenStream = function () {
        return screenShareInstance;
    };

    /**
     * @param {string} value
     */
    this.setScreenStream = function (value) {
        screenShareInstance = value;
    };

    /**
     * 
     * @returns {String}
     */
    this.getVisitorId = function () {
        return visitorId;
    };

    this.getSessionId = function () {
        return sessionId;
    };

    this.getRemoteSessionId = function () {
        return pcSessionId;
    };

    this.sendFile = function () {
        var file = fileInput.files[0];
        wsChat.send(JSON.stringify({
            action: 'startFileTransfer',
            room: room,
            size: file.size,
            name: file.name,
            sessionId: sessionId

        }));
    };

    this.createFileConnection = function () {
        pcConstraint = null;

        // Add localConnection to global scope to make it visible
        // from the browser console.
        localConnection = new RTCPeerConnection(svConfigs.iceServers, pcConstraint);
        console.log('Created local peer connection object localConnection');

        sendChannel = localConnection.createDataChannel('sendDataChannel', {ordered: true});
        sendChannel.binaryType = 'arraybuffer';
        console.log('Created send data channel');

        sendChannel.onopen = self.onSendChannelStateChange;
        sendChannel.onclose = self.onSendChannelStateChange;
        localConnection.onicecandidate = self.iceCallback1;

        localConnection.createOffer(self.gotDescription1, self.onCreateSessionDescriptionError);
    };

    this.fileAccepted = function (e) {
        wsChat.send(JSON.stringify({
            action: 'fileAccepted',
            room: room

        }));
        self.startFileTransfer(e);
    };

    this.fileRejected = function (id) {
        wsChat.send(JSON.stringify({
            action: 'fileRejected',
            room: room,
            sessionId: sessionId
        }));
    };

    this.startFileTransfer = function (e) {
        progressBar.max = e.size;
        remoteConnection = new RTCPeerConnection(svConfigs.iceServers, pcConstraint);
        receivedFile = {name: e.name, size: e.size};
        remoteConnection.onicecandidate = self.iceCallback2;
        remoteConnection.ondatachannel = self.receiveChannelCallback;
    };

    this.onCreateSessionDescriptionError = function (error) {
        console.log('Failed to create session description: ' + error.toString());
    };

    this.sendData = function () {
        var file = fileInput.files[0];
        var offset = 0;
        receiveBuffer = [];
        console.log('file is ' + [file.name, file.size, file.type,
            file.lastModifiedDate].join(' '));

        downloadAnchor.textContent = '';
        if (file.size === 0) {
//            statusMessage.textContent = 'File is empty, please select a non-empty file';
            self.closeDataChannels();
            return;
        }

        progressBar.max = file.size;
        fileType = file.type.length > 0 ? file.type : 'text/plain';
        var chunkSize = 16384;
        var bufferFullThreshold = 5 * chunkSize;
        var usePolling = true;
        if (typeof sendChannel.bufferedAmountLowThreshold === 'number') {
            usePolling = false;
            bufferFullThreshold = chunkSize / 2;
            sendChannel.bufferedAmountLowThreshold = bufferFullThreshold;
        }
        // Listen for one bufferedamountlow event.
        var listener = function () {
            sendChannel.removeEventListener('bufferedamountlow', listener);
            sliceFile(offset);
        };
        var sliceFile = function (offset) {
            var reader = new window.FileReader();
            reader.onload = (function () {
                return function (e) {
                    var packet = new Int8Array(e.target.result, 0, e.target.result.byteLength);
                    if (sendChannel.bufferedAmount > bufferFullThreshold) {
                        if (usePolling) {
                            setTimeout(sliceFile, 150, offset);
                        } else {
                            sendChannel.addEventListener('bufferedamountlow', listener);
                        }
                        return;
                    }
                    sendChannel.send(packet);
                    progressBar.value = offset + e.target.result.byteLength;
                    if (file.size > offset + e.target.result.byteLength) {
                        window.setTimeout(sliceFile, 0, offset + chunkSize);
                    } else {
                        sending = false;
                    }
                };
            })(file);
            var slice = file.slice(offset, offset + chunkSize);
            reader.readAsArrayBuffer(slice);
        };
        sliceFile(0);
    };

    this.closeDataChannels = function () {
        if (sendChannel) {
            sendChannel.close();
        }
        if (receiveChannel) {
            receiveChannel.close();
        }
        if (localConnection) {
            localConnection.close();
            localConnection = null;
        }
        if (remoteConnection) {
            remoteConnection.close();
            remoteConnection = null;
        }
    };

    this.gotDescription1 = function (desc) {
        localConnection.setLocalDescription(desc);
        console.log('Offer from localConnection \n' + desc.sdp);

        wsChat.send(JSON.stringify({
            action: 'fileRemoteDescription',
            sdp: desc,
            room: room
        }));

    };

    this.gotDescription2 = function (desc) {
        remoteConnection.setLocalDescription(desc);
        console.log('Answer from remoteConnection \n' + desc.sdp);
        wsChat.send(JSON.stringify({
            action: 'fileLocalDescription',
            sdp: desc,
            room: room
        }));
    };

    this.iceCallback1 = function (e) {
        console.log('local ice callback');
        if (e.candidate) {
            var c = {
                candidate: e.candidate.candidate,
                sdpMLineIndex: e.candidate.sdpMLineIndex,
                sdpMid: e.candidate.sdpMid
            };
            if (isIEA) {
                var cand = c;
            } else {
                cand = e.candidate;
            }

            //send my candidate to peer
            wsChat.send(JSON.stringify({
                action: 'fileCandidate',
                candidate: JSON.stringify(cand),
                room: room,
                sessionId: sessionId
            }));
        }
    };

    this.iceCallback2 = function (e) {
        if (e.candidate) {

            var c = {
                candidate: e.candidate.candidate,
                sdpMLineIndex: e.candidate.sdpMLineIndex,
                sdpMid: e.candidate.sdpMid
            };
            if (isIEA) {
                var cand = c;
            } else {
                cand = e.candidate;
            }

            //send my candidate to peer
            wsChat.send(JSON.stringify({
                action: 'fileLocalCandidate',
                candidate: JSON.stringify(cand),
                room: room,
                sessionId: sessionId
            }));
        }
    };

    this.receiveChannelCallback = function (event) {
        console.log('Receive Channel Callback');
        receiveChannel = event.channel;
        receiveChannel.binaryType = 'arraybuffer';
        receiveChannel.onmessage = self.onReceiveMessageCallback;
        if (receiveChannel.readyState === 'open') {
            self.onReceiveChannelStateChange();
        } else {
            receiveChannel.onopen = self.onReceiveChannelStateChange;
        }
        receiveChannel.onclose = self.onReceiveChannelStateChange;

        receivedSize = 0;
        bitrateMax = 0;
        downloadAnchor.textContent = '';
        downloadAnchor.removeAttribute('download');
        if (downloadAnchor.href) {
            URL.revokeObjectURL(downloadAnchor.href);
            downloadAnchor.removeAttribute('href');
        }
        self.trySending();
    };

    this.onReceiveMessageCallback = function (event) {
        // console.log('Received Message ' + event.data.byteLength);
        var packet = new Int8Array(event.data);
        receiveBuffer.push(packet);
        receivedSize += packet.byteLength;

        progressBar.value = receivedSize;

        // we are assuming that our signaling protocol told
        // about the expected file size (and name, hash, etc).

        if (receivedSize >= receivedFile.size) {
            var received = new window.Blob(receiveBuffer, {type: fileType});

            downloadAnchor.href = URL.createObjectURL(received);
            downloadAnchor.download = receivedFile.name;
            downloadAnchor.textContent = receivedFile.name + ' (' + receivedFile.size + ' bytes)';
            downloadAnchor.style.display = 'block';

            if (statsInterval) {
                window.clearInterval(statsInterval);
                statsInterval = null;
            }

            self.closeDataChannels();
        }
    };

    this.onSendChannelStateChange = function () {
        var readyState = sendChannel.readyState;
        console.log('Send channel state is: ' + readyState);
        self.trySending();
    };

    this.onReceiveChannelStateChange = function () {
        var readyState = receiveChannel.readyState;
        console.log('Receive channel state is: ' + readyState);
        if (readyState === 'open') {
            timestampStart = (new Date()).getTime();
            timestampPrev = timestampStart;
            statsInterval = window.setInterval(self.displayStats, 500);
        }
        self.trySending();
    };

    this.trySending = function () {
        if (sendChannel && sendChannel.readyState === 'open' && !sending) {
            sending = true;
            self.sendData();
        }
    };

// display bitrate statistics.
    this.displayStats = function () {
        if (remoteConnection &&
                remoteConnection.iceConnectionState === 'connected') {
            remoteConnection.getStats(null, function (stats) {
                if (statsInterval === null) {
                    // file was already completely sent
                    return;
                }
                for (var key in stats) {
                    var res = stats[key];
                    if (res.type === 'googCandidatePair' &&
                            res.googActiveConnection === 'true') {
                        // calculate current bitrate
                        var bytesNow = res.bytesReceived;
                        var bitrate = Math.round((bytesNow - bytesPrev) * 8 /
                                (res.timestamp - timestampPrev));
                        timestampPrev = res.timestamp;
                        bytesPrev = bytesNow;
                        if (bitrate > bitrateMax) {
                            bitrateMax = bitrate;
                        }
                    }
                }
            }, function (e) {
                console.log('GetStats failure ', e);
            });
        }
    };

};