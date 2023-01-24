/*jshint esversion: 6 */
/*jshint node: true */
const geoip = require("geoip-country");
let allUsers = [];
let waitingUsers = [];

//set user properties and request next partner
function handleStart(socket, io, data) {
    let geo = geoip.lookup(data.ip) || "127.0.0.1";

    let user = {
        id: socket.id,
        username: data.username,
        country: geo.country,
        isWaiting: true,
        isConnected: false,
        partner: null,
        chatType: data.chatType,
        gender: data.gender,
        genderFilter: data.genderFilter,
        countryFilter: data.countryFilter,
        ip: data.ip,
    };

    allUsers[socket.id] = user;
    handleNext(user, socket.id, io, data);

    sendToPeer(io, {
        type: "selfData",
        toSocketId: socket.id,
        country: geo.country,
        ip: user.ip,
    });
}

//find a partner
function handleNext(currentUser, socketId, io, data) {
    let partner;

    //release the current connection if any
    if (currentUser.isConnected && currentUser.partner) {
        let partnerId = currentUser.partner;

        setUserProperties(socketId, true, false, null);

        if (allUsers[partnerId]) {
            //notify the opponent if available
            sendToPeer(io, {
                type: "partnerLeft",
                toSocketId: partnerId,
            });

            setUserProperties(partnerId, true, false, null);
        }
    }

    //find the partner from waitingUsers
    for (let possiblePartner of Object.values(waitingUsers)) {
        if (
            possiblePartner.id !== socketId &&
            possiblePartner.isWaiting &&
            possiblePartner.chatType === data.chatType &&
            checkFilter(
                data.gender,
                possiblePartner.gender,
                data.genderFilter,
                possiblePartner.genderFilter
            ) &&
            checkFilter(
                currentUser.country,
                possiblePartner.country,
                data.countryFilter,
                possiblePartner.countryFilter
            )
        ) {
            partner = possiblePartner;
            break;
        }
    }

    try {
        if (
            partner &&
            allUsers[partner.id].isWaiting &&
            currentUser.isWaiting
        ) {
            delete waitingUsers[partner.id];
            setUserProperties(partner.id, false, true, socketId);
            setUserProperties(socketId, false, true, partner.id);

            //notify the parner about the match
            sendToPeer(io, {
                type: "match",
                toSocketId: socketId,
                username: allUsers[partner.id].username,
                country: currentUser.country,
                partnerCountry: allUsers[partner.id].country,
                ip: allUsers[partner.id].ip,
            });
        } else {
            //push the user to the waiting list
            waitingUsers[socketId] = currentUser;
        }
    } catch (e) {
        console.log("Error occurred: ", e);
    }
}

//check gender and country filter
function checkFilter(self, partner, selfFilter, partnerFilter) {
    if (!selfFilter && !partnerFilter) return true;

    let flagSelf = true;
    let flagPartner = true;

    if (partnerFilter) {
        flagSelf = self === partnerFilter;
    }

    if (selfFilter) {
        flagPartner = partner === selfFilter;
    }

    return flagSelf && flagPartner;
}

//handle disconnect event
function handleDisconnect(socketId, io) {
    if (allUsers[socketId] && allUsers[socketId].isConnected) {
        //notify the opponent
        sendToPeer(io, {
            type: "partnerLeft",
            toSocketId: allUsers[socketId].partner,
        });
    }

    if (waitingUsers[socketId]) delete waitingUsers[socketId];
    if (allUsers[socketId]) delete allUsers[socketId];
}

//set values to the user property
function setUserProperties(socketId, isWaiting, isConnected, partner) {
    if (!allUsers[socketId]) return;

    allUsers[socketId].isWaiting = isWaiting;
    allUsers[socketId].isConnected = isConnected;
    allUsers[socketId].partner = partner;
}

//send the message to particular user
function sendToPeer(io, data) {
    io.to(data.toSocketId).emit("message", JSON.stringify(data));
}

//send the message to all the users
function sendToAll(io, data) {
    io.sockets.emit("message", JSON.stringify(data));
}

//release the partner if any
function releasePartner(socketId, io) {
    let currentUser = allUsers[socketId];
    let partnerId = currentUser.partner;

    setUserProperties(socketId, false, true, 'fake');

    //release the current connection if any
    if (currentUser.isConnected && partnerId) {
        if (allUsers[partnerId]) {
            //notify the opponent if available
            sendToPeer(io, {
                type: "partnerLeft",
                toSocketId: partnerId,
            });

            setUserProperties(partnerId, true, false, null);
        }
    }
}

module.exports = function(io) {
    //broadcast online users count
    setInterval(function() {
        sendToAll(io, {
            type: "onlineCount",
            count: Object.keys(io.sockets.sockets).length,
        });
    }, 5000);

    //handle connection event
    io.sockets.on("connection", function(socket) {
        socket.on("message", function(data) {
            data = JSON.parse(data);

            switch (data.type) {
                case "start":
                    handleStart(socket, io, data);
                    break;
                case "next":
                    if (allUsers[socket.id])
                        handleNext(allUsers[socket.id], socket.id, io, data);
                    break;
                case "offer":
                case "answer":
                case "candidate":
                case "message":
                case "textConnected":
                case "typing":
                    if (allUsers[socket.id]) {
                        data.toSocketId = allUsers[socket.id].partner;
                        sendToPeer(io, data);
                    }
                    break;
                case "updateGenderFilter":
                    allUsers[socket.id].genderFilter = data.genderFilter;
                    break;
                case "updateCountryFilter":
                    allUsers[socket.id].countryFilter = data.countryFilter;
                    break;
                case "userBusy":
                    releasePartner(socket.id, io);
                    break;
            }
        });

        socket.on("disconnect", function() {
            handleDisconnect(socket.id, io);
        });
    });
};