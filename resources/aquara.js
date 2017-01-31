const crypto = require('crypto');
const dgram = require('dgram');
const serverSocket = dgram.createSocket('udp4');

var password = '';
var gateway = '';
var token = '';
var model = '';
var sid = '';
var cmd = '';
var state = '';
var short_id = '';
var version = '';

process.argv.forEach(function(val, index, array) {
	switch ( index ) {
		case 2 : password = val; break;
		case 3 : gateway = val; break;
		case 4 : token = val; break;
		case 5 : model = val; break;
		case 6 : sid = val; break;
        case 7 : cmd = val; break;
        case 8 : state = val; break;
        case 9 : short_id = val; break;
        case 10 : version = val; break;
	}
});

if (parseInt(version) >= 5) {
    const iv = Buffer.from([0x17, 0x99, 0x6d, 0x09, 0x3d, 0x28, 0xdd, 0xb3, 0xba, 0x69, 0x5a, 0x2e, 0x6f, 0x58, 0x56, 0x2e]);
} else {
    const iv = new Buffer([0x17, 0x99, 0x6d, 0x09, 0x3d, 0x28, 0xdd, 0xb3, 0xba, 0x69, 0x5a, 0x2e, 0x6f, 0x58, 0x56, 0x2e]);
}

var cipher = crypto.createCipheriv('aes-128-cbc', password, iv);

if (cipher && token) {
key = cipher.update(token, "ascii", "hex");
cipher.final('hex'); // Useless data, don't know why yet.
}

if (cmd != 'rgb') {
 state = '\\"' + state + '\\"';
}

var command = '{"cmd":"write","model":"' + model + '","sid":"' + sid + '","short_id":"' + short_id + '","data":"{\\"' + cmd + '\\":' + state + ', \\"key\\": \\"' + key + '\\"}"}';
console.log((new Date()).toLocaleString(), command);
serverSocket.send(command, 0, command.length, 9898, gateway);
