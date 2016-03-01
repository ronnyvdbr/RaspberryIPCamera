# ok, let's get started.
# To begin with, start by downloading a Raspbian Jessie Lite image from the Raspbian foundation website, write to sd card, boot it up.
# Ssh into it with username pi and password raspberry

# Bring the OS up to date: --note: make sure to reboot if the upgrade included kernel updates
sudo apt-get update && sudo apt-get -y upgrade

# Install git since we'll be pulling this from git repo.
sudo apt-get -y install git

# Clone our git repository.
git clone https://github.com/ronnyvdbr/RaspberryIPCamera.git

# add our pi user to www-data group.
sudo usermod -a -G www-data pi

# checking if apache exists, if not, deploy ngingx.
if [ $(dpkg-query -W -f='${Status}' apache apache2 2>/dev/null | grep -c "ok installed") -eq 0 ];
then
  echo No apache found, installing nginx;
  # Install our webserver with PHP support.
  sudo apt-get -y install nginx
  # Install php5 fast process manager.
  sudo apt-get -y install php5-fpm
  # Disable the nginx default website
  sudo rm /etc/nginx/sites-enabled/default
  # Copy our own website config file to the nginx available website configurations
  sudo cp RaspberryIPCamera/DefaultConfigFiles/RaspberryIPCamera.Nginx.Siteconf /etc/nginx/sites-available/RaspberryIPCamera.Nginx.Siteconf
  # Let's enable our new website:
  sudo ln -s /etc/nginx/sites-available/RaspberryIPCamera.Nginx.Siteconf /etc/nginx/sites-enabled/RaspberryIPCamera.Nginx.Siteconf
  # Restart our web server to pick up the new config.
  sudo systemctl restart nginx.service
fi

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

sudo chgrp www-data /etc/uv4l/uv4l-raspicam.conf
sudo chmod 664 /etc/uv4l/uv4l-raspicam.conf

# Put system service file for RTSP server into place
sudo cp /home/pi/RaspberryIPCamera/DefaultConfigFiles/RTSP-Server.service /etc/systemd/system/RTSP-Server.service
sudo systemctl daemon-reload
sudo systemctl disable RTSP-Server.service

# Put correct security rights on configuration files
sudo chgrp www-data /home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini
sudo chmod 664 /home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini

sudo chgrp www-data /etc/timezone
sudo chmod 664 /etc/timezone

sudo chgrp www-data /etc/ntp.conf
sudo chmod 664 /etc/ntp.conf


git clone https://github.com/mpromonet/h264_v4l2_rtspserver.git
sudo apt-get -y install cmake
sudo apt-get -y install liblivemedia-dev libv4l-dev liblog4cpp5-dev
cd h264_v4l2_rtspserver
cmake . && make
sudo make install

sudo reboot

## DEPRECATED
## install mjpeg streamer
# first install dependencies
#sudo apt-get install libv4l-dev
#sudo apt-get install libjpeg-dev
#git clone https://github.com/ronnyvdbr/mjpg-streamer.git
#cd ~/mjpg-streamer/mjpg-streamer
##make USE_LIBV4L2=true clean all







