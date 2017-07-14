<?php


$response = array();

if (isset($_GET["room"])) {

    $room= $_GET['room'];


    // connecting to db
    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }

    // if room = 0 then get info for all rooms and if room is not equal to 0 then get info for the room corresponding to the roomNumber
    if($room == 0){
      $result = mysqli_query($con,"SELECT roomNumber,capacity,features FROM rooms");
    }
    else{
      $result = mysqli_query($con,"SELECT roomNumber,capacity,features FROM rooms WHERE  roomNumber = '$room'");
    }

    if ((mysqli_num_rows($result) != 0)) {
      if($room == 0){
        $response["success"] = 1;
        $response["details"] = array();
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
          $details = array();
          $details["roomNumber"] = $row["roomNumber"];
          $details["capacity"] = $row["capacity"];
          $details["features"] = $row["features"];
          array_push($response["details"], $details);
        }

      }
      else{
        $result = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $response["roomNumber"] = $result["roomNumber"];
        $response["capacity"] = $result["capacity"];
        $response["features"] = $result["features"];
        $response["success"] = 1;
      }
      echo json_encode($response);
    } else {
        $response["success"] = 0;

        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) are missing";

    echo json_encode($response);
}
?>
