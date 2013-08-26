<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Kelihos Tracker | KelihosTracker.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
	
	<!-- jQuery -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="assets/js/jquery.base64.min.js" ></script>
	<script src="assets/js/jquery.md5.js" ></script>
	<script src="assets/js/engine.js"></script>
	<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.js"></script>
	

		

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons 
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="assets/ico/favicon.png">-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Kelihos Tracker | KelihosTracker.com</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">

            </p>
            <ul class="nav">
              <li><a href="/">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
           <li class="nav-header">Menu</li>
              <li><a href="/">Overview</a></li>
              <li class="nav-header">Domains</li>
			  <?php
			  if($_GET['id'] == "Active") {
			  echo '<li class="active"><a href="/domains.php?id=Active">Active</a></li>';
			  } else {
			  echo '<li><a href="/domains.php?id=Active">Active</a></li></li>';
			  }
			  if($_GET['id'] == "Suspended") {
			  echo '<li class="active"><a href="/domains.php?id=Suspended">Suspended</a></li>';
			  } else {
			  echo '<li><a href="/domains.php?id=Suspended">Suspended</a></li>';
			  }
			  ?>
				<li class="nav-header">IP Addresses</li>
              <li><a href="/ip.php?id=Daily">List Daily</a></li>
              <li><a href="/ip.php?id=Full">List Full</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
	  
<div class="hero-unit">
<?php 
if($_GET['id'] == "Active") {
?>
<h2>Domains - Active</h2>
<div id="dActive"></div>
<?php 
}
?>

<?php 
if($_GET['id'] == "Suspended") {
?>
<h2>Domains - Suspended</h2>
<div id="dSuspended"></div>
<?php 
}
?>

<footer>
</footer>
</div><!--/.fluid-container-->

    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<?php 
if($_GET['id'] == "Active") {
?>
<script>
$.getJSON( "engine/statistics.php", { request: "dActive" } )
.done(function( json ) {
$('#dActive').html( '<table cellpadding="0" class="table table-striped table-bordered table-hover" cellspacing="0" border="0" id="dActiveTable"></table>' );
				$('#dActiveTable').dataTable( {
					"aaData": json,
					"iDisplayLength": 25,
					"aoColumns": [
						{ "sTitle": "Domain" },
						{ "sTitle": "Registrar" },
						{ "sTitle": "Date", "sClass": "center" }
						]
				} );

})
</script>
<?php 
}
?>

<?php 
if($_GET['id'] == "Suspended") {
?>
<script>
$.getJSON( "engine/statistics.php", { request: "dSuspended" } )
.done(function( json ) {
$('#dSuspended').html( '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover" id="dSuspendedTable"></table>' );
				$('#dSuspendedTable').dataTable( {
					"aaData": json,
					"iDisplayLength": 25,
					"aoColumns": [
						{ "sTitle": "Domain" },
						{ "sTitle": "Registrar" },
						{ "sTitle": "Date", "sClass": "center" }

						]
				} );

})
</script>

<?php 
}
?>
<script type="text/javascript" src="http://script.opentracker.net/?site=kelihostracker.com"></script><noscript><a href="http://www.opentracker.net" target="_blank"><img src="http://img.opentracker.net/?cmd=nojs&site=kelihostracker.com" alt="user analytics" border="0"></a></noscript>
</body>
</html>
