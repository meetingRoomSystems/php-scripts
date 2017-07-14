<?php

$response = array();

if (isset($_GET['fullname']) && isset($_GET['username']) && isset($_GET['user_password'])) {

    $name = $_GET['fullname'];
    $username = $_GET['username'];
    $password= $_GET['user_password'];


    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }


    $check = mysqli_query($con,"SELECT username FROM login WHERE  username = '$username' LIMIT 1");


    if (mysqli_num_rows($check) == 0) {
      $result = mysqli_query($con,"INSERT INTO login (fullname, username, user_password,role) VALUES('$name', '$username', '$password','user')");
      if ($result) {
          $response["success"] = 1;
          $response["username"] = $username;
          $response["fullname"] = $name;

          echo json_encode($response);
      } else {
          $response["success"] = 0;
          $response["message"] = "Unable to register rignt now";

          echo json_encode($response);
      }
    }
    else {
      $response["success"] = 2;
      $response["message"] = "Username already exists. Choose another username";

      echo json_encode($response);

    }


} else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);
}
?>
