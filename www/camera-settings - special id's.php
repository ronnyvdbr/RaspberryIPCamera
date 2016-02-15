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
            <a href="Status.php" class="pull-left">
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
              <li><a href="logout.php">Log Off</a></li>
          </ul>
        </div>
      </div>
  </nav>

  <!-- InstanceBeginEditable name="body" -->
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
                    <option style="color:#00FF00" value="1196444237">MJPEG Video (streamable)</option>
                    <option style="color:#0000FF" selected="selected" value="875967048">H264 (raw, streamable)</option>
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
                <label class="control-label col-sm-4" for="timeserver">Brightness:</label>
                  <div class="col-sm-5">
                    <input name="9963776" type="range" class="form-control" id="9963776" form="frm-camerasettings" max="100" min="0" step="1" value="50">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Contrast:</label>
                  <div class="col-sm-5">
                    <input name="9963777" type="range" class="form-control" id="9963777" form="frm-camerasettings" max="100" min="-100" step="1" value="0">
                  </div>
              </div><!--form group-->
 
              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Saturation:</label>
                  <div class="col-sm-5">
                    <input name="9963778" type="range" class="form-control" id="9963778" form="frm-camerasettings" max="100" min="-100" step="1" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Red Balance:</label>
                  <div class="col-sm-5">
                    <input name="9963790" type="range" class="form-control" id="9963790" form="frm-camerasettings" max="800" min="0" step="1" value="100">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Blue Balance:</label>
                  <div class="col-sm-5">
                    <input name="9963791" type="range" class="form-control" id="9963791" form="frm-camerasettings" max="800" min="0" step="1" value="100">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Sharpness:</label>
                  <div class="col-sm-5">
                    <input name="9963803" type="range" class="form-control" id="9963803" form="frm-camerasettings" max="100" min="-100" step="1" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Rotate:</label>
                  <div class="col-sm-5">
                    <input name="9963810" type="range" class="form-control" id="9963810" form="frm-camerasettings" max="360" min="0" step="90" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Shutter Speed:</label>
                  <div class="col-sm-5">
                    <input name="134217728" type="number" class="form-control" id="134217728" form="frm-camerasettings" max="65535" min="0" step="1" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Zoom Factor:</label>
                  <div class="col-sm-5">
                    <input name="134217729" type="number" class="form-control" id="134217729" form="frm-camerasettings" max="8" min="1" step="1" value="1">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Iso Sensitivity:</label>
                  <div class="col-sm-5">
                    <input name="134217730" type="number" class="form-control" id="134217730" form="frm-camerasettings" max="800" min="0" step="50" value="0">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Jpeg Quality:</label>
                  <div class="col-sm-5">
                    <input name="134217739" type="number" class="form-control" id="134217739" form="frm-camerasettings" max="100" min="1" step="1" value="85">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Frame Rate:</label>
                  <div class="col-sm-5">
                    <input name="134217741" type="number" class="form-control" id="134217741" form="frm-camerasettings" max="120" min="0" step="1" value="30">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Horizontal Mirror:</label>
                  <div class="col-sm-1">
                    <input name="9963796" type="checkbox" class="form-control" id="9963796" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Vertical Mirror:</label>
                  <div class="col-sm-1">
                    <input name="9963797" type="checkbox" class="form-control" id="9963797" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Text Overlay:</label>
                  <div class="col-sm-1">
                    <input name="134217734" type="checkbox" class="form-control" id="134217734" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Object/Face detection:</label>
                  <div class="col-sm-1">
                    <input name="134217736" type="checkbox" class="form-control" id="134217736" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Stills denoise:</label>
                  <div class="col-sm-1">
                    <input name="134217737" type="checkbox" class="form-control" id="134217737" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Video denoise:</label>
                  <div class="col-sm-1">
                    <input name="134217738" type="checkbox" class="form-control" id="134217738" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Image Stabilisation:</label>
                  <div class="col-sm-1">
                    <input name="134217740" type="checkbox" class="form-control" id="134217740" form="frm-camerasettings" value="">
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">AWB Mode:</label>
                  <div class="col-sm-5">
                    <select name="134217731" class="form-control" id="134217731" form="frm-camerasettings">
                      <option selected="selected" value="0">auto</option>
                      <option value="1">cloudy</option>
                      <option value="2">flash</option>
                      <option value="3">fluorescent</option>
                      <option value="4">horizon</option>
                      <option value="5">incandescent</option>
                      <option value="6">off</option>
                      <option value="7">shade</option>
                      <option value="8">sun</option>
                      <option value="9">tungsten</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Exposure Mode:</label>
                  <div class="col-sm-5">
                    <select name="134217732" class="form-control" id="134217732" form="frm-camerasettings">
                      <option value="0">antishake</option>
                      <option selected="selected" value="1">auto</option>
                      <option value="2">backlight</option>
                      <option value="3">beach</option>
                      <option value="4">fireworks</option>
                      <option value="5">fixedfps</option>
                      <option value="6">night</option>
                      <option value="7">nightpreview</option>
                      <option value="8">snow</option>
                      <option value="9">sports</option>
                      <option value="10">spotlight</option>
                      <option value="11">verylong</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Exposure Metering:</label>
                  <div class="col-sm-5">
                    <select name="134217733" class="form-control" id="134217733" form="frm-camerasettings">
                      <option selected="selected" value="0">average</option>
                      <option value="1">backlit</option>
                      <option value="2">matrix</option>
                      <option value="3">spot</option>
                    </select>
                  </div>
              </div><!--form group-->

              <div class="form-group">
                <label class="control-label col-sm-4" for="timeserver">Exposure Metering:</label>
                  <div class="col-sm-5">
                    <select name="134217735" class="form-control" id="134217735" form="frm-camerasettings">
                      <option value="0">high</option>
                      <option value="1">low</option>
                      <option value="2">medium</option>
                      <option selected="selected" value="3">off</option>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <!-- InstanceBeginEditable name="php code" -->
  <!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>