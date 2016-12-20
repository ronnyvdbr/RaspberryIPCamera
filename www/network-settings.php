<!-- check if our login_user is set, otherwise redirect to the logon screen -->
<?php include('logincheck.php');?>
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
    <link href="css/background.css" rel="stylesheet" type="text/css">
	<!-- InstanceBeginEditable name="head" -->
    <?php include 'functions.php';?>
	<?php logmessage("Loading page network-settings.php");?>
	<!-- InstanceEndEditable -->
</head>

<body id="even-stops">
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
              <li><a href="Status.php">Status</a></li>
              <li class="active"><a href="network-settings.php">System Settings</a></li>
              <li><a href="camera-settings.php">Camera Settings</a></li>
			  <!-- InstanceEndEditable -->
              <li><a href="logout.php">Log Off</a></li>
          </ul>
        </div>
      </div>
  </nav>

  <!-- InstanceBeginEditable name="body" -->
 <!-- ********************************************************************************************************************** -->
  <?php date_default_timezone_set(trim(file_get_contents("/etc/timezone"),"\n"));
  		$configurationsettings = parse_ini_file("/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
		$passwordsettings = parse_ini_file("/home/pi/RaspberryIPCamera/secret/RaspberryIPCamera.secret");

  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timezone-apply'])) {
	shell_exec("sudo mount -o rw,remount,rw /");
	file_put_contents("/etc/timezone", $_POST["sel-timezone"]);
	shell_exec("sudo mount -o ro,remount,ro /");
	}
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timesync-apply'])) {
	$timeserver = "";
	$timeservererr = "";
	
	// uitlezen en valideren van formulier
	if (!empty($_POST["txt-timeserver"])) {
		$timeserver = test_input($_POST["txt-timeserver"]);
		if (!preg_match("/^[0-9a-zA-Z.]*$/",$timeserver)) {
		  $timeservererr = "Time Server: Only letters, numbers and . allowed!<br />"; 
		  logmessage($timeservererr);
		}
	  }
	
	// if the form was validated ok execute the rest
	if (empty($timeservererr)) {
		if 	(array_key_exists ("timesync_checkbox" , $_POST))
		  $configurationsettings['ntpclient'] = "enabled";
		else
		  $configurationsettings['ntpclient'] = "disabled";
		$configurationsettings['TimeServer'] = $timeserver;
		$splice = 0;
		$insertservers = array();
		$strconfigfile = "/etc/ntp.conf";
		$arrconfigfilecontents = file($strconfigfile);
		$arrconfigfilefiltered = preg_grep("/^server/",$arrconfigfilecontents);
		$splice = count($arrconfigfilefiltered);
		array_splice($arrconfigfilecontents,20,$splice,$insertservers);
		if(!empty($timeserver)) {
			array_push($insertservers,"server $timeserver iburst\n");
		}
		array_splice($arrconfigfilecontents,20,0,$insertservers);
		shell_exec("sudo mount -o rw,remount,rw /");
		file_put_contents("/etc/ntp.conf", implode($arrconfigfilecontents));
		write_php_ini($configurationsettings, "/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
		shell_exec("sudo mount -o ro,remount,ro /");
	}
  }
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	$strconfigfile = "/etc/ntp.conf";
	$arrconfigfilecontents = file($strconfigfile);
	$arrconfigfilefiltered = preg_grep("/^server/",$arrconfigfilecontents);
	$arrconfigfilefiltered = (str_replace("server " , "" , $arrconfigfilefiltered));
	$arrconfigfilefiltered = (str_replace("iburst" , "" , $arrconfigfilefiltered));
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-network-apply'])) {

	  $IPAssignment = $ipaddress = $subnetmask = $defaultgateway = $primarydns = $secondarydns = "";
	  $ipaddresserr = $subnetmaskerr = $defaultgatewayerr = $primarydnserr = $secondarydnserr = "";
	  
	  $IPAssignment = test_input($_POST["ip-assignment-select"]);

	  if (!empty($_POST["ip-address"])) {
		$ipaddress = test_input($_POST["ip-address"]);
		if (!preg_match("/^[0-9.]*$/",$ipaddress)) {
		  $ipaddresserr = "ipaddress field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		  logmessage($ipaddresserr);
		}
	  }
	  if (!empty($_POST["network-mask"])) {
		$subnetmask = test_input($_POST["network-mask"]);
		if (!preg_match("/^[0-9.]*$/",$subnetmask)) {
		  $subnetmaskerr = "subnetmask field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		  logmessage($subnetmaskerr);
		  
		}
	  }
	  if (!empty($_POST["gateway"])) {
		$defaultgateway = test_input($_POST["gateway"]);
		if (!preg_match("/^[0-9.]*$/",$defaultgateway)) {
		  $defaultgatewayerr = "defaultgateway field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		  logmessage($defaultgatewayerr);
		}
	  }
	  if (!empty($_POST["dns1"])) {
		$primarydns = test_input($_POST["dns1"]);
		if (!preg_match("/^[a-zA-Z0-9.]*$/",$primarydns)) {
		  $primarydnserr = "primarydns field contains incorrect data, only a-zA-Z0-9_- allowed!<br />";
		  logmessage($primarydnserr);
		}
	  }
	  if (!empty($_POST["dns2"])) {
		$secondarydns = test_input($_POST["dns2"]);
		if (!preg_match("/^[a-zA-Z0-9.]*$/",$secondarydns)) {
		  $secondarydnserr = "secondarydns field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		  logmessage($secondarydnserr);
		}
	  }
	  if(empty($ipaddresserr) && empty($subnetmaskerr) && empty($defaultgatewayerr) && empty($primarydnserr) && empty($secondarydnserr)) {
		
		$configurationsettings['IPAssignment'] = $IPAssignment;
		
		$configurationsettings['IPAddress'] = $ipaddress;
		$configurationsettings['NetworkMask'] = $subnetmask;

		if(!empty($defaultgateway)) 
			$configurationsettings['Gateway'] = $defaultgateway;
		else
			$configurationsettings['Gateway'] = "";

		if(!empty($primarydns)) 
			$configurationsettings['Dns1'] = $primarydns;
		else
			$configurationsettings['Dns1'] = "";

		if(!empty($secondarydns)) 
			$configurationsettings['Dns2'] = $secondarydns;
		else
			$configurationsettings['Dns2'] = "";
		shell_exec("sudo mount -o rw,remount,rw /");
		write_php_ini($configurationsettings, "/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
		shell_exec("sudo mount -o ro,remount,ro /");

	  }
	}
	     ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-wifi-apply'])) {

		$wifi = $ssid = $securitymode = $password = "";
		$ssiderror = $securitymodeerr = "";
		
		if(isset($_POST['chk-enable-wifi'])) {
			$wifi = "enabled";
		}
	  
		if (!empty($_POST["ssid"])) {
		  $ssid = test_input_no_trim($_POST["ssid"]);
		  if (!preg_match("/^[a-zA-Z0-9_\-\s]*$/",$ssid)) {
			  $ssiderror = "ssid field contains incorrect data, only a-zA-Z0-9 _ - allowed!<br />"; 
			  logmessage($ssiderror);
		  }
		}
		
		if (!empty($_POST["wifi-security"])) {
		  $securitymode = test_input($_POST["wifi-security"]);
		  if (!strcmp($securitymode, "None") && !strcmp($securitymode, "WEP") && !strcmp($securitymode, "WPA/WPA2 PSK")) {
			  $securitymodeerr = "Incorrect input received for Operation Mode!";
			  logmessage($securitymodeerr);
		  }
		}
		
		if (!empty($_POST["wifi-password"])) {
		  $password = $_POST["wifi-password"];
		}

	  // only apply actions when no form errors are present
	  if(empty($ssiderror) && empty($securitymodeerr)) {
		  switch($wifi) {
			  case "":
				  $configurationsettings['WifiClient'] = "disabled";
			  break;
			  case "enabled":
				  $configurationsettings['WifiClient'] = "enabled";
			  break;
		  }
  		  $configurationsettings['WifiSsid'] = $ssid;
		  switch($securitymode) {
			  case "None":
				  $configurationsettings['WifiSecurityMode'] = "None";
			  break;
			  case "WEP":
				  $configurationsettings['WifiSecurityMode'] = "WEP";
			  break;
			  case "WPA/WPA2 PSK":
				  $configurationsettings['WifiSecurityMode'] = "WPA/WPA2 PSK";
			  break;
		  }
  		  $configurationsettings['WifiPassword'] = $password;
	      
		  logmessage("Writing Wifi Settings to configuration file: /home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
		  shell_exec("sudo mount -o rw,remount,rw /");
		  write_php_ini($configurationsettings, "/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
		  shell_exec("sudo mount -o ro,remount,ro /");
	  }
	}
?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-password-apply'])) {
		$passworderror = "";
		
		if(!password_verify($_POST['currentpassword'], $passwordsettings['AdminPassword'])) {
			$passworderror = "Current password is incorrect!";
		}
		elseif(!$_POST['newpassword'] == $_POST['passwordrepeat']) {
			$passworderror = "New passwords don't match";
		}
		else {
			$passwordsettings['AdminPassword'] = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
			shell_exec("sudo mount -o rw,remount,rw /");
			write_php_ini($passwordsettings, "/home/pi/RaspberryIPCamera/secret/RaspberryIPCamera.secret");
			shell_exec("sudo mount -o ro,remount,ro /");
			echo "<script type='text/javascript'> document.location = 'logout.php'; </script>"; // Redirecting To Login Page
		}
	}
  ?>

