<?php
$configurationsettings = parse_ini_file("/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
session_start();// Starting Session


if(!$_SESSION['login_user'] == $configurationsettings['AdminUsername']){
echo "<script type='text/javascript'> document.location = 'Login.php'; </script>"; // Redirecting To Login Page
}
?>
