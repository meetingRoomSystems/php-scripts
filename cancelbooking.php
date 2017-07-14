<?php

$response = array();

if (isset($_GET['username']) && isset($_GET['room']) && isset($_GET['booking_date']) && isset($_GET['booking_time'])) {
    $username = $_GET['username'];
    $room= $_GET['room'];
    $date= $_GET['booking_date'];
    $time= $_GET['booking_time'];

    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");


    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $getData = mysqli_query($con,"SELECT capacity,fullname FROM booking WHERE username = '$username' AND room = '$room' AND booking_date = '$date' AND booking_time = '$time'");
    $row = mysqli_fetch_array($getData,MYSQLI_ASSOC);
    $fullname = $row["fullname"];
    $capacity = $row["capacity"];
    $result = mysqli_query($con,"DELETE FROM booking WHERE username = '$username' AND room = '$room' AND booking_date = '$date' AND booking_time = '$time'");
    $result2 = mysqli_query($con,"INSERT INTO reminders (fullname, username, capacity, room, booking_date, booking_time,reminder) VALUES('$fullname', '$username', '$capacity','$room','$date','$time','1')");
    if ($result) {
      $response["success"] = 1;
      $response["message"] = "Booking canceled.";

      echo json_encode($response);
    } else {
      // failed to insert row
      $response["success"] = 0;
      $response["message"] = "Unable to cancel book right now";

      echo json_encode($response);
    }

} else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);
}
?>
