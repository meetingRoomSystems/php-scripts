<?php


$response = array();

if (isset($_GET["username"])){
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $username = $_GET["username"];
    $result = mysqli_query($con,"DELETE FROM login WHERE username='$username'");


    if ($result) {
      $response["success"] = 1;

      echo json_encode($response);
    } else {
        $response["success"] = 0;
        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) are missing";

    echo json_encode($response);
}
?>
