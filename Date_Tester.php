<!-- © 2018 Daniel Luxa ALL RIGHTS RESERVED -->

<?php
  $pageRestriction = 0;
  include 'php/header.php';
  include 'php/backButton.php';
  
  $result=NULL;
  $dob="No Date";
  if(isset($_POST['ckage'])){  
	if(isset($_POST['dob'])){
		if(!empty($_POST['dob'])){
			$dob = $_POST['dob'];
		}
	}
  }
?>
	
<h3>Age Tester</h3>


<div class="body-content">
	
<!-- Build Form -->
  <form method="post" action="Date_Tester.php">
		
	<?php
		$age="";
	    if(!isset($_POST['ckage'])){
			$dob='';
		} else {
			$result = validateDate($dob);
			if($result[0]) {
				$age=calcage($dob, Date("Y-m-d"));
			} else {
				$done = count($result) - 1;  // subtract one for error code in first slot
				for ($i=1; $i<=$done; ++$i) {
					echo "<p>$result[$i]</p>";
				}
			}	
		} 
		
		echo "Date of Birth:<input type='text' name='dob' value='$dob'>";
		
		if($age<>"") {
			echo "<p>Age: $age </p>";
		} 
	
	?>
	
	<br>
	<button type="submit" class="btn-nav" name="ckage" value=1>Check Age</button>
	<br>
			
  </form>	
	
	     
<?php include 'php/footer.php'; ?>

<?php
function calcage($dateOfBirth,$compdate) {
	
	$diff = date_diff(date_create($dateOfBirth), date_create($compdate));
    $agestr = $diff->format('%y');
	
	return $agestr;
}
	
function validateDate($strdate) {
//The entered value is checked for proper Date format
//Must be 10 digits in MM/DD/YYYY format
//Checks for a valid date (i.e days in a month etc..)
//Returns an array
//	[0] - boolean 0 for good date, 1 for date error
//  [#] - error messasge

    
	$i = 0;	
	if(((strlen($strdate)<10) OR (strlen($strdate)>10)) OR
	   ((substr_count($strdate,"/"))<>2)) {
		$retarray[++$i] = "Date not in 'MM/DD/YYYY' format";
	} else {
		$month=(substr($strdate,0,2));                 //Parse the string to be validated
		$day=(substr($strdate,3,2));
		$year=(substr($strdate,6,4));	
			
		$result=ctype_digit($year);
		if(!($result)){
			$year=0;
			$isLeap=false;
			$retarray[++$i] = "Year is not numeric";
		} else {
			$isLeap = DateTime::createFromFormat('Y', $year)->format('L') === "1";
		}	
			
		$result=ctype_digit($month);
		if(!($result)){
			$month=0; 
			$retarray[++$i] = "Month is not numeric";
		} else {
			if(($month<=0)OR($month>12)){
				$retarray[++$i] = "Month is not between 01 and 12";
			}
		}
			
		$result=ctype_digit($day);
		if(!($result)){
			$day=0; 
			$retarray[++$i] = "Day is not numeric";
		} else {
			if(($day<=0)OR($day>31)){
				$retarray[++$i] = "Day is not between 01 and 31";
			}	
			
			if((($month==2) AND (((!$isLeap)AND($day>28))OR(($isLeap)AND($day>29)))) OR 
			  ((($month==4) OR ($month==6) OR ($month==9) OR ($month==11)) AND ($day>30))){
				$retarray[++$i] = "Day is not valid for month/year";
			}			
		}
	}
	
	if ($i > 0) {
		$retarray[0] = False;
	} else {
		$retarray[0] = True;
	}	
	
	return($retarray); 
}

?>	