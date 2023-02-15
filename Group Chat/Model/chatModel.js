var con = require('../Utilities/dbConfig').getDB;
var qb = require('../Utilities/dbConfig').qb;
var base64 = require('base-64');
let environment = require('../Utilities/environment').environment;
let serverURLs = require("../Utilities/cred").serverURLs;
var md5 = require('md5');
const transporter = require('../Utilities/util').transporter;
const moment = require('moment');
multer = require('multer'),
    util = require('../Utilities/util');
let fs = require('fs');


// ===================== Update Socket Status of User  ====================== //

let updateSocket = (postData, socket_id, callback) => {

    let updateData = {
        "chat_with": postData.id,
        "socket_id": socket_id
    }

    qb.update('users', updateData, { 'user_id': postData['s_id'] }, (err, res) => {
        if (err)
            callback(err);

        console.log("Query Ran: " + qb.last_query());
        callback(err, res)

    });

}



// ===================== Insert Chat Message Data ====================== //

let sendChatMessage = (postData, callback) => {
// console.log("ssssssssss=>",postData);
    var insertData = {};

    if (postData.chat_type == 'normal') {
        insertData = {
            "s_id": postData.s_id,
            "r_id": postData.id,
            "group_id": 0,
            "msg":postData.msg,
            "url": postData.url,
            "action": "",
            "thumb_url": postData.thumb_url,
            "msg_type": postData.msg_type,
            "read_status":postData.id,
            "deleted_by":postData.deleted_by,
            "chat_type": postData.chat_type,
            "date_added": postData.date_added
        }
    } else {
        insertData = {
            "s_id": postData.s_id,
            "r_id": 0,
            "group_id": postData.id,
            "msg": postData.msg,
            "url": "",
            "action": "",
            "thumb_url": "",
            "msg_type": "text",
            "deleted_by":"",
            "chat_type": postData.chat_type,
            "date_added": postData.date_added
        }
    }





    qb.returning('id').insert('chat', insertData, (err, insertRes) => {
        if (err)
            callback(err);
    // console.log("Query Ran: " + qb.last_query());
    // console.log("check========>",insertRes)
        callback(null,insertRes.insertId);
       

    });

}



// ===================== Get Receiver Socket Id ====================== //

let getRecevierSocketData = (postData, callback) => {
   let sql1=`select s_id,r_id from chat where s_id=${postData['s_id']} and r_id=${postData['id']}`;

    qb.query(sql1,(err, res1) =>{ 
        // console.log(res1.length)
        if(res1.length==0){
         var insertData = {};
        insertData={"user_id":postData.id,"accept_id":postData.s_id}
        qb.returning('id').insert('check_users_accept', insertData, (err, insertRes) => {
        if (err)
          console.log(err)
        });
        }
    });

   let sql=`select socket_id,chat_with,IFNULL((select block_status from chat_block_users where user_id=${postData['id']} and block_id=${postData['s_id']}),'0') as block_status from users where user_id=${postData['id']}`; 
    qb.query(sql,(err, res) =>{ 

         // console.log("Query Ran: " + qb.last_query());

        if (err)
            callback(err);
        //console.log("Query Ran: " + qb.last_query());

        callback(null, res[0])
    });
}

// ================ Update Read Status Of Chat List  ================== //

let updateReadStatus = (postData, callback) => {
    // console.log("manish",postData);
    qb.update('chat', {'read_status':''}, { 's_id': postData['r_id'], 's_id': postData['id'] }, (err, res) => {
        if (err){
         callback(err);
        }
        
        callback(err, res)
    });

}


// ================ Chat List of s_id and r_id  ================== //

let getChatList = (postData, callback) => {
   // console.log(postData)
 var sql=`select block_status from chat_block_users where user_id=${postData['s_id']} and block_id=${postData['id']}`;
 var sql1=`select accept_status from check_users_accept where accept_id=${postData['id']} and user_id=${postData['s_id']}`;
  var sql2=`select block_status from user_block where user_id=${postData['s_id']} and block_user_id=${postData['id']}`;
    if (postData.chat_type == 'normal') {
        qb.select('chat.*,DATE_FORMAT(date_added,"%Y-%m-%d %H:%i:%s") as date_added',false).where("((s_id = " + postData['s_id'] + " AND r_id = " + postData['id'] + " AND delete_for_everyone != '1' AND deleted_by !=" + postData['s_id'] + ") OR (s_id = " + postData['id'] + " AND r_id = " + postData['s_id'] + " AND delete_for_everyone != '1' AND deleted_by !=" + postData['s_id'] + "))").order_by('id', 'DESC').get('chat', (err, res) => {
             qb.query(sql,(err1, res1) => {
            if (err1){ 
            callback(err1); 
             }
            qb.query(sql1,(err2, res2) => {
             if (err2){ 
             callback(err2); 
           }
           qb.query(sql2,(err3, res3) => {
             if (err3){ 
             callback(err3); 
           }
            var result = {
block_status : (res1 && res1.length > 0) ? res1[0].block_status : "0",
accept_status :(res2 && res2.length > 0) ? res2[0].accept_status : "0",
profile_is_block :(res3 && res3.length > 0) ? res3[0].block_status : "0",
                data : res
            }
            // console.log("Dsff",result);
            callback(err, result)
        });
           });
              });
        });
    } else {
        qb.select('*').where("group_id = " + postData['id'] + " AND deleted_by !=" + postData['s_id']).order_by('id', 'DESC').get('chat', (err, res) => {

            if (err)
                callback(err);
            callback(err, res)
        });
    }



}



