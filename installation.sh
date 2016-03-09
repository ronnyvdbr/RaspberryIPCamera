# ok, let's get started.
# To begin with, start by downloading a Raspbian Jessie Lite image from the Raspbian foundation website, write to sd card, boot it up.
# Ssh into it with username pi and password raspberry


# Make your partition a bit larger so it will fit this install
sudo parted /dev/mmcblk0 resizepart 2 1600
sudo resize2fs /dev/mmcblk0p2


# Bring the OS up to date
sudo apt-get update && sudo apt-get -y upgrade
# Reboot to make sure you start with the latest updates after update
sudo reboot


# Log back in, download and install the RaspberryIPCamera git repository
wget -O /home/pi/RaspberryIPCamera.zip https://github.com/ronnyvdbr/RaspberryIPCamera/archive/master.zip
unzip /home/pi/RaspberryIPCamera.zip -d /home/pi
mv /home/pi/RaspberryIPCamera-master /home/pi/RaspberryIPCamera
rm /home/pi/RaspberryIPCamera.zip

# Set permissions for the config files
sudo chgrp www-data /home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini
chmod 664 /home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini
sudo chgrp www-data /home/pi/RaspberryIPCamera/secret/RaspberryIPCamera.secret
chmod 664 /home/pi/RaspberryIPCamera/secret/RaspberryIPCamera.secret


# add our pi user to www-data group.
sudo usermod -a -G www-data pi


# Install our webserver with PHP support.
sudo apt-get -y install nginx
# Install php5 fast process manager.
sudo apt-get -y install php5-fpm
# Disable the nginx default website
sudo rm /etc/nginx/sites-enabled/default
# Copy our own website config file to the nginx available website configurations
sudo cp /home/pi/RaspberryIPCamera/DefaultConfigFiles/RaspberryIPCamera.Nginx.Siteconf /etc/nginx/sites-available/RaspberryIPCamera.Nginx.Siteconf
# Let's enable our new website:
sudo ln -s /etc/nginx/sites-available/RaspberryIPCamera.Nginx.Siteconf /etc/nginx/sites-enabled/RaspberryIPCamera.Nginx.Siteconf


# Enable the Raspberry Pi Camera Module
sudo mount -o remount rw /boot
echo "start_x=1" | sudo tee -a /boot/config.txt
echo "gpu_mem=256" | sudo tee -a /boot/config.txt
echo "disable_camera_led=1" | sudo tee -a /boot/config.txt

# put a sudoers file in the correct location for php shell commands integration
sudo cp /home/pi/RaspberryIPCamera/DefaultConfigFiles/sudoers_commands /etc/sudoers.d/sudoers_commands



# Install UV4L software
curl http://www.linux-projects.org/listing/uv4l_repo/lrkey.asc | sudo apt-key add -
echo "deb http://www.linux-projects.org/listing/uv4l_repo/raspbian/ wheezy main" | sudo tee -a /etc/apt/sources.list
sudo apt-get update
sudo apt-get -y install uv4l uv4l-raspicam
sudo apt-get -y install uv4l-raspicam-extras
sudo apt-get -y install uv4l-uvc
sudo apt-get -y install uv4l-server
sudo cp /home/pi/RaspberryIPCamera/DefaultConfigFiles/uv4l-raspicam.conf /etc/uv4l/uv4l-raspicam.conf
sudo cp /home/pi/RaspberryIPCamera/DefaultConfigFiles/uv4l-server.conf /etc/uv4l/uv4l-server.conf
sudo sed -i "s/--editable-config-file=\$CONFIGFILE/--editable-config-file=\/etc\/uv4l\/uv4l-server.conf/g" /etc/init.d/uv4l_raspicam
sudo systemctl daemon-reload
# Set some permissions so our install can modify the config files
sudo chgrp www-data /etc/uv4l/uv4l-raspicam.conf
sudo chmod 664 /etc/uv4l/uv4l-raspicam.conf


# Put system service file for RTSP server into place
sudo cp /home/pi/RaspberryIPCamera/DefaultConfigFiles/RTSP-Server.service /etc/systemd/system/RTSP-Server.service
sudo systemctl daemon-reload
sudo systemctl disable RTSP-Server.service


# Put correct security rights on configuration files
sudo chgrp www-data /etc/timezone
sudo chmod 664 /etc/timezone
sudo chgrp www-data /etc/ntp.conf
sudo chmod 664 /etc/ntp.conf


# Install requirements for the RTSP Server
wget -O /home/pi/h264-v4l2-rtspserver_20160306-1_armhf.deb https://dl.dropboxusercontent.com/s/1nkuoaemreesu4g/h264-v4l2-rtspserver_20160306-1_armhf_2.deb?dl=0
wget -O /home/pi/live555_20160306-1_armhf.deb https://dl.dropboxusercontent.com/s/k7uncvqeugd9gpv/live555_20160306-1_armhf.deb?dl=0
sudo dpkg -i /home/pi/h264-v4l2-rtspserver_20160306-1_armhf.deb
sudo dpkg -i /home/pi/live555_20160306-1_armhf.deb
rm /home/pi/h264-v4l2-rtspserver_20160306-1_armhf.deb
rm /home/pi/live555_20160306-1_armhf.deb


## backup genomen op desktop - RaspberryIPCamera-v1.5-beta.img


sudo reboot
