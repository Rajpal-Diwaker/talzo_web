	let fs = require("fs"),
	config = require("./config").config,
	mustache = require('mustache'),
	bodyParser = require('body-parser'),
	nodemailer = require('nodemailer'),
	MD5 = require("md5"),
	Chance = require('chance'),
	async = require('async'),
	cred = require('../Utilities/cred'),
	querystring = require('querystring');

let environment = require('./environment').environment;
let serverURLs = require("./cred").serverURLs;



let videouploadUrl = `${serverURLs[environment].ROOT_DIR}/talzo/uploads`;

let uploadFolder = `${serverURLs[environment].ROOT_DIR}/talzo/uploads/`;
let defualtImageUrl = `${serverURLs[environment].ROOT_DIR}/talzo/uploads`;
let usersImageUrl = `${serverURLs[environment].ROOT_DIR}/talzo/uploads/userImages/`;

let webUrlRoot = `${serverURLs[environment].NODE_SERVER}/talzo/`;
let publicFolder = `${serverURLs[environment].NODE_SERVER}/public/`;

let DOCUMENT_ROOT=`${serverURLs[environment].ROOT_DIR}`;
let BASE_URL=`${serverURLs[environment].NODE_SERVER}/talzo/`;


// Define Error Codes
let statusCode = {
	OK: 200,
	FOUR_ZERO_ONE: 401,
	TWO_ZERO_ONE: 201,
	TWO_ZERO_TWO: 202,
	INTERNAL_SERVER_ERROR: 400,
	FOUR_ZERO_ZERO: 400,
	BAD_REQUEST: 404,
	FIVE_ZERO_ZERO: 500,
	THREE_ZERO_ZERO: 300
};

// Define Error Messages
let statusMessage = {
	PARAMS_MISSING: 'Mandatory Fields Missing',
	SERVER_BUSY: 'Our Servers are busy. Please try again later.',
	PAGE_NOT_FOUND: 'Page not found',
	SUCCESS: "Success.",
	USER_ALREADY_EXITS: "User already exists.",
	USER_NOT_EXITS: "User does not exists.",
	INVALID_TOKEN: "User authentication failed.",
	OLD_TOKEN: "Please provide new token",
	INVALID_PASS: "Invalid password.",
	INVALID_OTP: "Invalid OTP for mobile :",
	RESEND_OTP: "Please resend OTP.",
	VERIFY_NUMBER: "Please verify your mobile number before login.",
	STATUS_UPDATED: "User profile update successfully.",
	MECHANT_INSERTED: "Merchant added successfully.",
	PASSWORD_CHNAGED: "User password changed successfully.",
	DB_ERROR: "Database related error.",
	EMAIL_SENT: "An email with generate new password link has been sent on registered email.",
	USER_ADDED: "User signup successfully.",
	LOGIN_SUCCESS: "Login successfully.",
	EMAIL_VERIFY: "Signup successfully. A mail has been sent to your email address, please verify your email.",
	EMAIL_NOT_VALID: "Email not valid.",
	PASSWORD_NOT_VALID: "Password not valid.",
	CHECK_EMAIL_VERIFY: "Please verify your email."
};

let mailModule = nodemailer.createTransport(config.EMAIL_CONFIG);


let	transporter = nodemailer.createTransport({
		service: 'gmail',
		auth: {
			"user": config.EMAIL_CONFIG.auth.user,
			"pass": config.EMAIL_CONFIG.auth.pass
		}

	})

let generateToken = () => {
	return Date.now() + Math.floor(Math.random() * 99999) + 1000;
}

let generateUid = () => {

	const Chance = require('chance');
	const chance = new Chance(Date.now() + Math.random());
	let randomStr = chance.string({
		length: 25,
		pool: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
	});
	return randomStr;
}


let generateCode = () => {
	const chance = new Chance(Date.now() + Math.random());
	let randomStr = chance.string({
		length: 6,
		pool: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
	});
	return randomStr;
}

let entryDirectory = function (x) {
	var dir = usersImageUrl + x;

	if (!fs.existsSync(dir)) {
		fs.mkdirSync(dir);
	}

	return dir;
}
let addBasePath = (url) => {
	if (!url) {
		return '';
	}
	if (!/^(f|ht)tps?:\/\//i.test(url)) {
		url = publicFolder + url;
	}
	return url;
}



let getCoupon = () => {
	var idLength = 6;
	var chars = "1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,@,$,#,&";
	chars = chars.split(",");
	var min = 0;
	var max = chars.length - 1;
	var id = "";
	for (var i = 0; i < idLength; i++) {
		id += chars[Math.floor(Math.random() * (max - min + 1) + min)];
	}
	return id;
}



let talzo_Multi_image_upload = (files, callback) => {
	let a = [];
	async.eachSeries(files.images, (item, callbackNextIteratn) => {
		cloudinary.uploader.upload(item.path, function (url) {
			if (!url) callback(null)
				else {
					a.push(url.url);
					callbackNextIteratn();
				}
			})
	}, (err) => {
		console.log("Done with async loop")
		callback(null, a);

	})
}


module.exports = {
	statusCode: statusCode,
	statusMessage: statusMessage,
	generateToken: generateToken,
	generateUid: generateUid,
	entryDirectory: entryDirectory,
	defualtImageUrl: defualtImageUrl,
	usersImageUrl: usersImageUrl,
	uploadFolder: uploadFolder,
	webUrlRoot: webUrlRoot,
	publicFolder: publicFolder,
	generateCode: generateCode,
	addBasePath: addBasePath,
	transporter:transporter,
	BASE_URL:BASE_URL

}