<?php
	include('../utilities.php');
	include('../reportingOps.php');
	
	// AJAX call to update reporting with new dates
	if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
		runReportQueries($_GET['startDate'], $_GET['endDate']);
	}
?>