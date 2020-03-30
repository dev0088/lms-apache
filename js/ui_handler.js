var uiHandler = function () {
    var self = this;
    var _$, $container, commController;

    this.init = function (jQuery, container, comm) {
        _$ = jQuery;
        $container = container;
        commController = comm
    };

    this.setMobileChatOnly = function () {
        if (isAndroid || isiPhone) {
            _$('.wd-v-share').hide();
        }
        self.displayChatOnly();
    };

    this.setVideoBoxOff = function (panel) {
        audio_on = false;
        video_on = false;
        self.setMuteButton();
        self.setVideoButton();
        _$('#wd-widget-content-' + panel + ' .wd-video-box-on').hide();
        _$('#wd-widget-content-video-ringing').hide();
        _$('#wd-widget-content-video-waiting').hide();
        _$('#unsupported_div').show();
    };

    this.toggleHeaderChat = function (show) {
        _$('.header-auido-video').hide();
    };

    this.displayScreenShare = function () {
        if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
            _$('.wd-chat-box').show();
            _$('.wd-video-box').css('width', '70%');
            _$('.wd-chat-box').css('width', '30%');
        } else {
            _$('.wd-chat-box').hide();
            _$('.wd-video-box').css('width', '100%');
            _$('.wd-video-box').css('border-right', 0);
        }
        _$('.wd-avatar-agent').hide();
        _$('#mainleft_div').show();
        _$('#wd-widget-content-whiteboard').hide();
        _$('#wd-widget-content-video').show();
    };

    this.displayVideoOnly = function () {
        if (conferenceStyle == 'simple' && svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
            _$('.wd-chat-box').show();
            _$('.wd-video-box').css('width', '70%');
            _$('.wd-chat-box').css('width', '30%');
        } else {
            _$('.wd-chat-box').hide();
            _$('.wd-video-box').css('width', '100%');
            _$('.wd-video-box').css('border-right', 0);
        }
        _$('#mainleft_div').show();
        _$('#wd-widget-content-whiteboard').hide();
        _$('#wd-widget-content-video').show();
        _$('.' + classVideo).each(function () {
            var id = this.id;
            _$('#' + id).detach().appendTo('#' + videoElementContainer);
            _$('#' + id).removeClass('smallvideo');
        });
    };

    this.displayChatOnly = function () {
        if (commController.getStream(commController.getRemoteSessionId())) {
            _$('#call_audio_video').hide();
            _$('#slide_video').show();
        } else {
            _$('#slide_video').hide();
            if (svConfigs.videoScreen && (svConfigs.videoScreen.onlyAgentButtons === false || queryString.isAdmin)) {
                _$('#call_audio_video').show();
            }
        }

        if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
            _$('.wd-v-text').hide();
        } else {
            _$('.wd-v-text').show();
        }
        _$('#mainleft_div').hide();
        _$('.wd-chat-box').show();
        _$('.wd-chat-box').css('width', '100%');
        _$('#wd-widget-content-whiteboard').hide();

    };

    this.restoreVideoBox = function () {
        if (conferenceStyle == 'conference') {
            _$('#wd-widget-content-greenroom').hide();
            _$('#wd-widget-content-prev').hide();
            if (!isAndroid && !isiPhone) {
                stopFullScreenPopup();
                _$('#invideo').attr('style', '');
            }
            _$('#invideo').show();
            _$('.wd-video-c').hide();
        } else {
            _$('#call_audio_video').hide();
            if (!isAndroid && !isiPhone) {
                stopFullScreenPopup();
                _$('#mainleft_div').attr('style', '');
            }
            _$('#mainleft_div').show();
        }
    };

    this.syncVideoChatPanelsPos = function () {
        var vpanel = _$('#newdev_video');
        var cpanel = _$('#newdev_chat');

        if (_$('#newdev_video').is(":visible")) {
            panel_xpos = vpanel.css('left');
            panel_ypos = vpanel.css('top');
            cpanel.css('left', panel_xpos);
            cpanel.css('top', panel_ypos);
        } else if (_$('#newdev_chat').is(":visible")) {
            panel_xpos = cpanel.css('left');
            panel_ypos = cpanel.css('top');
            vpanel.css('left', panel_xpos);
            vpanel.css('top', panel_ypos);
        }
    };

    this.setScreenDisabled = function (disabled) {
        if (disabled) {
            _$('.wd-v-share').addClass('disabled');
            _$('#screenshare_div').addClass('disabled');
            if (conferenceStyle == 'simple') {
                _$('.' + classVideo).each(function () {
                    var id = this.id;
                    _$('#' + id).detach().appendTo('#small_video');
                    _$('#' + id).addClass('smallvideo');
                });
            } else {
                _$('.bigvideo').hide();
            }
        } else {
            _$('.wd-v-share').removeClass('disabled');
            _$('#screenshare_div').removeClass('disabled');
            if (conferenceStyle == 'simple') {
                _$('.' + classVideo).each(function () {
                    var id = this.id;
                    _$('#' + id).detach().appendTo('#' + videoElementContainer);
                    _$('#' + id).removeClass('smallvideo');
                });
            } else {
                _$('.bigvideo').show();
                _$('.sourcevideo').show();
            }
        }
    };

    this.setDisabled = function (disabled) {
        if (disabled) {
            _$('#raisehand_div').addClass('disabled');
            _$('#exit_meeting').addClass('disabled');
            _$('#call_video').addClass('disabled');
            _$('#call_audio').addClass('disabled');
            _$('#file_transfer').addClass('disabled');
            _$('#startscreenshare').addClass('disabled');
            _$('#callButton_1').addClass('disabled');
            _$('#callAudioButton_1').addClass('disabled');
            _$('#newdev_chat_message1').addClass('disabled');
            _$('#whiteboard').addClass('disabled');
            _$('#startVideoButton').addClass('disabled');
            _$('.startVideoButton').addClass('disabled');
            _$('#answer_call_button').addClass('disabled');
            _$('#answer_audiocall_button').addClass('disabled');
            _$('#reject_call_button').addClass('disabled');
            _$('.wd-v-share').addClass('disabled');
        } else {
            _$('#raisehand_div').removeClass('disabled');
            _$('#exit_meeting').removeClass('disabled');
            _$('#call_video').removeClass('disabled');
            _$('#call_audio').removeClass('disabled');
            _$('#startscreenshare').removeClass('disabled');
            _$('#file_transfer').removeClass('disabled');
            if (queryString.room || queryString.broadcast) {
                _$('#callButton_1').removeClass('disabled');
            }
            if (queryString.room) {
                _$('#callAudioButton_1').removeClass('disabled');
            }
            _$('#newdev_chat_message1').removeClass('disabled');
            _$('#whiteboard').removeClass('disabled');
            _$('#startVideoButton').removeClass('disabled');
            _$('.startVideoButton').removeClass('disabled');
            _$('#answer_call_button').removeClass('disabled');
            _$('#answer_audiocall_button').removeClass('disabled');
            _$('#reject_call_button').removeClass('disabled');
            _$('.wd-v-share').removeClass('disabled');
        }
    };

    this.toggleWidget = function () {
        _$('#nd_widget_content').toggle();
        _$('.agent-address-wd').hide();
        _$('#peer_email_video').toggle(false);
    };

    this.toggleVisitors = function (show) {
        _$('#nd_widget_visitors').toggle(show);
    };

    this.setAgentOnlyButtons = function () {
        _$('#startVideoButton').hide();
        _$('.wd-v-pickupaudio').hide();
        _$('.wd-v-pickup').hide();
        _$('.wd-v-share').hide();
        _$('.header-auido-video').hide();
    };

    this.disableScreenShare = function () {
        _$('.wd-v-share').hide();
        _$('#startscreenshare').hide();
    };

    this.disableVideo = function () {
        _$('#startVideoButton').hide();
        _$('.wd-v-pickup').hide();
        _$('#call_video').hide();
        _$('#answer_call_button').hide();
        _$('#muteVideo1'). prop('checked', true);
        _$('#muteVideo1').hide();
        _$('.turnOffCamera').hide();
    };

    this.disableAudio = function () {
        _$('.wd-v-pickupaudio').hide();
        _$('#call_audio').hide();
        _$('#answer_audiocall_button').hide();
        _$('#muteAudio1'). prop('checked', true);
        _$('#muteAudio1').hide();
        _$('.muteMe').hide();
    };

    this.disableWhiteboard = function () {
        _$('#whiteboard').hide();
    };

    this.disableTransfer = function () {
        _$('#file_transfer').hide();
    };

    this.setVideoBox = function () {
        _$('#recordingIcon').hide();
        _$('#newdev_video').show();
        _$('#mainleft_div').children().hide();
        _$('#video_container').show();
        _$('#video_container_oneway').hide();
        _$('#video_container_oneway_agent').hide();
        _$('.wd-v-nosound').removeClass('disabledDiv');
        _$('#video_back').show();
    };

    this.setOneWay = function () {
        _$('#localVideo').hide();
        _$('#video_container_oneway').show();

        _$('#local_video_div').hide();
        _$('.wd-v-video').attr('class', 'wd-v-novideo');
        _$('.wd-v-novideo').addClass('disabledDiv');
        _$('.wd-v-sound').attr('class', 'wd-v-nosound');
        _$('.wd-v-nosound').addClass('disabledDiv');
    };

    this.togglePermissionError = function () {
        self.syncVideoChatPanelsPos();
        self.togglePermissionWidget(false);
        self.setVideoBox();

        _$('#permission_browsers_error').children().hide();
        if (isChrome) {
            _$('#permission_div_error_chrome').show();
        }
        if (isFirefox) {
            _$('#permission_div_error_firefox').show();
        }
        _$('#wd-widget-error').show();
        self.setVideoButton();
    };

    this.toggleInstaWhiteboard = function () {

        window.resizeTo(
                window.screen.availWidth,
                window.screen.availHeight
                );
        stopIncomingCall();

        self.syncVideoChatPanelsPos();

        _$('#mainleft_div').show();
        if (conferenceStyle == 'simple' && svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
            _$('.wd-chat-box').show();
            _$('.wd-chat-box').css('width', '30%');
            _$('.wd-video-box').css('width', '70%');
            _$('.wd-video-box').css('border-right', 0);
            _$('.wd-v-text').hide();
        } else {
            _$('.wd-chat-box').hide();
            _$('.wd-video-box').css('width', '100%');
            _$('.wd-video-box').css('border-right', 0);
            _$('.wd-v-text').show();
        }

        _$('#wd-widget-content-whiteboard').show();
        _$('#wd-widget-content-chat-main').hide();
        _$('#wd-widget-content-video').hide();
        _$('#wd-avatar-agent').hide();
        _$('#video_container_chat').hide();
        if (queryString.isAdmin || localStorage.getItem('hasPrivileges')) {
            _$('.wd-v-tovideo').show();
            _$('#cleanCanvas').show();
        }
        _$('.' + classVideo).each(function () {
            var id = this.id;
            _$('#' + id).detach().appendTo('#whiteboard_video');
            _$('#' + id).addClass('smallvideo');
        });
        _$('.bigvideo').each(function () {
            var id = this.id;
            _$('#' + id).detach().appendTo('#whiteboard_video');
            _$('#' + id).addClass('smallvideo');
        });
        _$('.broadcastvideo').each(function () {
            _$(this).detach().appendTo('#whiteboard_video');
            _$(this).addClass('smallvideo');
        });

    };

    this.toggleInstaChat = function () {
        stopIncomingCall();
        if (window.outerHeight == screen.availHeight && typeof widgetSize !== 'undefined') {
            stopFullScreenPopup();
        } else {
            stopFullScreen();
        }
        _$('.wd-video-c').removeClass('disabled');
        self.syncVideoChatPanelsPos();
        self.togglePermissionWidget(true);
        self.setVideoBox();
        _$('#recordingIcon').hide();
        _$('#wd-widget-content-chat-main').show();
        _$('#wd-avatar-agent').show();
        if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
            _$('.wd-v-text').hide();
        } else {
            _$('.wd-v-text').show();
        }
        _$('.wd-v-recording').hide();
        _$('#video_container_chat').hide();
        _$('#wd-widget-content-whiteboard').hide();
        audio_on = video_on = true;
        self.setVideoButton();
        self.setMuteButton();
    };

    this.toggleInstaChatScreen = function () {

        self.syncVideoChatPanelsPos();
        self.togglePermissionWidget(true);
        self.setVideoBox();
        _$('#wd-widget-content-chat-main').show();
        _$('.wd-avatar-agent').hide();
        _$('#video_container_chat').show();
        _$('#wd-widget-content-whiteboard').hide();
        self.setVideoButton();
        self.setMuteButton();
    };

    this.onIncomingChat = function () {
        self.restoreVideoBox();
    };

    this.onIncomingVideo = function () {
        self.restoreVideoBox();
    };

    this.toggleRinging = function (callback) {
        self.setMobileChatOnly();
        self.displayVideoOnly();
        _$('#toggle_icon').removeClass('video');
        _$('#wd-widget-content-video').hide();
        _$('#wd-widget-content-chat-main').hide();
        _$('#wd-widget-content-video-waiting').hide();
        _$('#wd-widget-content-video-ringing').show();
        _$('#wd-widget-content-whiteboard').hide();
        _$('#answer_call_button').off();
        _$('#answer_audiocall_button').off();
        _$('#reject_call_button').off();
        _$('#answer_call_button').on('click', function () {
            video_on = true;
            self.setVideoButton();
            self.toggleVideoBox(false);
            callback(true);
        });
        _$('#answer_audiocall_button').on('click', function () {
            if (isiPhone) {
                video_on = true;
                video_iphone_on = false;
            } else {
                video_on = false;
            }
            self.setVideoButton();
            callback(true);
            self.toggleVideoBox(false);
        });
        _$('#reject_call_button').on('click', function () {
            callback(false);
            self.toggleInstaChat();
        });
    };

    this.toggleVideoBox = function (show) {
        stopIncomingCall();
        _$('#wd-widget-content-chat-main').hide();
        _$('#wd-widget-content-greenroom').hide();
        _$('#wd-widget-content-prev').hide();
        _$('#wd-widget-content-video-ringing').hide();
        _$('#wd-widget-content-whiteboard').hide();
        if (show === true) {

            if (svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
                _$('#call_audio_video').hide();
                _$('.wd-v-text').hide();
            } else {
                _$('.wd-v-text').show();
            }
            _$('#wd-widget-content-video').show();
            _$('#wd-widget-content-video-waiting').hide();


            _$('.' + classVideo).each(function () {
                var id = this.id;
                _$('#' + id).detach().appendTo('#' + videoElementContainer);
                _$('#' + id).removeClass('smallvideo');
            });

        } else if (show == 4) {
            _$('.wd-chat-box').hide();
            _$('.wd-video-box').css('width', '100%');
            _$('.wd-video-box').css('border-right', 0);
            _$('#wd-widget-content-greenroom').show();
            _$('#wd-widget-content-video').hide();
            _$('#wd-widget-content-video-waiting').hide();
        } else {
            _$('#wd-widget-content-video').hide();
            _$('#wd-widget-content-video-waiting').show();
            if (conferenceStyle == 'simple' && svConfigs.videoScreen && svConfigs.videoScreen.chat === true) {
                _$('.wd-chat-box').show();
                _$('.wd-video-box').css('width', '70%');
                _$('.wd-chat-box').css('width', '30%');
            } else {
                _$('.wd-chat-box').hide();
                _$('.wd-video-box').css('width', '100%');
                _$('.wd-video-box').css('border-right', 0);
            }

        }
    };

    this.setWidgetValues = function () {
        _$('#peer_name_video').html(peer_name);
        _$('.peer_name_video').html(peer_name);
        _$('#peer_name_chat').html(peer_name);
        _$('.dw-chat-avatar').attr('src', peer_avatar);
        _$('#peer_email_video').html(peer_email);
        _$('#peer_email_chat').html(peer_email);
        _$('.agent-address-wd a').attr('href', 'mailto:' + peer_email);
        _$('#peer_phone_video').html(peer_phone);
        _$('#peer_phone_chat').html(peer_phone);

        var time = getCurrentTime();
        _$('#timestamp').html(time);

        if (peer_avatar) {
            _$('#nd_widget_content' + ' .peer_avatar').attr('src', peer_avatar);
        } else {
            _$('#nd_widget_content' + ' .peer_avatar').attr('src', lsRepUrl + 'img/small-avatar.jpg');
        }

        var bgsite = document.querySelector(".bg-site4");
        if (peer_background && bgsite !== undefined && bgsite !== null) {
            bgsite.style.background = 'url(' + peer_background + ') no-repeat center center';
            bgsite.style.backgroundSize = 'cover';
        }

        if (peer_logo) {
            _$('#nd_widget_content' + ' .firm-logo-wd img').attr('src', peer_logo);
            _$('#nd_widget_content' + ' .firm-logo-wd img').width(100);
            _$('#nd_widget_content' + ' .firm-logo-wd img').height('auto');
        }
        _$('#popup_widget_text').html(popup_message);

    };

    this.toggleInstaVideo = function (show) {
        self.syncVideoChatPanelsPos();
        self.setMuteButton();
        self.setVideoBox();
        self.setVideoButton();
        self.toggleVideoBox(show);
    };

    this.togglePermissionWidget = function (toggle, showRing, video) {

        if (isAndroid) {
            return;
        }
        if (toggle) {
            if (showRing) {
                _$('#wd-widget-content-video-waiting').show();
            } else {
                _$('#wd-widget-content-video-waiting').hide();
            }
            _$('#permission_div').hide();

        } else {
            _$('#wd-widget-content-chat-main').hide();
            _$('#wd-widget-content-video-waiting').hide();
            self.permissionDisplay();
        }
    };

    this.permissionDisplay = function () {
        var video = (video_on) ? 'video' : '';
        _$('#permission_browsers').children().hide();
        _$('#permission_div_span').show();
        if (isChrome) {
            _$('#permission_div_chrome' + video).show();
        }
        if (isFirefox) {
            _$('#permission_div_firefox' + video).show();
        }
        if (isIEA) {
            _$('#permission_div_ie' + video).show();
        }

        _$('#wd-widget-content-video-waiting').hide();
        _$('#permission_div').show();
    };

    this.resetCallHoldState = function () {
        _$('#on_hold').hide();
    };

    this.setMuteButton = function () {
        if (audio_on) {
            _$('.wd-v-nosound').attr('class', 'wd-v-sound');
            _$('.fa-microphone-slash').closest('a').addClass('active');
        } else {
            _$('.wd-v-sound').attr('class', 'wd-v-nosound');
            _$('.fa-microphone-slash').closest('a').removeClass('active');
        }
    };


    this.setRecordingUi = function (state) {
        if (state) {
            _$('.wd-v-recording').removeClass('recording-off');
            _$('.wd-v-recording').addClass('recording-on');
            _$('#startRecording').addClass('active');

        } else {
            _$('.wd-v-recording').removeClass('recording-on');
            _$('.wd-v-recording').addClass('recording-off');
            _$('#startRecording').removeClass('active');
        }
    };

    this.setScreenButton = function (show) {
        if ((isChrome || isFirefox) && !isAndroid && !isiPhone) {
            if (show) {
                _$('.wd-v-share').hide();
                _$('.wd-v-stopshare').show();
                _$('#startscreenshare').hide();
                _$('#screensharestop_div').show();
            } else {
                _$('.wd-v-share').show();
                _$('.wd-v-stopshare').hide();
                _$('#screensharestop_div').hide();
                _$('#startscreenshare').show();
            }
        }
    };

    this.setVideoButton = function () {
        if (video_on) {
            _$('#local_video_div').show();
            _$('.wd-v-novideo').attr('class', 'wd-v-video');
            _$('.fa-video-camera').closest('a').addClass('active');
        } else {
            _$('#local_video_div').hide();
            _$('.wd-v-video').attr('class', 'wd-v-novideo');
            _$('.fa-video-camera').closest('a').removeClass('active');
        }
    };

    this.showTranslateMessage = function (msg) {
        _$('#translate_message').css('width', _$('.bigvideo.bigvideoadd').css('width'));
        _$('#translate_message').css('bottom', _$('.bigvideo.bigvideoadd').css('bottom'));
        _$('#translate_message').show();
        _$('#translate_message').html(msg);
    };

    this.setLocalRemote = function (tennantId) {
        _$('#localVideo').removeClass('localvideo');
        _$('#localVideo').addClass(classVideo);
    };


    this.setRemoteLocal = function (tennantId) {
        _$('#localVideo').removeClass(classVideo);
        _$('#localVideo').addClass('localvideo');
    };
};
