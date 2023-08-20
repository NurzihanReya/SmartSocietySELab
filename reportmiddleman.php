<?php

include('db_connect.php');

session_start();

if(isset($_SESSION['username'])){
  $username=mysqli_real_escape_string($conn,$_SESSION['username']);
  $type = $_SESSION['type'];

  if($type=="user"){
    $sql = "SELECT * FROM users WHERE name='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
  }
  else{
    $sql = "SELECT * FROM organizations WHERE name='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
  }
  
  $_SESSION['username'] = $username;
  $_SESSION['type'] = $type;
  // mysqli_free_result($result);
  // mysqli_close($conn);

}
else{
  header("HTTP/1.0 404 Not Found");
  echo "<h1>404 Not Found</h1>";
  echo "The page that you have requested could not be found.";
  exit();
}

if(isset($_SESSION['reportblock'])){
    $sostype=$_POST['sosreport'];
    $sql="INSERT INTO reports2 (type, r_time, flag, u_id, b_id)
          VALUES ('$sostype', now(), 1, $user[u_id], $_SESSION[reportblock])";
    $result=mysqli_query($conn,$sql);
    $report=mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    header("Location: profile.php");
}
?>