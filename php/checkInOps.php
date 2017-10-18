 <?php
 //include 'utilities.php';
 //  first use todays date
 //for tables what information is needed? first and last Name of head of house, time of appointment and a button

 //SELECT FamilyMember.firstName, FamilyMember.LastName, Invoice.visitTime, Invoice.status
 //FROM Invoice
 //INNER JOIN Client ON Invoice.clientID=Client.clientID
 //INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID
 //WHERE Invoice.visitDate = "2017-07-10" && FamilyMember.isHeadOfHousehold =true
 //ORDER BY Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC;

date_default_timezone_set('America/Chicago');

    $date = date("Y-m-d");
    echo "Today is  $date <br>";

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

updateStatusInitial($date, $conn);
echo"<h1>Waiting for clients</h1>";
displayWaitTable($date, $conn);

echo"<h1>Clients that are here</h1>";
displayShowedUpTable($date, $conn);

echo"<h1>Clients ready to be reviewed</h1>";
displayReadyToBeReviewed($date, $conn);


echo"<h1>Clients that are ready to print</h1>";
displayReadyToPrint($date, $conn);

echo"<h1>Clients that are printed and completed</h1>";
displayPrinted($date, $conn);



function displayWaitTable($date, $conn)
{
    

    
    $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status = " . GetActiveStatus() . " ORDER BY  Invoice.visitTime ASC,Invoice.status ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        // output data of each row
        $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {
            
            if($rowTitle != $row["visitTime"])
            {
                $rowTitle = $row["visitTime"];
                echo "<b>";            
                echo $rowTitle;
                echo "</b>";
            }
 

            echo "<table>";
            echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Verified?</th></tr>";
            echo "<tr>";
            //grab donation id
            echo "<form action='checkIn.php' method='post'>";
            $invoiceID=$row["invoiceID"];
            echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
            echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
            echo "<td><input type='submit' name='checkInHere' value='Verified'></td>";
            echo "</form>";
            echo "</tr>";    
            echo "</table>";            
        }      
    }
    else
    {
        echo"No appointments are being waited on for today";
    }     
}  
            
 
function displayShowedUpTable($date, $conn)
{
    $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetArrivedLow() . " AND Invoice.status <= " . GetArrivedHigh() . " ORDER BY  Invoice.visitTime ASC,Invoice.status ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {
            
            if($rowTitle != $row["visitTime"])
            {
                $rowTitle = $row["visitTime"];
                echo "<b>";            
                echo $rowTitle;
                echo "</b>";
            }
 

            echo "<table>";
            echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Ready to review?</th></tr>";
            echo "<tr>";
            //grab donation id
            echo "<form action='checkIn.php' method='post'>";
            $invoiceID=$row["invoiceID"];
            echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
            echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
            echo "<td><input type='submit' name='readyToReview' value='Ready to review'></td>";
            echo "</form>";
            echo "</tr>";        
            echo "</table>";            
            
        }      
    }
    else
    {
        echo"No appointments are here yet";
    }   
}
  
function displayReadyToBeReviewed($date, $conn)
{
    $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetReadyToReviewLow() . " AND Invoice.status <= " . GetReadyToReviewHigh() . " ORDER BY  Invoice.visitTime ASC,Invoice.status ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {
            
            if($rowTitle != $row["visitTime"])
            {
                $rowTitle = $row["visitTime"];
                echo "<b>";            
                echo $rowTitle;
                echo "</b>";
            }
 

            echo "<table>";
            echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Review?</th></tr>";
            echo "<tr>";
            //grab donation id
            echo "<form action='checkIn.php' method='post'>";
            $invoiceID=$row["invoiceID"];
            echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
            echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
            echo "<td><input type='submit' name='review' value='Review'></td>";
            echo "</form>";
            echo "</tr>";      
            echo "</table>";            
            
        }      
    }
    else
    {
        echo"No appointments are here yet";
    }   
}

function displayReadyToPrint($date, $conn)
{
    $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetReadyToPrintLow() . " AND Invoice.status <= " . GetReadyToPrintHigh() . " ORDER BY  Invoice.visitTime ASC,Invoice.status ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {
            
            if($rowTitle != $row["visitTime"])
            {
                $rowTitle = $row["visitTime"];
                echo "<b>";            
                echo $rowTitle;
                echo "</b>";
            }
 

            echo "<table>";
            echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Print?</th></tr>";
            echo "<tr>";
            //grab donation id
            echo "<form action='checkIn.php' method='post'>";
            $invoiceID=$row["invoiceID"];
            echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
            echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
            echo "<td><input type='submit' name='print' value='Print'></td>";
            echo "</form>";
            echo "</tr>";      
            echo "</table>";            
            
        }      
    }
    else
    {
        echo"No appointments are ready to print";
    }   
}
    

