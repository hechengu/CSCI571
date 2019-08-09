var express = require('express');
var http = require('http');
var path = require('path');
var router = require('./router');
var url = require('url');
var bodyParser = require('body-parser');

var app = express(); //express frame
app.use(bodyParser.json());//receive JSON data, in this part simular as app.get(path, callback);
app.use(bodyParser.urlencoded({extended:true}));
app.use(express.static(__dirname + '/public'));//load and offer resources in public document
app.set('views', path.join(__dirname, 'views'));//get all ejs file in views and load webpages
app.set('view engine', 'ejs'); //type of files in views are ejs.

app.use('/', router); //start router once start server

app.listen(process.env.PORT || 3000, function(){ //display information once server start and before stopping
	console.log("Server start.");
});