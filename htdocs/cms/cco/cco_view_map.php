<html>
<head>
	<!----------Header----------->
	<?php include('cco_header.php');?>
</head>
<body>
	<!------------------------Navigation bar-------------------->
	<!----------Top Navigation Bar-------->
	<?php include('cco_top_nav_bar.php');?>

	<!----------Side Navigation Bar-------->
	<?php include('../script/side_nav_bar.php');?>
	
	<!----------------Map-------------->
	<div class = "main-content">
	<div class="container">
	<div class="row">
	<div class="col-md-12">
	<h3 class="label">Map</h3>
	<p>Click on the buttons to get latest map of the respective emergencies</p>
	<center>
		<label for="chkSevere">Severe Only</label>
		<input type="checkbox" id="chkSevere" name="chkSevere" value="Severe Only"></input>
		<input type="button" class="btn btn-outline-danger" id="rbCrises" value="Incidents"></input>
		<input type="button" class="btn btn-outline-primary" id="rbWeather" value="Weather"></input>
		<input type="button" class="btn btn-outline-success" id="rbDengue" value="Dengue"></input>
		<input type="button" class="btn btn-outline-secondary" id="rbHaze" value="Haze"></input>
		<input type="hidden" id="userId" value="<?php session_start(); echo isset($_SESSION['username']) ?>"></input>
	</center><br/>

        <div id="map" style="height: 70%;width: 80%;margin: 0 auto"></div>
    <script src="../script/map-script.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-QzO882Yq3gtz_m7yjxHXwhJhFMTSW2c&callback=initMap"
    async defer></script>
	</div>
	</div>
	</div>
	</div>
	<!----------Footer----------->
	<div class = "main-content">
		<?php include('../includes/footer.php');?>
	</div>
</body>
</html>