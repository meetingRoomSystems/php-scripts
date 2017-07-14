<?php


// array for JSON response
$response = array();

// check for required fields
if (isset($_GET['username']) && isset($_GET['capacity']) && isset($_GET['room']) && isset($_GET['booking_date']) && isset($_GET['booking_time']) && isset($_GET['length'])) {

    $username = $_GET['username'];
    $capacity= $_GET['capacity'];
    $room= $_GET['room'];
    $date= $_GET['booking_date'];
    $time= $_GET['booking_time'];
    $length = $_GET['length'];
    $minDate = date("Y-m-d");

    $time2 = new DateTime($time);
    if($length == 30){
      $time2 ->add(new DateInterval('PT30M'));
    }
    else if($length == 60){
      $time2 ->add(new DateInterval('PT1H'));
    }
    else if($length == 90){
      $time2 ->add(new DateInterval('PT1H30M'));
    }
    $endTime =  $time2->format('H:i:s');

    // connecting to db
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if($length == 30){
      $check = mysqli_query($con,"SELECT room FROM booking WHERE booking_date='$date' AND (booking_time='$time' OR booking_time_end='$endTime') AND room='$room' LIMIT 1");
    }
    else if($length == 60){
      $time3 = new DateTime($time);
      $time3 ->add(new DateInterval('PT30M'));
      $queryTime = $time3->format('H:i:s');
      $check = mysqli_query($con,"SELECT room FROM booking WHERE booking_date='$date' AND (booking_time='$time' OR booking_time='$queryTime' OR booking_time_end='$queryTime' OR booking_time_end='$endTime') AND room='$room' LIMIT 1");
    }
    else if($length == 90){
      $time3 = new DateTime($time);
      $time3 ->add(new DateInterval('PT30M'));
      $queryTime = $time3->format('H:i:s');
      $time3 ->add(new DateInterval('PT30M'));
      $queryTime2 = $time3->format('H:i:s');
      $check = mysqli_query($con,"SELECT room FROM booking WHERE booking_date='$date' AND (booking_time='$time' OR booking_time='$queryTime' OR booking_time='$queryTime2' OR booking_time_end='$queryTime' OR booking_time_end='$queryTime2' OR booking_time_end='$endTime') AND room='$room' LIMIT 1");
    }
    if(mysqli_num_rows($check) == 0){
      $getName = mysqli_query($con,"SELECT fullname FROM login WHERE username='$username'");
      if($getName){
        $row = mysqli_fetch_array($getName,MYSQLI_ASSOC);
        $name = $row["fullname"];
      }
      $result = mysqli_query($con,"INSERT INTO booking (fullname, username, capacity, room, booking_date, booking_time,booking_time_end,duration) VALUES('$name', '$username', '$capacity','$room','$date','$time','$endTime','$length')");
      $result2 = mysqli_query($con,"INSERT INTO reminders (fullname, username, capacity, room, booking_date, booking_time,reminder) VALUES('$name', '$username', '$capacity','$room','$date','$time','0')");
      $response["success"] = 1;
      $response["message"] = "Booking made.";
      echo json_encode($response);

    }
    else {
      $response["success"] = 0;
      $response["message"] = "Booking space not available (already booked)";
      // echoing JSON response
      echo json_encode($response);
    }

} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

  
    echo json_encode($response);
}
?>
