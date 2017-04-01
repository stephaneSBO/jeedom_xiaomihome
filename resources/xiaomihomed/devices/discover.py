import logging
from mirobo.vacuum import Vacuum
FORMAT = '[%(asctime)-15s][%(levelname)s] : %(message)s'
logging.basicConfig(level=logging.DEBUG,format=FORMAT, datefmt="%Y-%m-%d %H:%M:%S")
vacuum=Vacuum()
result = vacuum.discover()
