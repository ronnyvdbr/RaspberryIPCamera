<?php 
##################################
# uv4l core options
##################################
  $cameradefaultsettings = array(
	"driver" => "raspicam",
	"video_nr" => "0",
	"auto-video_nr" => "true",
	"verbosity" => "6",
	"syslog-host" => "localhost",
	"syslog-port" => "514",
	"frame-timeout" => "5000",
	"frame-buffers" => "4",
	"drop-bad-frames" => "true",
	"relaxed-ownership" => "true",
##################################
# raspicam driver options
##################################
	"encoding" => "mjpeg",
	"width" => "1920",
	"height" => "1080",
	"framerate" => "30",
#   "custom-sensor-config" => "2",
### dual camera options:
#	"stereoscopic-mode" => "side_by_side",
#	"camera-number" => "1",
#	"decimate" => "true",
#	"swap-eye" => "true",
### still and/or video options:
	"quality" => "85",
	"stills-denoise" => "false",
	"video-denoise" => "false",
	"raw" => "false",
### h264 options:
	"profile" => "high",
	"bitrate" => "17000000",
	"intra-refresh-mode" => "dummy",
# intra-period = #arg
# inline-headers = true
# quantisation-parameter #arg
### video overlay options:
	"nopreview" => "true",
	"fullscreen" => "false", 
	"opacity" => "255",
### preview window <x, y, w, h>:
#	"preview" => "0,0,1920,1080",
### post-processing options:
	"text-overlay" => "false",
    "text-filename" => "/usr/share/uv4l/raspicam/text.json",
	"object-detection" => "false",
# object-detection-mode = accurate_tracking
# min-object-size = 80
# min-object-size = 80
# main-classifier = /usr/share/uv4l/raspicam/lbpcascade_frontalface.xml
# secondary-classifier =/usr/share/uv4l/raspicam/lbpcascade_frontalface.xml
### image settings options:
	"sharpness" => "0",
	"contrast" => "0",
	"brightness" => "50",
	"saturation" => "0",
	"iso" => "400",
	"vstab" => "false",
	"ev" => "0",
	"exposure" => "auto",
	"awb" => "auto",
#	"imgfx" => "none",
	"metering" => "average",
	"rotation" => "0",
	"hflip" => "false",
	"vflip" => "false",
	"shutter-speed" => "0",
	"drc" => "off",
	"red-gain" => "100",
	"blue-gain" => "100");
#	"text-annotation" => "HelloWorld!"
#	"text-annotation-background" = "true"
### ROI <x, y, w, h> normalized to [0, 1]
# roi = 0
# roi = 0
# roi = 1
# roi = 1

### advanced options:
# statistics = true
# output-buffers = 3

### serial Number & License Key:
# serial-number = #arg
# license-key = #arg


#################################
# streaming server options
#################################

### path to another config file parsed by the streaming server directly
### in which you are allowed to specify all the streaming server options
### listed below in the short form "option=value" instead of the longer
### "--server-option = --option=value" form that you must use
### in this configuration file.
#server-config-file = #path

#	"server-option" => "--port=8081",
# server-option = --user-password=myp4ssw0rd
# server-option = --admin-password=myp4ssw0rd
### To enable 'config' user authentication
# server-option = --config-password=myp4ssw0rd
# md5-passwords = md5

### HTTPS options:
# server-option = --use-ssl=no
# server-option = --ssl-private-key-file=#path
# server-option = --ssl-certificate-file=#path

### WebRTC options:
# server-option = --enable-webrtc=true
# server-option = --enable-webrtc-video=true
# server-option = --enable-webrtc-audio=true
# server-option = --webrtc-receive-video=true
### video rendering window size on display
### all four lines below one for each (x, y, width, height)
### fullscreen should be disabled if you want to set the size of the window:
# server-option = --webrtc-renderer-window=320
# server-option = --webrtc-renderer-window=0
# server-option = --webrtc-renderer-window=480
# server-option = --webrtc-renderer-window=352
# server-option = --webrtc-renderer-fullscreen=no
# server-option = --webrtc-renderer-rotation=180
# server-option = --webrtc-renderer-opacity=255
# server-option = --webrtc-receive-audio=true
# server-option = --webrtc-received-audio-volume=5.0
# server-option = --webrtc-vad=true
# server-option = --webrtc-preferred-vcodec=0
# server-option = --webrtc-enable-hw-codec=true
# server-option = --webrtc-hw-vcodec-minbitrate=2000
# server-option = --webrtc-cpu-overuse-detection=no
# server-option = --webrtc-combined-audiovideo-bwe=no
# server-option = --webrtc-stun-urls=stun:stun.l.google.com:19302
# server-option = --webrtc-ice-servers=[{"urls": "stun:stun1.example.net"}, {"urls": "turn:turn.example.org", "username": "user", "credential": "myPassword"}]
# server-option = --webrtc-stun-server=true
# server-option = --webrtc-tcp-candidate-policy=true
# server-option = --webrtc-ignore-loopback=true

### XMPP options:
# server-option = --xmpp-server=lambada.jitsi.net
# server-option = --xmpp-port=5222
# server-option = --xmpp-muc-domain=meet.jit.si
# server-option = --xmpp-room=room
# server-option = --xmpp-room-password=room_password
# server-option = --xmpp-username=me
# server-option = --xmpp-password=mypassword
# server-option = --xmpp-reconnect=true
# server-option = --xmpp-bosh-enable
# server-option = --xmpp-bosh-tls
# server-option = --xmpp-bosh-server
# server-option = --xmpp-bosh-port
# server-option = --xmpp-bosh-hostname
# server-option = --xmpp-bosh-path
# server-option = --xmpp-bridge-host=localhost
# server-option = --xmpp-bridge-port=7999

### Fine-tuning options:
##	"server-option" => "--connection-timeout=15",
#	"server-option" => "--enable-keepalive=true",
#	"server-option" => "--max-keepalive-requests=0",
#	"server-option" => "--keepalive-timeout=7",
#	"server-option" => "--max-queued-connections=8",
#	"server-option" => "--max-streams=3",
#	"server-option" => "--max-threads=5",
#	"server-option" => "--thread-idle-time=10",
#	"server-option" => "--chuncked-transfer-encoding=true",

### Advanced options:
#"server-option" => "--frame-timeout=5000",
#"server-option" => "--frame-buffers=auto");

### Other options:
# server-option = --editable-config-file=#path
# server-option = --enable-control-panel=true