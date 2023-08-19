<?php

include('db_connect.php');

session_start();

if(isset($_SESSION['username'])){
    $username=mysqli_real_escape_string($conn,$_SESSION['username']);
  
    $_SESSION['username'] = $username;

}
else{
  header("HTTP/1.0 404 Not Found");
  echo "<h1>404 Not Found</h1>";
  echo "The page that you have requested could not be found.";
  exit();
}

if(isset($_SESSION['searchtype'])){
    $searchtype=mysqli_real_escape_string($conn,$_SESSION['searchtype']);
    if(isset($_SESSION['searchtext'])){
      $searchtext=mysqli_real_escape_string($conn,$_SESSION['searchtext']);
    }
    if($searchtype=="students"){
        $sql = "SELECT *
                FROM student
                WHERE name LIKE '%$searchtext%'
                OR s_id LIKE '%$searchtext%'";
        $result = mysqli_query($conn, $sql);
        $searchresults=mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    else if($searchtype=="verifiers"){
        $sql = "SELECT *
                FROM verifier
                WHERE name LIKE '%$searchtext%'
                OR v_id LIKE '%$searchtext%'";
        $result = mysqli_query($conn, $sql);
        $searchresults=mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    else if($searchtype=="achievements"){
        $sql = "SELECT *
                FROM achievements
                WHERE name LIKE '%$searchtext%'
                OR keywords LIKE '%$searchtext%'";
        $result = mysqli_query($conn, $sql);
        $searchresults=mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    else if($searchtype=="eventstudents"){
        $sql = "SELECT *
                FROM student
                WHERE s_id IN
                            (SELECT participates.s_id
                              FROM participates
                              INNER JOIN events
                              ON participates.e_id=events.e_id
                              WHERE events.e_id='$searchtext')";
        $result = mysqli_query($conn, $sql);
        $searchresults=mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
}

if(isset($_POST['submitsearch'])){
  if(!empty($_POST['searchtext'])){
    $searchtype = filter_input(INPUT_POST, 'searchtype', FILTER_SANITIZE_STRING);
    $searchtext=mysqli_real_escape_string($conn,$_POST['searchtext']);
    if($searchtype=="Students"){
      $sql = "SELECT *
              FROM student
              WHERE name LIKE '%$searchtext%'
                OR s_id LIKE '%$searchtext%'";
      $result = mysqli_query($conn, $sql);
      if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['searchtext'] = $_POST['searchtext'];
        $_SESSION['searchtype'] = "students";
        header("Location:view_all_profiles.php");
      }
      else {
        echo "<script>alert('Sorry. We do not have that information in our database.')</script>";
      }
    }
    else if($searchtype=="Verifiers"){
      $sql = "SELECT *
              FROM verifier
              WHERE name LIKE '%$searchtext%'
                OR v_id LIKE '%$searchtext%'";
      $result = mysqli_query($conn, $sql);
      echo ("Happy1");
      if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['searchtext'] = $_POST['searchtext'];
        $_SESSION['searchtype'] = "verifiers";
        echo "Happy2";
        header("Location:view_all_profiles.php");
      }
      else {
        echo "<script>alert('Sorry. We do not have that information in our database.')</script>";
      }
    }
    else if($searchtype=="Achievements"){
      $sql = "SELECT *
              FROM achievements
              WHERE name LIKE '%$searchtext%'
                OR keywords LIKE '%$searchtext%'";
      $result = mysqli_query($conn, $sql);
      if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['searchtext'] = $_POST['searchtext'];
        $_SESSION['searchtype'] = "achievements";
        header("Location: view_all_profiles.php");
      }
      else {
        echo "<script>alert('Sorry. We do not have that information in our database.')</script>";
      }
    }
    else if($searchtype=="Events"){
      $sql = "SELECT *
              FROM events
              WHERE name LIKE '%$searchtext%'
                OR summary LIKE '%$searchtext%'
                OR keywords LIKE '%$searchtext%'";
      $result = mysqli_query($conn, $sql);
      if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['searchtext'] = $_POST['searchtext'];
        $_SESSION['searchtype'] = "events";
        header("Location:view_all_events.php");
      }
      else {
        echo "<script>alert('Sorry. We do not have that information in our database.')</script>";
      }
    }
    else if($searchtype=="Notices"){
      $sql = "SELECT *
              FROM notices
              WHERE name LIKE '%$searchtext%'
                OR content LIKE '%$searchtext%'
                OR keywords LIKE '%$searchtext%'";
      $result = mysqli_query($conn, $sql);
      if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['searchtext'] = $_POST['searchtext'];
        $_SESSION['searchtype'] = "notices";
        header("Location: view_all_notices.php");
      }
      else {
        echo "<script>alert('Sorry. We do not have that information in our database.')</script>";
      }
    }
    else{
      echo "<script>alert('Please choose an option to search.')</script>";
    }
  }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
    <?php
      if(isset($_SESSION['searchtype'])){
      ?>
      UIUSAT - Searched Profiles
      <?php
      }
    ?>
    </title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="crossorigin"/>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap" media="print" onload="this.media='all'"/>
    <noscript>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap"/>
    </noscript>
    <link href="css/font-awesome/css/all.min.css?ver=1.2.1" rel="stylesheet">
    <link href="css/mdb.min.css?ver=1.2.1" rel="stylesheet">
    <link href="css/aos.css?ver=1.2.1" rel="stylesheet">
    <link href="css/main.css?ver=1.2.1" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="nav.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome/css/fontawesome.min.css" >
    <noscript>
      <style type="text/css">
        [data-aos] {
            opacity: 1 !important;
            transform: translate(0) scale(1) !important;
        }
      </style>
    </noscript>
  </head>
  <body > 
  
  
    <header class="d-print-none">
      
      <div class="container text-center text-lg-left">
       
          <div class="site-nav"> 
         <!-- Nav bar Start -->
            <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
	<a href="#" class="navbar-brand"><i class="fa fa-cube"></i>UIU<b>SAT</b></a>  		
	<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
		<span class="navbar-toggler-icon"></span>
	</button>
	<!-- Collection of nav links, forms, and other content for toggling -->
	<div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">		
		<!-- <form class="navbar-form form-inline">
			<div class="input-group search-box" style="position:relative; left:-10px">								
				<input type="text" id="search" class="form-control" placeholder="Search here...">
				<span class="input-group-addon"><i class="material-icons"></i></span>
			</div>
		</form> -->
    <form method="POST">
      <div class="search">
      <input type="text" name="searchtext" id="search" placeholder="Search" style="position:relative; top: 40px; width: 200px; left:-50px; padding:6px; border-radius:5px;">
      <!-- <div class="col col-lg-6"> -->
      <!-- <label for="exampleFormControlInput1" class="form-label">Choose From Below</label> -->
        <select class="form-select" name="searchtype" aria-label="Default select example" style="position:relative; left: 170px; width: 150px">
          <option selected>Filter</option>
          <option value="Students">Students</option>
          <option value="Verifiers">Verifiers</option>
          <option value="Achievements">Achievements</option>
          <option value="Events">Events</option>
          <option value="Notices">Notices</option>
        </select>
      <!-- </div> -->
        <!-- <button name="submitfiltersearch" class="btn">Search</button> -->
        <button name="submitsearch" class="btn" style="position:relative; top: -40px; left: 280px; background-color:#FFF; ">Search</button>
      </div>
    </form>
		<div class="navbar-nav ml-auto" style="position:relative; left:280px">
    <?php
      if(strlen($username)==9 && is_numeric($username)){
      ?>
        <a href="studenthome.php" class="nav-item nav-link active"><i class="fa fa-home"></i><span>Home</span></a>
      <?php
      }
      else{
      ?>
        <a href="teacherhome.php" class="nav-item nav-link active"><i class="fa fa-home"></i><span>Home</span></a>
      <?php
      }
    ?>
	
			<a href="profile.php" class="nav-item nav-link"><i class="fa fa-users"></i><span>Profile</span></a>

			<a href="view_all_events.php" class="nav-item nav-link"><i class="fa fa-briefcase"></i><span>Events</span></a>
			<a href="view_all_notices.php" class="nav-item nav-link"><i class="fa fa-envelope"></i><span>Notices</span></a>		
			<a href="notification.php" class="nav-item nav-link"><i class="fa fa-bell"></i><span>Notifications</span></a>
      <a href="logout.php" class="nav-item nav-link"><i class="fa-solid fa-right-from-bracket"></i><span>Log Out</span></a>
			<?php
        if (strlen($username) == 9 && is_numeric($username)) {
        ?>
          <div class="nav-item dropdown">
            <a href="profile.php" data-toggle="dropdown" class="nav-item nav-link dropdown-toggle user-action"><img src="images/student.jpg" class="avatar" alt="Avatar"> Student </a>
          </div>
        <?php
        }
        else {
        ?>
          <div class="nav-item dropdown">
            <a href="profile.php" data-toggle="dropdown" class="nav-item nav-link dropdown-toggle user-action"><img src="images/verifier.jpg" class="avatar" alt="Avatar"> Verifier </a>
          </div>
        <?php
        }
        ?>
	</div>
