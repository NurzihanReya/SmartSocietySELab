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

if(isset($_GET['b_id'])){
    $b_id=$_GET['b_id'];
    $block_id=mysqli_real_escape_string($conn,$_GET['b_id']);
    $sql="SELECT *
          FROM blocks
          INNER JOIN organizations
          WHERE organizations.b_id = $block_id";  //?
    $result=mysqli_query($conn,$sql);
    $block=mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    //echo $block['o_id'];

    $o_id=$block['o_id'];

    $sql2="SELECT *
        FROM organizations
        INNER JOIN services
        WHERE services.o_id = '$o_id'";
    $result2=mysqli_query($conn,$sql2);
    $block2=mysqli_fetch_assoc($result2);

    $s_id=$block2['s_id'];

    mysqli_free_result($result2);


    $_SESSION['b_id']=$b_id;
    $_SESSION['o_id']=$o_id;
    $_SESSION['s_id']=$s_id;

    if($block2['type']=="Hospital"){
        $_SESSION['typeorg']="Hospital";
        header("Location:view_specific_event1.php");
    }
    elseif($block2['type']=="Police Station"){
        $_SESSION['typeorg']="Police Station";
        header("Location:view_specific_event2.php");
    }
    elseif($block2['type']=="Fire Station"){
        $_SESSION['typeorg']="Fire Station";
        header("Location:view_specific_event3.php");
    }

    // mysqli_close($conn);

}
?>