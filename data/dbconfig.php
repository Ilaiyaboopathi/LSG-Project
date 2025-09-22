<?php
  // $servername = "localhost";
  //    $username = "MBWtaskmanagerDemo";
  //    $password = "Fctm17zyyUeLSfoy74kX";
  //    $dbname = "DemoTmDBmbw24";



      //     $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "ags_task_new";


    $servername = "localhost";
   $username = "root";
   $password = "Boopathi@123";
    $dbname = "lsg";



    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>