<?php
  // © 2018 Daniel Luxa ALL RIGHTS RESERVED
  $pageRestriction = 99;
  include 'php/checkLogin.php';
  include 'php/header.php';
  include 'php/backButton.php';


  $sql = "SELECT i.itemName, i.small, i.medium, i.large,
            i.aisle, i.rack, i.shelf,
            c.name, c.small AS csmall, c.medium AS cmedium, c.large AS clarge
          FROM item i
          LEFT JOIN category c
            ON i.categoryID = c.categoryID
          WHERE (i.small > 0 OR i.medium > 0 OR i.large > 0)
          AND i.isDeleted = 0
          AND c.isDeleted = 0
          ORDER BY c.formOrder, i.aisle, i.rack, i.shelf, i.itemName";

  $conn = connectDB();

  $results = runQuery($conn, $sql);

  $currCat = "";
  $floatBreak = 0;
?>
<link rel="stylesheet" type="text/css" href="css/printInvManagement.css" media="print" />
<button class='float-right' id='btn-print' onClick='window.print();'><i class='fa fa-print'></i> Print</button>
<h3 class="text-center">Inventory Management</h3>

	<div class="body-content">

    <?php foreach ($results as $result) {
      if ($currCat != $result['name']) {
        if (!empty($currCat)) {
          echo "</table><hr>";
        }
        $floatDir = $floatBreak % 2 == 0 ? 'left' : 'right';
        $currCat = $result['name'];
        echo "<table style='width:75%'><tr><th colspan=4>" . $currCat . "</th>";
        echo "<th>" . $result['csmall'] . "</th>";
        echo "<th>" . $result['cmedium'] . "</th>";
        echo "<th>" . $result['clarge'] . "</th></tr>";
        echo "<tr><th style='width:40%;'>Item Name</th><th>Aisle</th><th>Rack</th>";
        echo "<th>Shelf</th><th>Small</th><th>Medium</th><th>Large</th></tr>";
      }
      echo "<tr><td class='text-left'>" . $result['itemName'] . "</td>";
      echo "<td>" . aisleDecoder($result['aisle']) . "</td>";
      echo "<td>" . rackDecoder($result['rack']) . "</td>";
      echo "<td>" . shelfDecoder($result['shelf']) . "</td>";
      echo "<td>" . $result['small'] . "</td>";
      echo "<td>" . $result['medium'] . "</td>";
      echo "<td>" . $result['large'] . "</td></tr>";
    } ?>
    </table>

<?php include 'php/footer.php'; ?>