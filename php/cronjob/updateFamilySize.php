<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  include '../utilities.php';
  include '../header.php';
  
  $sql = "SELECT birthDate, clientID
          FROM familyMember
          WHERE isDeleted = 0;";
  
  $results = rawQuery($sql);
  
  $clientInfo = [];
  foreach ($results as $result) {
    $clientInfo[$result['clientID']]['numAdults'] = 0;
    $clientInfo[$result['clientID']]['numKids'] = 0;
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
    $sql = "UPDATE client 
            SET numOfKids = {$famInfo['numKids']}, numOfAdults = {$famInfo['numAdults']} 
            WHERE clientID = {$clientID}";
    rawUpdate($sql);
    echo "Setting client " . $clientID . " kids to: " . $famInfo['numKids'] . " and adults to: " . $famInfo['numAdults'] . "<br>";
  }
 
?>