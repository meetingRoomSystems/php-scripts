<?php

/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_GET['username']) && isset($_GET['room']) && isset($_GET['booking_date']) && isset($_GET['booking_time'])) {
    $username = $_GET['username'];
    $room= $_GET['room'];
    $date= $_GET['booking_date'];
    $time= $_GET['booking_time'];

    // connecting to db
  $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $getData = mysqli_query($con,"SELECT others,capacity,fullname FROM booking WHERE username = '$username' AND room = '$room' AND booking_date = '$date' AND booking_time = '$time'");
    $row = mysqli_fetch_array($getData,MYSQLI_ASSOC);
    $fullname = $row["fullname"];
    $capacity = $row["capacity"];
    $newCapacity = $capacity - 1;
    $others = $row["others"];
    $result = mysqli_query($con,"DELETE FROM booking WHERE username = '$username' AND room = '$room' AND booking_date = '$date' AND booking_time = '$time'");
    $result2 = mysqli_query($con,"INSERT INTO reminders (fullname, username, capacity, room, booking_date, booking_time,reminder) VALUES('$fullname', '$username', '$capacity','$room','$date','$time','1')");
    if($others != ""){
      $update = explode(",", $others);
      for($i=0;$i<sizeof($update);$i++){
        $newOthers = '';
        $uname = $update[$i];
        for($j=0;$j<sizeof($update);$j++){
          if($update[$j] != $uname){
            $newOthers .= "" . $update[$j] . ",";
          }
        }
        $newOthers = rtrim($newOthers,",");
        $updateRes = mysqli_query($con,"UPDATE booking SET others = '$newOthers', capacity = '$newCapacity' WHERE fullname = '$uname' AND room = '$room' AND booking_date = '$date' AND booking_time = '$time'");
      }
    }

    if ($result) {
      // successfully inserted into database
      $response["success"] = 1;
      $response["message"] = "Booking canceled.";

      // echoing JSON response
      echo json_encode($response);
    } else {
      // failed to insert row
      $response["success"] = 0;
      $response["message"] = "Unable to cancel book right now";
      // echoing JSON response
      echo json_encode($response);
    }

} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>
