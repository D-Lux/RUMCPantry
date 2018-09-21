<?php
  session_start();
// © 2018 Daniel Luxa ALL RIGHTS RESERVED
	include('../utilities.php');

	// AJAX call to update reporting with new dates
	if (!isset($_GET['startDate']) ||  !isset($_GET['endDate'])) {
		DIE("Please Select Dates");
	}
  $startDate = DATE("Y-m-d" , strtotime($_GET['startDate']));
  $endDate   = DATE("Y-m-d" , strtotime($_GET['endDate']));
	$conn      = connectDB();


  // *****************************************************************************
  // * Overview information

	$sql = "SELECT COUNT(*) as totalAppts, SUM(numOfKids) as totalKids,
					SUM(numOfAdults + numOfKids) as totalAffected,
					SUM(CASE WHEN numOfKids>0 THEN 1 ELSE 0 END) as familiesWithKids
          FROM invoice
          JOIN client
          ON invoice.clientID = client.clientID
          WHERE invoice.visitDate BETWEEN '" . $startDate . "' AND '" . $endDate . "'
          AND invoice.status = " . GetCompletedStatus();

	// Family query
	$familyQueryData = runQueryForOne($conn, $sql);

	// get the total worth of all items donated to clients
	$sql = "SELECT sum(totalItemsPrice) as donationWorth
          FROM invoice
          JOIN invoicedescription
          ON invoicedescription.InvoiceID = invoice.InvoiceID
          WHERE invoice.visitDate BETWEEN '" . $startDate . "' AND '" . $endDate . "'
          AND invoice.status = " . GetCompletedStatus();

	// Invoice query test for donation worth
	$invoiceQueryData = runQueryForOne($conn, $sql);

	// get information of items donated to the pantry
	$sql = "SELECT SUM(
            (refbakery * "       . WEIGHT_BAKERY . ") +
            (refDairyAndDeli * " . WEIGHT_DAIRY . ") +
            (frozenMeat * "      . WEIGHT_MEAT . ") +
            (dryShelfStable * "  . WEIGHT_MIX . ") +
            (dryNonFood * "      . WEIGHT_NONFOOD . ") +
            (frozenPrepared * "  . WEIGHT_PREPARED . ") +
            (refProduce * "      . WEIGHT_PRODUCE . ") +
            (frozenNonMeat * "   . WEIGHT_FROZEN . ") +
            (dryFoodDrive * "    . WEIGHT_FOODDRIVE . ") ) as donatedWeight
          FROM donation
          WHERE dateOfPickup > '" . $startDate . "'
          AND dateOfPickup < '" . $endDate . "'";


	// Query test for donations from partners
	$donationQueryData = runQueryForOne($conn, $sql);

	// Assign out our data to be more readable
	$totalAppts       = (isset($familyQueryData['totalAppts']))    ? $familyQueryData['totalAppts']     : 0;
	$totalKids        = (isset($familyQueryData['totalKids']))        ? $familyQueryData['totalKids']         : 0;
	$totalAffected    = (isset($familyQueryData['totalAffected']))    ? $familyQueryData['totalAffected']     : 0;
	$familiesWithKids = (isset($familyQueryData['familiesWithKids'])) ? $familyQueryData['familiesWithKids']  : 0;
	$donationWorth    = (isset($invoiceQueryData['donationWorth']))   ? $invoiceQueryData['donationWorth']    : 0;
	$donatedWeight    = (isset($donationQueryData['donatedWeight']))  ? $donationQueryData['donatedWeight']   : 0;


  // ******************************************************************************
  // * Breakdowns
  $sql = "SELECT c.name, c.categoryID, item.itemName, SUM(invD.totalItemsPrice) as totalItemsPrice, SUM(invD.quantity) as totalQty
          FROM invoice inv
          JOIN invoicedescription invD
            ON invD.invoiceID = inv.invoiceID
          JOIN item
            ON item.itemID = invD.itemID
          JOIN category c
            ON c.categoryID = item.reportID
          WHERE inv.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
          AND inv.status = " . GetCompletedStatus() . "
          GROUP BY item.itemName
          ORDER BY c.name";
  //die($sql);
  $results = runQuery($conn, $sql);

  $catInfo  = [];   // [cat] - ['qty'] | ['price']
  $itemInfo = [];   // [cat][item] - ['qty'] | ['price']
  if (!empty($results)) {
    foreach ($results as $result) {
      if (array_key_exists ( $result['categoryID'],  $catInfo )) {
        $catInfo[$result['categoryID']]['qty']   += $result['totalQty'];
        $catInfo[$result['categoryID']]['price'] += $result['totalItemsPrice'];
      }
      else {
        $catInfo[$result['categoryID']]['qty']    = $result['totalQty'];
        $catInfo[$result['categoryID']]['price']  = $result['totalItemsPrice'];
        $catInfo[$result['categoryID']]['name']   = $result['name'];
      }

      $itemInfo[$result['categoryID']][$result['itemName']]['qty']   = $result['totalQty'];
      $itemInfo[$result['categoryID']][$result['itemName']]['price'] = $result['totalItemsPrice'];
    }
  }

 	// ****************************************************************
	// * Output block
  ?>
