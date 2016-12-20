<!-- check if our login_user is set, otherwise redirect to the logon screen <?php include('logincheck.php');?>-->

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
	<?php include 'camera-settings-array.php';?>
	<?php logmessage("Loading page camera-settings.php");?>
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
              <li><a href="network-settings.php">System Settings</a></li>
              <li class="active"><a href="camera-settings.php">Camera Settings</a></li>
			  <!-- InstanceEndEditable -->
              <li><a href="logout.php">Log Off</a></li>
          </ul>
        </div>
      </div>
  </nav>

  <!-- InstanceBeginEditable name="body" -->

 <!-- ********************************************************************************************************************** -->
  <?php 
	$camerasettings = parse_ini_file("/etc/uv4l/uv4l-raspicam.conf");
	if($camerasettings['drc'] == "") {$camerasettings['drc'] = "off";}
	//var_dump($camerasettings);
  ?>
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-camerasettings-apply'])) {
		$width = $height = $format = $brightness = $contrast = $saturation = $redbalance = $bluebalance = 0;
		$sharpness = $rotate = $shutterspeed = /*$zoomfactor = */$jpegquality = $framerate = $bitrate = 0;
		$shutterspeed = 0;
		$isosensitivity = 0;
		$resolution = $horizontalmirror = $verticalmirror = $textoverlay = $objectfacedetection = "false";
		$stillsdenoise = $videodenoise = $imagestabilisation = "false";
		$awbmode = $exposuremode = $exposuremetering = $drcstrenght = "";

		/*$widtherr = $heighterr = */$formaterr = $brightnesserr = $contrasterr = $saturationerr = $redbalanceerr = "";
		$bluebalanceerr = $sharpnesserr = $rotateerr = $shutterspeederr = /*$zoomfactorerr = */$isosensitivityerr = "";
		$jpegqualityerr = $framerateerr = $resolutionerr = $horizontalmirrorerr = $verticalmirrorerr = $textoverlayerr = "";
		$objectfacedetectionerr = $stillsdenoiseerr = $videodenoiseerr = $imagestabilisationerr = $awbmodeerr = "";
		$exposuremodeerr = $exposuremeteringerr = $drcstrenghterr = $bitrateerr = "";
		
		if (!empty($_POST["resolution"])) {
		  $resolution = test_input($_POST["resolution"]);
		  if(!$resolution == "2592×1944" || !$resolution == "1920×1080" || !$resolution == "1296×972" || !$resolution == "1296×730" || !$resolution == "1280×720" || !$resolution == "640x480") {
			  $resolutionerr = "Only '2592×1944', '1920×1080', '1296×972', '1296×730', '1280×720', or '640x480' is allowed as input!";
			  logmessage($resolutionerr);
		  }
		}

		/*if (!empty($_POST["width"])) {
		  $width = test_input($_POST["width"]);
		  if (!preg_match("/^[0-9]*$/",$width)) {
			$widtherr = "height field contains incorrect data, only 0-9 allowed!"; 
			logmessage($widtherr);
		  }
		}

		if (!empty($_POST["height"])) {
		  $height = test_input($_POST["height"]);
		  if (!preg_match("/^[0-9]*$/",$height)) {
			$heighterr = "height field contains incorrect data, only 0-9 allowed!"; 
			logmessage($heighterr);
		  }
		}*/

		if (!empty($_POST["format"])) {
		  $format = test_input($_POST["format"]);
		  if(!$format == "mjpeg" || !$format == "h264") {
			  $formaterr = "Only MJPEG or H264 is allowed as input!";
			  logmessage($formaterr);
		  }
		}
		if (!empty($_POST["brightness"])) {
		  $brightness = test_input($_POST["brightness"]);
		  if (!preg_match("/^[0-9]*$/",$brightness)) {
			$brightnesserr = "brightness field contains incorrect data, only 0-9 allowed!"; 
			logmessage($brightnesserr);
		  }
		}

		if (!empty($_POST["contrast"])) {
		  $contrast = test_input($_POST["contrast"]);
		  if (!preg_match("/^[0-9\-]*$/",$contrast)) {
			$contrasterr = "contrast field contains incorrect data, only 0-9 allowed!"; 
			logmessage($contrasterr);
		  }
		}

		if (!empty($_POST["saturation"])) {
		  $saturation = test_input($_POST["saturation"]);
		  if (!preg_match("/^[0-9\-]*$/",$saturation)) {
			$saturationerr = "saturation field contains incorrect data, only 0-9 allowed!"; 
			logmessage($saturationerr);
		  }
		}

		if (!empty($_POST["redbalance"])) {
		  $redbalance = test_input($_POST["redbalance"]);
		  if (!preg_match("/^[0-9]*$/",$redbalance)) {
			$redbalanceerr = "redbalance field contains incorrect data, only 0-9 allowed!"; 
			logmessage($redbalanceerr);
		  }
		}

		if (!empty($_POST["bluebalance"])) {
		  $bluebalance = test_input($_POST["bluebalance"]);
		  if (!preg_match("/^[0-9]*$/",$bluebalance)) {
			$bluebalanceerr = "bluebalance field contains incorrect data, only 0-9 allowed!"; 
			logmessage($bluebalanceerr);
		  }
		}

		if (!empty($_POST["sharpness"])) {
		  $sharpness = test_input($_POST["sharpness"]);
		  if (!preg_match("/^[0-9\-]*$/",$sharpness)) {
			$sharpnesserr = "sharpness field contains incorrect data, only 0-9 allowed!"; 
			logmessage($sharpnesserr);
		  }
		}

		if (!empty($_POST["rotate"])) {
		  $rotate = test_input($_POST["rotate"]);
		  if (!preg_match("/^[0-9]*$/",$rotate)) {
			$rotateerr = "rotate field contains incorrect data, only 0-9 allowed!"; 
			logmessage($rotateerr);
		  }
		}

		if (!empty($_POST["shutterspeed"])) {
		  $shutterspeed = test_input($_POST["shutterspeed"]);
		  if (!preg_match("/^[0-9]*$/",$shutterspeed)) {
			$shutterspeederr = "shutterspeed field contains incorrect data, only 0-9 allowed!"; 
			logmessage($shutterspeederr);
		  }
		}
