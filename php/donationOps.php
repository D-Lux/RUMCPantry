<?php
/*
Submits that pass through here:
UpdateItem
updateDonation
updateDonationPartner
createDonationPartner
createDonation
DeleteItem
deleteDonation
deleteDonationPartner
updateDonationIndividual
updateDonationPartnerIndividual

*/
include('utilities.php');

if (isset($_GET['UpdateItem'])) {
	header ("location: /RUMCPantry/ap_io3.php?itemID=" . $_GET['itemID']);
}
elseif (isset($_GET['updateDonation'])) {
	header ("location: /RUMCPantry/ap_do4.php?donationID=" . $_GET['donationID']);
}
elseif (isset($_GET['updateDonationPartner'])) {
	header ("location: /RUMCPantry/ap_do5.php?donationPartnerID=" . $_GET['donationPartnerID']);
}

// ****************************
// Adding a new donation partner
elseif(isset($_POST['createDonationPartner'])) {
  header('Content-type: application/json');
  $error    = '';
  $name     = fixInput($_POST['name']);
  $state    = $_POST['state'];
  $zip      = $_POST['zip'];
  $address  = fixInput($_POST['address']);
  $city     = fixInput($_POST['city']);
  $areaCode = $_POST['areaCode'];
  $phone1   = $_POST['phoneNumber1'];
  $phone2   = $_POST['phoneNumber2'];

  if (empty($name)) {
    $error .= "<p>Name is required.</p>";
  }
  if (empty($zip)) {
    $error .= "<p>Zip Code is required.</p>";
  }
  if (empty($address)) {
    $error .= "<p>Address is required.</p>";
  }
  if (empty($city)) {
    $error .= "<p>City is required.</p>";
  }
  if (empty($areaCode)) {
    $error .= "<p>Area code required.</p>";
  }
  if (empty($phone1) || empty($phone2) ) {
    $error .= "<p>Full phone number is required.</p>";
  }

  if ($error == '') {
    $conn = connectDB();
    $sql = "INSERT INTO DonationPartner (name, city, state, zip, address, phoneNumber)
       VALUES ('" . $name . "', '" . $city . "', '" . $state . "', " . $zip . ", '" . $address . "', " . $areaCode.$phone1.$phone2 . ")";

    if (queryDB($conn,$sql) === FALSE) {
      $error = "There was an error connecting to the database, please try again later.";
    }
    closeDB($conn);
  }

  echo json_encode(array("error" => $error));
}
elseif (isset($_POST['updateDonationPartner'])) {
  header('Content-type: application/json');
  $error    = '';
  $name     = fixInput($_POST['name']);
  $state    = $_POST['state'];
  $zip      = $_POST['zip'];
  $address  = fixInput($_POST['address']);
  $city     = fixInput($_POST['city']);
  $areaCode = $_POST['areaCode'];
  $phone1   = $_POST['phoneNumber1'];
  $phone2   = $_POST['phoneNumber2'];
  $pid      = $_POST['donationPartnerID'];

  if (empty($name)) {
    $error .= "<p>Name is required.</p>";
  }
  if (empty($zip)) {
    $error .= "<p>Zip Code is required.</p>";
  }
  if (empty($address)) {
    $error .= "<p>Address is required.</p>";
  }
  if (empty($city)) {
    $error .= "<p>City is required.</p>";
  }
  if (empty($areaCode)) {
    $error .= "<p>Area code required.</p>";
  }
  if (empty($phone1) || empty($phone2) ) {
    $error .= "<p>Full phone number is required.</p>";
  }
  $phoneNumber = $areaCode.$phone1.$phone2;

  if ($error == '') {
    $conn = connectDB();
    $sql = "UPDATE DonationPartner
            SET name = '" . $name . "', state = '" . $state . "', zip = " . $zip . ", address = '" . $address . "', city = '" . $city . "', phoneNumber = " . $phoneNumber . "
            WHERE donationPartnerID = $pid";

    if (queryDB($conn,$sql) === FALSE) {
      $error = "There was an error connecting to the database, please try again later.";
      //$error = sqlError($conn);
    }
    closeDB($conn);
  }
  echo json_encode(array("error" => $error));
}

