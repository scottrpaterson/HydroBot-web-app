import os
import fileinput
import subprocess

os.system('sudo rm -f /etc/wpa_supplicant/wpa_supplicant.conf')
os.system('sudo rm -f /home/pi/Projects/RaspiWifi/tmp/*')
os.system('sudo rm /etc/cron.raspiwifi/apclient_bootstrapper')
os.system('sudo cp /usr/lib/raspiwifi/reset_device/static_files/aphost_bootstrapper /etc/cron.raspiwifi/')
os.system('sudo chmod +x /etc/cron.raspiwifi/aphost_bootstrapper')
os.system('sudo mv /etc/dhcpcd.conf /etc/dhcpcd.conf.original')
os.system('sudo cp /usr/lib/raspiwifi/reset_device/static_files/dhcpcd.conf /etc/')
os.system('sudo mv /etc/dnsmasq.conf /etc/dnsmasq.conf.original')
os.system('sudo cp /usr/lib/raspiwifi/reset_device/static_files/dnsmasq.conf /etc/')
os.system('sudo cp /usr/lib/raspiwifi/reset_device/static_files/dhcpcd.conf /etc/')
os.system('sudo touch /etc/raspiwifi/host_mode')