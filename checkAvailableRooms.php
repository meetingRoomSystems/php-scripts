<?php


// array for JSON response
$response = array();


if (isset($_GET["booking_time"]) && isset($_GET["booking_date"])){
  $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");


  if (mysqli_connect_errno($con)) {
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $date = $_GET["booking_date"];
  $time = $_GET["booking_time"];
  $result = mysqli_query($con,"SELECT room FROM booking WHERE booking_time='$time' AND booking_date='$date' LIMIT 4");

  if ((mysqli_num_rows($result) != 0)) {
    $response["success"] = 1;
    $response["rooms"] = array();
    $rm =  array('1','2','3','4');

    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
      if(($key = array_search($row["room"], $rm)) !== false) {
          unset($rm[$key]);
      }
    }
    array_push($response["rooms"], $rm);
    echo json_encode($response);
  } else {
      $response["success"] = 2;
      $response["rooms"] = array('1','2','3','4');
      echo json_encode($response);
  }
} else {
      $response["success"] = 0;
      $response["message"] = "Required field(s) are missing";

      echo json_encode($response);
}
?>