elseif(isset($_POST["createDonation"])) {
  header('Content-type: application/json');
  $pickupDate       = $_POST["pickupDate"];
  $networkPartner   = fixInput($_POST["networkPartner"]);
  $agency           = fixInput($_POST["agency"]);
  $donorID          = $_POST["donorName"];
  $frozenNonMeat    = isset($_POST["frozenNonMeat"])    ? (int)$_POST["frozenNonMeat"]   : (int)0;
  $frozenMeat       = isset($_POST["frozenMeat"])       ? (int)$_POST["frozenMeat"]      : (int)0;
  $frozenPrepared   = isset($_POST["frozenPrepared"])   ? (int)$_POST["frozenPrepared"]  : (int)0;
  $refBakery        = isset($_POST["refBakery"])        ? (int)$_POST["refBakery"]       : (int)0;
  $refProduce       = isset($_POST["refProduce"])       ? (int)$_POST["refProduce"]      : (int)0;
  $refDairyAndDeli  = isset($_POST["refDairyAndDeli"])  ? (int)$_POST["refDairyAndDeli"] : (int)0;
  $dryShelfStable   = isset($_POST["dryShelfStable"])   ? (int)$_POST["dryShelfStable"]  : (int)0;
  $dryNonFood       = isset($_POST["dryNonFood"])       ? (int)$_POST["dryNonFood"]      : (int)0;
  $dryFoodDrive     = isset($_POST["dryFoodDrive"])     ? (int)$_POST["dryFoodDrive"]    : (int)0;

  $totalDonatedItems = $frozenNonMeat + $frozenMeat + $frozenPrepared + $refBakery + $refProduce + $refDairyAndDeli + $dryShelfStable + $dryNonFood + $dryFoodDrive;

  $error = '';

  //Verify input
  if (empty($pickupDate)) {
    $error .= "<p>Date required.</p>";
  }
  if (empty($networkPartner)) {
    $error .= "<p>Network partner required.</p>";
  }
  if (empty($agency)) {
    $error .= "<p>Agency required.</p>";
  }
  if ($donorID == 0) {
    $error .= "<p>Must select a donor.</p>";
  }
  if ($totalDonatedItems == 0) {
    $error .= "<p>Must have at least one item donated.</p>";
  }
  
  if ($error == '') {
    $conn = connectDB();
    $sql = "INSERT INTO Donation
                        (donationPartnerID, dateOfPickup, networkPartner, agency,
                          frozenNonMeat, frozenMeat, frozenPrepared,
                          refBakery, refProduce, refDairyAndDeli,
                          dryShelfStable, dryNonFood, dryFoodDrive)
            VALUES (" . $donorID . ", '" . $pickupDate . "', '" . $networkPartner . "', '" . $agency . "',
                    " . $frozenNonMeat . ", " . $frozenMeat . ", " . $frozenPrepared . ",
                    " . $refBakery . ", " . $refProduce . ", " . $refDairyAndDeli . ",
                    " . $dryShelfStable . ", " . $dryNonFood . ", " . $dryFoodDrive . ")";

    if (queryDB($conn,$sql) === FALSE) {
      $error .= "There was an error connecting to the database, please try again later.";
    }
    closeDB($conn);
  }
  echo json_encode(array("error" => $error));
}
// ****************************************
// * Update a single donation event
elseif (isset($_POST['updateDonation'])) {
  header('Content-type: application/json');
  $pickupDate       = $_POST["pickupDate"];
  $networkPartner   = fixInput($_POST["networkPartner"]);
  $agency           = fixInput($_POST["agency"]);
  $donorID          = $_POST["donorName"];
  $donationID       = $_POST["donationID"];
  $frozenNonMeat    = isset($_POST["frozenNonMeat"])    ? (int)$_POST["frozenNonMeat"]   : (int)0;
  $frozenMeat       = isset($_POST["frozenMeat"])       ? (int)$_POST["frozenMeat"]      : (int)0;
  $frozenPrepared   = isset($_POST["frozenPrepared"])   ? (int)$_POST["frozenPrepared"]  : (int)0;
  $refBakery        = isset($_POST["refBakery"])        ? (int)$_POST["refBakery"]       : (int)0;
  $refProduce       = isset($_POST["refProduce"])       ? (int)$_POST["refProduce"]      : (int)0;
  $refDairyAndDeli  = isset($_POST["refDairyAndDeli"])  ? (int)$_POST["refDairyAndDeli"] : (int)0;
  $dryShelfStable   = isset($_POST["dryShelfStable"])   ? (int)$_POST["dryShelfStable"]  : (int)0;
  $dryNonFood       = isset($_POST["dryNonFood"])       ? (int)$_POST["dryNonFood"]      : (int)0;
  $dryFoodDrive     = isset($_POST["dryFoodDrive"])     ? (int)$_POST["dryFoodDrive"]    : (int)0;

  $totalDonatedItems = $frozenNonMeat + $frozenMeat + $frozenPrepared + $refBakery + $refProduce + $refDairyAndDeli + $dryShelfStable + $dryNonFood + $dryFoodDrive;

  $error = '';

  //Verify input
  if (empty($pickupDate)) {
    $error .= "<p>Date required.</p>";
  }
  if (empty($networkPartner)) {
    $error .= "<p>Network partner required.</p>";
  }
  if (empty($agency)) {
    $error .= "<p>Agency required.</p>";
  }
  if ($donorID == 0) {
    $error .= "<p>Must select a donor.</p>";
  }
  if ($totalDonatedItems == 0) {
    $error .= "<p>Must have at least one item donated.</p>";
  }
  
  if ($error == '') {
    $conn = connectDB();
    $sql = "UPDATE Donation SET 
              donationPartnerID = " . $donorID . ",
              dateOfPickup      = '" . $pickupDate . "',
              networkPartner    = '" . $networkPartner . "',
              agency            = '" . $agency . "',
              frozenNonMeat     = " . $frozenNonMeat . ",
              frozenMeat        = " . $frozenMeat . ",
              frozenPrepared    = " . $frozenPrepared . ",
              refBakery         = " . $refBakery . ",
              refProduce        = " . $refProduce . ",
              refDairyAndDeli   = " . $refDairyAndDeli . ",
              dryShelfStable    = " . $dryShelfStable  . ",
              dryNonFood        = " . $dryNonFood . ",
              dryFoodDrive      = " . $dryFoodDrive. "
            WHERE donationID    = " . $donationID ;

    if (queryDB($conn,$sql) === FALSE) {
      $error .= "There was an error connecting to the database, please try again later.<br>" . $sql . "<br>" . mysqli_error ($conn);
    }
    closeDB($conn);
  }
  echo json_encode(array("error" => $error));
}





