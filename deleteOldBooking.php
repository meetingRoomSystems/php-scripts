<?php

$response = array();

if (isset($_GET['username'])){
  $username = $_GET['username'];

  $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

  if (mysqli_connect_errno($con)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $minDate = date("Y-m-d");
  $check =  mysqli_query($con,"SELECT role FROM login WHERE username='$username' LIMIT 1");
  $row = mysqli_fetch_array($check,MYSQLI_ASSOC);
  if($row["role"] == "admin"){
    $result = mysqli_query($con,"DELETE FROM booking WHERE booking_date < '$minDate'");
    $result2 = mysqli_query($con,"DELETE FROM reminders WHERE booking_date < '$minDate'");
    if($result && $result2){
      $response["success"] = 1;
      $response["message"] = "Done";
      echo json_encode($response);
    }
    else{
      $response["success"] = 0;
      $response["message"] = "Unable to delete";
      echo json_encode($response);
    }
  }
  else{
    $response["success"] = 0;
    $response["message"] = "RESTRICTED";
    echo json_encode($response);
  }

}
else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);
}



?>
