<?php

$response = array();

if (isset($_GET["username"])){
  $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

  if (mysqli_connect_errno($con)) {
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $username = $_GET["username"];
  $result = mysqli_query($con,"SELECT fullname,room,capacity,booking_time,booking_date,reminder FROM reminders WHERE username='$username'");
  $response["success"] = 1;
  $response["bookings"] = array();
  while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
    $booking = array();
    $booking["fullname"] = $row["fullname"];
    $booking["capacity"] = $row["capacity"];
    $booking["room"] = $row["room"];
    $booking["booking_date"] = $row["booking_date"];
    $booking["booking_start"] = $row["booking_time"];
    $booking["reminder"] = $row["reminder"];
    array_push($response["bookings"], $booking);
  }
  $del = mysqli_query($con,"DELETE FROM reminders WHERE username='$username'");
  echo json_encode($response);
} else {
     $response["success"] = 0;
     $response["message"] = "Required field(s) are missing";

     echo json_encode($response);
 }
 ?>
