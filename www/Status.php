<!-- check if our login_user is set, otherwise redirect to the logon screen -->
<?php //include('logincheck.php');?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/Site-Template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Raspberry IP Camera">
    <meta name="author" content="Ronny Van den Broeck">
    <link rel="icon" href="Images/RaspberryIPCamera-Favicon.jpg">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>Raspberry IP Camera</title>
    <!-- InstanceEndEditable -->
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/Status.css" rel="stylesheet" type="text/css">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet" type="text/css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<!-- InstanceBeginEditable name="head" -->
    <!-- InstanceEndEditable -->
</head>

<body>
  <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-container">
              <span class="sr-only">Show and hide the navigation.</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>                                
            </button>
            <a href="Status.php" class="pull-left">
              <img src="Images/IP-cam-icon-w110-flip.png" alt="" width="57" height="50" />
             </a>
          <p class="navbar-brand">Raspberry IP Camera</p>
        </div>
        <div class="collapse navbar-collapse" id="navbar-container">
          <ul class="nav navbar-nav">
			  <!-- InstanceBeginEditable name="navbar" -->
              <li class="active"><a href="Status.php">Status</a></li>
              <li><a href="network-settings.php">System Settings</a></li>
              <li><a href="camera-settings.php">Camera Settings</a></li>
			  <!-- InstanceEndEditable -->
              <li><a href="logout.php">Log Off</a></li>
          </ul>
        </div>
      </div>
  </nav>

  <!-- InstanceBeginEditable name="body" -->
  	<?php date_default_timezone_set(trim(file_get_contents("/etc/timezone"),"\n"));?>
    <?php $configsettings = parse_ini_file("/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");?>

    <div class="container">
      <div class="row">
          <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Camera View</h4></div><!--end panel heading-->
          
          <div class="panel-body">
            <img class="img-responsive img-rounded center-block" src="http://<?php echo shell_exec("ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://'");?>:8080/?action=stream" alt=""/>
          </div><!--end panel-body-->
          </div><!--end panel-->
      </div>  <!-- /row -->
      <div class="row">
          <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">General Settings</h4></div><!--end panel heading-->
          <div class="panel-body">
            	<div class="col-sm-6"><p class="text-center bg-info">Current Timezone:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . date_default_timezone_get();?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Current Date/Time:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . date('d/m/Y - H:i:s');?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Software Version:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . $configsettings['SoftwareVersion'];?></p></div>
          </div><!--end panel-body-->
          </div><!--end panel-->
      </div>  <!-- /row -->
      <div class="row">
          <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Network Settings</h4></div><!--end panel heading-->
          <div class="panel-body">
            	<div class="col-sm-6"><p class="text-center bg-info">IP Address Assignment:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . $configsettings['IPAssignment'];?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Cable Connection Status:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("cat /sys/class/net/eth0/operstate");?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Mac Address:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("cat /sys/class/net/eth0/address");?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">IP Address:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://'");?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Subnet Mask:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("ifconfig eth0 | awk '/Mask:/{ print $4;} ' | sed 's/Mask://'");?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Default Gateway:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("ip route | awk '/default/ { print $3 }'");?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Primary DNS Server:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("cat /etc/resolv.conf | awk -v n=1 '/^nameserver/{l++} (l==n){print}' | sed -e 's/nameserver //g'");?></p></div>
            	<div class="col-sm-6"><p class="text-center bg-info">Secondary DNS Server:</p></div>
            	<div class="col-sm-6"><p class="text-center bg-info"><?php echo "&nbsp;" . shell_exec("cat /etc/resolv.conf | awk -v n=2 '/^nameserver/{l++} (l==n){print}' | sed -e 's/nameserver //g'");?></p></div>
          </div><!--end panel-body-->
          </div><!--end panel-->
      </div>  <!-- /row -->
    </div> <!-- /container -->
  <!-- InstanceEndEditable -->

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>
  <!--
      Bootstrap javascript and JQuery should be loaded
      Placed at the end of the document for faster load times
  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <!-- InstanceBeginEditable name="php code" -->
  <!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>