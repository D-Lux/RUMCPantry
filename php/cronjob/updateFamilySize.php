<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  include '/home/roselleu/public_html/php/utilities.php';

$conn = connectDB();


  echo "starting cronJob @ " . date("F d, Y | h:i:s") . "\n";
  
  $sql = "SELECT birthDate, familymember.clientID
          FROM familymember
          LEFT JOIN client 
            ON client.clientID = familymember.clientID
          WHERE client.isDeleted = 0
          AND familymember.isDeleted = 0
          AND redistribution = 0";
  
  $results = runQuery($conn, $sql);

  $clientInfo = [];
  foreach ($results as $result) {
    $clientInfo[$result['clientID']]['numAdults'] = (int)0;
    $clientInfo[$result['clientID']]['numKids'] = (int)0;
  }
  
  foreach ($results as $result) {
    if(isAdultBirthdate($result['birthDate'])) {
      $clientInfo[$result['clientID']]['numAdults']++;
    }
    else {
      $clientInfo[$result['clientID']]['numKids']++;
    }
  }
  foreach($clientInfo AS $clientID => $famInfo) {
    $sql = "SELECT numOfKids, numOfAdults FROM client WHERE clientID = {$clientID}";
    $oldInfo = runQueryForOne($sql);
    if ($oldInfo['numOfKids'] != $famInfo['numKids'] || $oldInfo['numAdults'] != $famiInfo['numAdults']) {
        $sql = "UPDATE client 
            SET numOfKids = {$famInfo['numKids']}, numOfAdults = {$famInfo['numAdults']} 
            WHERE clientID = {$clientID}";
        queryDB($conn, $sql);
        $outString = "Setting client " . $clientID . " kids to: " . $famInfo['numKids'] . " and adults to: " . $famInfo['numAdults'];
        echo $outString . "\n";
        
        storeCronEvent($conn, "Update Family Size", $outString);
    }
    
  }
  closeDB($conn);
  echo "cronJob done @ " . date("F d, Y | h:i:s") . "\n";
?>