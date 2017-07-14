<?php


$response = array();


if (isset($_GET['username']) && isset($_GET['user_password'])) {

    $username = $_GET['username'];
    $password= $_GET['user_password'];


    $con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

    if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }

    $result = mysqli_query($con,"SELECT fullname,username,role FROM login WHERE  username = '$username' AND user_password = '$password' LIMIT 1");


    if ((mysqli_num_rows($result) != 0)) {
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $response["success"] = 1;
        $response["username"] = $row["username"];
        $response["fullname"] = $row["fullname"];
        $response["role"] = $row["role"];

        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Username or password is wrong";

        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Required field(s) are missing";

    echo json_encode($response);
}
?>
