<?php

$response = array();

$con = mysqli_connect("localhost", "id2172274_compulynxmeetingroom", "Compulynx123","id2172274_booking_system");

if (mysqli_connect_errno($con)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$result = mysqli_query($con,"SELECT username,count(*) FROM booking Group by username");
$response['results'] = array();
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
  $res = array();
  $res['username'] = $row['username'];
  $res['count']  = $row['count(*)'];
  array_push($response["results"],$res );
}
echo json_encode($response);
?>
