const dgram = require('dgram');
const inherits = require('util').inherits;
const crypto = require('crypto');
const iv = Buffer.from([0x17, 0x99, 0x6d, 0x09, 0x3d, 0x28, 0xdd, 0xb3, 0xba, 0x69, 0x5a, 0x2e, 0x6f, 0x58, 0x56, 0x2e]);
const serverSocket = dgram.createSocket('udp4');
const multicastAddress = '224.0.0.50';
const multicastPort = 4321;
const serverPort = 9898;


var gatewaySid = platform.gatewaySids[this.deviceSid];
var password = platform.passwords[gatewaySid];

var cipher = crypto.createCipheriv('aes-128-cbc', password, iv);
var gatewayToken = platform.gatewayTokens[gatewaySid];

if (cipher && gatewayToken) {
key = cipher.update(gatewayToken, "ascii", "hex");
cipher.final('hex'); // Useless data, don't know why yet.
}

var command = '{"cmd":"write","model":"' + this.deviceModel + '","sid":"' + this.deviceSid + '","data":"{\\"' + this.switchName + '\\":\\"' + (on ? 'on' : 'off') + '\\", \\"key\\": \\"' + key + '\\"}"}';
var remoteAddress = this.platform.gatewayAddress[this.deviceSid];
var remotePort = this.platform.gatewayPort[this.deviceSid];
serverSocket.send(command, 0, command.length, remotePort, remoteAddress);