<style>
  .breakrow:hover {
    background-color: lightgrey;
  }
  .down-caret {
    transform: rotate(90deg);
    color: green !important;
  }
</style>

  <div class="tab">
    <button class="tablinks" id="Overview">Overview<br><i class="fab fa-stack-overflow fa-3x"></i></button>
    <button class="tablinks" id="Breakdown">Distribution Breakdown<br><i class="fa fa-bar-chart fa-3x"></i></button>
    <button class="tablinks" id="ClientInfo">Client Information<br><i class="fa fa-user fa-3x"></i></button>
    <button class="tablinks" id="ReallocationContent">Reallocation Information<br><i class="fa fa-gift fa-3x"></i></button>
  </div>

  <div id="OverviewContent" class="tabcontent defaultTab">
    <table class="table">
      <tr>
        <th>Number of Appointments</th>
        <td><?=$totalAppts?></td>
      </tr>
      <tr>
        <th>Number of Appointments with Children</th>
        <td><?=$familiesWithKids?></td>
      </tr>
      <tr>
        <th>Number of People</th>
        <td><?=$totalAffected?></td>
      </tr>
      <tr>
        <th>Number of Children</th>
        <td><?=$totalKids?></td>
      </tr>
      <tr>
        <th>Total Retail Cash Value of Foods Distributed</th>
        <td><?=formatCurrency($donationWorth)?></td>
      </tr>

      <tr>
        <th>Average Retail Cash Value per Appointment</th>
        <?php if ( $totalAppts != 0 ) { ?>
          <td><?=formatCurrency(round(($donationWorth / $totalAppts), 2, PHP_ROUND_HALF_UP))?></td>
        <?php } else { ?>
          <td><?=formatCurrency(0)?></td>
        <?php } ?>
      </tr>
      <tr>
        <th>Average Retail Cash Value per Person</th>
        <?php if ( $totalAppts != 0 ) { ?>
          <td><?=formatCurrency(round(($donationWorth / $totalAffected), 2, PHP_ROUND_HALF_UP))?></td>
        <?php } else { ?>
          <td><?=formatCurrency(0)?></td>
        <?php } ?>
      </tr>
      <tr>
        <th>Weight of Items Donated by Partners</th>
        <td><?=$donatedWeight?></td>
      </tr>
    </table>
  </div>

  <div id="BreakdownContent" class="tabcontent">
    <div id="breakdownChartHolder" style="height: 1200px"></div>
    <div style="padding-top:200px" id="itemBreakdownTable">
      <table class="table">
        <thead>
          <tr>
            <th>Item / Category Name</th>
            <th>Quantity Distributedd</th>
            <th>Total Distribution Worth</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($catInfo as $catID => $cInfo) { ?>
            <tr id="catRow<?=$catID?>" style="cursor:pointer;background-color:#FFD97A;">
              <th><i class="fa fa-caret-right" style="padding-right:5px;color:blue;"></i><?=$cInfo['name']?></th>
              <td><?=$cInfo['qty']?></td>
              <td><?=formatCurrency($cInfo['price'])?></td>
            </tr>
            <?php foreach ($itemInfo[$catID] as $iName => $iInfo) { ?>
              <tr class="catRow<?=$catID?> breakrow" style="display:none;">
                <td><?=$iName?></td>
                <td><?=$iInfo['qty']?></td>
                <td><?=formatCurrency($iInfo['price'])?></td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php
    // *********************************************************************
    // * Client breakdown information
    $completedStatus = GetCompletedStatus();
    $sql = "SELECT familyMemberID, firstName, lastName, birthDate, gender, isHeadOfHousehold, client.clientID
            FROM invoice
            JOIN client
            ON invoice.clientID = client.clientID
            JOIN familymember
            ON familymember.clientID = client.clientID
            WHERE invoice.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
            AND invoice.status = {$completedStatus} 
            AND familymember.isDeleted = 0 
            GROUP BY familyMemberID";

    $cbResults = runQuery($conn, $sql);

    $Clients = [];
    $Women   = 0;
    $Men     = 0;
    $NoGender= 0;

    foreach ($cbResults as $result) {
      if ($result['isHeadOfHousehold'] == 1) {
        $Clients['family'][$result['clientID']] = $result['lastName'];
      }

      // Put our client in the right bins
      $addGender = false;
      if (isKidBirthdate($result['birthDate'])) {
        $Clients['kids'][$result['familyMemberID']] = $result['firstName'] . " " . $result['lastName'];
      }
      elseif (isSeniorBirthdate($result['birthDate'])) {
        $Clients['seniors'][$result['familyMemberID']] = $result['firstName'] . " " . $result['lastName'];
      }
      else {
        $Clients['adults'][$result['familyMemberID']] = $result['firstName'] . " " . $result['lastName'];
      }

      if ($result['gender'] == -1)   { $Men++;      }
      elseif($result['gender'] == 1) { $Women++;    }
      else                           { $NoGender++; }
      
    }
  
    $sql = "SELECT clientID, SUM(totalItemsPrice) as minDonation, visitDate
            FROM invoice
            JOIN invoicedescription
            ON invoicedescription.InvoiceID = invoice.InvoiceID
            WHERE invoice.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
            AND invoice.status = {$completedStatus} 
            GROUP BY invoice.invoiceID
            HAVING SUM(totalItemsPrice) = 
                ( SELECT MIN(totalDonation) 
                  FROM  ( SELECT SUM(totalItemsPrice) as totalDonation
                          FROM invoice
                          JOIN invoicedescription
                          ON invoicedescription.InvoiceID = invoice.InvoiceID
                          WHERE invoice.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
                          AND invoice.status = {$completedStatus} 
                          GROUP BY invoice.invoiceID) subQ)";

    $minOrder = runQueryForOne($conn, $sql);

    $sql = "SELECT clientID, SUM(totalItemsPrice) as maxDonation, visitDate
            FROM invoice
            JOIN invoicedescription
            ON invoicedescription.InvoiceID = invoice.InvoiceID
            WHERE invoice.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
            AND invoice.status = {$completedStatus} 
            GROUP BY invoice.invoiceID
            HAVING SUM(totalItemsPrice) = 
                ( SELECT MAX(totalDonation) 
                  FROM  ( SELECT SUM(totalItemsPrice) as totalDonation
                          FROM invoice
                          JOIN invoicedescription
                          ON invoicedescription.InvoiceID = invoice.InvoiceID
                          WHERE invoice.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
                          AND invoice.status = {$completedStatus} 
                          GROUP BY invoice.invoiceID) subQ)";

    $maxOrder = runQueryForOne($conn, $sql);

  ?>
  <div id="ClientInfoContent" class="tabcontent">
    <table class="table">
      <tr><td>Number of Families</td><td><?=count($Clients['family'])?></td></tr>
      <tr><td>Number of Distinct People</td><td><?=$Women + $Men + $NoGender?></td></tr>
      <tr><td>Number of Distinct Adults</td><td><?=count($Clients['adults']) + count($Clients['seniors'])?></td></tr>
      <tr><td>Number of Distinct Children Under the Age of 18</td><td><?=count($Clients['kids'])?></td></tr>
      <tr><td>Number of Distinct People Aged <?=SENIOR_AGE_CUTOFF?> or Older</td><td><?=count($Clients['seniors'])?></td></tr>
      <tr><td>Number of Men</td><td><?=$Men?></td></tr>
      <tr><td>Number of Women</td><td><?=$Women?></td></tr>
      <tr><td>Number of Unknown Gender</td><td><?=$NoGender?></td></tr>
      <tr><td>Largest Redistribution Worth</td><td>$<?=$maxOrder['maxDonation']?> - <?=$Clients['family'][$maxOrder['clientID']]?> - <?=$maxOrder['visitDate']?></td></tr>
      <tr><td>Smallest Redistribution Worth</td><td>$<?=$minOrder['minDonation']?> - <?=$Clients['family'][$minOrder['clientID']]?> - <?=$minOrder['visitDate']?></td></tr>
    </table>
  </div>


  <?php
    // TODO: Reallocation data
  ?>
  <div id="ReallocationContent" class="tabcontent">TO COME</div>


<?php closeDB($conn); ?>
<script type="text/javascript">
  Highcharts.chart('breakdownChartHolder', {
    chart     : { type: 'bar' },
    title     : { text: 'Item Distribution' },
    subtitle  : { text: 'Click a bar to view individual items' },
    xAxis     : { type: 'category' },
    yAxis     : { title: { text: 'Quantity Distributed' } },
    legend    : { enabled: false },
    plotOptions: {
        series: {
            pointWidth: 20,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },
    events        : {
      // Drill events to change the subtitle to be helpful
      drillup       : function (e) {
                      var chart = this;
                      chart.setTitle(null, { text: "Click a bar to view individual items", });
                      var newSize = 25 * (e.seriesOptions.data.length + 10);
                      chart.setSize(undefined, newSize);
                      $("#breakdownChartHolder").height(newSize + 20);
                  },
      drilldown     : function (e) {
                        var chart = this;
                        chart.setTitle(null, { text: e.seriesOptions.name , });
                        var newSize = 25 * (e.seriesOptions.data.length + 10);
                        chart.setSize(undefined, newSize);
                        $("#breakdownChartHolder").height(newSize + 20);
                      }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> items distributed<br/>'
    },

    "series": [
        {
            "name": "Categories",
            "colorByPoint": true,
            "data": [
                  <?php foreach ($catInfo as $catID => $info) { ?>
                    {
                      "name" : "<?=$info['name']?>",
                      "y"    : <?=$info['qty']?>,
                      "drilldown" : "<?=$info['name']?>",
                    },
                  <?php } ?>
            ]
        }
    ],

    "drilldown": {
        "series": [

                <?php foreach ($itemInfo as $catID => $iinfo) { ?>
                {
                  "name" : "<?=$catInfo[$catID]['name']?>",
                  "id"   : "<?=$catInfo[$catID]['name']?>",
                  "data" : [
                    <?php foreach ($iinfo as $iName => $data) { ?>
                      [
                        "<?=$iName?>",
                        <?=$data['qty']?>
                      ],
                    <?php } ?>
                  ]
                },
              <?php } ?>


        ]
    },
  });
  </script>


