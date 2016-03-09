<?php
$passwordsettings = parse_ini_file("/home/pi/RaspberryIPCamera/secret/RaspberryIPCamera.secret");
session_start();// Starting Session


if(!$_SESSION['login_user'] == $passwordsettings['AdminUsername']){
echo "<script type='text/javascript'> document.location = 'Login.php'; </script>"; // Redirecting To Login Page
}
?>
