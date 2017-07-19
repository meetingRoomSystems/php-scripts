<?php


// array for JSON response
$response = array();

// check for required fields
if (isset($_GET["username"])){
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $username = $_GET["username"];
    $result = mysqli_query($con,"SELECT fullname,username FROM login WHERE NOT username='$username'");
    if ($result) {
      $response["success"] = 1;
      $response["names"] = array();
      while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $user = array();
        $user["fullname"] = $row["fullname"];
        $user["username"] = $row["username"];
        array_push($response["names"], $user);
      }
      echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        // echoing JSON response
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) are missing";

    // echoing JSON response
    echo json_encode($response);
}
?>
