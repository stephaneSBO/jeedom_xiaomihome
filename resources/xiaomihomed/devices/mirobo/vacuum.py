import logging
import socket
import datetime
import codecs
import binascii
from protocol import Message, Utils
from containers import VacuumStatus, ConsumableStatus, CleaningDetails, CleaningSummary, Timer

class Vacuum:
    """Main class representing the vacuum."""
    def __init__(self, ip='', token='', serial='', devtype=''):
        self.ip = ip
        self.port = 54321
        self.token = bytearray.fromhex(token)
        self.serial = serial
        self.devtype = devtype

        # TODO this is a mess, find a nicer way to provide token to construct
        Utils.token = self.token
        self._timeout = 5
        self.__id = 0

    @property
    def _id(self):
        """Returns running id."""
        self.__id += 1
        return self.__id

    @classmethod
    def discover(self):
        """Scan for devices in the network."""
        logging.info("Sending discovery packet to broadcast address..")
        # magic, length 32
        helobytes = '21310020ffffffffffffffffffffffffffffffffffffffffffffffffffffffff'.decode('hex')

        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
        s.settimeout(5)
        s.sendto(helobytes, ('<broadcast>', 54321))
        result={}
        while True:
            try:
                data, addr = s.recvfrom(1024)
                m = Message.parse(data)
                # logging.debug("Got a response: %s" % m)
                logging.info("  IP %s: %s - token: %s" % (addr[0],
                                                          m.header.value.devtype,
                                                          codecs.encode(m.checksum, 'hex')))
                result[addr[0]] = {'devtype' : m.header.value.devtype, 'serial' : m.header.value.serial, 'token' : codecs.encode(m.checksum, 'hex')}
            except Exception as ex:
                #logging.warning("error while reading discover results: %s", ex)
                return result

    def send(self, command, parameters=None):
        """Build and send the given command."""
        cmd = {
            "id": self._id,
            "method": command,
        }

        if parameters:
            cmd["params"] = parameters

        header = {'length': 0, 'unknown': 0x00000000,
                  'devtype': self.devtype	, 'serial': self.serial,
                  'ts': datetime.datetime.utcnow()}

        msg = {'data': {'value': cmd}, 'header': {'value': header}, 'checksum': 0}
        m = Message.build(msg)
        logging.debug("%s:%s >>: %s" % (self.ip, self.port, cmd))

        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.settimeout(self._timeout)

        try:
            s.sendto(m, (self.ip, self.port))
        except Exception as ex:
            logging.error("failed to send msg: %s" % ex)

        try:
            data, addr = s.recvfrom(1024)
            m = Message.parse(data)
            logging.debug("%s:%s (ts: %s) << %s" % (self.ip, self.port,
                                                    m.header.value.ts,
                                                    m.data.value))
            return m.data.value["result"]
        except Exception as ex:
            logging.error("got error when receiving: %s" % ex)
            raise

    def start(self):
        return self.send("app_start")

    def stop(self):
        return self.send("app_stop")

    def spot(self):
        return self.send("app_spot")

    def pause(self):
        return self.send("app_pause")

    def home(self):
        self.send("app_stop")
        return self.send("app_charge")

    def status(self):
        return VacuumStatus(self.send("get_status")[0]).json_result()

    def log_upload_status(self):
        # {"result": [{"log_upload_status": 7}], "id": 1}
        return self.send("get_log_upload_status")

    def consumable_status(self):
        return ConsumableStatus(self.send("get_consumable")[0]).json_result()

    def map(self):
        # returns ['retry'] without internet
        self.send("get_map_v1")
        return self.send("get_map_v1")

    def clean_history(self):
        return CleaningSummary(self.send("get_clean_summary"))

    def clean_details(self, id_):
        details = self.send("get_clean_record", [id_])

        res = list()
        for rec in details:
            res.append(CleaningDetails(rec))

        return res

    def find(self):
        return self.send("find_me", [""])

    def timer(self):
        timers = list()
        for rec in self.send("get_timer", [""]):
            timers.append(Timer(rec))

        return timers

    def set_timer(self, details):
        # how to create timers/change values?
        # ['ts', 'on'] to enable
        raise NotImplementedError()
        return self.send("upd_timer", ["ts", "on"])

    def dnd_status(self):
        # {'result': [{'enabled': 1, 'start_minute': 0, 'end_minute': 0,
        #  'start_hour': 22, 'end_hour': 8}], 'id': 1}
        return self.send("get_dnd_timer")

    def set_dnd(self, start_hr, start_min, end_hr, end_min):
        return self.send("set_dnd_timer", [start_hr, start_min, end_hr, end_min])

    def disable_dnd(self):
        return self.send("close_dnd_timer", [""])

    def set_fan_speed(self, speed):
        # speed = [38, 60 or 77]
        return self.send("set_custom_mode", [speed])

    def fan_speed(self):
        return self.send("get_custom_mode")[0]

    def raw_command(self, cmd, params):
        return self.send(cmd, params)
