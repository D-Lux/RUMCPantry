<?php 

            $servername = "127.0.0.1";
            $username = "root";
            $password = "";
            $dbname = "foodpantry";
            /* previous lines set up the strings for connextion*/

            mysql_connect($servername, $username, $password);
            mysql_select_db($dbname);
            //standard DB stuff up to here
            $sql = "SELECT DISTINCT displayName FROM Item"; //select distinct values from the collumn in this table
            $result = mysql_query($sql);

            echo "<input list='diaplayNames' name='displayName'>";
            
            echo "<datalist id='diaplayNames'>"; //this id must be the same as the list = above
            
            while ($row = mysql_fetch_array($result)) {
                echo "<option value='" . $row['displayName'] . "'>" . $row['displayName'] . "</option>";
            }
            echo "</datalist>";

?>