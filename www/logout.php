<?php
session_start();
session_unset(); 
session_destroy(); 
echo "<script type='text/javascript'> document.location = 'Login.php'; </script>";
?>
