<?php


function echoDivWithColor($message, $color)
{
    echo  '<div style="color: '.$color.';">'; /*must do color like this, can't do these three lines on the same line*/
    echo $message;
    echo  '</div>';
}

?>