// ***********************************************************************************************

// *************************************************************************************************
elseif (isset($_GET['DeleteItem'])) {

    $conn = connectDB();
    $itemID = $_GET['itemID'];
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $result = $conn->query("SELECT DISTINCT itemID FROM Item WHERE itemID = '$itemID'");
    if($result->num_rows > 0) {

        $sql = "update Item set isDeleted=true where itemID=$itemID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Item with item id $itemID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Error, failed to connect to database at delete." . $conn->connect_error, "red" );
          }
    }

}
elseif (isset($_GET['deleteDonation'])) {

	$conn       = connectDB();
  $donationID = $_GET['donationID'];

  $result = $conn->query("SELECT DISTINCT donationID FROM Donation WHERE donationID = '$donationID'");
  if($result->num_rows > 0) {

      $sql = "delete from Donation where donationID=$donationID";

       if ($conn->query($sql) === TRUE) {
              echoDivWithColor( "<h3>Donation with Donation id $donationID deleted</h3>", "green");
        }
        else{
          echoDivWithColor("Error, failed to connect to database at delete." . $conn->connect_error, "red" );
        }
  }
}

// **********************************************
// * Delete donation partner
elseif (isset($_GET['deleteDonationPartner'])) {

	$conn = connectDB();
    $donationPartnerID = $_GET['donationPartnerID'];
    /* Check connection*/
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT DISTINCT donationPartnerID FROM DonationPartner WHERE donationPartnerID = '$donationPartnerID'");
    if($result->num_rows > 0) {

        $sql = "delete from DonationPartner where donationPartnerID=$donationPartnerID";

         if ($conn->query($sql) === TRUE) {
                echoDivWithColor( "<h3>Donation Partner with donationPartnerID id $donationPartnerID deleted</h3>", "green");
          }
          else{
            echoDivWithColor("Error, this donation partner is in use" . $conn->connect_error, "red" );
          }
    }
}


?>