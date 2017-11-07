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
echo"<h1>Clients that were scheduled for today and didn't show up, didn't have correct documentations, or cancelled</h1>";
displayTable($date, $conn);




function displayTable($date, $conn)
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
            echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>No show?</th><th>Incorrect documentation?</th><th>Cancelled?</th></tr>";
            echo "<tr>";
            //grab donation id
            echo "<form action='endOfDay.php' method='post'>";
            $invoiceID=$row["invoiceID"];
            echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
            echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
            echo "<td><input type='submit' name='noShow' value='No show'></td>";
            echo "<td><input type='submit' name='documentation' value='Documentation'></td>";
            echo "<td><input type='submit' name='cancelled' value='Cancelled'></td>";
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
                   
               }
        }
    }


if(isset($_POST['noShow'])) /*when the button is pressed on post request*/
{
    
    $invoiceID = $_POST['invoiceID'];
    $sql = "UPDATE Invoice SET status = " . GetNoShowStatus() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sql);
    echo "<meta http-equiv='refresh' content='0'>"; 
    
}
elseif(isset($_POST['documentation'])) /*when the button is pressed on post request*/
{
    
    $invoiceID = $_POST['invoiceID'];
    $sql = "UPDATE Invoice SET status = " . GetBadDocumentationStatus() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sql);
    echo "<meta http-equiv='refresh' content='0'>"; 
    
}
elseif(isset($_POST['cancelled'])) /*when the button is pressed on post request*/
{
    
    $invoiceID = $_POST['invoiceID'];
    $sql = "UPDATE Invoice SET status = " . GetCanceledStatus() . " WHERE visitDate = '$date' AND invoiceID = $invoiceID";
    $resultOfFirstPersonHere = $conn->query($sql);
    echo "<meta http-equiv='refresh' content='0'>"; 
    
}

?>

