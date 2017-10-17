<?php include 'php/header.php'; ?>
<?php include 'php/checkLogin.php';?>



<button id='btn_back' onclick="goBack()">Back</button>
<h3>Donation Operations</h3>
    <form method="get" action="ap_do2.php">
        <input type="submit" value="Add a donation">
    </form>

    <form method="get" action="ap_do3.php">
        <input type="submit" value="Add a donation partner">
    </form>

    <!--<?php include 'php/displayDonations.php';?> -->
    <?php include 'php/displayDonationPartners.php';?>
    
</div><!-- /body_content -->
</div><!-- /content -->

</body>

</html>