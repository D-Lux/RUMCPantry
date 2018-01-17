<?php 
  include 'php/header.php';
  include 'php/backButton.php';
?>

<h3>Donation Operations</h3>
    <form method="get" action="ap_do2.php">
        <input type="submit" value="Add a donation">
    </form>

    <form method="get" action="ap_do3.php">
        <input type="submit" value="Add a donation partner">
    </form>

    <!--<?php include 'php/displayDonations.php';?> -->
    <?php include 'php/displayDonationPartners.php';?>
    
<?php include 'php/footer.php'; ?>