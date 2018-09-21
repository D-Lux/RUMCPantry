<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  include '/home/roselleu/public_html/php/utilities.php';

  $conn = connectDB();

  echo "starting cronJob @ " . date("F d, Y | h:i:s") . "\n";

    // If this query is going to work, it needs to check for days > today
  //$sql = "SELECT MIN(visitDate) as nextApptDate
  //          FROM invoice
  //         WHERE status = " . GetAssignedStatus();

  //$apptDate = runQueryForOne($conn, $sql)['nextApptDate'];
  // Switching this to strictly check for TOMORROW
  $apptDate = date("Y-m-d", strtotime("tomorrow"));
  echo "Running appointment reminders for date: " . $apptDate . "\n";

  $sql = "SELECT c.email, CONCAT(fm.firstName, ' ', fm.lastName) AS clientName, i.visitDate, i.visitTime, c.apptWarnings, c.phoneNumber
          FROM client c
          LEFT JOIN familymember fm
            ON fm.clientID = c.clientID
            AND fm.isHeadOfHousehold = 1
          LEFT JOIN invoice i
            ON i.clientID = c.clientID
          WHERE i.visitDate = '{$apptDate}'";

  $results = runQuery($conn, $sql);
  $vDate = date('F jS, Y', strtotime($apptDate));
  $adminMsg = "";
  $adminSuccesses = "<p>The following people had appointments</p>
                    <table style='width:75%'>
                      <thead>
                        <tr>
                          <th>Client</th>
                          <th>Appointment</th>
                          <th>Email</th>
                        </tr>
                      </thead>";
  foreach($results as $result) {
    if (empty($result['email']) || $result['apptWarnings'] == 0 || $result['email'] == "n/a") {
      if (empty($adminMsg)) {
        $adminMsg = "<p>The following people did not receive an email notification: </p>";
        $adminMsg .= "<table style='width:75%'>
                      <thead>
                        <tr>
                          <th>Client</th>
                          <th>Appointment</th>
                          <th>Phone Number</th>
                          <th>Reason</th>
                        </tr>
                      </thead>";
      }
      $adminMsg .= "<tr>
                      <td>" . $result['clientName'] . "</td>
                      <td>" . $result['visitTime'] . "</td>
                      <td>" . displayPhoneNo($result['phoneNumber']) . "</td>";
      $adminMsg .=  "<td>" . ($result['apptWarnings'] == 0 ? "Opted Out" : "No Email Address") . "</td></tr>";
    }
    else {
      $visitTime = date("g:i A", strtotime("today " . $result['visitTime']));
      
      $adminSuccesses .= "<tr><td>" . $result['clientName'] . "</td><td>" . $visitTime . "</td><td>" . $result['email'] . "</td></tr>";
      $to = $result['email'];
      $subject = "Appointment Reminder: " . $vDate;
      $message = "
      <html stlye='background-color: #EEE'>
        <head>
          <title>Appointment Reminder</title>
        </head>
        <body style='text-align:center; font-family: Arial, sans-serif !important;'>
          <h1>Hello, " . $result['clientName'] . ", From The Roselle United Methodist Community Food Pantry</h1>
          <p>This email is to remind you of your appointment scheduled on</p>
          <p style='background-color: #CCC;'>" . $vDate . " at " . $visitTime . "</p>
          <p>Please remember to bring your drivers license or state identification card as well as proof of residency dated within the last 30 days.</p>
          <p>Proof of residency is electric bill, gas bill, village water bill or cable bill only.</p>
          <p>If for some reason you cannot make your appointment, please call or e-mail the office as soon as possible.</p><br>
            <p>Thank you</p>
            <p>Vicki Johnson</p>
            <p>Director</p>

          
        </body>
      </html>";
      
      //<br><p>Email address intended: " . $result['email'] . "</p>

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      $headers .= 'From: <vicki.johnson@roselleumcpantry.org>' . "\r\n";

      mail($to,$subject,$message,$headers);
    }
  }

  // Send a message to admin of all apointments missing email information
  if (!empty($adminMsg . $adminSuccesses)) {
    $adminMsg .= "</table>";
    $adminSuccesses .= "</table>";

    $to = "vjoh927811@aol.com";
    $subject = "No Reminder Appointments For: " . date('F d, Y', strtotime($apptDate));

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <vicki.johnson@roselleumcpantry.org>' . "\r\n";

    mail($to,$subject,$adminMsg . $adminSuccesses,$headers);
    mail("daniel_luxa@hotmail.com",$subject,$adminMsg . $adminSuccesses,$headers);
  }

  echo "cronJob done @ " . date("F d, Y | h:i:s") . "\n";
?>