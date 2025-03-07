<html>
<head>
	<!----------Header----------->
	<?php include('cmo_header.php');?>
</head>
<body onload="fillFunction()">
	<!----------Top Navigation Bar-------->
	<?php include('cmo_top_nav_bar.php');?>

	<!----------Side Navigation Bar-------->
	<?php include('../script/side_nav_bar.php');?>

	<!----------------Publish Incident-------------->
	<div class = "main-content">
	<div class="container">
	<div class="row">
	<div class="col-md-6">
		<h3 class="label">Publish Incident</h3>
		<h5 class="label" id="labelMsg"></h5><br/>
	</div>
	</div>
	<a href="cmo_publish_incident.php" class="btn btn-light">Back</a>
	</div>
	</div>

	<!----------Footer----------->
	<div class = "main-content">
		<?php include('../includes/footer.php');?>
	</div>
</body>
</html>

<script>


function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&]*)|&|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function fillFunction(){

        startTime();
	var labelMsg = document.getElementById('labelMsg');

	var incidentId = getParameterByName('incidentId');
	var emergencyType = getParameterByName('emergencyType');
	var location = getParameterByName('location');

	labelMsg.textContent = "Incident " + incidentId + " - " + emergencyType + " at " + location + " has been published.";

}
</script>