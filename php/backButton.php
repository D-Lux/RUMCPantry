<?php

$buttonText = isset($btnText) ? $btnText : "Back";

echo "<button style='z-index:20;' id='btn-back' onclick='goBack()'><i style='padding-bottom=8px;font-size:0.8em;padding-right:5px;' class='fa fa-chevron-circle-left'></i>". $buttonText. "</button>";


?>

<script type="text/javascript" >
  function goBack() {
    switch (true) {
        case (location.pathname.includes("ap_ro2.php")):
        case (location.pathname.includes("ap_ro2i.php")):
        case (location.pathname.includes("ap_ro5.php")):
        case (location.pathname.includes("ap_ro5i.php")):
        case (location.pathname.includes("ap_ro8.php")):
            window.location.assign("<?=$basePath?>ap_ro1.php");
            break;
        case (location.pathname.includes("ap_ro3.php")):
        case (location.pathname.includes("ap_ro4.php")):
            window.location.assign("<?=$basePath?>ap_ro2.php");
            break;
        case (location.pathname.includes("ap_ro9.php")):
        case (location.pathname.includes("ap_ro10.php")):
            window.location.assign("<?=$basePath?>ap_ro8.php");
            break;
        case (location.pathname.includes("ap_ro6.php")):
        case (location.pathname.includes("ap_ro7.php")):
            window.location.assign("<?=$basePath?>ap_ro5.php");
            break;

        case (location.pathname.includes("ap_ao2.php")):
        case (location.pathname.includes("ap_ao3.php")):
            window.location.assign("<?=$basePath?>ap_ao1.php");
            break;

        case (location.pathname.includes("ap_io1.php")):
        case (location.pathname.includes("ap_ao1.php")):
        case (location.pathname.includes("ap_co1.php")):
        case (location.pathname.includes("ap_co1i.php")):
        case (location.pathname.includes("ap_do1.php")):
        case (location.pathname.includes("ap_oo3.php")):
        case (location.pathname.includes("ap_ro1.php")):
        case (location.pathname.includes("checkIn.php")):
        case (location.pathname.includes("adjustLogins.php")):
            window.location.assign("<?=$basePath?>ap1.php");
            break;

        case (location.pathname.includes("ap_co2.php")):
        case (location.pathname.includes("ap_co3.php")):
        case (location.pathname.includes("ap_oo4e.php")):
            window.location.assign("<?=$basePath?>ap_co1.php");
            break;

        case (location.pathname.includes("ap_do2.php")):
        case (location.pathname.includes("ap_do3.php")):
        case (location.pathname.includes("ap_do4.php")):
        case (location.pathname.includes("ap_do5.php")):
        case (location.pathname.includes("donationOps.php")):
            window.location.assign("<?=$basePath?>ap_do1.php");
            break;


        case (location.pathname.includes("itemOps.php")):
        case (location.pathname.includes("ap_io6.php")):
        case (location.pathname.includes("ap_io7.php")):
        case (location.pathname.includes("ap_io8.php")):
        case (location.pathname.includes("ap_oo1.php")):
            window.location.assign("<?=$basePath?>ap_io1.php");
            break;

        case (location.pathname.includes("ap_io2.php")):
        case (location.pathname.includes("ap_io3.php")):
            window.location.assign("<?=$basePath?>ap_io7.php");
            break;

        case (location.pathname.includes("ap_io4.php")):
        case (location.pathname.includes("ap_io5.php")):
            window.location.assign("<?=$basePath?>ap_io8.php");
            break;

        case (location.pathname.includes("ap_oo2.php")):
        case (location.pathname.includes("ap_oo5.php")):
            window.location.assign("<?=$basePath?>ap_oo1.php");
            break;

        case (location.pathname.includes("cp1.php")):
        case (location.pathname.includes("ap1.php")):
        case (location.pathname.includes("cap.php")):
        case (location.pathname.includes("ciup.php")):
            window.location.assign("<?=$basePath?>mainpage.php");
            break;

        case (location.pathname.includes("cp2.php")):
        case (location.pathname.includes("cof.php")):
            // If the admin is viewing the order form, we need to go back to a different page
            if (getQueryVariable("Small") || getQueryVariable("Medium") || getQueryVariable("Large")) {
                window.location.assign("<?=$basePath?>ap_oo1.php");
            } else {
                window.location.assign("<?=$basePath?>cp1.php");
            }
            break;

        case (location.pathname.includes("ap_oo4.php")):
        case (location.pathname.includes("awc.php")):
        case (location.pathname.includes("endOfDay.php")):
            window.location.assign("<?=$basePath?>checkIn.php");
            break;

            //case (location.pathname.includes("ap_ao4.php")) :
            //case (location.pathname.includes("ap_co4.php")) :
            //case (location.pathname.includes("ap_co5.php")) :
        default:
            window.history.back();
    }
}
</script>