</nav>
<!-- Nav bar end -->
          </div>
        </div>
      </div>
    </header>
    <div class="page-content"style="position: relative;
  top: 30px; background-color:#7c7583;height:2000px;">
      <div class="container">
        
<div class="resume-container">
  
  </div>
  <div class="shadow-1-strong bg-white my-5 p-5" id="about">
    <div class="about-section"style="position: relative;
   background-color:#fff;height:1500px;">
      <div class="row">
        
  </div>
  
    <h1 class="h2 fw-light mb-4"style="color:#a06b08;position:relative;left:400px;"><b>
    <?php
      if(isset($_SESSION['searchtype'])){
      ?>
      Searched Profiles
      <?php
      }
    ?>
    </b></h1>
    
    
     <div class="shadow-1-strong bg-white my-5 p-5" id="education">
      <div class="education-section"style="position: relative;top: 30px; background-color:#fff;height:1200px;">
      <!-- Here is the div that u need  -->
      <?php foreach($searchresults as $searchresult){ ?>
      <div class="notific">
      <div class="note1" style="height: 150px;
   width: 900px;
   background-color:#d18c25;
   border-radius: 0px;
   border-bottom: 5px solid #8143a0;
   border-right: 5px solid #8143a0;
   color: #fff;
   padding-bottom:15px ;
   position: relative;left:7px; "> 
   
   <div class="textbox"style="position:relative;left:50px;top:40px"><h5><?php echo htmlspecialchars($searchresult['name']) ?></h5></div>

 <div class="textdetail"style="position:relative;left:50px;top:40px">
    <?php
        if($searchtype=="students" || $searchtype=="achievements" || $searchtype=="eventstudents"){
        ?>
        <a href="viewprofile.php?s_id=<?php
        echo $searchresult['s_id'] ?>"><?php echo ("Click To View Details") ?></a></div>
        <?php
        }
        else if($searchtype=="verifiers"){
            ?>
            <a href="viewprofile.php?v_id=<?php
            echo $searchresult['v_id'] ?>"><?php echo ("Click To View Details") ?></a></div>
            <?php
        }
        else if($searchresult=="achievements"){
          ?>
          <a href="view_specific_achievement.php?a_id=<?php
          echo $searchresult['a_id'] ?>"><?php echo ("Click To View Details") ?></a></div>
          <?php
        }
    ?>
 
 
 </div>

<!-- End here -->

 
 </div>
 <br></br>

 <?php } ?>
 

      </div>
         
          
        </div>
      </div>
    </div>
  
 
  </div>
  
    </footer>
    <script src="scripts/mdb.min.js?ver=1.2.1"></script>
    <script src="scripts/aos.js?ver=1.2.1"></script>
    <script src="scripts/main.js?ver=1.2.1"></script>
  </body>
</html>