/*
		if (!empty($_POST["zoomfactor"])) {
		  $zoomfactor = test_input($_POST["zoomfactor"]);
		  if (!preg_match("/^[0-9]*$/",$zoomfactor)) {
			$zoomfactorerr = "zoomfactor field contains incorrect data, only 0-9 allowed!"; 
			logmessage($zoomfactorerr);
		  }
		}
*/
		if (!empty($_POST["isosensitivity"])) {
		  $isosensitivity = test_input($_POST["isosensitivity"]);
		  if (!preg_match("/^[0-9]*$/",$isosensitivity)) {
			$isosensitivityerr = "isosensitivity field contains incorrect data, only 0-9 allowed!"; 
			logmessage($isosensitivityerr);
		  }
		}

		if (!empty($_POST["jpegquality"])) {
		  $jpegquality = test_input($_POST["jpegquality"]);
		  if (!preg_match("/^[0-9]*$/",$jpegquality)) {
			$jpegqualityerr = "jpegquality field contains incorrect data, only 0-9 allowed!"; 
			logmessage($jpegqualityerr);
		  }
		}

		if (!empty($_POST["framerate"])) {
		  $framerate = test_input($_POST["framerate"]);
		  if (!preg_match("/^[0-9]*$/",$framerate)) {
			$framerateerr = "framerate field contains incorrect data, only 0-9 allowed!"; 
			logmessage($framerateerr);
		  }
		}

		if (!empty($_POST["bitrate"])) {
		  $bitrate = test_input($_POST["bitrate"]);
		  if (!preg_match("/^[0-9]*$/",$bitrate)) {
			$bitrateerr = "framerate field contains incorrect data, only 0-9 allowed!"; 
			logmessage($bitrateerr);
		  }
		}
		
		if (!empty($_POST["horizontalmirror"])) {
		  $horizontalmirror = test_input($_POST["horizontalmirror"]);
		  if (!$horizontalmirror == "true") {
			$horizontalmirrorerr = "Incorrect response received from horizontalmirror checkbox!"; 
			logmessage($horizontalmirror);
		  }
		  else {
			$horizontalmirror = "1";
		  }

		}

		if (!empty($_POST["verticalmirror"])) {
		  $verticalmirror = test_input($_POST["verticalmirror"]);
		  if (!$verticalmirror == "true") {
			$verticalmirrorerr = "Incorrect response received from verticalmirror checkbox!"; 
			logmessage($verticalmirrorerr);
		  }
		  else {
			$verticalmirror = "1";
		  }

		}

		if (!empty($_POST["textoverlay"])) {
		  $textoverlay = test_input($_POST["textoverlay"]);
		  if (!$textoverlay == "true") {
			$textoverlayerr = "Incorrect response received from textoverlay checkbox!"; 
			logmessage($textoverlayerr);
		  }
		  else {
			$textoverlay = "1";
		  }
		}

		if (!empty($_POST["objectfacedetection"])) {
		  $objectfacedetection = test_input($_POST["objectfacedetection"]);
		  if (!$objectfacedetection == "true") {
			$objectfacedetectionerr = "Incorrect response received from objectfacedetection checkbox!"; 
			logmessage($objectfacedetectionerr);
		  }
		  else {
			$objectfacedetection = "1";
		  }

		}

		if (!empty($_POST["stillsdenoise"])) {
		  $stillsdenoise = test_input($_POST["stillsdenoise"]);
		  if (!$stillsdenoise == "true") {
			$stillsdenoiseerr = "Incorrect response received from stillsdenoise checkbox!"; 
			logmessage($stillsdenoiseerr);
		  }
		  else {
			$stillsdenoise = "1";
		  }

		}

		if (!empty($_POST["videodenoise"])) {
		  $videodenoise = test_input($_POST["videodenoise"]);
		  if (!$videodenoise == "true") {
			$videodenoiseerr = "Incorrect response received from videodenoise checkbox!"; 
			logmessage($videodenoiseerr);
		  }
		  else {
			$videodenoise = "1";
		  }

		}

		if (!empty($_POST["imagestabilisation"])) {
		  $imagestabilisation = test_input($_POST["imagestabilisation"]);
		  if (!$imagestabilisation == "true") {
			$imagestabilisationerr = "Incorrect response received from imagestabilisation checkbox!"; 
			logmessage($imagestabilisationerr);
		  }
		  else {
			$imagestabilisation = "1";
		  }
		}

		if (!empty($_POST["awbmode"])) {
		  $awbmode = test_input($_POST["awbmode"]);
		  if(!$awbmode == "auto" || !$awbmode == "cloudy" || !$awbmode == "flash" || !$awbmode == "fluorescent" || !$awbmode == "horizon" || !$awbmode == "incandescent" || !$awbmode == "off" || !$awbmode == "shade" || !$awbmode == "sun" || !$awbmode == "tungsten") {
			  $awbmodeerr = "Only auto, cloudy, flash, fluorescent, horizon, incandescent,  off, sun, shade, or tungsten is allowed as input for awbmode selector!";
			  logmessage($awbmodeerr);
		  }
		}

		if (!empty($_POST["exposuremode"])) {
		  $exposuremode = test_input($_POST["exposuremode"]);
		  if(!$exposuremode == "antishake" || !$exposuremode == "auto" || !$exposuremode == "backlight" || !$exposuremode == "beach" || !$exposuremode == "fireworks" || !$exposuremode == "fixedfps" || !$exposuremode == "night" || !$exposuremode == "nightpreview" || !$exposuremode == "snow" || !$exposuremode == "sports" || !$exposuremode == "spotlight" || !$exposuremode == "verylong") {
			$exposuremodeerr = "Only antishake, auto, backlight, beach, fireworks, fixedfps, night, nightpreview, snow, sports, spotlight, or verylong is allowed as input for exposuremode selector!"; 
			logmessage($exposuremodeerr);
		  }
		}

		if (!empty($_POST["exposuremetering"])) {
		  $exposuremetering = test_input($_POST["exposuremetering"]);
		  if(!$exposuremetering == "average" || !$exposuremetering == "backlit" || !$exposuremetering == "matrix" || !$exposuremetering == "spot") { 
			$exposuremeteringerr = "Only average, backlit, matrix or spot is allowed as input for exposuremetering selector!"; 
			logmessage($exposuremeteringerr);
		  }
		}

		if (!empty($_POST["drcstrenght"])) {
		  $drcstrenght = test_input($_POST["drcstrenght"]);
		  //echo "drcstrength=" . $drcstrenght;
		  if(!$drcstrenght == "high" || !$drcstrenght == "low" || !$drcstrenght == "medium" || !$drcstrenght == "off") {
			$drcstrenghterr = "Only high, low, medium, or off is allowed as input for drcstrenght selector!"; 
			logmessage($drcstrenghterr);
		  }
		}
		
		// Only continue when no errors are found.
		if(/*empty($widtherr) && empty($heighterr) && */empty($resolutionerr) && empty($formaterr) && empty($brightnesserr) && empty($contrasterr) && empty($saturationerr) && empty($redbalanceerr) && empty($bluebalanceerr) && empty($sharpnesserr) && empty($rotateerr) && empty($shutterspeederr) && /*empty($zoomfactorerr) && */empty($isosensitivityerr) && empty($jpegqualityerr) && empty($framerateerr) && empty($bitrateerr) && empty($horizontalmirrorerr) && empty($verticalmirrorerr) && empty($textoverlayerr) && empty($objectfacedetectionerr) && empty($stillsdenoiseerr) && empty($videodenoiseerr) && empty($imagestabilisationerr) && empty($awbmodeerr) && empty($exposuremodeerr) && empty($exposuremeteringerr) && empty($drcstrenghterr)) {
			
			/*$camerasettings['width'] = $width;
			$camerasettings['height'] = $height;*/
			$camerasettings['encoding'] = $format;
			$camerasettings['brightness'] = $brightness;
			$camerasettings['contrast'] = $contrast;
			$camerasettings['saturation'] = $saturation;
			$camerasettings['red-gain'] = $redbalance;
			$camerasettings['blue-gain'] = $bluebalance;
			$camerasettings['sharpness'] = $sharpness;
			$camerasettings['rotation'] = $rotate;
			$camerasettings['shutter-speed'] = $shutterspeed;
			$camerasettings['iso'] = $isosensitivity;
			$camerasettings['quality'] = $jpegquality;
			$camerasettings['framerate'] = $framerate;
			$camerasettings['bitrate'] = $bitrate;
			$camerasettings['hflip'] = $horizontalmirror;
			$camerasettings['vflip'] = $verticalmirror;
			$camerasettings['text-overlay'] = $textoverlay;
			$camerasettings['object-detection'] = $objectfacedetection;
			$camerasettings['stills-denoise'] = $stillsdenoise;
			$camerasettings['video-denoise'] = $videodenoise;
			$camerasettings['vstab'] = $imagestabilisation;
			$camerasettings['awb'] = $awbmode;
			$camerasettings['exposure'] = $exposuremode;
			$camerasettings['metering'] = $exposuremetering;
			$camerasettings['drc'] = $drcstrenght;
						switch ($resolution) {
			  case "2592×1944":
			  	logmessage("in resolution routine");
				$camerasettings['width'] = 2592;
				$camerasettings['height'] = 1944;
				if($framerate > 15) 
					$camerasettings['framerate'] = 15;
			  break;
			  case "1920×1080":
			  	$camerasettings['width'] = 1920;
				$camerasettings['height'] = 1080;
				if($framerate > 30) 
					$camerasettings['framerate'] = 30;
			  break;
			  case "1296×972":
			  	$camerasettings['width'] = 1296;
				$camerasettings['height'] = 972;
				if($framerate > 42) 
					$camerasettings['framerate'] = 42;
			  break;
			  case "1296×730":
			  	$camerasettings['width'] = 1296;
				$camerasettings['height'] = 730;
				if($framerate > 49) 
					$camerasettings['framerate'] = 49;
			  break;
			  case "1280×720":
			  	$camerasettings['width'] = 1280;
				$camerasettings['height'] = 720;
				if($framerate > 49) 
					$camerasettings['framerate'] = 49;
			  break;
			  case "640x480-1":
			  	$camerasettings['width'] = 640;
				$camerasettings['height'] = 480;
				if($framerate < 43)
					$camerasettings['framerate'] = 43;
				if($framerate > 60) 
					$camerasettings['framerate'] = 60;
			  break;
			  case "640x480-2":
			  	$camerasettings['width'] = 640;
				$camerasettings['height'] = 480;
				if($framerate < 61)
					$camerasettings['framerate'] = 61;
				if($framerate > 90) 
					$camerasettings['framerate'] = 90;
			  break;
			}

//echo "<br><br>CameraDefaults:<br>";
//var_dump($cameradefaultsettings);
//echo "<br><br>CameraSettings:<br>";
//var_dump($camerasettings);
//echo "<br><br>Camerasettings + defaultsettings<br>";
//var_dump($camerasettings + $cameradefaultsettings);
//echo "<br><br>";
			
			shell_exec("sudo mount -o rw,remount,rw /");
			write_camerasettings_conf($camerasettings + $cameradefaultsettings, "/etc/uv4l/uv4l-raspicam.conf");
			shell_exec("sudo mount -o ro,remount,ro /");
		}
	}
  ?>
