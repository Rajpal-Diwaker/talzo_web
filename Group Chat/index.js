const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const path = require('path');
const cors = require('cors');
const fs = require('fs');
const app = express();
const server = require('http').Server(app);
const http = require('http');
const moment = require('moment');
const io = require('socket.io')(server);
const morgan = require('morgan');
const chatModel = require('./Model/chatModel');
const {
    Users
} = require('./Utilities/users');
const users = new Users();
app.use(cors());
app.use(bodyParser.json({
    limit: '50mb'
}));
app.use(bodyParser.urlencoded({
    limit: '50mb',
    extended: true
}));
app.use(function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next()
});
app.use(morgan("dev"));




//setup port for server
server.listen(5125, function() {
    console.log("Server now connected on port 5125.");
});




// =================Create Socket Connection =============== //
io.on('connection', function(socket) {

    io.clients((error, clients) => {
        if (error) throw error;
        console.log(clients);
    })




    // ========= Get Conversation List ============ //

    socket.on('conversationList', function(data) {
        chatModel.conversationList(data, (err, chatList) => {
            // create group if exist group in conversationList and add into group
            chatList.forEach(element => {
                if (element.chat_type == 'group') {
                    socket.join(element.user_name + '_' + element.id);
                    users.removeUser(socket.id);
                    users.addUser(socket.id, element.user_name + '_' + element.id);
                }
            });

            if (err)
                io.sockets.to(socket.id).emit('error_callback', {
                    "message": "error occur."
                });

            io.sockets.to(socket.id).emit('conversationList', {
                "result": chatList
            });
        });

    });


    // ================= Init Chat =============== //
    socket.on('initChat', async function(data) {
        console.log('socket connected');
        chatModel.updateSocket(data, socket.id, (err, dbData) => {
            if (err)
                console.log(err);
        });
    });



    // ========= Get Chat List Between s_id and r_id ============ //
    socket.on('getChatList', function(data) {

        chatModel.updateReadStatus(data, (err, updateStatus) => {
            if (err)
                console.log("shuuuuuuuuuuuu", err);
        });
        chatModel.getChatList(data, (err, chatList) => {

            console.log(JSON.stringify(chatList));

            if (err)
                io.sockets.to(socket.id).emit('error_callback', {
                    "message": "error occur."
                });

            io.sockets.to(socket.id).emit('getChatList', {
                "result": chatList
            });

        });
    });




    // ================= Send Message =============== //

    socket.on('sendMessage', function(data) {
         console.log('datattatatattatttttattatattat',data)
        data.date_added = moment(new Date).format("YYYY-MM-DD HH:mm:ss");   
        if (data.chat_type == 'normal') {
            chatModel.getRecevierSocketData(data, async (err1, r_socketData) => {
                if (r_socketData.block_status == '1') {
                    data.deleted_by = String(data.id);
                   } else {
                    data.deleted_by = '';  
                  }
               chatModel.sendChatMessage(data, (err, dbData) => {
                result=data;
                result.r_id=data.id;
                result.id=dbData;
                // console.log("check=====",result);
                if (err1) {
                    io.sockets.to(socket.id).emit('error_callback', {
                        "message": "error occur."
                    });
                }
                if (r_socketData.chat_with == data.s_id && r_socketData.block_status == '0') {
                    io.sockets.to(r_socketData.socket_id).emit('receiveMessage', {
                        "result": result
                    });
                } else if (r_socketData.socket_id == '') {
                    console.log('Send Notification');
                }
                
                io.sockets.to(socket.id).emit('receiveMessage', {
                    "result": result
                });

                let newData = { 
                'user_id': data.id
                }
                chatModel.conversationList(newData, (err, chatList) => {

                    io.sockets.to(r_socketData.socket_id).emit('conversationList', {
                        "result": chatList
                    });
                });
            });

        });
        } else {
            chatModel.sendChatMessage(data, (err, dbData) => {});
            const user = users.getUser(data.group_name + '_' + data.id);

            if (user && data.msg != '') {
                io.to(user.room).emit('receiveMessage', {
                    "result": data
                });
            }
            let newData = {
                'user_id': data.id
            }
            chatModel.conversationList(newData, (err, chatList) => {
                chatList.forEach(element => {
                    if (element.chat_type == 'group') {
                        socket.join(element.user_name + '_' + element.id);
                        users.removeUser(socket.id);
                        users.addUser(socket.id, element.user_name + '_' + element.id);
                    }
                });
                io.sockets.to(r_socketData.socket_id).emit('conversationList', {
                    "result": chatList
                });
            });
        }


    })

// ========= Delete msg ============ //

    socket.on('deletemsg', function(data) {
        console.log("apidata========>",data)
        chatModel.deletemsg(data, (err, res) => {
           console.log("shubhm=>",res); 
        });
        });

// ========= Delete for everyone msg ============ //

    socket.on('deletefor_everyone', function(data) {
        console.log("apidata========>",data)
        chatModel.deletefor_everyone(data, (err, res) => {
           console.log("manish=>",res); 
        });
        });



    // ========= Disconnect Chat ============ //

    socket.on('disconnect', function() {

        const user = users.removeUser(socket.id);

        chatModel.disconnectUser(socket.id, (err, dbData) => {
            if (err) {
                console.log(err);
            }
        })
    });




});