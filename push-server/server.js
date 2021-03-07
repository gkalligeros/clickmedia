var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');

var redisSubscriber = new Redis();
redisSubscriber.subscribe('push-notification');
redisSubscriber.on('message', function (channel, message) {
    console.log(message);
    io.sockets.emit('restaurant-updated', message);
});


http.listen(3001, function () {
    console.log('Listening on Port 3001');
});
