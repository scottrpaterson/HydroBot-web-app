import serial
import time



ser = serial.Serial('/dev/ttyACM0', 2000000, timeout=10)

ser.write('input_temp')

print ser.readline()