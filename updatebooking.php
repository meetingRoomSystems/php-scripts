<?php

/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_GET['username']) || isset($_GET['old_room']) || isset($_GET['old_booking_date']) || isset($_GET['old_booking_time']) || isset($_GET['new_room']) || isset($_GET['new_booking_date']) || isset($_GET['new_booking_time']) || isset($_GET['type'])) {

    // type 1 = change room , type 2 = change date and time, type 3 = change all
    $type = $_GET['type'];
    // connecting to db
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if($type == 1){
      $username = $_GET['username'];
      $old_room= $_GET['old_room'];
      $new_room= $_GET['new_room'];
      $old_date= $_GET['old_booking_date'];
      $old_time= $_GET['old_booking_time'];
      $getOthers = mysqli_query($con,"SELECT others FROM booking WHERE username='$username' AND room='$old_room' AND booking_date='$old_date' AND booking_time = '$old_time'");
      $row = mysqli_fetch_array($getOthers,MYSQLI_ASSOC);
      $others = $row['others'];
      if($others != ""){
        $update = explode(",", $others);
        $result = mysqli_query($con,"UPDATE booking SET room = '$new_room' WHERE username = '$username' AND room = '$old_room' AND booking_date = '$old_date' AND booking_time = '$old_time'");
        for($i=0;$i<sizeof($update);$i++){
          $uname = $update[$i];
          $othersResult = mysqli_query($con,"UPDATE booking SET room = '$new_room' WHERE fullname = '$uname' AND room = '$old_room' AND booking_date = '$old_date' AND booking_time = '$old_time'");
        }
      }
    }
    // else if($type == 2){
    //   $username = $_GET['username'];
    //   $old_room= $_GET['old_room'];
    //   $old_date= $_GET['old_booking_date'];
    //   $old_time= $_GET['old_booking_time'];
    //   $new_date= $_GET['new_booking_date'];
    //   $new_time= $_GET['new_booking_time'];
    //   $result = mysqli_query($con,"UPDATE booking SET booking_date = '$new_date', booking_time = '$new_time'  WHERE username = '$username' AND room = '$old_room' AND booking_date = '$old_date' AND booking_time = '$old_time'");
    // }
    // else if($type == 3){
    //   $username = $_GET['username'];
    //   $old_room= $_GET['old_room'];
    //   $new_room= $_GET['new_room'];
    //   $old_date= $_GET['old_booking_date'];
    //   $old_time= $_GET['old_booking_time'];
    //   $new_date= $_GET['new_booking_date'];
    //   $new_time= $_GET['new_booking_time'];
    //   $result = mysqli_query($con,"UPDATE booking SET room= '$new_room', booking_date = '$new_date', booking_time = '$new_time' WHERE username = '$username' AND room = '$old_room' AND booking_date = '$old_date' AND booking_time = '$old_time'");
    // }
    if ($result) {
      // successfully inserted into database
      $response["success"] = 1;
      $response["message"] = "Booking updated.";

      // echoing JSON response
      echo json_encode($response);
    } else {
      // failed to insert row
      $response["success"] = 0;
      $response["message"] = "Unable to update right now";
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