function displayPrinted($date, $conn)
{
    $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetPrintedLow() . " AND Invoice.status <= " . GetPrintedHigh() . " ORDER BY  Invoice.visitTime ASC,Invoice.status ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {
            
            if($rowTitle != $row["visitTime"])
            {
                $rowTitle = $row["visitTime"];
                echo "<b>";            
                echo $rowTitle;
                echo "</b>";
            }
 

            echo "<table>";
            echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th>";
            echo "<tr>";
            //grab donation id
            echo "<form action='checkIn.php' method='post'>";
            $invoiceID=$row["invoiceID"];
            echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
            echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
            echo "</form>";
            echo "</tr>";     
            echo "</table>";            
            
        }      
    }
    else
    {
        echo"No appointments are ready to print";
    }   
}


    

 
    function updateStatusInitial($date, $conn)
    {
        $sql = "SELECT FamilyMember.firstName, FamilyMember.LastName, Invoice.visitTime, Invoice.status 
				FROM Invoice 
				INNER JOIN Client 
				ON Invoice.clientID=Client.clientID 
				INNER JOIN FamilyMember 
				On Client.clientID=FamilyMember.clientID 
				WHERE Invoice.visitDate = '$date' && FamilyMember.isHeadOfHousehold = true 
				ORDER BY Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $sql = "UPDATE Invoice SET status = " . GetActiveStatus() . " WHERE visitDate = '$date' AND status = " . GetAssignedStatus() . "";
            
            //update the status numbers on each of the rows
               if ($conn->query($sql) === TRUE) {
                   echo"<div>status of appointments updated to 200</div>";
               }
        }
    }


if(isset($_POST['checkInHere'])) /*when the button is pressed on post request*/
{
    
    $invoiceID = $_POST['invoiceID'];
    $sqlToSeeHowManyAreHere = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetArrivedLow() . " AND Invoice.status <= " . GetArrivedHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $resultToSeeHowManyAreHere = $conn->query($sqlToSeeHowManyAreHere);
    if ($resultToSeeHowManyAreHere->num_rows == 0) 
    {
    $sqlToUpdateFirstPersonHere = "UPDATE Invoice SET status = " . GetArrivedLow() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sqlToUpdateFirstPersonHere);
    echo "<meta http-equiv='refresh' content='0'>"; 
    }
    else
    {
        $currentStatus = $resultToSeeHowManyAreHere->num_rows + GetArrivedLow(); 
        $sqlToUpdateRemainingPersonsHere = "UPDATE Invoice SET status = $currentStatus WHERE visitDate = '$date' AND invoiceID = $invoiceID";
        $resultOfRemainingPersonsHere = $conn->query($sqlToUpdateRemainingPersonsHere);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}

elseif(isset($_POST['readyToReview'])) /*when the button is pressed on post request*/
{
    $invoiceID = $_POST['invoiceID'];
    $sqlToSeeHowManyAreHere = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetReadyToReviewLow() . " AND Invoice.status <= " . GetReadyToReviewHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $resultToSeeHowManyAreHere = $conn->query($sqlToSeeHowManyAreHere);
    if ($resultToSeeHowManyAreHere->num_rows == 0) 
    {
    $sqlToUpdateFirstPersonHere = "UPDATE Invoice SET status = " . GetReadyToReviewLow() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sqlToUpdateFirstPersonHere);
    echo "<meta http-equiv='refresh' content='0'>"; 
    }
    else
    {
        $currentStatus = $resultToSeeHowManyAreHere->num_rows + GetReadyToReviewLow(); 
        $sqlToUpdateRemainingPersonsHere = "UPDATE Invoice SET status = $currentStatus WHERE visitDate = '$date' AND invoiceID = $invoiceID";
        $resultOfRemainingPersonsHere = $conn->query($sqlToUpdateRemainingPersonsHere);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
elseif(isset($_POST['review'])) /*when the button is pressed on post request*/
{
    $invoiceID = $_POST['invoiceID'];
    $sqlToSeeHowManyAreHere = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetReadyToPrintLow() . " AND Invoice.status <= " . GetReadyToPrintHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $resultToSeeHowManyAreHere = $conn->query($sqlToSeeHowManyAreHere);
    if ($resultToSeeHowManyAreHere->num_rows == 0) 
    {
    $sqlToUpdateFirstPersonHere = "UPDATE Invoice SET status = " . GetReadyToPrintLow() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sqlToUpdateFirstPersonHere);
    echo "<meta http-equiv='refresh' content='0'>"; 
    }
    else
    {
        $currentStatus = $resultToSeeHowManyAreHere->num_rows + GetReadyToPrintLow(); 
        $sqlToUpdateRemainingPersonsHere = "UPDATE Invoice SET status = $currentStatus WHERE visitDate = '$date' AND invoiceID = $invoiceID";
        $resultOfRemainingPersonsHere = $conn->query($sqlToUpdateRemainingPersonsHere);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
elseif(isset($_POST['print'])) /*when the button is pressed on post request*/
{
    $invoiceID = $_POST['invoiceID'];
    $sqlToSeeHowManyAreHere = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status >= " . GetPrintedLow() . " AND Invoice.status <= " . GetPrintedHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
    $resultToSeeHowManyAreHere = $conn->query($sqlToSeeHowManyAreHere);
    if ($resultToSeeHowManyAreHere->num_rows == 0) 
    {
    $sqlToUpdateFirstPersonHere = "UPDATE Invoice SET status = " . GetPrintedLow() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sqlToUpdateFirstPersonHere);
    echo "<meta http-equiv='refresh' content='0'>"; 
    }
    else
    {
        $currentStatus = $resultToSeeHowManyAreHere->num_rows + GetPrintedLow(); 
        $sqlToUpdateRemainingPersonsHere = "UPDATE Invoice SET status = $currentStatus WHERE visitDate = '$date' AND invoiceID = $invoiceID";
        $resultOfRemainingPersonsHere = $conn->query($sqlToUpdateRemainingPersonsHere);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
?>

