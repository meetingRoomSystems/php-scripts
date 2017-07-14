<?php

$response = array();

if (isset($_GET['username']) || isset($_GET['old_room']) || isset($_GET['old_booking_date']) || isset($_GET['old_booking_time']) || isset($_GET['new_room']) || isset($_GET['new_booking_date']) || isset($_GET['new_booking_time']) || isset($_GET['type'])) {

    // type 1 = change room , type 2 = change date and time, type 3 = change all
    $type = $_GET['type'];
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
      $result = mysqli_query($con,"UPDATE booking SET room = '$new_room' WHERE username = '$username' AND room = '$old_room' AND booking_date = '$old_date' AND booking_time = '$old_time'");
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
      $response["success"] = 1;
      $response["message"] = "Booking updated.";

      echo json_encode($response);
    } else {
      $response["success"] = 0;
      $response["message"] = "Unable to update right now";

      echo json_encode($response);
    }

} else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);
}
?>
