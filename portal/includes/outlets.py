import serial
import time
import sys


ser = serial.Serial('/dev/ttyACM0', 2000000, timeout=5)
#time.sleep(2) // needed for uno

ser.write("input_send_rf|" + sys.argv[1] + "|" + sys.argv[2] + "|" +sys.argv[3])

print ser.readline()
