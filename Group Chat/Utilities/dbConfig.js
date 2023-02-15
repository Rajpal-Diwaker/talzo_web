var config = require("../Utilities/config").config;
const QueryBuilder = require('node-querybuilder');

var mysql = require('mysql');
var connection = mysql.createConnection({
    host: config.DB_URL.host,
    user: config.DB_URL.user,
    password: config.DB_URL.password,
    database: config.DB_URL.database,
    charset: 'utf8mb4',
    dbcollat: 'utf8mb4_unicode_ci'

});
connection.connect((err) => {
    if (err) {
        throw err;
    }
    console.log("Database connect successfully.");
});


const settings = {
    "host": config.DB_URL.host,
    "user": config.DB_URL.user,
    "password": config.DB_URL.password,
    "database": config.DB_URL.database,
    "charset": 'utf8mb4',
    "dbcollat": 'utf8mb4_unicode_ci'

};

const qb = new QueryBuilder(settings, 'mysql', 'single');

// connection.connect(() => {
//     require('../Models/User').initialize();
// });


let getDB = () => {
    return connection;
}







module.exports = {
    getDB: getDB,
    qb: qb
}