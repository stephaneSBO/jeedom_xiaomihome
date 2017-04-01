from past.builtins import basestring
import socket
import binascii
import struct
import json
import logging
import globals
import utils
import threading
from mirobo.vacuum import Vacuum

def discover(message):
	device = message['model']
	default={}
	if device == 'vacuum':
		vacuum=Vacuum(message['dest'],message['token'])
		result = vacuum.discover()
		if message['dest'] in result:
			logging.debug('Found the vacuum : ' + str(result[message['dest']]))
			result[message['dest']]['model']='vacuum'
			result[message['dest']]['ip']=message['dest']
			result[message['dest']]['found']=1
			globals.JEEDOM_COM.send_change_immediate({'devices':{'wifi':result[message['dest']]}})
		else:
			default['ip']=message['dest'];
			default['notfound'] =1;
			globals.JEEDOM_COM.send_change_immediate({'devices':{'wifi':default}})
			logging.debug('Did not find the vacuum try again')
	return

def execute_action(message):
	device = message['model']
	if device == 'vacuum':
		vacuum=Vacuum(message['dest'],message['token'],int(message['serial']),int(message['devtype']))
		if message['action'] == 'start':
			vacuum.start()
		elif message['action'] == 'stop':
			vacuum.stop()
		elif message['action'] == 'pause':
			vacuum.pause()
		elif message['action'] == 'spot':
			vacuum.spot()
		elif message['action'] == 'home':
			vacuum.home()
		elif message['action'] == 'find':
			vacuum.find()
		elif message['action'] == 'fanspeed':
			vacuum.set_fan_speed(int(message['option']))
		t = threading.Timer(2, refresh,args=(message,))
		t.start()
	return

def refresh(message):
	try:
		device = message['model']
		result={}
		result['model'] = 'vacuum'
		result['ip'] = message['dest']
		if device == 'vacuum':
			vacuum=Vacuum(message['dest'],message['token'],int(message['serial']),int(message['devtype']))
			status = vacuum.status()
		result['status'] = status
		globals.JEEDOM_COM.send_change_immediate({'devices':{'wifi':result}})
	except:
		pass
	return