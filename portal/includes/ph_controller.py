import serial
import time
import sys


ser = serial.Serial('/dev/ttyACM0', 2000000, timeout=5)
#time.sleep(2)


#input_motor_ph_up or input_motor_ph_down | delay in ms | speed, where 255 is max

#ex: python2 ph_controller.py "input_motor_ph_up" "1000" "255"

ser.write(sys.argv[1] + "|" + sys.argv[2] + "|" +sys.argv[3])

print ser.readline()