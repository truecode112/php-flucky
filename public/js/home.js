/*jshint esversion: 6 */
/*jshint esversion: 8 */
(function() {
    "use strict";

    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    let socket;
    let connection;
    let localStream;
    let partnerName;
    let partnerCountry;
    let partnerIp;
    let selfIp;
    let chatType;
    let timeout;
    let falseVideoTimeout;
    let facingMode = "user";
    let connectedCount = 0;
    let initiator = false;
    let isChatConnected = false;
    let searching = false;
    let typing = false;
    let initiated = false;
    let allowed = false;
    let banned = false;
    let settings = {};
    let configuration = {};
    let urlRegex =
        /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi;

    //get the details
    (function() {
        $.ajax({
                url: "/get-details",
            })
            .done(function(data) {
                data = JSON.parse(data);

                if (data.success) {
                    settings = data.data;
                    initializeSocket(settings.signalingURL);

                    configuration = {
                        iceServers: [{
                                urls: settings.stunUrl,
                            },
                            {
                                urls: settings.turnUrl,
                                username: settings.turnUsername,
                                credential: settings.turnPassword,
                            },
                        ],
                    };
                } else {
                    showErrorAlert(languages.no_session_details);
                }
            })
            .catch(function() {
                showErrorAlert(languages.no_session_details);
            });
    })();

    //connect to the signaling server and add listeners
    function initializeSocket(signalingURL) {
        socket = io.connect(signalingURL);

        //listen for socket message event and handle it
        socket.on("message", function(data) {
            data = JSON.parse(data);

            switch (data.type) {
                case "match":
                    handleMatch(data);
                    break;
                case "partnerLeft":
                    requestNextPartner(languages.user_left);
                    break;
                case "offer":
                    handleOffer(data);
                    break;
                case "answer":
                    handleAnswer(data);
                    break;
                case "candidate":
                    handleCandidate(data);
                    break;
                case "message":
                    $(".typing-dots").closest(".remote-chat").remove();
                    appendMessage(data.message, false, false);
                    break;
                case "onlineCount":
                    $("#onlineCount").text(
                        settings.liveCountPrefix + data.count
                    );
                    break;
                case "textConnected":
                    partnerName = data.username;
                    partnerCountry = data.country;
                    handleChatConnected();
                    break;
                case "typing":
                    handleTyping(data);
                    break;
                case "selfData":
                    setSelfData(data);
                    break;
            }
        });

        //handle socket disconnect event and notify the user
        socket.on("disconnect", function() {
            $("#messageInput, #send, #next").attr("disabled", true);
        });
    }

    //listen for message form submit event and send message
    $(document).on("submit", "#chatForm", function(e) {
        e.preventDefault();

        //prevent XSS vulnerability
        let message = htmlEscape($("#messageInput").val().trim());

        if (message) {
            $("#messageInput").val("");
            appendMessage(message, true, false);

            send({
                type: "message",
                message: message,
            });

            typing = false;
            clearTimeout(timeout);
        }
    });

    //to prevent XSS vulnerability
    function htmlEscape(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    //append message to the chat body
    function appendMessage(message, self, system) {
        let systemClass = "";
        if (system) {
            $(".chat-body").html("");
            systemClass =
                '<span class="font-weight-bold">' +
                languages.system +
                "</span>: ";
        }

        let className = self ? "local-chat" : "remote-chat",
            messageDiv =
            "<div class='" +
            className +
            "'>" +
            "<div>" +
            '<span class="server-msg">' +
            systemClass +
            linkify(message) +
            "</span>" +
            "</div>" +
            "</div>";

        $(".chat-body").append(messageDiv);
        $(".chat-body").animate({
                scrollTop: $(".chat-body").prop("scrollHeight"),
            },
            100
        );
    }

    //set chat type to text and proceed to check the connection
    $("#text").on("click", function() {
        if (settings.textChatPaid && settings.userType == "free") {
            showPremium();
            return;
        }

        $(this).attr("disabled", true);
        $("#video").attr("disabled", true);
        chatType = "text";
        checkConnection();
    });

    //set chat type to video and proceed to check the connection
    $("#video").on("click", function() {
        if (settings.videoChatPaid && settings.userType == "free") {
            showPremium();
            return;
        }

        $(this).attr("disabled", true);
        $("#text").attr("disabled", true);
        chatType = "video";
        checkConnection();
    });

    //check if the socket is connected or not
    function checkConnection() {
        //prevent unncessary ajax requests
        if (banned) {
            showBan();
            $("#text, #video").attr("disabled", false);
            return;
        } else if (allowed) {
            continueToChat();
            $("#text, #video").attr("disabled", false);
            return;
        }

        //check if the socket is connected or not
        if (!socket.connected) {
            showSystemError(languages.cant_connect_server);
            $("#text, #video").attr("disabled", false);
            return;
        }

        //check if the user is banned or not
        $.ajax({
                url: "/check-user",
            })
            .done(function(data) {
                data = JSON.parse(data);
                $("#text, #video").attr("disabled", false);

                if (data.success) {
                    allowed = true;
                    continueToChat();
                } else {
                    banned = true;
                    socket.disconnect();
                    showBan();
                    throw new Error(languages.banned);
                }
            })
            .catch(function() {
                showErrorAlert();
                $("#text, #video").attr("disabled", false);
            });
    }

    //show welcome modal or continue directly to chat
    function continueToChat() {
        if (settings.userLoggedIn || localStorage.getItem("gender")) {
            gender.value =
                settings.userGender || localStorage.getItem("gender");
            $("#welcomeForm").trigger("submit");
        } else if (
            settings.genderFilterActive &&
            !localStorage.getItem("gender")
        ) {
            $("#noticeModal").modal("show");
        } else if (localStorage.getItem("termsAccepted")) {
            $("#welcomeForm").trigger("submit");
        } else {
            $("#noticeModal").modal("show");
        }
    }

    //handle click on start button
    $("#welcomeForm").on("submit", async function(e) {
        e.preventDefault();
        $("#start").attr("disabled", true);
        $("#noticeModal").modal("hide");
        $(".chat-panel").removeClass("hide");
        $(".about, #text, #video").hide();
        localStorage.setItem("gender", $("#gender").val() || "");
        localStorage.setItem("termsAccepted", true);

        if (chatType == "text") {
            //manage the UI when chatType is text
            $(".video-section").hide();
            $(".chat-main")
                .removeClass("col-md-7 col-lg-7 col-xl-9")
                .addClass("text-chat-panel");
            $(".chat-section, .chat-area").removeClass("pl-0");
            $("#partnerCountryText").removeClass("d-none").addClass("d-inline");
            if ($(window).width() < 767) {
                let height = $(window).height();
                let chatResponsive = height - 144;
                $(".chat-area").css("height", chatResponsive + "px");
            }
        } else {
            //manage the UI when chatType is video
            try {
                //get usermedia
                localStream = await navigator.mediaDevices.getUserMedia({
                    audio: true,
                    video: true,
                });
            } catch (e) {
                showSystemError(languages.no_device + e);
                $("#start").attr("disabled", false);
                $(".about, #text, #video").show();
                return;
            }

            $(".local-video-container .video-load-icon").hide();
            $(".video-actions").show();
            $(".remote-video-container .video-load-icon").css(
                "animation",
                "blink 1s linear infinite"
            );

            if (isMobile) {
                $(".rotate").show();
            }

            localVideo.srcObject = localStream;
        }

        $("#stop, #next").removeClass("hide");

        searching = true;
        $("#next").attr("disabled", true);
        appendMessage(languages.searching, false, true);

        //notify the system to find a partner
        send({
            type: "start",
            username: settings.username,
            chatType: chatType,
            gender: gender.value,
            genderFilter: genderFilter.value,
            countryFilter: countryFilter.value,
            ip: settings.ip,
        });

        initiated = true;
        initializeFalseVideoTimer();
    });

    //play false video if the user is not connected within specified time
    function initializeFalseVideoTimer() {
        falseVideoTimeout = setTimeout(function() {
            if (!isChatConnected &&
                settings.falseVideoEnabled &&
                chatType == "video" &&
                settings.videos.length
            ) {
                playFalseVideo();
            }
        }, settings.falseVideoTime * 1000);
    }

    //play false video
    function playFalseVideo() {
        send({
            type: "userBusy",
        });

        let name = getRandomName(settings.videos);
        remoteVideo.src = "videos/" + name;
        partnerName = name.replace(".mp4", "");
        partnerIp = "false";
        partnerCountry = getRandomName(settings.flagCodes);
        handleChatConnected();
        $(".remote-video-container .video-load-icon").addClass("hide");

        remoteVideo.onended = function() {
            remoteVideo.src = "";
            requestNextPartner(languages.user_left);
        };
    }

    //return a random array value
    function getRandomName(arr) {
        return arr[Math.floor((Math.random() * arr.length) | 0)];
    }

    //turn off the video
    $(".video-off").on("click", function() {
        $(this).hide();
        $(".video-on").show();
        localStream
            .getVideoTracks()
            .forEach((track) => (track.enabled = false));
    });

    //turn on the video
    $(".video-on").on("click", function() {
        $(this).hide();
        $(".video-off").show();
        localStream.getVideoTracks().forEach((track) => (track.enabled = true));
    });

    //mute the audio
    $(".audio-mute").on("click", function() {
        $(this).hide();
        $(".audio-unmute").show();
        localStream
            .getAudioTracks()
            .forEach((track) => (track.enabled = false));
    });

    //unmute the audio
    $(".audio-unmute").on("click", function() {
        localStream.getAudioTracks().forEach((track) => (track.enabled = true));
        $(this).hide();
        $(".audio-mute").show();
    });

    //rotate camera for mobile device
    $(".rotate").on("click", function() {
        //stop the video track and remove it from the stream
        localStream.getVideoTracks().forEach((track) => track.stop());
        localStream.removeTrack(localStream.getVideoTracks()[0]);

        facingMode = facingMode === "user" ? "environment" : "user";

        navigator.mediaDevices
            .getUserMedia({
                video: {
                    facingMode: {
                        exact: facingMode,
                    },
                },
            })
            .then(function(stream) {
                let videoTrack = stream.getVideoTracks()[0];

                if (connection) {
                    let sender = connection.getSenders().find(function(s) {
                        return s.track.kind === videoTrack.kind;
                    });

                    sender.replaceTrack(videoTrack);
                }

                //add track to the stream
                localStream.addTrack(videoTrack);

                //make sure the proper video icon is visible
                if ($(".video-on").is(":visible")) {
                    $(".video-on").hide();
                    $(".video-off").show();
                }
            })
            .catch(function(e) {
                console.log(languages.error_occurred, e);
            });
    });

    //handle keydown
    $("#messageInput").keydown((e) => {
        if (e.which != 13) {
            if (!typing) {
                //notify the opponent that the user is typing
                typing = true;
                send({
                    type: "typing",
                    typing: true,
                });
                timeout = setTimeout(typingTimeout, 3000);
            } else {
                clearTimeout(timeout);
                timeout = setTimeout(typingTimeout, 3000);
            }
        }
    });

    //notify the opponent that the user has stopped typing
    function typingTimeout() {
        typing = false;
        send({
            type: "typing",
            typing: false,
        });
    }

    //handle typing status
    function handleTyping(data) {
        if (data.typing) {
            appendMessage('<p class="typing-dots"></p>', false, false);
        } else {
            $(".typing-dots").parent().parent().remove();
        }
    }

    //stringify the data and send it to opponent
    function send(data) {
        socket.emit("message", JSON.stringify(data));
    }

    //set partner name, country and manage other information
    function handleChatConnected() {
        isChatConnected = true;
        searching = false;
        if (falseVideoTimeout) clearTimeout(falseVideoTimeout);
        appendMessage(languages.connected_with + " " + partnerName, false, true);
        $("#partnerCountryVideo, #partnerCountryText").attr(
            "src",
            partnerCountry ?
            "//flagcdn.com/64x48/" +
            partnerCountry.toLocaleLowerCase() +
            ".png" :
            "./images/globe.png"
        );
        $("#partnerName").text(partnerName);
        $(".remote-user-info, .report").removeClass("hide");
        $("#messageInput, #send, #next").attr("disabled", false);
        $(".remote-video-container .video-load-icon").addClass("hide");
        if (partnerIp != "false") connectedCount++;
    }

    //handle match event
    //create and send the offer to the opponent
    function handleMatch(data) {
        if (isChatConnected) return;

        partnerName = data.username;
        partnerCountry = data.partnerCountry;
        partnerIp = data.ip;

        if (chatType === "text") {
            send({
                type: "textConnected",
                username: settings.username,
                country: data.country,
            });

            handleChatConnected();
            return;
        }

        //create RTCPeerConnection if the chatType is video
        connection = new RTCPeerConnection(configuration);
        setupListeners();
        initiator = true;

        connection
            .createOffer()
            .then(function(offer) {
                return connection.setLocalDescription(offer);
            })
            .then(function() {
                send({
                    type: "offer",
                    sdp: connection.localDescription,
                    username: settings.username,
                    country: data.country,
                    ip: selfIp,
                });
            })
            .catch(function(e) {
                console.log(languages.error_occurred, e);
            });
    }

    //handle offer event
    //create and send the answer to the opponent
    function handleOffer(data) {
        if (!isChatConnected) {
            connection = new RTCPeerConnection(configuration);
            setupListeners();
        }

        partnerName = data.username;
        partnerCountry = data.country;
        partnerIp = data.ip;

        initiator = false;

        connection.setRemoteDescription(data.sdp);
        connection
            .createAnswer()
            .then(function(answer) {
                connection.setLocalDescription(answer);
                send({
                    type: "answer",
                    answer: answer,
                });
            })
            .catch(function(e) {
                console.log(languages.error_occurred, e);
            });
    }

    //handle answer and set remote description
    function handleAnswer(data) {
        if (
            connection.signalingState !== "closed" ||
            connection.signalingState !== "stable"
        ) {
            connection.setRemoteDescription(data.answer);
        }
    }

    //handle candidate and add ice candidate
    function handleCandidate(data) {
        if (data.candidate && connection.signalingState !== "closed") {
            connection.addIceCandidate(new RTCIceCandidate(data.candidate));
        }
    }

    //add local track to the connection,
    //manage remote track,
    //ice candidate and state change event
    //when chatType is video
    function setupListeners() {
        localStream
            .getTracks()
            .forEach((track) => connection.addTrack(track, localStream));

        connection.onicecandidate = (event) => {
            if (event.candidate) {
                send({
                    type: "candidate",
                    candidate: event.candidate,
                });
            }
        };

        connection.ontrack = (event) => {
            if (remoteVideo.srcObject) return;
            remoteVideo.srcObject = event.streams[0];
            handleChatConnected();
            $(".remote-video-container .video-load-icon").addClass("hide");
        };

        connection.addEventListener("connectionstatechange", () => {
            if (connection.connectionState === "connected") {
                $(".remote-video-container .video-load-icon").addClass("hide");
            } else if (connection.connectionState === "disconnected") {
                $(".remote-video-container .video-load-icon").removeClass(
                    "hide"
                );
            } else if (connection.connectionState === "failed" && initiator) {
                //perform iceRestart if the connection fails
                connection
                    .createOffer({
                        iceRestart: true,
                        offerToReceiveVideo: true,
                    })
                    .then(function(offer) {
                        return connection.setLocalDescription(offer);
                    })
                    .then(function() {
                        send({
                            type: "offer",
                            sdp: connection.localDescription,
                        });
                    })
                    .catch(function(e) {
                        console.log(languages.error_occurred, e);
                    });
            }
        });
    }

    //reload the page on stop button click
    $("#stop").on("click", function() {
        window.location.reload();
    });

    //free the opponent
    $("#next").on("click", function() {
        if (searching) return;
        requestNextPartner(languages.searching);
    });

    //request next partner
    //reinitialize the RTCPeerConnection object
    function requestNextPartner(message) {
        searching = true;
        isChatConnected = false;
        $("#messageInput, #send, #next").attr("disabled", true);
        appendMessage(message, false, true);
        $(".remote-user-info, .report").addClass("hide");

        //check if the false video should be played or not
        if (
            partnerIp != "false" &&
            connectedCount % settings.falseVideoFrequency == 0 &&
            settings.falseVideoEnabled &&
            chatType == "video" &&
            settings.videos.length
        ) {
            if (connection) {
                connection.close();
                connection.onicecandidate = null;
                connection.ontrack = null;
            }
            remoteVideo.srcObject = null;
            playFalseVideo();
            return;
        }

        send({
            type: "next",
            username: settings.username,
            chatType: chatType,
            gender: gender.value,
            genderFilter: genderFilter.value,
            countryFilter: countryFilter.value,
        });

        if (chatType === "text") return;

        if (connection) {
            connection.close();
            connection.onicecandidate = null;
            connection.ontrack = null;
        }

        $(".remote-video-container .video-load-icon").removeClass("hide");
        remoteVideo.srcObject = null;
        remoteVideo.src = "";

        initializeFalseVideoTimer();
    }

    //set gender filter and notify the server
    $("#genderFilter").on("change", function() {
        if (settings.genderFilterPaid && settings.userType == "free") {
            $("#genderFilter").val("");
            showPremium();
            return;
        }

        if (!initiated) return;

        send({
            type: "updateGenderFilter",
            genderFilter: genderFilter.value,
        });

        requestNextPartner(languages.searching);
    });

    //set country filter and notify the server
    $("#countryFilter").on("change", function() {
        if (settings.countryFilterPaid && settings.userType == "free") {
            $("#countryFilter").val("");
            showPremium();
            return;
        }

        if (!initiated) return;

        send({
            type: "updateCountryFilter",
            countryFilter: countryFilter.value,
        });

        requestNextPartner(languages.searching);
    });

    //set chat design height when the document loads
    heigthSet();

    //remove loader when the window loads
    $(window).on("load", function() {
        setTimeout(removeLoader);
    });

    //remove the loader
    function removeLoader() {
        $(".loader").fadeOut(500);
    }

    //update the chat area height on window resize
    $(window).on("resize", function() {
        heigthSet();
    });

    //calculate and set chat area height
    function heigthSet() {
        let width = $(window).width();
        let height = $(window).height();
        let chatheight = height - 128;
        let videoHeight = height / 2 - 41;

        if (width < 767) {
            $(".chat-main").css("top", videoHeight + 11 + "px");

            if ($(".chat-main").hasClass("text-chat-panel")) {
                let chatTextResponsive = height - 150;
                $(".chat-area").css("height", chatTextResponsive + "px");
            } else {
                let chatResponsive = height / 2 - 80.5;
                $(".chat-area").css("height", chatResponsive + "px");
            }
        } else {
            $(".chat-main").css("top", "0");
            $(".chat-area").css("height", chatheight + "px");
        }
        $(".remote-video-container").css("height", videoHeight + "px");
        $(".local-video-container").css("height", videoHeight + "px");

        if (width < 368) {
            $("#text, #video, #stop, #next").addClass("btn-sm");
        } else {
            $("#text, #video, #stop, #next").removeClass("btn-sm");
        }
    }

    //detect and replace text with url
    function linkify(text) {
        return text.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank">' + url + "</a>";
        });
    }

    //set self IP address and country flag
    function setSelfData(data) {
        selfIp = data.ip;

        $("#selfCountryflag")
            .attr(
                "src",
                data.country ?
                "//flagcdn.com/64x48/" +
                data.country.toLocaleLowerCase() +
                ".png" :
                "images/globe.png"
            )
            .removeAttr("hidden");
    }

    //capture opponent's screenshot
    $(".report").on("click", function() {
        $(this).addClass("hide");
        canvas.width = remoteVideo.videoWidth / 2;
        canvas.height = remoteVideo.videoHeight / 2;
        canvas
            .getContext("2d")
            .drawImage(remoteVideo, 0, 0, canvas.width, canvas.height);

        fetch(canvas.toDataURL("image/jpeg"))
            .then((res) => res.blob())
            .then(sendImage);
    });

    //send the captured screenshot to the server
    function sendImage(blob) {
        let form = new FormData();
        form.append("ip", partnerIp);
        form.append("image", blob);

        $.ajax({
                url: "/report-user",
                data: form,
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(data) {
                data = JSON.parse(data);

                if (data.success) {
                    Swal.fire({
                        icon: "info",
                        iconHtml: '<i class="fa fa-flag"></i>',
                        text: languages.user_reported,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    showErrorAlert();
                }
            })
            .catch(function() {
                showErrorAlert();
            });
    }

    //show alert with text only
    function showErrorAlert(message) {
        Swal.fire({
            icon: "error",
            text: message || languages.error_occurred,
            showConfirmButton: false,
            timer: 1500,
        });
    }

    //show alert with title and text
    function showBan() {
        Swal.fire({
            icon: "error",
            iconHtml: '<i class="fa fa-user-slash"></i>',
            title: languages.oops,
            text: languages.you_banned,
            confirmButtonColor: settings.primaryColor,
        });
    }

    //show alert with title and text
    function showSystemError(message) {
        Swal.fire({
            icon: "error",
            title: languages.oops,
            text: message,
            confirmButtonColor: settings.primaryColor,
        });
    }

    //the selected feature is premium
    function showPremium() {
        Swal.fire({
            icon: "info",
            iconHtml: '<i class="far fa-gem"></i>',
            title: settings.paidPlanName + " " + languages.feature,
            text: languages.upgrade_now,
            showCancelButton: true,
            confirmButtonColor: settings.primaryColor,
            confirmButtonText: languages.upgrade,
        }).then((result) => {
            if (result.isConfirmed) {
                window.open("/pricing");
            }
        });
    }

    //scroll to top
    $('.start-btn').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: $(this.hash).position().top }, 'slow');
    });

    remoteVideo.oncontextmenu = function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    };

})();