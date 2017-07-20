<?php
// array for JSON response
$response = array();

// check for required fields
if (isset($_GET["username"]) || isset($_GET["booking_date"]) || isset($_GET["booking_time"]) || isset($_GET["room"]) || isset($_GET["all"])){
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
       echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $minDate = date("Y-m-d");

    if (isset($_GET["booking_date"]) && isset($_GET["username"])){
      $username = $_GET["username"];
      $date = $_GET["booking_date"]; // date in the format "YYYY-MM-DD"
      $result = mysqli_query($con,"SELECT * FROM booking WHERE username='$username' AND booking_date='$date' ORDER BY booking_date ASC, booking_time ASC");
    }
    else if(isset($_GET["room"]) && isset($_GET["booking_date"]) && isset($_GET["booking_time"])){
      $room = $_GET["room"];
      $date = $_GET["booking_date"];
      $time = $_GET["booking_time"];
      $time2 = new DateTime($time);
      $getDuration = mysqli_query($con,"SELECT duration FROM booking WHERE booking_date = '$date' AND booking_time ='$time' AND room='$room'");
      $data = mysqli_fetch_array($getDuration,MYSQLI_ASSOC);
      $duration = $data['duration'];
      if($duration == 30){
        $time2 ->add(new DateInterval('PT30M'));
      }
      else if($duration == 60){
        $time2 ->add(new DateInterval('PT1H'));
      }
      else if($duration == 90){
        $time2 ->add(new DateInterval('PT1H30M'));
      }
      $endTime =  $time2->format('H:i:s');
      if($date < $minDate){
        $response["success"] = 2;
        $response["message"] = "Not allowed";
        echo json_encode($response);
        return;
      }
      else{
          $rm =  array('1','2','3','4');
          if($duration == 30){
            $res = mysqli_query($con,"SELECT room FROM booking WHERE booking_date='$date' AND (booking_time='$time' OR booking_time_end='$endTime')");
          }
          else if($duration == 60){
            $time3 = new DateTime($time);
            $time3 ->add(new DateInterval('PT30M'));
            $queryTime = $time3->format('H:i:s');
            $res = mysqli_query($con,"SELECT room FROM booking WHERE booking_date='$date' AND (booking_time='$time' OR booking_time='$queryTime' OR booking_time_end='$queryTime' OR booking_time_end='$endTime')");
          }
          else if($duration == 90){
            $time3 = new DateTime($time);
            $time3 ->add(new DateInterval('PT30M'));
            $queryTime = $time3->format('H:i:s');
            $time3 ->add(new DateInterval('PT30M'));
            $queryTime2 = $time3->format('H:i:s');
            $res = mysqli_query($con,"SELECT room FROM booking WHERE booking_date='$date' AND (booking_time='$time' OR booking_time='$queryTime' OR booking_time='$queryTime2' OR booking_time_end='$queryTime' OR booking_time_end='$queryTime2' OR booking_time_end='$endTime')");
          }
          else {
            $response["success"] = 2;
            $response["rooms"] = $rm;
            echo json_encode($response);
            return;
          }
          $response["rooms"] = array();
          if ((mysqli_num_rows($res) != 0)) {
            while($row = mysqli_fetch_array($res,MYSQLI_ASSOC)){
              if(($key = array_search($row["room"], $rm)) !== false) {
                  unset($rm[$key]);
              }
            }
            $response["success"] = 1;
            $response["rooms"] = $rm;
            echo json_encode($response);
            return;
          }
          else{
            $response["success"] = 2;
            $response["rooms"] = $rm;
            echo json_encode($response);
            return;
          }

      }
    }
    else if(isset($_GET["room"]) && isset($_GET["booking_date"])){
      $room = $_GET["room"];
      $date = $_GET["booking_date"];
      if($date < $minDate){
        $response["success"] = 2;
        $response["message"] = "Not allowed";
        echo json_encode($response);
        return;
      }
      else{
        if($room == 0){
          $res = mysqli_query($con,"SELECT booking_time,booking_time_end,duration,room FROM booking WHERE booking_date = '$date' ORDER BY booking_time ASC");
          $response["success"] = 2;
          $response["room1"] = array();
          $response["room2"] = array();
          $response["room3"] = array();
          $response["room4"] = array();
          while($row = mysqli_fetch_array($res,MYSQLI_ASSOC)){
            $datetime1 = new DateTime($row['booking_time']);
            if($row['room'] == 1){
              if($row['duration'] == 30){
                array_push($response["room1"], $datetime1->format('H:i:s'));
              }
              else if($row['duration'] == 60){
                array_push($response["room1"], $datetime1->format('H:i:s'));
                array_push($response["room1"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
              else if($row['duration'] == 90){
                array_push($response["room1"], $datetime1->format('H:i:s'));
                array_push($response["room1"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
                array_push($response["room1"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
            }
            else if($row['room'] == 2){
              if($row['duration'] == 30){
                array_push($response["room2"], $datetime1->format('H:i:s'));
              }
              else if($row['duration'] == 60){
                array_push($response["room2"], $datetime1->format('H:i:s'));
                array_push($response["room2"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
              else if($row['duration'] == 90){
                array_push($response["room2"], $datetime1->format('H:i:s'));
                array_push($response["room2"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
                array_push($response["room2"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
            }
            else if($row['room'] == 3){
              if($row['duration'] == 30){
                array_push($response["room3"], $datetime1->format('H:i:s'));
              }
              else if($row['duration'] == 60){
                array_push($response["room3"], $datetime1->format('H:i:s'));
                array_push($response["room3"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
              else if($row['duration'] == 90){
                array_push($response["room3"], $datetime1->format('H:i:s'));
                array_push($response["room3"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
                array_push($response["room3"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
            }
            else if($row['room'] == 4){
              if($row['duration'] == 30){
                array_push($response["room4"], $datetime1->format('H:i:s'));
              }
              else if($row['duration'] == 60){
                array_push($response["room4"], $datetime1->format('H:i:s'));
                array_push($response["room4"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
              else if($row['duration'] == 90){
                array_push($response["room4"], $datetime1->format('H:i:s'));
                array_push($response["room4"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
                array_push($response["room4"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
              }
            }
          }
          echo json_encode($response);
          return;
        }
        else{
          $result = mysqli_query($con,"SELECT * FROM booking WHERE room='$room' AND booking_date='$date' ORDER BY booking_time ASC");
        }
      }
    }
    else if(isset($_GET["username"])){
      $time = new DateTime("now", new DateTimeZone("Africa/Nairobi"));
      $minTime = $time->format('H:i:s');
      $username = $_GET["username"];
      $result = mysqli_query($con,"SELECT * FROM booking WHERE username='$username' AND (booking_date > '$minDate' OR (booking_date = '$minDate' AND booking_time >= '$minTime')) ORDER BY booking_date ASC, booking_time ASC");
    }
    else if(isset($_GET["all"])){
      $all = $_GET["all"];
      if($all == 1){
          $result = mysqli_query($con,"SELECT * FROM booking WHERE booking_date >= '$minDate' ORDER BY booking_date ASC, booking_time ASC");
      }
      else{
        $result = mysqli_query($con,"SELECT * FROM booking ORDER BY booking_date ASC, booking_time ASC");
      }
    }
    else if(isset($_GET["booking_date"])){
      $date = $_GET["booking_date"]; // date in the format "YYYY-MM-DD"
      $result = mysqli_query($con,"SELECT * FROM booking WHERE booking_date='$date' ORDER BY booking_date ASC, booking_time ASC");
    }

    if ((mysqli_num_rows($result) != 0)) {
      $response["success"] = 1;
      $response["bookings"] = array();
      while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $booking = array();
        $booking["fullname"] = $row["fullname"];
        $booking["username"] = $row["username"];
        $booking["capacity"] = $row["capacity"];
        $booking["room"] = $row["room"];
        $booking["booking_date"] = $row["booking_date"];
        $booking["booking_start"] = $row["booking_time"];
        $booking["booking_end"] = $row["booking_time_end"];
        $booking["duration"] = $row["duration"];
        $booking["others"] = $row["others"];

        $datetime1 = new DateTime($row['booking_time']);
        $booking["all_bookings"] = array();
        if($row['duration'] == 30){
          array_push($booking["all_bookings"], $datetime1->format('H:i:s'));
        }
        else if($row['duration'] == 60){
          array_push($booking["all_bookings"], $datetime1->format('H:i:s'));
          array_push($booking["all_bookings"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
        }
        else if($row['duration'] == 90){
          array_push($booking["all_bookings"], $datetime1->format('H:i:s'));
          array_push($booking["all_bookings"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
          array_push($booking["all_bookings"], $datetime1->add(new DateInterval('PT30M'))->format('H:i:s'));
        }
        array_push($response["bookings"], $booking);
      }

      echo json_encode($response);
    } else {
        $response["success"] = 1;
        $response["bookings"] = "[]";
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
