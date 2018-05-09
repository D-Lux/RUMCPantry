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

	$sql = "SELECT COUNT(*) as totalFamilies, SUM(numOfKids) as totalKids,
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
	$totalFamilies    = (isset($familyQueryData['totalFamilies']))    ? $familyQueryData['totalFamilies']     : 0;
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
            ON c.categoryID = item.categoryID
          WHERE inv.visitDate BETWEEN '{$startDate}' AND '{$endDate}'
          AND inv.status = " . GetCompletedStatus() . "
          GROUP BY item.itemName";
  //die($sql);
  $results = runQuery($conn, $sql);

  $catInfo  = [];   // [cat] - ['qty'] | ['price']
  $itemInfo = [];   // [cat][item] - ['qty'] | ['price']
  if (!empty($results)) {
    foreach ($results as $result) {
      if (array_key_exists ( $result['name'],  $catInfo )) {
        $catInfo[$result['categoryID']]['qty']   += $result['totalQty'];
        $catInfo[$result['categoryID']]['price'] += $result['totalItemsPrice'];
      }
      else {
        $catInfo[$result['categoryID']]['qty']    = $result['totalQty'];
        $catInfo[$result['categoryID']]['price']  = $result['totalItemsPrice'];
        $catInfo[$result['categoryID']]['name']   = $result['name'];
      }

      $itemInfo[$result['categoryID']][htmlspecialchars($result['itemName'])]['qty']   = $result['totalQty'];
      $itemInfo[$result['categoryID']][htmlspecialchars($result['itemName'])]['price'] = $result['totalItemsPrice'];
    }
  }

  /*
  I'd also like to see monthly how many cans of kernal corn, string beans, peas, carrots, pasta sauce and peanut butter (all sizes collectively), oh and breakfast cereal (separate sweet and non-sweetened). This will help us with inventory management and ordering.

  */
  // Close the database connection, we're done with it
  closeDB($conn);


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
    <button class="tablinks" id="ClientInfo">Reallocation Information<br><i class="fa fa-gift fa-3x"></i></button>
  </div>

  <div id="OverviewContent" class="tabcontent defaultTab">
    <table class="table">
      <tr>
        <th>Number of families</th>
        <td><?=$totalFamilies?></td>
      </tr>
      <tr>
        <th>Number of families with children</th>
        <td><?=$familiesWithKids?></td>
      </tr>
      <tr>
        <th>Number of people</th>
        <td><?=$totalAffected?></td>
      </tr>
      <tr>
        <th>Number of children</th>
        <td><?=$totalKids?></td>
      </tr>
      <tr>
        <th>Total Value Distributed</th>
        <td><?=formatCurrency($donationWorth)?></td>
      </tr>

      <tr>
        <th>Average order cash value per family</th>
        <?php if ( $totalFamilies != 0 ) { ?>
          <td><?=formatCurrency(round(($donationWorth / $totalFamilies), 2, PHP_ROUND_HALF_UP))?></td>
        <?php } else { ?>
          <td><?=formatCurrency(0)?></td>
        <?php } ?>
      </tr>
      <tr>
        <th>Average order cash value per person</th>
        <?php if ( $totalFamilies != 0 ) { ?>
          <td><?=formatCurrency(round(($donationWorth / $totalAffected), 2, PHP_ROUND_HALF_UP))?></td>
        <?php } else { ?>
          <td><?=formatCurrency(0)?></td>
        <?php } ?>
      </tr>
      <tr>
        <th>Weight of items donated by partners</th>
        <td><?=$donatedWeight?></td>
      </tr>
    </table>
  </div>

  <div id="BreakdownContent" class="tabcontent">
    <div id="breakdownPieChartHolder"></div>
    <div id="itemBreakdownTable">
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
  <div id="ClientInfoContent" class="tabcontent">TO COME</div>
  <div id="ReallocationContent" class="tabcontent">TO COME</div>


  <script type="text/javascript">
    Highcharts.chart('breakdownPieChartHolder', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Item Distribution Breakdown'
    },
    xAxis: {
        categories: [
          <?php foreach ($catInfo as $catID => $info) { ?>
            '<?=$info['name']?>',
          <?php } ?>
          ],
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Quantity Distributed',
        },
        labels: {
            overflow: 'justify'
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [
    <?php foreach ($catInfo as $catID => $info) { ?>
          {
            "name"      : "<?=$info['name']?>",
            "data"      : <?=$info['qty']?>,
          },
    <?php } ?>
    ]
});
  // Highcharts.chart('breakdownPieChartHolder', {
  //   chart       : {
  //     type        : 'pie',
  //       	width: 550,
  //         height:500,
  //               },
  //   title       : {
  //     text        : 'Category and item donation breakdown'
  //               },
  //   subtitle    : {
  //     text        : 'Click slices for drilldown',
  //               },
  //   plotOptions : {
  //     pie         : {
  //       allowPointSelect: true,
  //       cursor          : 'pointer',
  //       dataLabels      : {
  //         enabled         : true,
  //         format          : '{point.name}: {point.y} Item(s)'
  //                       },
  //       showInLegend    : true
  //     }
  //   },
  //   tooltip      : {
  //     headerFormat : '<span style="font-size:11px">{series.name}</span><br>',
  //     pointFormat  : '<span style="color:{point.color}">\u25CF</span> {point.name}: <b>{point.percentage:.2f}% of Total</b><br/>'
  //                 },
  //   series      : [{
  //     name      : "Categories",
  //     colorByPoint: true,
  //     data        : [
  //       <?php foreach ($catInfo as $catID => $info) { ?>
  //         {
  //           "name"      : "<?=$info['name']?>",
  //           "y"         : <?=$info['qty']?>,
  //           "drilldown" : "drill<?=$catID?>"
  //         },
  //       <?php } ?>
  //     ],

  //   }],
  //   drilldown   : {
  //     series      : [
  //       <?php foreach ($itemInfo as $catID => $info) { ?>
  //         {
  //           "name" : "<?=$catInfo[$catID]['name']?>",
  //           "id"   : "drill<?=$catID?>",
  //           "data" : [
  //             <?php foreach ($info as $iName => $data) { ?>
  //               [
  //                 "<?=$iName?>",
  //                 <?=$data['qty']?>
  //               ],
  //             <?php } ?>
  //           ],
  //         },
  //       <?php } ?>
  //               ]
  //             }
  // });
  </script>


