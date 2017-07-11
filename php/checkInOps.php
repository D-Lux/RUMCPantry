 <?php
 include 'utilities.php';
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

    $timeDifferenceOnTables='00:30:00';
    $timeStart='00:30:00';
    $timeEnd='20:00:00';
    $timeIter=$timeStart;

    $timeDifferenceOnTables=convertTimeToInt($timeDifferenceOnTables);
    $timeStart=convertTimeToInt($timeStart);
    $timeEnd=convertTimeToInt($timeEnd);
    $timeIter=convertTimeToInt($timeIter);

 

    /* Create connection*/
    $conn = createPantryDatabaseConnection();
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

updateStatusInitial($date, $conn);
echo"<h1>Waiting for clients</h1>";
displayWaitTable($date, $conn,$timeDifferenceOnTables, $timeStart, $timeEnd, $timeIter);
$timeIter = $timeStart;
echo "</table></br>";
echo"<h1>Clients that are here</h1>";
displayShowedUpTable($date, $conn,$timeDifferenceOnTables, $timeStart, $timeEnd, $timeIter);
$timeIter = $timeStart;
echo "</table></br>";
echo"<h1>Clients ready to be sent back</h1>";
displayReadyToGoBackTable($date, $conn,$timeDifferenceOnTables, $timeStart, $timeEnd, $timeIter);
$timeIter = $timeStart;
echo "</table></br>";
echo"<h1>Clients sent back</h1>";
displaySentBackTable($date, $conn,$timeDifferenceOnTables, $timeStart, $timeEnd, $timeIter);
$timeIter = $timeStart;


function displaySentBackTable($date, $conn, $timeDifferenceOnTables, $timeStart, $timeEnd,$timeIter)
    {
       

        
        $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status > " . GetProcessedLow() . " AND Invoice.status < " . GetProcessedHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
        

            $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {

            
            while($timeIter <= $timeEnd)
            {
                
               if($row["visitTime"] == convertIntToTime($timeIter) )
                {   
                    if($rowTitle != $row["visitTime"])
                    {
                        echo "</table>";
                        $rowTitle = $row["visitTime"];
                        echo "<b>";            
                        echo $rowTitle;
                        echo "</b>";
                        echo "<table>";
                        echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th></tr>";
                    }
                    
                    echo "<tr>";
                    //grab donation id
                    echo "<form action=''>";
                    $invoiceID=$row["invoiceID"];
                    echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
                    echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";

                    echo "</form>";
                    echo "</tr>";                   
                }
                
                (string)$timeIter = addToIntTime($timeIter );
                
                
                
            }
            
            $timeIter = $timeStart;
        }
        

    }
    else{
        echo"No appointments have been processed yet today";
        }
        
    }   

    function displayReadyToGoBackTable($date, $conn, $timeDifferenceOnTables, $timeStart, $timeEnd,$timeIter)
    {
       

        
        $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status > " . GetPrintedLow() . " AND Invoice.status < " . GetPrintedHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
        

            $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {

            
            while($timeIter <= $timeEnd)
            {
                
               if($row["visitTime"] == convertIntToTime($timeIter) )
                {   
                    if($rowTitle != $row["visitTime"])
                    {
                        echo "</table>";
                        $rowTitle = $row["visitTime"];
                        echo "<b>";            
                        echo $rowTitle;
                        echo "</b>";
                        echo "<table>";
                        echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Send Back?</th></tr>";
                    }
                    
                    echo "<tr>";
                    //grab donation id
                    echo "<form action=''>";
                    $invoiceID=$row["invoiceID"];
                    echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
                    echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
                    echo "<td><input type='submit' name='SendBack' value='Send Back'></td>";
                    echo "</form>";
                    echo "</tr>";                   
                }
                
                (string)$timeIter = addToIntTime($timeIter );
                
                
                
            }
            
            $timeIter = $timeStart;
        }
        

    }
    else{
        echo"No appointments ready to print";
        }
        
    }   

