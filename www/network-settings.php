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
    <?php include 'functions.php';?>
	<?php logmessage("Loading page Configuration-DateTime.php");?>
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
            <a href="#" class="pull-left">
              <img src="Images/IP-cam-icon-w110-flip.png" alt="" width="57" height="50" />
             </a>
          <p class="navbar-brand">Raspberry IP Camera</p>
        </div>
        <div class="collapse navbar-collapse" id="navbar-container">
          <ul class="nav navbar-nav">
			  <!-- InstanceBeginEditable name="navbar" -->
              <li><a href="Status.php">Status</a></li>
              <li class="active"><a href="network-settings.php">Network Settings</a></li>
              <li><a href="camera-settings.php">Camera Settings</a></li>
			  <!-- InstanceEndEditable -->
          </ul>
        </div>
      </div>
  </nav>

  <!-- InstanceBeginEditable name="body" -->
 <!-- ********************************************************************************************************************** -->
  <?php date_default_timezone_set(trim(file_get_contents("/etc/timezone"),"\n"));
  		$configurationsettings = parse_ini_file("/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timezone-apply'])) {
	file_put_contents("/etc/timezone", $_POST["sel-timezone"]);
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
		file_put_contents("/etc/ntp.conf", implode($arrconfigfilecontents));
		write_php_ini($configurationsettings, "/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
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
		}
	  }
	  if (!empty($_POST["network-mask"])) {
		$subnetmask = test_input($_POST["network-mask"]);
		if (!preg_match("/^[0-9.]*$/",$subnetmask)) {
		  $subnetmaskerr = "subnetmask field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		}
	  }
	  if (!empty($_POST["gateway"])) {
		$defaultgateway = test_input($_POST["gateway"]);
		if (!preg_match("/^[0-9.]*$/",$defaultgateway)) {
		  $defaultgatewayerr = "defaultgateway field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		}
	  }
	  if (!empty($_POST["dns1"])) {
		$primarydns = test_input($_POST["dns1"]);
		if (!preg_match("/^[a-zA-Z0-9.]*$/",$primarydns)) {
		  $primarydnserr = "primarydns field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		}
	  }
	  if (!empty($_POST["dns2"])) {
		$secondarydns = test_input($_POST["dns2"]);
		if (!preg_match("/^[a-zA-Z0-9.]*$/",$secondarydns)) {
		  $secondarydnserr = "secondarydns field contains incorrect data, only a-zA-Z0-9_- allowed!<br />"; 
		}
	  }
	  if(empty($ipaddresserr) && empty($subnetmaskerr) && empty($defaultgatewayerr) && empty($primarydnserr) && empty($secondarydnserr) && !empty($ipaddress) && !empty($subnetmask)) {
		
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

		write_php_ini($configurationsettings, "/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");

		/*update_interfaces_file($configurationsettings['operationmode']);*/
	  }
	}
  ?>
<!-- ********************************************************************************************************************** -->
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
	   
		$wifi = $ssid = $securitymode = $password = "";
		$ssiderror = $securitymodeerr = $passworderr = "";
		
		if(isset($_POST['chk-enable-wifi'])) {
			$wifi = "enabled";
		}
	  
		if (!empty($_POST["ssid"])) {
		  $ssid = test_input($_POST["ssid"]);
		  if (!preg_match("/^[a-zA-Z0-9_-]*$/",$ssid)) {
			  $ssiderror = "ssid field contains incorrect data, only a-zA-Z0-9 _ - allowed!<br />"; 
		  }
		}
		
		if (!empty($_POST["wifi-security"])) {
		  $securitymode = test_input($_POST["wifi-security"]);
		  if (!strcmp($securitymode, "None") && !strcmp($securitymode, "WEP") && !strcmp($securitymode, "WPA/WPA2 PSK")) {
			  $securitymodeerr = "Incorrect input received for Operation Mode!";
		  }
		}
		
		if (!empty($_POST["wifi-password"])) {
		  $password = test_input($_POST["wifi-password"]);
		  if (!preg_match("/^[a-zA-Z0-9]*$/",$password)) {
			  $passworderr = "Password field contains incorrect data, only a-zA-Z0-9 allowed!<br />"; 
		  }
		}


	  // only apply actions when no form errors are present
	  if(empty($ssiderror) && empty($securitymodeerr) && empty($passworderr)) {
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
		  write_php_ini($configurationsettings, "/home/pi/RaspberryIPCamera/www/RaspberryIPCameraSettings.ini");
	  }
	}
?>
<!-- ********************************************************************************************************************** -->

 
 <div class="container">
  	<h3 class="text-center">Date/Time Configuration</h3>
    <br />

   <h4 class="text-center">Time Synchronisation</h4>

	  <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-timesync" role="form">
            <div class="form-group">
              <div class="checkbox col-sm-offset-4 col-sm-8">
                <label><input name="timesync_checkbox" type="checkbox" id="timesync_checkbox" form="frm-timesync" value="on" <?php if ($configurationsettings['ntpclient'] == "enabled") {echo "checked";}?>>Enable time synchronisation</label>
              </div>
            </div><!--formgroup-->
            
            <div id="div-timesync">
            
              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Time Server:</label>
                <div class="col-sm-5">
                  <input name="txt-timeserver" type="text" class="form-control" id="timeserver" form="frm-timesync" placeholder="pool.ntp.org" pattern="^[0-9a-zA-Z.]*$" value="<?php echo rtrim(array_shift(array_slice($arrconfigfilefiltered, 0, 1)));?>" maxlength="40">
                </div>
              </div><!--form group-->
              
              <div class="form-group"> 
                <div class="col-sm-offset-4 col-sm-8">
                  <input name="btn-timesync-apply" type="submit" class="btn btn-default" id="btn-timesync-apply" form="frm-timesync" value="Apply"></button>
                </div>
              </div><!--form group-->
            </div><!-- end div div-timesync -->
          </form>
        </div><!-- end div col-sm-10 -->
      <div class="col-sm-1"></div>
    </div><!-- end div row -->

   <div id="div-timesync2">
      <h4 class="text-center">Timezone</h4>
      <div class="row">
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
      </div><!--row-->
    </div><!-- end div div-timesync -->

          
    <br>
    <h3 class="text-center">Network Configuration</h3>
    <br />
    <h4 class="text-center">IP Addressing</h4>
      <div class="row">
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
                  <input name="ip-address" type="text" autofocus required class="form-control" id="ip-address" form="frm-ipconfig" pattern="^[0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['IPAddress'])) {echo "value=" . $configurationsettings['IPAddress'];}?>>
                </div>
              </div><!--form group-->
              <div class="form-group">
                <label class="control-label col-sm-4" for="network-mask">Network Mask:</label>
                <div class="col-sm-5">
                  <input name="network-mask" type="text" required class="form-control" id="network-mask" form="frm-ipconfig" pattern="^[0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['NetworkMask'])) {echo "value=" . $configurationsettings['NetworkMask'];}?>>
                </div>
              </div><!--form group-->
              <div class="form-group">
                <label class="control-label col-sm-4" for="gateway">Gateway:</label>
                <div class="col-sm-5">
                  <input name="gateway" type="text" class="form-control" id="gateway" form="frm-ipconfig" pattern="^[0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['Gateway'])) {echo "value=" . $configurationsettings['Gateway'];}?>>
                </div>
              </div><!--form group-->
            
              <br />
              <h4 class="text-center">DNS Servers</h4>
              <div class="form-group">
                <label class="control-label col-sm-4" for="dns1">DNS Server 1:</label>
                <div class="col-sm-5">
                  <input name="dns1" type="text" class="form-control" id="dns1" form="frm-ipconfig" pattern="^[a-zA-Z0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['Dns1'])) {echo "value=" . $configurationsettings['Dns1'];}?>>
                </div>
              </div><!--form group-->
              <div class="form-group">
                <label class="control-label col-sm-4" for="dns2">DNS Server 2:</label>
                <div class="col-sm-5">
                  <input name="dns2" type="text" class="form-control" id="dns2" form="frm-ipconfig" pattern="^[a-zA-Z0-9.]*$" maxlength="15" <?php if(!empty($configurationsettings['Dns2'])) {echo "value=" . $configurationsettings['Dns2'];}?>>
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
    </div><!--row-->
          
          
    <br />
    <h4 class="text-center">Wifi Settings</h4>
      <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-wificonfig" role="form">
            <div class="form-group">
              <div class="checkbox col-sm-offset-4 col-sm-8">
                <label><input name="chk-enable-wifi" id="chk-enable-wifi" type="checkbox" id="chk-enable-wifi" form="frm-wificonfig" <?php if ($configurationsettings['WifiClient'] == "enabled") {echo "checked";}?>>Enable Wifi</label>
              </div>
            </div><!--formgroup-->
            
            <div class="form-group">
              <label class="control-label col-sm-4" for="ssid">Connect to SSID:</label>
              <div class="col-sm-5">
                <input name="ssid" type="text" class="form-control" id="ssid" form="frm-wificonfig" pattern="^[a-zA-Z0-9_-]*$" <?php if(!empty($configurationsettings['WifiSsid'])) {echo "value=" . $configurationsettings['WifiSsid'];}?>>
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
                <input name="wifi-password" type="password" class="form-control" id="wifi-password" form="frm-wificonfig" pattern="^[a-zA-Z0-9]*$" <?php if(!empty($configurationsettings['WifiPassword'])) {echo "value=" . $configurationsettings['WifiPassword'];}?>>
              </div>
            </div><!--form group-->
            <div class="form-group"> 
              <div class="col-sm-offset-4 col-sm-8">
                <input name="btn-wifi-apply" type="submit" class="btn btn-default" id="btn-wifi-apply" form="frm-wificonfig" value="Apply"></button>
              </div>
            </div><!--form group-->
          
          </form>
        </div><!--col-sm-10-->
      <div class="col-sm-1"></div>
    </div><!--row-->
  </div><!--container-->
  <!-- InstanceEndEditable -->

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>
  <!--
      Bootstrap javascript and JQuery should be loaded
      Placed at the end of the document for faster load times
  -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <!-- InstanceBeginEditable name="php code" -->
<!-- ********************************************************************************************************************** -->
<script>
$("#ip-assignment-select").on('change', function() { if($(this).val() == 'STATIC') {$("#ipconfig").show();} });
$("#ip-assignment-select").on('change', function() { if($(this).val() == 'DHCP') {$("#ipconfig").hide();} });
</script>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timezone-apply'])) {
	  shell_exec("sudo dpkg-reconfigure -f noninteractive tzdata 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
	}
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-timesync-apply'])) {
	  if (empty($timeservererr)) {
		if 	(array_key_exists ("timesync_checkbox" , $_POST)) {
			shell_exec("sudo systemctl enable ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			shell_exec("sudo systemctl reload-or-restart ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}
		else {
			shell_exec("sudo systemctl disable ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
			shell_exec("sudo systemctl stop ntp 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
		}
	  }
	}
  ?>
<!-- ********************************************************************************************************************** -->
  <!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>