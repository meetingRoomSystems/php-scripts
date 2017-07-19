<?php


// array for JSON response
$response = array();

// check for required fields
if (isset($_GET['username']) && isset($_GET['capacity']) && isset($_GET['room']) && isset($_GET['booking_date']) && isset($_GET['booking_time']) && isset($_GET['length']) && isset($_GET['tags'])) {
    $username = $_GET['username'];
    $capacity= $_GET['capacity'];
    $room= $_GET['room'];
    $date= $_GET['booking_date'];
    $time= $_GET['booking_time'];
    $length = $_GET['length'];
    $minDate = date("Y-m-d");
    $tags = $_GET['tags'];


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
      $usernamesQuery = "SELECT fullname,username FROM login WHERE username IN ('$username',";
      if($tags[0] != "none"){
        for($i=0;$i<sizeof($tags)-1;$i++){
          $usernamesQuery .= "'$tags[$i]',";
        }
        $usernamesQuery .= "'$tags[$i]')";
        $getName = mysqli_query($con,$usernamesQuery);
      }
      else{
        $getName = mysqli_query($con,"SELECT fullname,username FROM login WHERE username='$username'");
      }

      if($getName){
        $allUsernames = array();
        while($r = mysqli_fetch_array($getName,MYSQLI_ASSOC)){
          $un['fullname'] = $r["fullname"];
          $un['username'] = $r["username"];
          array_push($allUsernames,$un);
        }
        $insertQueryBooking = "INSERT INTO booking (fullname, username, capacity, room, booking_date, booking_time,booking_time_end,duration,others) VALUES ";
        $insertQueryReminders = "INSERT INTO reminders (fullname, username, capacity, room, booking_date, booking_time,reminder) VALUES ";
        mysqli_data_seek($getName, 0);
        while($row = mysqli_fetch_array($getName,MYSQLI_ASSOC)){
          $fname = $row["fullname"];
          $uname = $row["username"];
          $others = "";
          for($j=0;$j<sizeof($allUsernames);$j++){
            if($allUsernames[$j]['username'] != $uname){
              $others .= "" . $allUsernames[$j]['fullname'] . ",";
            }
          }
          $others = rtrim($others, ',');
          $insertQueryBooking .= "('$fname', '$uname', '$capacity','$room','$date','$time','$endTime','$length','$others'),";
          $insertQueryReminders.= "('$fname', '$uname', '$capacity','$room','$date','$time','0'),";
        }

        $insertQueryBooking = rtrim($insertQueryBooking, ',');
        $insertQueryReminders = rtrim($insertQueryReminders, ',');
        $result = mysqli_query($con,$insertQueryBooking);
        $result2 = mysqli_query($con,$insertQueryReminders);

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
    }else {
      $response["success"] = 0;
      $response["message"] = "Booking space not available (already booked)";
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
