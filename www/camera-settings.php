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
	<?php logmessage("Loading page camera-settings.php");?>
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
              <li><a href="network-settings.php">Network Settings</a></li>
              <li class="active"><a href="camera-settings.php">Camera Settings</a></li>
			  <!-- InstanceEndEditable -->
          </ul>
        </div>
      </div>
  </nav>

  <!-- InstanceBeginEditable name="body" -->
<!-- ********************************************************************************************************************** -->
  <?php
	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['btn-camerasettings-apply'])) {
		
		$width = $height = $format = $brightness = $contrast = $saturation = $redbalance = "";
		$bluebalance = $sharpness = $rotate = $shutterspeed = $zoomfactor = $isosensitivity = "";
		$jpegquality = $framerate = $horizontalmirror = $verticalmirror = $textoverlay = "";
		$objectfacedetection = $stillsdenoise = $videodenoise = $imagestabilisation = $awbmode = "";
		$exposuremode = $exposuremetering = $drcstrenght = "";

		$widtherr = $heighterr = $formaterr = $brightnesserr = $contrasterr = $saturationerr = $redbalanceerr = "";
		$bluebalanceerr = $sharpnesserr = $rotateerr = $shutterspeederr = $zoomfactorerr = $isosensitivityerr = "";
		$jpegqualityerr = $framerateerr = $horizontalmirrorerr = $verticalmirrorerr = $textoverlayerr = "";
		$objectfacedetectionerr = $stillsdenoiseerr = $videodenoiseerr = $imagestabilisationerr = $awbmodeerr = "";
		$exposuremodeerr = $exposuremeteringerr = $drcstrenghterr = "";
		
		if (!empty($_POST["width"])) {
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
		}

		if (!empty($_POST["format"])) {
		  $format = test_input($_POST["format"]);
		  if($format != "MJPEG" || $format != "H264") {
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
		  if (!preg_match("/^[0-9]*$/",$contrast)) {
			$contrasterr = "contrast field contains incorrect data, only 0-9 allowed!"; 
			logmessage($contrasterr);
		  }
		}

		if (!empty($_POST["saturation"])) {
		  $saturation = test_input($_POST["saturation"]);
		  if (!preg_match("/^[0-9]*$/",$saturation)) {
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
		  if (!preg_match("/^[0-9]*$/",$sharpness)) {
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

		if (!empty($_POST["zoomfactor"])) {
		  $zoomfactor = test_input($_POST["zoomfactor"]);
		  if (!preg_match("/^[0-9]*$/",$zoomfactor)) {
			$zoomfactorerr = "zoomfactor field contains incorrect data, only 0-9 allowed!"; 
			logmessage($zoomfactorerr);
		  }
		}

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
		
		if (!empty($_POST["horizontalmirror"])) {
		  $horizontalmirror = test_input($_POST["horizontalmirror"]);
		  if ($horizontalmirror != "on") {
			$horizontalmirrorerr = "Incorrect response received from horizontalmirror checkbox!"; 
			logmessage($horizontalmirror);
		  }
		}

		if (!empty($_POST["verticalmirror"])) {
		  $verticalmirror = test_input($_POST["verticalmirror"]);
		  if ($verticalmirror != "on") {
			$verticalmirrorerr = "Incorrect response received from verticalmirror checkbox!"; 
			logmessage($verticalmirrorerr);
		  }
		}

		if (!empty($_POST["textoverlay"])) {
		  $textoverlay = test_input($_POST["textoverlay"]);
		  if ($textoverlay != "on") {
			$textoverlayerr = "Incorrect response received from textoverlay checkbox!"; 
			logmessage($textoverlayerr);
		  }
		}

		if (!empty($_POST["objectfacedetection"])) {
		  $objectfacedetection = test_input($_POST["objectfacedetection"]);
		  if ($objectfacedetection != "on") {
			$objectfacedetectionerr = "Incorrect response received from objectfacedetection checkbox!"; 
			logmessage($objectfacedetectionerr);
		  }
		}

		if (!empty($_POST["stillsdenoise"])) {
		  $stillsdenoise = test_input($_POST["stillsdenoise"]);
		  if ($stillsdenoise != "on") {
			$stillsdenoiseerr = "Incorrect response received from stillsdenoise checkbox!"; 
			logmessage($stillsdenoiseerr);
		  }
		}

		if (!empty($_POST["videodenoise"])) {
		  $videodenoise = test_input($_POST["videodenoise"]);
		  if ($videodenoise != "on") {
			$videodenoiseerr = "Incorrect response received from videodenoise checkbox!"; 
			logmessage($videodenoiseerr);
		  }
		}

		if (!empty($_POST["imagestabilisation"])) {
		  $imagestabilisation = test_input($_POST["imagestabilisation"]);
		  if ($imagestabilisation != "on") {
			$imagestabilisationerr = "Incorrect response received from imagestabilisation checkbox!"; 
			logmessage($imagestabilisationerr);
		  }
		}

		if (!empty($_POST["awbmode"])) {
		  $awbmode = test_input($_POST["awbmode"]);
		  if($awbmode != "auto" || $awbmode != "cloudy" || $awbmode != "flash" || $awbmode != "fluorescent" || $awbmode != "horizon" || $awbmode != "incandescent" || $awbmode != "off" || $awbmode != "shade" || $awbmode != "sun" || $awbmode != "tungsten") {
			  $awbmodeerr = "Only auto, cloudy, flash, fluorescent, horizon, incandescent,  off, sun, shade, or tungsten is allowed as input for awbmode selector!";
			  logmessage($awbmodeerr);
		  }
		}

		if (!empty($_POST["exposuremode"])) {
		  $exposuremode = test_input($_POST["exposuremode"]);
		  if($exposuremode != "antishake" || $exposuremode != "auto" || $exposuremode != "backlight" || $exposuremode != "beach" || $exposuremode != "fireworks" || $exposuremode != "fixedfps" || $exposuremode != "night" || $exposuremode != "nightpreview" || $exposuremode != "snow" || $exposuremode != "sports" || $exposuremode != "spotlight" || $exposuremode != "verylong") {
			$exposuremodeerr = "Only antishake, auto, backlight, beach, fireworks, fixedfps, night, nightpreview, snow, sports, spotlight, or verylong is allowed as input for exposuremode selector!"; 
			logmessage($exposuremodeerr);
		  }
		}

		if (!empty($_POST["exposuremetering"])) {
		  $exposuremetering = test_input($_POST["exposuremetering"]);
		  if($exposuremetering != "average" || $exposuremetering != "backlit" || $exposuremetering != "matrix" || $exposuremetering != "spot") { 
			$exposuremeteringerr = "Only average, backlit, matrix or spot is allowed as input for exposuremetering selector!"; 
			logmessage($exposuremeteringerr);
		  }
		}

		if (!empty($_POST["drcstrenght"])) {
		  $drcstrenght = test_input($_POST["drcstrenght"]);
		  if($drcstrenght != "high" || $drcstrenght != "low" || $drcstrenght != "medium" || $drcstrenght != "off") {
			$drcstrenghterr = "Only high, low, medium, or off is allowed as input for drcstrenght selector!"; 
			logmessage($drcstrenghterr);
		  }
		}
		
		// Only continue when no errors are found.
		if(empty($widtherr) && empty($heighterr) && empty($formaterr) && empty($brightnesserr) && empty($contrasterr) && empty($saturationerr) && empty($redbalanceerr) && empty($bluebalanceerr) && empty($sharpnesserr) && empty($rotateerr) && empty($shutterspeederr) && empty($zoomfactorerr) && empty($isosensitivityerr) && empty($jpegqualityerr) && empty($framerateerr) && empty($horizontalmirrorerr) && empty($verticalmirrorerr) && empty($textoverlayerr) && empty($objectfacedetectionerr) && empty($stillsdenoiseerr) && empty($videodenoiseerr) && empty($imagestabilisationerr) && empty($awbmodeerr) && empty($exposuremodeerr) && empty($exposuremeteringerr) && empty($drcstrenghterr)) {
			
		
		
		
		
		
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
                <label class="control-label col-sm-4" for="width">Width:</label>
                  <div class="col-sm-5">
                    <input name="width" type="number" class="form-control" id="width" form="frm-camerasettings" min="64" step="8" value="1920">
                  </div>
              </div><!--form group-->
              <div class="form-group">
                <label class="control-label col-sm-4" for="height">Height:</label>
                <div class="col-sm-5">
                  <input name="height" type="number" class="form-control" id="height" form="frm-camerasettings" min="64" step="8" value="1080">
                </div>
              </div><!--form group-->
              <div class="form-group">
                <label class="control-label col-sm-4" for="format">Format:</label>
                <div class="col-sm-5">
                  <select name="format" class="form-control" id="format" form="frm-camerasettings">
                    <option value="MJPEG">MJPEG Video (streamable)</option>
                    <option value="H264" selected="SELECTED">H264 (raw, streamable)</option>
                  </select>            
                </div>
              </div><!--form group-->
              <div class="alert alert-info">
                <strong>Info!</strong> NOTE: if the camera is already in use for streaming by another application/client, applying the resolution &amp; format will not have any effect (until all the streaming sessions have been closed).
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
                    <input name="brightness" type="range" class="form-control" id="brightness" form="frm-camerasettings" max="100" min="0" step="1" value="50">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="contrast">Contrast:</label>
                  <div class="col-sm-5">
                    <input name="contrast" type="range" class="form-control" id="contrast" form="frm-camerasettings" max="100" min="-100" step="1" value="0">
                  </div>
              </div><!--form group-->
 
              <div class="form-group">
                <label class="control-label col-sm-4" for="saturation">Saturation:</label>
                  <div class="col-sm-5">
                    <input name="saturation" type="range" class="form-control" id="saturation" form="frm-camerasettings" max="100" min="-100" step="1" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="redbalance">Red Balance:</label>
                  <div class="col-sm-5">
                    <input name="redbalance" type="range" class="form-control" id="redbalance" form="frm-camerasettings" max="800" min="0" step="1" value="100">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="bluebalance">Blue Balance:</label>
                  <div class="col-sm-5">
                    <input name="bluebalance" type="range" class="form-control" id="bluebalance" form="frm-camerasettings" max="800" min="0" step="1" value="100">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="sharpness">Sharpness:</label>
                  <div class="col-sm-5">
                    <input name="sharpness" type="range" class="form-control" id="sharpness" form="frm-camerasettings" max="100" min="-100" step="1" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="rotate">Rotate:</label>
                  <div class="col-sm-5">
                    <input name="rotate" type="range" class="form-control" id="rotate" form="frm-camerasettings" max="360" min="0" step="90" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="shutterspeed">Shutter Speed:</label>
                  <div class="col-sm-5">
                    <input name="shutterspeed" type="number" class="form-control" id="shutterspeed" form="frm-camerasettings" max="65535" min="0" step="1" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="zoomfactor">Zoom Factor:</label>
                  <div class="col-sm-5">
                    <input name="zoomfactor" type="number" class="form-control" id="zoomfactor" form="frm-camerasettings" max="8" min="1" step="1" value="1">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="isosensitivity">Iso Sensitivity:</label>
                  <div class="col-sm-5">
                    <input name="isosensitivity" type="number" class="form-control" id="isosensitivity" form="frm-camerasettings" max="800" min="0" step="50" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="jpegquality">Jpeg Quality:</label>
                  <div class="col-sm-5">
                    <input name="jpegquality" type="number" class="form-control" id="jpegquality" form="frm-camerasettings" max="100" min="1" step="1" value="85">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="framerate">Frame Rate:</label>
                  <div class="col-sm-5">
                    <input name="framerate" type="number" class="form-control" id="framerate" form="frm-camerasettings" max="120" min="0" step="1" value="30">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="horizontalmirror">Horizontal Mirror:</label>
                  <div class="col-sm-1">
                    <input name="horizontalmirror" type="checkbox" class="form-control" id="horizontalmirror" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="verticalmirror">Vertical Mirror:</label>
                  <div class="col-sm-1">
                    <input name="verticalmirror" type="checkbox" class="form-control" id="verticalmirror" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="textoverlay">Text Overlay:</label>
                  <div class="col-sm-1">
                    <input name="textoverlay" type="checkbox" class="form-control" id="textoverlay" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="objectfacedetection">Object/Face detection:</label>
                  <div class="col-sm-1">
                    <input name="objectfacedetection" type="checkbox" class="form-control" id="objectfacedetection" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="stillsdenoise">Stills denoise:</label>
                  <div class="col-sm-1">
                    <input name="stillsdenoise" type="checkbox" class="form-control" id="stillsdenoise" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="videodenoise">Video denoise:</label>
                  <div class="col-sm-1">
                    <input name="videodenoise" type="checkbox" class="form-control" id="videodenoise" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="imagestabilisation">Image Stabilisation:</label>
                  <div class="col-sm-1">
                    <input name="imagestabilisation" type="checkbox" class="form-control" id="imagestabilisation" form="frm-camerasettings" value="on">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="awbmode">AWB Mode:</label>
                  <div class="col-sm-5">
                    <select name="awbmode" class="form-control" id="awbmode" form="frm-camerasettings">
                      <option value="auto" selected="SELECTED">auto</option>
                      <option value="cloudy">cloudy</option>
                      <option value="flash">flash</option>
                      <option value="fluorescent">fluorescent</option>
                      <option value="horizon">horizon</option>
                      <option value="incandescent">incandescent</option>
                      <option value="off">off</option>
                      <option value="shade">shade</option>
                      <option value="sun">sun</option>
                      <option value="tungsten">tungsten</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="exposuremode">Exposure Mode:</label>
                  <div class="col-sm-5">
                    <select name="exposuremode" class="form-control" id="exposuremode" form="frm-camerasettings">
                      <option value="antishake">antishake</option>
                      <option value="auto" selected="SELECTED">auto</option>
                      <option value="backlight">backlight</option>
                      <option value="beach">beach</option>
                      <option value="fireworks">fireworks</option>
                      <option value="fixedfps">fixedfps</option>
                      <option value="night">night</option>
                      <option value="nightpreview">nightpreview</option>
                      <option value="snow">snow</option>
                      <option value="sports">sports</option>
                      <option value="spotlight">spotlight</option>
                      <option value="verylong">verylong</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="exposuremetering">Exposure Metering:</label>
                  <div class="col-sm-5">
                    <select name="exposuremetering" class="form-control" id="exposuremetering" form="frm-camerasettings">
                      <option selected="selected" value="0">average</option>
                      <option value="1">backlit</option>
                      <option value="2">matrix</option>
                      <option value="3">spot</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="drcstrenght">DRC Strenght:</label>
                  <div class="col-sm-5">
                    <select name="drcstrenght" class="form-control" id="drcstrenght" form="frm-camerasettings">
                      <option value="high">high</option>
                      <option value="low">low</option>
                      <option value="medium">medium</option>
                      <option value="off" selected="SELECTED">off</option>
                    </select>
                  </div>
              </div><!--form group-->
          </div><!-- end div col-sm-10 -->
          <div class="col-sm-1"></div>
        </div><!-- end div row -->
      </div><!-- end div panel body -->
    </div><!-- end div panel -->

  <div class="alert alert-info">
    <strong>Notes</strong>
    <ul>
      <li>If you want to turn on <i>text-overlay</i> while the camera is in use by another application AND <i>text-overlay</i> was turned off when that application opened the Camera, you need to close that application first.  The same consideration is valid for <i>object-detection</i> (<i>face</i> detection by default). In case, you can also turn on these options when loading the driver.</li>
      <li>When <i>text-overlay</i> is enabled, both image width and image height should be multiple of 16.</li>
      <li><i>red-balance</i> and <i>blue-balance</i> have effect only when <i>awb mode</i> is set to <i>off</i>.</li>
      <li><b>Many other controls are available on driver loading only.</b></li>
    </ul>
  </div><!--end dif alert info-->

  <div class="well well-sm">
    <div class="form-group">
      <div class="col-sm-4"></div>
        <div class="col-sm-5">
          <input name="btn-camerasettings-apply" type="button" class="form-control btn btn-primary" id="btn-camerasettings-apply" form="frm-camerasettings" value="Apply">
        </div>
    </div><!--form group-->
  </div>
  </form>
 </div><!-- end div container -->
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
  <!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>