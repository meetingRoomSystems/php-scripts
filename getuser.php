<?php


$response = array();

if (isset($_GET["username"])){
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if(isset($_GET["username"])){
      $username = $_GET["username"];
      $result = mysqli_query($con,"SELECT fullname,username,role FROM login WHERE username='$username' LIMIT 1");
    }

    if ((mysqli_num_rows($result) != 0)) {
      $response["success"] = 1;
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $response["username"] = $row["username"];
      $response["fullname"] = $row["fullname"];
      $response["role"] = $row["role"];
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