function displayShowedUpTable($date, $conn, $timeDifferenceOnTables, $timeStart, $timeEnd,$timeIter)
    {
       

        
        $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status, (Client.numOfKids + numOfAdults) AS familySize FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status > " . GetArrivedLow() . " AND Invoice.status < " . GetArrivedHigh() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
        

            $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {

            
            while($timeIter <= $timeEnd)
            {
                
               if($row["visitTime"] == convertIntToTime($timeIter) )
                {   
                    if($rowTitle != $row["visitTime"])
                    {
                        echo "</table>";
                        $rowTitle = $row["visitTime"];
                        echo "<b>";            
                        echo $rowTitle;
                        echo "</b>";
                        echo "<table>";
                        echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Print?</th></tr>";
                    }
                    


			
		
			
			
			


                    echo "<tr>";
                    //grab donation id
                    echo "<form method='post' action='ap_oo4.php'>";
                    $invoiceID=$row["invoiceID"];
                    echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
                    echo "<input type='hidden' value='" . $row['lastName'] . "' name='name'>";
                    echo "<input type='hidden' value='" . $row['visitTime'] . "' name='visitTime'>";
                    echo "<input type='hidden' value='" . $row['status'] . "' name='status'>";
                    echo "<input type='hidden' value='" . $row['familySize'] . "' name='familySize'>";
                    echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
                    echo "<td><input type='submit' name='viewInvoice' value='Print'></td>";
                    echo "</form>";
                    echo "</tr>";                   
                }
                
                (string)$timeIter = addToIntTime($timeIter );
                
                
                
            }
            
            $timeIter = $timeStart;
        }
        

    }
    else{
        echo"No appointments have shown up and are waiting for today";
        }
        
    }   

    function displayWaitTable($date, $conn, $timeDifferenceOnTables, $timeStart, $timeEnd,$timeIter)
    {
       

        
        $sql = "SELECT FamilyMember.firstName, FamilyMember.lastName, Invoice.visitTime, Invoice.invoiceID, Invoice.status FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' AND FamilyMember.isHeadOfHousehold = true AND Invoice.status = " . GetActiveStatus() . " ORDER BY Invoice.status ASC, Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
        

            $rowTitle = null;
        while($row = $result->fetch_assoc()) 
        {

            
            while($timeIter <= $timeEnd)
            {
                
               if($row["visitTime"] == convertIntToTime($timeIter) )
                {   
                    if($rowTitle != $row["visitTime"])
                    {
                        echo "</table>";
                        $rowTitle = $row["visitTime"];
                        echo "<b>";            
                        echo $rowTitle;
                        echo "</b>";
                        echo "<table>";
                        echo "<tr><th>First name</th><th>Last name</th><th>Visit Time</th><th>Status</th><th>Here?</th></tr>";
                    }
                    
                    echo "<tr>";
                    //grab donation id
                    echo "<form action=''>";
                    $invoiceID=$row["invoiceID"];
                    echo "<input type='hidden' name='invoiceID' value='$invoiceID'>";
                    echo "<td>". $row["firstName"]. "</td><td>". $row["lastName"]. "</td><td>" . $row["visitTime"] . "</td><td>" . $row["status"] . "</td>";
                    echo "<td><input type='submit' name='checkInHere' value='Here'></td>";
                    echo "</form>";
                    echo "</tr>";                   
                }
                
                (string)$timeIter = addToIntTime($timeIter );
                
                
                
            }
            
            $timeIter = $timeStart;
        }
        

    }
    else{
        echo"No appointments are being waited on for today";
        }
        
    }  

 
    function updateStatusInitial($date, $conn)
    {
        $sql = "SELECT FamilyMember.firstName, FamilyMember.LastName, Invoice.visitTime, Invoice.status FROM Invoice INNER JOIN Client ON Invoice.clientID=Client.clientID INNER JOIN FamilyMember On Client.clientID=FamilyMember.clientID WHERE Invoice.visitDate = '$date' && FamilyMember.isHeadOfHousehold = true ORDER BY Invoice.visitTime ASC, FamilyMember.LastName ASC, FamilyMember.FirstName ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $sql = "UPDATE Invoice SET status = " . GetAssignedStatus() . " WHERE visitDate = '$date' AND status = " . GetAssignedStatus() . "";
            
            //update the status numbers on each of the rows
               if ($conn->query($sql) === TRUE) {
                   echo"<div>status of appointments updated to 200</div>";
               }
        }
    }

 //this is to get rid of the : so i can math the times. 
    function convertTimeToInt($time)
    {
        return str_replace(":","",$time);
    }
    //will only work with a 6 character int
    function convertIntToTime($int)
    {
      
        if($int >= 130000 && $int <= 219999)
        {
            $newInt = $int - 120000;
            $newString = "0$newInt";
           return substr(chunk_split(($newString), 2, ':'),0,-1);
            
            
        }
        elseif($int == 3000)
        {
            return "00:30:00";
        }
        elseif($int > 219999)
        {
            $newInt = $int - 120000;
            $newString = "$newInt";
           return substr(chunk_split(($newString), 2, ':'),0,-1);
        }
        else
        {
            return substr(chunk_split($int, 2, ':'),0,-1);
        }
        
    }
    //adds 30 minutes to the time
    function addToIntTime($num)
    {
       
       

        if ($num == 93000)
        {
            return 100000;
        }
        elseif($num <100000)
        {
            $convertedNum = (($num % 10000) / 100);
            
          


            if($convertedNum == 0)
            {
                $num += 3000;
            }
            elseif($convertedNum == 30)
            {
                $num += 7000;
            }
            
             return "0$num";
        }
        elseif($num >=100000 && $num <=129000){
            //this gets the time in minutes. need to find out if its 00 or 30
            $convertedNum = (($num % 10000) / 100);
            


            if($convertedNum == 00)
            {
                $num += 3000;
            }
            elseif($convertedNum == 30)
            {
                $num += 7000;
            }
             return $num;
        }
        else{
             $convertedNum = (($num % 10000) / 100);
            


            if($convertedNum == 00)
            {
                $num += 3000;
            }
            elseif($convertedNum == 30)
            {
                $num += 7000;
            }
             return "$num";
        }
        


       

    }
  
    
?>

