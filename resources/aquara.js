const crypto = require('crypto');
const iv = Buffer.from([0x17, 0x99, 0x6d, 0x09, 0x3d, 0x28, 0xdd, 0xb3, 0xba, 0x69, 0x5a, 0x2e, 0x6f, 0x58, 0x56, 0x2e]);
const dgram = require('dgram');
const serverSocket = dgram.createSocket('udp4');

var password = '';
var gateway = '';
var token = '';
var model = '';
var sid = '';
var cmd = '';
var state = '';

process.argv.forEach(function(val, index, array) {
	switch ( index ) {
		case 2 : password = val; break;
		case 3 : gateway = val; break;
		case 4 : token = val; break;
		case 5 : model = val; break;
		case 6 : sid = val; break;
        case 7 : cmd = val; break;
        case 8 : state = val; break;
	}
});


var cipher = crypto.createCipheriv('aes-128-cbc', password, iv);

if (cipher && token) {
key = cipher.update(token, "ascii", "hex");
cipher.final('hex'); // Useless data, don't know why yet.
}

var command = '{"cmd":"write","model":"' + model + '","sid":"' + sid + '","data":"{\\"' + cmd + '\\":\\"' + state + '\\", \\"key\\": \\"' + key + '\\"}"}';
console.log((new Date()).toLocaleString(), command);
serverSocket.send(command, 0, command.length, 9898, gateway);
