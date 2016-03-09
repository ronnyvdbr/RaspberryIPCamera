<?php session_start(); // Starting Session ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Raspberry IP Camera">
    <meta name="author" content="Ronny Van den Broeck">
    <link rel="icon" href="Images/RaspberryIPCamera-Favicon.jpg">

    <title>Raspberry IP Camera</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet" type="text/css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<?php

$passwordsettings = parse_ini_file("/home/pi/RaspberryIPCamera/secret/RaspberryIPCamera.secret");




$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
	  $error = "Username or Password is invalid";
	}
	else {
	  // Define $username and $password
	  
	  if($_POST['username'] == $passwordsettings['AdminUsername'] && password_verify($_POST['password'], $passwordsettings['AdminPassword'])) {

		$_SESSION['login_user']=$_POST['username']; // Initializing Session
		echo "<script type='text/javascript'> document.location = 'Status.php'; </script>"; // Redirecting To Other Page
	  } 
	  else {
		$error = "Username or Password is invalid";
	  }
	}
}


if(isset($_SESSION['login_user'])){
echo "<script type='text/javascript'> document.location = 'Status.php'; </script>";
}
?>

    <div class="container">

      <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="application/x-www-form-urlencoded" class="form-signin" id="frm-login">
        <h2 class="form-signin-heading"><img class="center-block" src="Images/IP-cam-icon-w110-flip.png" width="110" height="96"  alt=""/></h2>
        <h2 class="form-signin-heading">Raspberry IP Camera</h2>
        <p>Log in to get access to configuration settings.</p>
        <label for="username" class="sr-only">Username</label>
        <input name="username" type="text" autofocus required class="form-control" id="user-name" form="frm-login" placeholder="Username">
        <label for="password" class="sr-only">Password</label>
        <input name="password" type="password" required class="form-control" id="pass-word" form="frm-login" placeholder="Password">
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <input name="submit" type="submit" class="btn btn-lg btn-primary btn-block" id="submit" form="frm-login" value="Log In"><br>
        <span style="color:red"><?php echo $error; ?></span>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  

</body></html>