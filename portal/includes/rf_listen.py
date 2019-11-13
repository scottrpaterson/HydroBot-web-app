import serial
import time
ser = serial.Serial('/dev/ttyACM0', 2000000, timeout=5)
#time.sleep(2)

ser.write('input_read_rf')

print ser.readline()