// ===================== Disconnect User  ====================== //

let disconnectUser = (socket_id, callback) => {

    let updateData = {
        "chat_with": 0,
        "socket_id": ""
    }

    qb.where('socket_id', socket_id).update('users', updateData, (err, res) => {
        if (err)
            callback(err);

        // console.log("Query Ran: " + qb.last_query());
        callback(res)

    });

}


// ===================== Conversation List  ====================== //

let conversationList = (postData, callback) => {

    let sql = `SELECT (case when g.group_id !='' then g.group_id  when c.r_id=${postData['user_id']} then su.user_id else ru.user_id end ) as id, (case when g.group_name !='' then g.group_name  when c.r_id=${postData['user_id']} then su.name else ru.name end ) as user_name,(case when g.group_name !='' then g.group_image  when c.r_id=${postData['user_id']} then IFNULL((select  user_image from users where users.user_id=su.user_id),'') else IFNULL((select  user_image from users where users.user_id=ru.user_id),'') end ) as profile_picture,c.date_added as date_time,c.chat_type,c.msg_type,c.msg,c.url,IFNULL((select  'unread' as status from chat where chat.read_status=${postData['user_id']} and chat.id=c.id),'read') as read_status FROM chat as c LEFT  JOIN groups AS g ON g.group_id = c.group_id LEFT  JOIN users AS ru ON ru.user_id = c.r_id LEFT  JOIN users AS su ON su.user_id = c.s_id   WHERE c.id IN (SELECT max(id) FROM chat where (s_id= ${postData['user_id']} or r_id= ${postData['user_id']}) AND  (delete_for_everyone != '1' and deleted_by != ${postData['user_id']})  GROUP by if(s_id = ${postData['user_id']},r_id,s_id) order by id desc) order by date_added desc`;

     console.log(sql);


    qb.query(sql, (err, res) => {
        if (err)
            callback(err);

        let userList = [];
        var user_id;

        // console.log('fddddddddddddd',res);

        callback(err, res)



    });

}

//============delete msg================//
let deletemsg = (postData, callback) => {
var msg_array=[];
var msg_array=postData.msg_id.split(',');
var msglength=msg_array.length;
for (let i = 0; i < msglength; i++) {
var sql=`select deleted_by from chat where chat_type='${postData.chat_type}' and id=${msg_array[i]}`;
  qb.query(sql,(err, res) => {
            if (err){
            callback(err , null); 
             }                                   
 if(res && res.length == 0){
  var sql1=`UPDATE chat SET delete_for_everyone='1' where chat_type='${postData.chat_type}' and id=${msg_array[i]}`;     
  }else{
 var sql1=`UPDATE chat SET deleted_by=${postData.s_id} where chat_type='${postData.chat_type}' and id=${msg_array[i]}`;  
  }  
 qb.query(sql1,(err1, res1) => {
    if (err1){ 
    callback(err1 , null); 
 }
});
});
}
callback(null,"1");
}


//============delete for everyone  msg================//
let deletefor_everyone = (postData, callback) => {
var msg_array=[];
var msg_array=postData.msg_id.split(',');
var msglength=msg_array.length;
for (let i = 0; i < msglength; i++) {
var sql=`UPDATE chat SET delete_for_everyone='1' where chat_type='${postData.chat_type}' and s_id=${postData.s_id} and id=${msg_array[i]}`;
  qb.query(sql,(err, res) => {
             if (err){
         callback(err);
        }  
    });
} 
callback(null,"1")                 
}

module.exports = {
    updateSocket,
    sendChatMessage,
    getRecevierSocketData,
    updateReadStatus,
    getChatList,
    disconnectUser,
    conversationList,
    deletemsg,
    deletefor_everyone


}