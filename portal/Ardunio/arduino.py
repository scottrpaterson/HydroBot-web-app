import serial
import time
ser = serial.Serial('/dev/ttyACM0', 2000000, timeout=5)
time.sleep(2)




#ser.write('input_send_rf|4486403|24|170')
#ser.write('input_send_rf|4486412|24|170')
#ser.write('input_read_rf')
ser.write('input_temp')





print ser.readline()
