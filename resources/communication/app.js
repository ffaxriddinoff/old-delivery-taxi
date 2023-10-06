const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server);

const drivers = new Map();
const clients = new Map();

// Tarmoqqa ulash
io.on('connection', (socket) => {
    console.log('Yangi odam ulandi');

    let driver_id = socket.handshake.query.id;
    if (driver_id) {
        drivers.set(parseInt(driver_id), socket.id);
    }
    
    let client_id = socket.handshake.query.client_id;
    if (client_id) {
        clients.set(parseInt(client_id), socket.id);
    }

    // Opertor joylashuvni olishi uchun
    socket.on('operator-server', (data) => {
        if (data.all) {
         io.emit('server-driver', data)   
        } else {
            try {   
                for (let id of data.drivers) {
                    io.to(drivers.get(id)).emit('server-driver', data)
                }
            } catch(e) {}
        }
    });

    // Haydovchidan kelgan xabar
    socket.on('driver-server', (data) => {
        // Mijozning olinganligi haqida hammaga bildirish
        io.emit('driver-driver', data)
    });

    // Hamkordan kelgan xabar
    socket.on('partner-server', (data) => {
        // Operatorga jo'natish
        io.emit('server-operator', data)
    });

    // Foydalnuvchi
    socket.on('user-server', (data) => {
        // Haydovchilarga jo'natish
        for (let id of data.drivers) {
            io.to(drivers.get(id)).emit('server-driver', data)
        }
        // io.emit('server-operator', data)
    });
    
    // talk, listen
    socket.on('driver-back', (data) => {
        // Ovozli jo'natish
        io.emit('server-driver', data)
    });

    // Uzildi
    socket.on('disconnect', () => {
      console.log('Foydalanuvchi tarmoqdan uzildi');
    });
    
    // Mijozdan kelgan xabar
    socket.on('client-server', (data) => {
        // Operatorga jo'natish
        io.emit('client-operator', data)
    });
    
    // Haydovchida kelgan xabar
    socket.on('driver-client', (data) => {
        // Mijozga jo'natish
        try {
            io.to(clients.get(data.client_id)).emit('server-client', data)
        } catch(e) {
        }
    });

    // socket.broadcast.emit('hi');
});

server.listen(3000, () => {
  console.log('Port: 3000');
});