<!-- ********************************************************************************************************************** -->
 <div class="container">
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frm-camerasettings" role="form">
    <div class="panel panel-default">
      <div class="panel-heading"><h4 class="text-center">Resolution and Format</h4></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">

              <div class="form-group">
                <label class="control-label col-sm-4" for="resolution">Resolution:</label>
                <div class="col-sm-5">
                  <select name="resolution" class="form-control" id="resolution" form="frm-camerasettings">
                    <option value="2592×1944" <?php if($camerasettings['width'] == "2592") {echo "selected='selected'";}?>>2592×1944 / 1-15 fps / 4:3 / Full FOV</option>
                    <option value="1920×1080" <?php if($camerasettings['width'] == "1920") {echo "selected='selected'";}?>>1920×1080 / 1-30 fps / 16:9 / Partial FOV</option>
                    <option value="1296×972" <?php if($camerasettings['height'] == "972") {echo "selected='selected'";}?>>1296×972 / 1-42 fps / 4:3 / Full FFOV</option>
                    <option value="1296×730" <?php if($camerasettings['height'] == "730") {echo "selected='selected'";}?>>1296×730 / 1-49fps / 16:9 / Full FFOV</option>
                    <option value="1280×720" <?php if($camerasettings['width'] == "1280") {echo "selected='selected'";}?>>1280×720 / 1-49fps / 16:9 / Full FFOV</option>
                    <option value="640x480-1" <?php if($camerasettings['width'] == "640" && $camerasettings['framerate'] <= 60) {echo "selected='selected'";}?>>640x480 / 42.1-60 fps / 4:3 / Full FFOV</option>
                    <option value="640x480-2" <?php if($camerasettings['width'] == "640" && $camerasettings['framerate'] > 60) {echo "selected='selected'";}?>>640x480 / 60.1-90 fps / 4:3 / Full FFOV</option>
                  </select>
                </div>
              </div><!--form group-->


