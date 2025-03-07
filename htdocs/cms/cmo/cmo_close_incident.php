<html>
<head>
	<!----------Header----------->
	<?php include('../classes/cmoController.php'); ?>
	<?php include('cmo_header.php');?>
</head>
<body>
	<!----------Top Navigation Bar-------->
	<?php include('cmo_top_nav_bar.php');?>

	<!----------Side Navigation Bar-------->
	<?php include('../script/side_nav_bar.php');?>

	<!----------------View Incident-------------->
	<section>
		<div class = "main-content">
		<div class="container">
		<div class="row">
		<div class="col-md-8">
		<h3 class="label">View Incident</h3>
		<?php
			if(isset($_GET['close'])!= true){
				header("Location: cmo_update_incident_status.php");
				exit();
			}

			$incidentController = new cmoController();
			//$incidentController->setAccessToken("cmo");
			$incidentId = $_GET['incidentId'];
			$result = $incidentController->getSpecificIncident($incidentId);

			if($result == 'viewSentRequestError'){
				echo '<div class="alert alert-danger" role="alert">
 	 						Database execution error. Please try again later
						</div>';
			}
			else if($result == 'sqlError'){
				echo '<div class="alert alert-danger" role="alert">
 	 						Database connection error. Please try again later
						</div>';
			}
		?>
		<table class="table table-borderless">
			<tr>
				<td>
					<label class="boldLbl">Incident ID:</label>
				</td>
				<td>
					<?php echo($incidentId); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">Name:</label>
				</td>
				<td>
					<?php echo($result->getName()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">Mobile No.:</label>
				</td>
				<td>
					<?php echo($result->getMobileNo()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">Location:</label>
				</td>
				<td>
					<?php echo($result->getLocation()); ?>
				</td>
			</tr>
			<?php
				if($result->getUnitNo() != "" && $result->getUnitNo() != "-"){
			?>
					<tr>
						<td>
							<label class="boldLbl">Unit Number:</label>
						</td>
						<td>
							<?php echo($result->getUnitNo()); ?>
						</td>
					</tr>
			<?php		
				}
			?>
			<tr>
				<td>
					<label class="boldLbl">Emergency Type:</label>
				</td>
				<td>
					<?php echo($result->getEmergencyType()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">Type of Assistance:</label>
				</td>
				<td>
					<?php echo($result->getTypeOfAssistance()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">Remarks:</label>
				</td>
				<td>
					<?php echo($result->getRemarks()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">File Date Time:</label>
				</td>
				<td>
					<?php echo($result->getFileDateTime()); ?>
				</td>
			</tr>
			<tr>
				<td>
					<label class="boldLbl">Filed By:</label>
				</td>
				<td>
					<?php 
						if($result->getCcoUsername() == ""){
							echo "Public";
						}
						else{
							echo ($result->getCcoUsername());
						}
					?>
				</td>
			</tr>


			<!-----------------button----------------->
			<tr>
				<td>
					<a href="cmo_update_incident_status.php" class="btn btn-basic">Back</a>
				</td>
				<td>
					<?php
						//$severeIncidentController = new severeIncidentController();
						//$severeIncidentController->setAccessToken("cmo");
						
						//$dispatchController = new dispatchController();
						//$dispatchController->setAccessToken("cmo");
						
						//$updatesController = new updatesController();
						//$updatesController->setAccessToken("cmo");
					?>
						<!-- Button trigger modal -->
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewUpdates">View Updates</button>
						
						<!-- Button trigger modal -->
						<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#cfmClose">Close Incident</button>
				</td>
			</tr>
		</table>
		</div>
		</div>
		</div>
		</div>
	</section>

	<!-- Modal 3: view updates -->
	<div class="modal fade" id="viewUpdates" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Previous Updates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          	<span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <table>
        	<?php
        		$checkMsgRecordResult = $incidentController->getAllMsg($incidentId);
        		foreach($checkMsgRecordResult as $row ){
        	?>
        			<tr>
						<td><b><?php echo ($row['updateDateTime']); echo(":&nbsp;"); ?></b></td>
						<?php 
							if($row['msg'] == ''){
						?>
								<td><?php echo("-"); ?></td>
						<?php
							}
							else{
						?>
								<td><?php echo ($row['msg']); ?></td>
						<?php
							}
						?>
					</tr>
					<tr>
							<?php
								if($row['updateStatus'] == 'Resolved'){
							?>
									<td><i>Number of Deaths:</i></td>
									<td><?php echo($row['numDeaths']); ?></td>
						</tr>
						<tr>
									<td><i>Number of Injured:</i></td>
									<td><?php echo($row['numInjured']); ?></td>
							<?php
								}
							?>
						</tr>
			<?php
				}
			?>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
   	</div>
 	</div>
	</div>

	<!-- Modal 4: confirm close incident -->
	<div class="modal fade" id="cfmClose" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    	<div class="modal-header">
        	<h5 class="modal-title" id="exampleModalLabel">Confirm Close Incident</h5>
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">&times;</span>
        	</button>
      	</div>
      	<div class="modal-body">
        	<center>
        		Are you sure you would like to close this incident?<br/>
        		This process cannot be undone.
      		</center>
      	</div>
     	<div class="modal-footer">
        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        	<button type="button" class="btn btn-primary" onclick="clickConfirm(<?php echo($incidentId); ?>)">Confirm</button>
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


<!---------------Client-side Validation----------------->
<script>
//if user click on confirm button
function clickConfirm(incidentId){
	url = "../script/cmo_confirm_close_incident.php?confirmClose=true&incidentId="+incidentId;
	window.location.assign(url);
}
</script>