<!-- ********************************************************************************************************************** -->
 <div class="container">
 
       <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Network Configuration</h4></div><!--end panel heading-->
        <div class="panel-body">
        
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-ipconfig" role="form">
              <div class="form-group">
                <label class="control-label col-sm-4" for="ip-assignment-select">IP Assignment:</label>
                  <div class="col-sm-5">
                    <select name="ip-assignment-select" class="form-control" id="ip-assignment-select" form="frm-ipconfig">
                      <option value="DHCP" <?php if($configurationsettings['IPAssignment'] == "DHCP") {echo "selected='selected'";}?>>DHCP</option>
                      <option value="STATIC" <?php if($configurationsettings['IPAssignment'] == "STATIC") {echo "selected='selected'";}?>>STATIC</option>
                    </select>
                  </div>
              </div><!--form group-->
  
              <!-- begin ip configuration -->
              <div id="ipconfig" <?php if($configurationsettings["IPAssignment"] == "DHCP") echo 'style="display:none"'; ?>>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="ip-address">IP Address:</label>
                  <div class="col-sm-5">
                    <input name="ip-address" type="text" autofocus required class="form-control" id="ip-address" form="frm-ipconfig" placeholder="192.168.0.1" pattern="^[0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['IPAddress'])) {echo "value=" . $configurationsettings['IPAddress'];}?>>
                  </div>
                </div><!--form group-->
                <div class="form-group">
                  <label class="control-label col-sm-4" for="network-mask">Network Mask:</label>
                  <div class="col-sm-5">
                    <input name="network-mask" type="text" required class="form-control" id="network-mask" form="frm-ipconfig" placeholder="255.255.255.0" pattern="^[0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['NetworkMask'])) {echo "value=" . $configurationsettings['NetworkMask'];}?>>
                  </div>
                </div><!--form group-->
                <div class="form-group">
                  <label class="control-label col-sm-4" for="gateway">Gateway:</label>
                  <div class="col-sm-5">
                    <input name="gateway" type="text" class="form-control" id="gateway" form="frm-ipconfig" placeholder="192.168.0.254" pattern="^[0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['Gateway'])) {echo "value=" . $configurationsettings['Gateway'];}?>>
                  </div>
                </div><!--form group-->
              
                <br />
                <h4 class="text-center">DNS Servers</h4>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="dns1">DNS Server 1:</label>
                  <div class="col-sm-5">
                    <input name="dns1" type="text" class="form-control" id="dns1" form="frm-ipconfig" placeholder="8.8.8.8" pattern="^[a-zA-Z0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['Dns1'])) {echo "value=" . $configurationsettings['Dns1'];}?>>
                  </div>
                </div><!--form group-->
                <div class="form-group">
                  <label class="control-label col-sm-4" for="dns2">DNS Server 2:</label>
                  <div class="col-sm-5">
                    <input name="dns2" type="text" class="form-control" id="dns2" form="frm-ipconfig" placeholder="8.8.4.4" pattern="^[a-zA-Z0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['Dns2'])) {echo "value=" . $configurationsettings['Dns2'];}?>>
                  </div>
                </div><!--form group-->
              </div><!-- end div ipconfig -->
              <div class="form-group"> 
                <div class="col-sm-offset-4 col-sm-8">
                  <input name="btn-network-apply" type="submit" class="btn btn-default" id="btn-network-apply" form="frm-ipconfig" value="Apply"></button>
                </div>
              </div><!--form group-->
            </form>
          </div><!-- col-sm-10 -->
        <div class="col-sm-1"></div>
      </div><!--end panel-body-->
      </div><!--end panel-->
    </div><!--row-->
          
          
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Wifi Configuration</h4></div><!--end panel heading-->
        <div class="panel-body">
      
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-wificonfig" role="form">
              <div class="form-group">
                <div class="checkbox col-sm-offset-4 col-sm-8">
                  <label><input name="chk-enable-wifi" id="chk-enable-wifi" type="checkbox" id="chk-enable-wifi" value="1" form="frm-wificonfig" <?php if ($configurationsettings['WifiClient'] == "enabled") {echo "checked";}?>>Enable Wifi</label>
                </div>
              </div><!--formgroup-->

              <div id="wificonfig" <?php if($configurationsettings["WifiClient"] == "disabled") echo 'style="display:none"'; ?>>
              
                <div class="form-group">
                  <label class="control-label col-sm-4" for="ssid">Connect to SSID:</label>
                  <div class="col-sm-5">
                    <input name="ssid" type="text" class="form-control" id="ssid" form="frm-wificonfig" pattern="^[a-zA-Z0-9_\-\s]*$" value="<?php echo($configurationsettings['WifiSsid']); ?>">
                  </div>
                </div><!--form group-->
               
                <div class="form-group">
                  <label class="control-label col-sm-4" for="wifi-security">Security mode:</label>
                    <div class="col-sm-5">
                      <select name="wifi-security" class="form-control" id="wifi-security" form="frm-wificonfig">
                        <option value="None" <?php if($configurationsettings['WifiSecurityMode'] == "None") {echo "selected='selected'";}?>>None</option>
                        <option value="WEP" <?php if($configurationsettings['WifiSecurityMode'] == "WEP") {echo "selected='selected'";}?>>WEP</option>
                        <option value="WPA/WPA2 PSK" <?php if($configurationsettings['WifiSecurityMode'] == "WPA/WPA2 PSK") {echo "selected='selected'";}?>>WPA/WPA2 PSK</option>
                      </select>
                    </div>
                </div><!--form group-->
                
                <div class="form-group">
                  <label class="control-label col-sm-4" for="wifi-password">Password:</label>
                  <div class="col-sm-5">
                    <input name="wifi-password" type="password" class="form-control" id="wifi-password" form="frm-wificonfig" <?php if(!empty($configurationsettings['WifiPassword'])) {echo "value=" . $configurationsettings['WifiPassword'];}?>>
                  </div>
                </div><!--form group-->
              
              </div><!-- end div wificonfig-->
              <div class="form-group"> 
                <div class="col-sm-offset-4 col-sm-8">
                  <input name="btn-wifi-apply" type="submit" class="btn btn-default" id="btn-wifi-apply" form="frm-wificonfig" value="Apply"></button>
                </div>
              </div><!--form group-->
            
            </form>
          </div><!--col-sm-10-->
        <div class="col-sm-1"></div>
      </div><!--end panel-body-->
      </div><!--end panel-->

    </div><!--row-->

       <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Password Configuration</h4></div><!--end panel heading-->
        <div class="panel-body">
        
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <form action="" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-password" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-4" for="currentpassword">Current Password:</label>
                  <div class="col-sm-5">
                    <input name="currentpassword" type="password" required class="form-control" id="currentpassword" form="frm-password" >
                  </div>
                </div><!--form group-->
                
                <div class="form-group">
                  <label class="control-label col-sm-4" for="newpassword">New Password:</label>
                  <div class="col-sm-5">
                    <input name="newpassword" type="password" required class="form-control" id="newpassword" form="frm-password" >
                  </div>
                </div><!--form group-->

                <div class="form-group">
                  <label class="control-label col-sm-4" for="passwordrepeat">Password Repeat:</label>
                  <div class="col-sm-5">
                    <input name="passwordrepeat" type="password" required class="form-control" id="passwordrepeat" form="frm-password" >
                  </div>
                </div><!--form group-->

              <div class="form-group"> 
                <div class="col-sm-offset-4 col-sm-8">
                  <input name="btn-password-apply" type="submit" class="btn btn-default" id="btn-password-apply" form="frm-password" value="Apply"></button>
                  <span style="color:red"><?php echo $passworderror; ?></span>
                </div>
              </div><!--form group-->
                
                     </form>
          </div><!--col-sm-10-->
        <div class="col-sm-1"></div>
      </div><!--end panel-body-->
      </div><!--end panel-->
    </div><!--row-->
   
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            

 
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Time Synchronisation</h4></div><!--end panel heading-->
        <div class="panel-body">

          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-timesync" role="form">
              <div class="form-group">
                <div class="checkbox col-sm-offset-4 col-sm-8">
                  <label><input name="timesync_checkbox" type="checkbox" id="timesync_checkbox" form="frm-timesync" value="on" <?php if ($configurationsettings['ntpclient'] == "enabled") {echo "checked";}?>>Enable time synchronisation</label>
                </div>
              </div><!--formgroup-->
              
              <div id="div-timesync" <?php if($configurationsettings["ntpclient"] == "disabled") echo 'style="display:none"'; ?>>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="timeserver">Time Server:</label>
                  <div class="col-sm-5">
                    <input name="txt-timeserver" type="text" class="form-control" id="timeserver" form="frm-timesync" placeholder="pool.ntp.org" pattern="^[0-9a-zA-Z.]*$" value="<?php echo rtrim(array_shift(array_slice($arrconfigfilefiltered, 0, 1)));?>" maxlength="40">
                  </div>
                </div><!--form group-->
              </div><!-- end div div-timesync -->
                <div class="form-group"> 
                  <div class="col-sm-offset-4 col-sm-8">
                    <input name="btn-timesync-apply" type="submit" class="btn btn-default" id="btn-timesync-apply" form="frm-timesync" value="Apply"></button>
                  </div>
                </div><!--form group-->
            </form>
          </div><!-- end div col-sm-10 -->
        <div class="col-sm-1"></div>
      </div><!--end panel-body-->
      </div><!--end panel-->
    </div><!-- end div row -->

      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="text-center">Timezone Configuration</h4></div><!--end panel heading-->
        <div class="panel-body">
      
          <div class="col-sm-1"></div>
          <div class="col-sm-10">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-timezone" role="form">
              <div class="form-group">
                <label class="control-label col-sm-4" for="sel-timezone">Set Timezone:</label>
                <div class="col-sm-5">
                  <select name="sel-timezone" class="form-control" id="sel-timezone" form="frm-timezone">
                    <?php
                    $timezones = timezone_identifiers_list();
                    $systemtimezone = trim(file_get_contents("/etc/timezone"),"\n");
                    
                    foreach($timezones as $timezone)
                    {
                      if (($systemtimezone == $timezone) || ($systemtimezone == "Etc/UTC")) {
                        echo '<option selected="selected" value="' . $timezone . '">' . $timezone . '</option>';
                      }
                      else {
                        echo '<option value="' . $timezone . '">' . $timezone . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group"> 
                <div class="col-sm-offset-4 col-sm-8">
                  <input name="btn-timezone-apply" type="submit" class="btn btn-default" id="btn-timezone-apply" form="frm-timezone" value="Apply"></button>
                </div>
              </div><!--form group-->
            </form>
          </div><!--col-sm-10-->
          <div class="col-sm-1"></div>
        </div><!--end panel-body-->
        </div><!--end panel-->
      </div><!--row-->
 
          
  </div><!--container-->
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
<!-- ********************************************************************************************************************** -->
<script>
$("#ip-assignment-select").on('change', function() { if($(this).val() == 'STATIC') {$("#ipconfig").show();} });
$("#ip-assignment-select").on('change', function() { if($(this).val() == 'DHCP') {$("#ipconfig").hide();$("#ip-address").removeAttr('required');$("#network-mask").removeAttr('required');} });
</script>
<script>
$("#chk-enable-wifi").on('click', function() { if($(this).is(':checked')) {$("#wificonfig").show();} else {$("#wificonfig").hide();} });
$("#timesync_checkbox").on('click', function() { if($(this).is(':checked')) {$("#div-timesync").show();} else {$("#div-timesync").hide();} });

</script>

<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timezone-apply'])) {
	  shell_exec("sudo mount -o rw,remount,rw /");
	  shell_exec("sudo dpkg-reconfigure -f noninteractive tzdata 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
	  shell_exec("sudo mount -o ro,remount,ro /");
	}
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timesync-apply'])) {
	  if (empty($timeservererr)) {
		shell_exec("sudo mount -o rw,remount,rw /");
		if 	(array_key_exists ("timesync_checkbox" , $_POST)) {
			shell_exec("sudo systemctl enable ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			shell_exec("sudo systemctl reload-or-restart ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}
		else {
			shell_exec("sudo systemctl disable ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			shell_exec("sudo systemctl stop ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}
		shell_exec("sudo mount -o ro,remount,ro /");
	  }
	}
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-network-apply'])) {
	  if(empty($ipaddresserr) && empty($subnetmaskerr) && empty($defaultgatewayerr) && empty($primarydnserr) && empty($secondarydnserr)) {
		  logmessage("about to apply network settings");
		  shell_exec("sudo mount -o rw,remount,rw /");
		  if($configurationsettings['IPAssignment'] == 'DHCP') {
			  shell_exec("sudo sed -i '42,\$d' /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  shell_exec("sudo dhcpcd -n eth0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
	  		  if($configurationsettings['WifiClient'] == 'enabled') {
				  shell_exec("sudo dhcpcd -n wlan0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
		  }
		  if($configurationsettings['IPAssignment'] == 'STATIC') {
			  shell_exec("sudo sed -i '42,\$d' /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  shell_exec("echo '\ninterface eth0' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  shell_exec("echo 'static ip_address=" . $configurationsettings['IPAddress'] . "/" . mask2cidr($configurationsettings['NetworkMask']) . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  if($configurationsettings['Gateway'] != "") {
			  	shell_exec("echo 'static routers=" . $configurationsettings['Gateway'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  if($configurationsettings['Dns1'] != "" && $configurationsettings['Dns2'] == "") {
			  	shell_exec("echo 'static domain_name_servers=" . $configurationsettings['Dns1'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  if($configurationsettings['Dns1'] == "" && $configurationsettings['Dns2'] != "") {
			  	shell_exec("echo 'static domain_name_servers=" . $configurationsettings['Dns2'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  if($configurationsettings['Dns1'] != "" && $configurationsettings['Dns2'] != "") {
			  	shell_exec("echo 'static domain_name_servers=" . $configurationsettings['Dns1'] . " " . $configurationsettings['Dns2'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  shell_exec("echo '\ninterface wlan0' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  shell_exec("echo 'static ip_address=" . $configurationsettings['IPAddress'] . "/" . mask2cidr($configurationsettings['NetworkMask']) . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  if($configurationsettings['Gateway'] != "") {
			  	shell_exec("echo 'static routers=" . $configurationsettings['Gateway'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  if($configurationsettings['Dns1'] != "" && $configurationsettings['Dns2'] == "") {
			  	shell_exec("echo 'static domain_name_servers=" . $configurationsettings['Dns1'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  if($configurationsettings['Dns1'] == "" && $configurationsettings['Dns2'] != "") {
			  	shell_exec("echo 'static domain_name_servers=" . $configurationsettings['Dns2'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
			  if($configurationsettings['Dns1'] != "" && $configurationsettings['Dns2'] != "") {
			  	shell_exec("echo 'static domain_name_servers=" . $configurationsettings['Dns1'] . " " . $configurationsettings['Dns2'] . "' | sudo tee -a /etc/dhcpcd.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }

  			  shell_exec("sudo dhcpcd -n eth0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
	  		  if($configurationsettings['WifiClient'] == 'enabled') {
				  shell_exec("sudo dhcpcd -n wlan0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			  }
		  shell_exec("sudo mount -o ro,remount,ro /");
		  }
	  }
	}
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-wifi-apply'])) {
	
	  if(empty($ssiderror) && empty($securitymodeerr)) {
		
		logmessage("Setting file system read/write.");
		shell_exec("sudo mount -o rw,remount,rw /");

		if($configurationsettings['WifiSecurityMode'] == 'None') {
		  logmessage("Configuring Wifi for open authentication and no security.");
		  logmessage("Writing Wifi configuration to /etc/network/interfaces");
		  shell_exec("sudo sed -i '16,\$d' /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'wireless-essid \"" . $configurationsettings['WifiSsid'] . "\"' | sudo tee -a /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}

		if($configurationsettings['WifiSecurityMode'] == 'WEP') {
		  logmessage("Configuring Wifi for open authentication and WEP security.");
		  logmessage("Writing Wifi configuration to /etc/network/interfaces");
		  shell_exec("sudo sed -i '16,\$d' /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'wireless-essid \"" . $configurationsettings['WifiSsid'] . "\"' | sudo tee -a /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'wireless-key \"" . $configurationsettings['WifiPassword'] . "\"' | sudo tee -a /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}
       
		if($configurationsettings['WifiSecurityMode'] == 'WPA/WPA2 PSK') {
		  logmessage("Writing Wifi configuration to /etc/network/interfaces");
		  shell_exec("sudo sed -i '16,\$d' /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'wpa-conf /etc/wpa_supplicant/wpa_supplicant.conf' | sudo tee -a /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  logmessage("Writing WPA configuration to /etc/wpa_supplicant/wpa_supplicant.conf");
		  shell_exec("sudo sed -i '3,\$d' /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo '\nnetwork={' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'ssid=\"" . $configurationsettings['WifiSsid'] . "\"' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'proto=RSN' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'key_mgmt=WPA-PSK' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'pairwise=CCMP' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'group=CCMP' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo 'psk=\"" . $configurationsettings['WifiPassword'] . "\"' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		  shell_exec("echo '}' | sudo tee -a /etc/wpa_supplicant/wpa_supplicant.conf 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}

		if($configurationsettings['WifiClient'] == 'enabled') {
			logmessage("Bringing down interface wlan0.");
			shell_exec("sudo ifdown wlan0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			logmessage("Bringing up interface wlan0.");
			shell_exec("sudo ifup wlan0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			logmessage("Scheduling interface to come up at next boot. Writing configuration to /etc/network/interfaces");
			shell_exec("sudo sed -i 's/# allow-hotplug wlan0/allow-hotplug wlan0/g' /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}
		if($configurationsettings['WifiClient'] == 'disabled') {
			logmessage("Bringing down interface wlan0.");
			shell_exec("sudo ifdown wlan0 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			logmessage("Unscheduling interface to come up at next boot. Writing configuration to /etc/network/interfaces");
			shell_exec("sudo sed -i 's/allow-hotplug wlan0/# allow-hotplug wlan0/g' /etc/network/interfaces 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");

		}
		logmessage("Setting file system read only.");
		shell_exec("sudo mount -o ro,remount,ro /");
	  }
	}
  ?>
  <!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>