<!--              <div class="form-group">
                <label class="control-label col-sm-4" for="width">Width:</label>
                  <div class="col-sm-5">
                    <input name="width" type="number" class="form-control" id="width" form="frm-camerasettings" min="64" step="8" <?php //if(!empty($camerasettings['width'])) {echo "value=" . $camerasettings['width'];}?>>
                  </div>
              </div><!--form group-->
              
<!--              <div class="form-group">
                <label class="control-label col-sm-4" for="height">Height:</label>
                <div class="col-sm-5">
                  <input name="height" type="number" class="form-control" id="height" form="frm-camerasettings" min="64" step="8" <?php //if(!empty($camerasettings['height'])) {echo "value=" . $camerasettings['height'];}?>>
                </div>
              </div><!--form group-->
              
              <div class="form-group">
                <label class="control-label col-sm-4" for="format">Format:</label>
                <div class="col-sm-5">
                  <select name="format" class="form-control" id="format" form="frm-camerasettings">
                    <option value="mjpeg" <?php if($camerasettings['encoding'] == "mjpeg") {echo "selected='selected'";}?>>MJPEG Video (streamable)</option>
                    <option value="h264" <?php if($camerasettings['encoding'] == "h264") {echo "selected='selected'";}?>>H264 (Streamable over RTSP)</option>
                  </select>            
                </div>
              </div><!--form group-->
              <div class="alert alert-info">
              
				<?php 
                    if($camerasettings['encoding'] == "mjpeg") {
					  echo('<p class="text-center">To connect to MJPEG stream use the URL:</p>'); 
					  echo('<p class="text-center" style="width: 100%; display: inline-block; word-wrap: break-word;"><a href="http://');
					  if (preg_match('/^up/',shell_exec("cat /sys/class/net/eth0/operstate"))) {
						echo trim(shell_exec("ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://'"));
						echo(':8080/stream/video.mjpeg"><span>http://');
						echo trim(shell_exec("ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://'"));
						echo(':8080/stream/video.mjpeg</span></a></p>');
					  }
					  else {
						echo trim(shell_exec("ifconfig wlan0 | awk '/inet / { print $2 }' | sed 's/addr://'"));
						echo(':8080/stream/video.mjpeg"><span>http://');
						echo trim(shell_exec("ifconfig wlan0 | awk '/inet / { print $2 }' | sed 's/addr://'"));
						echo(':8080/stream/video.mjpeg</span></a></p>');
					  }
					}
				?>

				<?php 
                    if($camerasettings['encoding'] == "h264") {
					  echo('<p class="text-center">To connect to RTSP stream use the MRL:</p>'); 
					  echo('<p class="text-center"><mark>rtsp://');
					  if (preg_match('/^up/',shell_exec("cat /sys/class/net/eth0/operstate"))) {
						echo trim(shell_exec("ifconfig eth0 | awk '/inet / { print $2 }' | sed 's/addr://'"));
					  }
					  else {
						echo trim(shell_exec("ifconfig wlan0 | awk '/inet / { print $2 }' | sed 's/addr://'"));
					  }
					  echo(':8554</mark></p>');
					}
				?>
              
              </div><!-- end div alert -->
          </div><!-- end div col-sm-10 -->
          <div class="col-sm-1"></div>
        </div><!-- end div row -->
      </div><!-- end div panel body -->
    </div><!-- end div panel -->

                
    <div class="panel panel-default">
      <div class="panel-heading"><h4 class="text-center">Control Settings</h4></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-1"></div>
          <div class="col-sm-10">

              <div class="form-group">
                <label class="control-label col-sm-4" for="brightness">Brightness:</label>
                  <div class="col-sm-5">
                    <input name="brightness" type="range" class="form-control" id="brightness" form="frm-camerasettings" max="100" min="0" step="1" <?php echo "value=" . $camerasettings['brightness'];?> oninput="brightnessnum.value=brightness.value + ' pts.'">
                  </div>
                  <div class="col-sm-2">
					<output name="brightnessnum" id="brightnessnum" class="show" for="brightness"><?php echo $camerasettings['brightness'];?> pts.</output>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="contrast">Contrast:</label>
                  <div class="col-sm-5">
                    <input name="contrast" type="range" class="form-control" id="contrast" form="frm-camerasettings" max="100" min="-100" step="1" <?php echo "value=" . $camerasettings['contrast'];?> oninput="contrastnum.value=contrast.value + ' pts.'">
                  </div>
                  <div class="col-sm-2">
					<output name="contrastnum" id="contrastnum" for="contrast"><?php echo $camerasettings['contrast'];?> pts.</output>
                  </div>

              </div><!--form group-->
 
              <div class="form-group">
                <label class="control-label col-sm-4" for="saturation">Saturation:</label>
                  <div class="col-sm-5">
                    <input name="saturation" type="range" class="form-control" id="saturation" form="frm-camerasettings" max="100" min="-100" step="1" <?php echo "value=" . $camerasettings['saturation'];?> oninput="saturationnum.value=saturation.value + ' pts.'">
                  </div>
                  <div class="col-sm-2">
					<output name="saturationnum" id="saturationnum" for="saturation"><?php echo $camerasettings['saturation'];?> pts.</output>
                  </div>

              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="redbalance">Red Balance:</label>
                  <div class="col-sm-5">
                    <input name="redbalance" type="range" class="form-control" id="redbalance" form="frm-camerasettings" max="800" min="0" step="1" <?php echo "value=" . $camerasettings['red-gain'];?> oninput="redbalancenum.value=redbalance.value + ' pts.'">
                  </div>
                  <div class="col-sm-2">
					<output name="redbalancenum" id="redbalancenum" for="redbalance"><?php echo $camerasettings['red-gain'];?> pts.</output>
                  </div>

              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="bluebalance">Blue Balance:</label>
                  <div class="col-sm-5">
                    <input name="bluebalance" type="range" class="form-control" id="bluebalance" form="frm-camerasettings" max="800" min="0" step="1" <?php echo "value=" . $camerasettings['blue-gain'];?> oninput="bluebalancenum.value=bluebalance.value + ' pts.'">
                  </div>
                  <div class="col-sm-2">
					<output name="bluebalancenum" id="bluebalancenum" for="bluebalance"><?php echo $camerasettings['blue-gain'];?> pts.</output>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="sharpness">Sharpness:</label>
                  <div class="col-sm-5">
                    <input name="sharpness" type="range" class="form-control" id="sharpness" form="frm-camerasettings" max="100" min="-100" step="1" <?php echo "value=" . $camerasettings['sharpness'];?> oninput="sharpnessnum.value=sharpness.value + ' pts.'">
                  </div>
                  <div class="col-sm-2">
					<output name="sharpnessnum" id="sharpnessnum" for="sharpness"><?php echo $camerasettings['sharpness'];?> pts.</output>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="rotate">Rotate:</label>
                  <div class="col-sm-5">
                    <input name="rotate" type="range" class="form-control" id="rotate" form="frm-camerasettings" max="360" min="0" step="90" <?php echo "value=" . $camerasettings['rotation'];?> oninput="rotatenum.value=rotate.value + ' °'">
                  </div>
                  <div class="col-sm-2">
					<output name="rotatenum" id="rotatenum" for="rotate"><?php echo $camerasettings['rotation'];?> °</output>
                  </div>

              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="shutterspeed">Shutter Speed:</label>
                  <div class="col-sm-5">
                    <input name="shutterspeed" type="number" class="form-control" id="shutterspeed" form="frm-camerasettings" max="65535" min="0" step="1" <?php echo "value=" . $camerasettings['shutter-speed'];?>> 
                  </div>
                  <div class="col-sm-2">
					<output name="shutterspeedunit" id="shutterspeedunit" for="shutterspeed">µs</output>
                  </div>
              </div>

            <div class="form-group">
                <label class="control-label col-sm-4" for="isosensitivity">Iso Sensitivity:</label>
                  <div class="col-sm-5">
                    <input name="isosensitivity" type="number" class="form-control" id="isosensitivity" form="frm-camerasettings" max="800" min="0" step="50" <?php echo "value=" . $camerasettings['iso'];?>>
                </div>
                  <div class="col-sm-2">
					<output name="isosensitivityunit" id="isosensitivityunit" for="isosensitivity">lux</output>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="jpegquality">Jpeg Quality:</label>
                  <div class="col-sm-5">
                    <input name="jpegquality" type="range" class="form-control" id="jpegquality" form="frm-camerasettings" max="100" min="25" step="5" <?php echo "value=" . $camerasettings['quality'];?> oninput="jpegqualitynum.value=jpegquality.value">
                  </div>
                  <div class="col-sm-2">
					<output name="jpegqualitynum" id="jpegqualitynum" for="jpegquality"><?php echo $camerasettings['quality'];?></output>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="framerate">Frame Rate:</label>
                  <div class="col-sm-5">
                    <input name="framerate" type="number" class="form-control" id="framerate" form="frm-camerasettings" max="120" min="0" step="1" <?php if(!empty($camerasettings['framerate'])) {echo "value=" . $camerasettings['framerate'];}?>>
                  </div>
                  <div class="col-sm-2">
					fps.
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="bitrate">Streaming Bitrate:</label>
                  <div class="col-sm-5">
                    <input name="bitrate" type="range" class="form-control" id="bitrate" form="frm-camerasettings" max="17000000" min="100000" step="100000" <?php echo "value=" . intval($camerasettings['bitrate']);?> oninput="bitratenum.value=Math.round(bitrate.value/1000);">
                  </div>
                  <div class="col-sm-2">
					<output name="bitratenum" id="bitratenum" for="bitrate"><?php echo intval($camerasettings['bitrate']/1000) ;?> Kb/s</output>
                  </div>

              </div><!--form group-->


              <div class="form-group">
                <label class="control-label col-sm-4" for="horizontalmirror">Horizontal Mirror:</label>
                  <div class="col-sm-1">
                    <input name="horizontalmirror" type="checkbox" class="form-control" id="horizontalmirror" form="frm-camerasettings" value="1" <?php if ($camerasettings['hflip'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="verticalmirror">Vertical Mirror:</label>
                  <div class="col-sm-1">
                    <input name="verticalmirror" type="checkbox" class="form-control" id="verticalmirror" form="frm-camerasettings" value="1" <?php if ($camerasettings['vflip'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="textoverlay">Text Overlay:</label>
                  <div class="col-sm-1">
                    <input name="textoverlay" type="checkbox" class="form-control" id="textoverlay" form="frm-camerasettings" value="1" <?php if ($camerasettings['text-overlay'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="objectfacedetection">Object/Face detection:</label>
                  <div class="col-sm-1">
                    <input name="objectfacedetection" type="checkbox" class="form-control" id="objectfacedetection" form="frm-camerasettings" value="1" <?php if ($camerasettings['object-detection'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="stillsdenoise">Stills denoise:</label>
                  <div class="col-sm-1">
                    <input name="stillsdenoise" type="checkbox" class="form-control" id="stillsdenoise" form="frm-camerasettings" value="1" <?php if ($camerasettings['stills-denoise'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="videodenoise">Video denoise:</label>
                  <div class="col-sm-1">
                    <input name="videodenoise" type="checkbox" class="form-control" id="videodenoise" form="frm-camerasettings" value="1" <?php if ($camerasettings['video-denoise'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="imagestabilisation">Image Stabilisation:</label>
                  <div class="col-sm-1">
                    <input name="imagestabilisation" type="checkbox" class="form-control" id="imagestabilisation" form="frm-camerasettings" value="1" <?php if ($camerasettings['vstab'] == "1") {echo "checked";}?>>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="awbmode">AWB Mode:</label>
                  <div class="col-sm-5">
                    <select name="awbmode" class="form-control" id="awbmode" form="frm-camerasettings">
                      <option value="auto" <?php if($camerasettings['awb'] == "auto") {echo "selected='selected'";}?>>auto</option>
                      <option value="cloudy" <?php if($camerasettings['awb'] == "cloudy") {echo "selected='selected'";}?>>cloudy</option>
                      <option value="flash" <?php if($camerasettings['awb'] == "flash") {echo "selected='selected'";}?>>flash</option>
                      <option value="fluorescent" <?php if($camerasettings['awb'] == "fluorescent") {echo "selected='selected'";}?>>fluorescent</option>
                      <option value="horizon" <?php if($camerasettings['awb'] == "horizon") {echo "selected='selected'";}?>>horizon</option>
                      <option value="incandescent" <?php if($camerasettings['awb'] == "incandescent") {echo "selected='selected'";}?>>incandescent</option>
                      <option value="off" <?php if($camerasettings['awb'] == "off") {echo "selected='selected'";}?>>off</option>
                      <option value="shade" <?php if($camerasettings['awb'] == "shade") {echo "selected='selected'";}?>>shade</option>
                      <option value="sun" <?php if($camerasettings['awb'] == "sun") {echo "selected='selected'";}?>>sun</option>
                      <option value="tungsten" <?php if($camerasettings['awb'] == "tungsten") {echo "selected='selected'";}?>>tungsten</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="exposuremode">Exposure Mode:</label>
                  <div class="col-sm-5">
                    <select name="exposuremode" class="form-control" id="exposuremode" form="frm-camerasettings">
                      <option value="antishake" <?php if($camerasettings['exposure'] == "antishake") {echo "selected='selected'";}?>>antishake</option>
                      <option value="auto" <?php if($camerasettings['exposure'] == "auto") {echo "selected='selected'";}?>>auto</option>
                      <option value="backlight" <?php if($camerasettings['exposure'] == "backlight") {echo "selected='selected'";}?>>backlight</option>
                      <option value="beach" <?php if($camerasettings['exposure'] == "beach") {echo "selected='selected'";}?>>beach</option>
                      <option value="fireworks" <?php if($camerasettings['exposure'] == "fireworks") {echo "selected='selected'";}?>>fireworks</option>
                      <option value="fixedfps" <?php if($camerasettings['exposure'] == "fixedfps") {echo "selected='selected'";}?>>fixedfps</option>
                      <option value="night" <?php if($camerasettings['exposure'] == "night") {echo "selected='selected'";}?>>night</option>
                      <option value="nightpreview" <?php if($camerasettings['exposure'] == "nightpreview") {echo "selected='selected'";}?>>nightpreview</option>
                      <option value="snow" <?php if($camerasettings['exposure'] == "snow") {echo "selected='selected'";}?>>snow</option>
                      <option value="sports" <?php if($camerasettings['exposure'] == "sports") {echo "selected='selected'";}?>>sports</option>
                      <option value="spotlight" <?php if($camerasettings['exposure'] == "spotlight") {echo "selected='selected'";}?>>spotlight</option>
                      <option value="verylong" <?php if($camerasettings['exposure'] == "verylong") {echo "selected='selected'";}?>>verylong</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="exposuremetering">Exposure Metering:</label>
                  <div class="col-sm-5">
                    <select name="exposuremetering" class="form-control" id="exposuremetering" form="frm-camerasettings">
                      <option value="average" <?php if($camerasettings['metering'] == "average") {echo "selected='selected'";}?>>average</option>
                      <option value="backlit" <?php if($camerasettings['metering'] == "backlit") {echo "selected='selected'";}?>>backlit</option>
                      <option value="matrix" <?php if($camerasettings['metering'] == "matrix") {echo "selected='selected'";}?>>matrix</option>
                      <option value="spot" <?php if($camerasettings['metering'] == "spot") {echo "selected='selected'";}?>>spot</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="drcstrenght">DRC Strenght:</label>
                  <div class="col-sm-5">
                    <select name="drcstrenght" class="form-control" id="drcstrenght" form="frm-camerasettings">
                      <option value="high" <?php if($camerasettings['drc'] == "high") {echo "selected='selected'";}?>>high</option>
                      <option value="low" <?php if($camerasettings['drc'] == "low") {echo "selected='selected'";}?>>low</option>
                      <option value="medium" <?php if($camerasettings['drc'] == "medium") {echo "selected='selected'";}?>>medium</option>
                      <option value="off" <?php if($camerasettings['drc'] == "off") {echo "selected='selected'";}?>>off</option>
                    </select>
                  </div>
              </div><!--form group-->
          </div><!-- end div col-sm-10 -->
          <div class="col-sm-1"></div>
        </div><!-- end div row -->
        <br>
        <div class="form-group">
          <div class="col-sm-4"></div>
            <div class="col-sm-5">
              <input name="btn-camerasettings-apply" type="submit" class="form-control btn btn-primary" id="btn-camerasettings-apply" form="frm-camerasettings" value="Apply">
            </div>
        </div><!--form group-->
      </div><!-- end div panel body -->
    </div><!-- end div panel -->
  </form>
 </div><!-- end div container -->
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
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-camerasettings-apply'])) {
		// Only continue when no errors are found.
		if(/*empty($widtherr) && empty($heighterr) && */empty($resolutionerr) && empty($formaterr) && empty($brightnesserr) && empty($contrasterr) && empty($saturationerr) && empty($redbalanceerr) && empty($bluebalanceerr) && empty($sharpnesserr) && empty($rotateerr) && empty($shutterspeederr) && /*empty($zoomfactorerr) && */empty($isosensitivityerr) && empty($jpegqualityerr) && empty($framerateerr) && empty($horizontalmirrorerr) && empty($verticalmirrorerr) && empty($textoverlayerr) && empty($objectfacedetectionerr) && empty($stillsdenoiseerr) && empty($videodenoiseerr) && empty($imagestabilisationerr) && empty($awbmodeerr) && empty($exposuremodeerr) && empty($exposuremeteringerr) && empty($drcstrenghterr)) {
			
			if($camerasettings['encoding'] == "mjpeg") {
				shell_exec("sudo mount -o rw,remount,rw /");
				logmessage("Stopping & disabling RTSP server if running.");
				shell_exec("sudo systemctl stop RTSP-Server.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
				shell_exec("sudo systemctl disable RTSP-Server.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
				logmessage("Restarting uv4l_raspicam.service via systemctl.");
				shell_exec("sudo systemctl restart uv4l_raspicam.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log"); 
				shell_exec("sudo mount -o ro,remount,ro /");
			}
			
			if($camerasettings['encoding'] == "h264") {
				shell_exec("sudo mount -o rw,remount,rw /");
				logmessage("Restarting uv4l_raspicam.service via systemctl.");
				shell_exec("sudo systemctl restart uv4l_raspicam.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log"); 
				logmessage("Starting & enabling RTSP server if stopped.");
				shell_exec("sudo systemctl enable RTSP-Server.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
				shell_exec("sudo systemctl stop RTSP-Server.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
				shell_exec("sudo systemctl start RTSP-Server.service 2>&1 | sudo tee -a /var/log/RaspberryIPCamera.log");
				shell_exec("sudo mount -o ro,remount,ro /");
			}
		}
	}
  ?>
<!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>
