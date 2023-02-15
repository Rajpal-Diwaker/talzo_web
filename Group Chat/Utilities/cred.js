let serverURLs = {
    "localdev": {
        "NODE_SERVER": "http://localhost",
         "ROOT_DIR":"/var/www/html",
        "NODE_SERVER_PORT": "5125",
        "MYSQL_HOST": 'localhost',
        "MYSQL_USER": 'root',
        "MYSQL_PASSWORD": '123',
        'MYSQL_DATABASE': 'talzo',
        "EMAIL_USER": 'tecugopm@gmail.com',
        "EMAIL_PASS": 'Techugo@123',
        "EMAIL_HOST": 'smtp.gmail.com',
        "EMAIL_PORT": "465",
        "EMAIL_SECURE": true,
        "EMAIL_TLS": true
    },
    "serverdev": {
        "NODE_SERVER": "http://13.232.62.239",
        "ROOT_DIR":"/var/www/html",
        "NODE_SERVER_PORT": "5125",
        "MYSQL_HOST": 'localhost',
        "MYSQL_USER": 'talzo_db',
        "MYSQL_PASSWORD": 'UcFNjag4qZ',
        'MYSQL_DATABASE': 'talzo_db',
        "EMAIL_USER": 'tecugopm@gmail.com',
        "EMAIL_PASS": 'Techugo@123',
        "EMAIL_HOST": 'smtp.gmail.com',
        "EMAIL_PORT": "465",
        "EMAIL_SECURE": true,
        "EMAIL_TLS": true
    }
    
}

module.exports = {
    serverURLs: serverURLs
}