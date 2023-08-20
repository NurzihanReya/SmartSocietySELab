<?php

include('db_connect.php');

session_start();

if(isset($_SESSION['username'])){
  $username = mysqli_real_escape_string($conn, $_SESSION['username']);
  $type = mysqli_real_escape_string($conn, $_SESSION['type']);

  if($type=="user"){
    $sql = "SELECT * FROM users WHERE name='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
  } else {
    $sql = "SELECT * FROM organizations WHERE name='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
  }

  $sql = "SELECT *
          FROM blocks";
  $result = mysqli_query($conn, $sql);
  $blocks = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // $sql = "SELECT *
  //         FROM notices
  //         ORDER BY post_date DESC LIMIT 3";
  // $result = mysqli_query($conn, $sql);
  // $notices=mysqli_fetch_all($result,MYSQLI_ASSOC);

  if (isset($_POST['createeventbutton'])) {
    header("Location: createevent.php");
  }

  if (isset($_POST['createnoticebutton'])) {
    header("Location: createnotice.php");
  }
  
  $_SESSION['username'] = $username;
  $_SESSION['type'] = $type;
  // mysqli_free_result($result);

}
else{
  header("HTTP/1.0 404 Not Found");
  echo "<h1>404 Not Found</h1>";
  echo "The page that you have requested could not be found.";
  exit();
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
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>SS - Homepage</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!--

TemplateMo 546 Sixteen Clothing

https://templatemo.com/tm-546-sixteen-clothing

-->

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-sixteen.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/flex-slider.css">
    <link href="navhome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/fontawesome/css/fontawesome.min.css" >
  </head>

  <body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    <!-- Header -->
    <header class="">
      
         
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark" style="width:1500px">
	<a href="#" class="navbar-brand"><i class="fa fa-cube"></i>Smart<b>Society</b></a>  		
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
      <input type="text" name="searchtext" id="search" placeholder="Search" style="position:relative; width: 200px; padding:6px; border-radius:5px;">
      <!-- <div class="col col-lg-6"> -->
      <!-- <label for="exampleFormControlInput1" class="form-label">Choose From Below</label> -->
        <!-- <select class="form-select" name="searchtype" aria-label="Default select example" style="position:relative; height:40px; width: 150px">
          <option selected>Filter</option>
          <option value="Students">Students</option>
          <option value="Verifiers">Verifiers</option>
          <option value="Achievements">Achievements</option>
          <option value="Events">Events</option>
          <option value="Notices">Notices</option>
        </select> -->
      <!-- </div> -->
        <!-- <button name="submitfiltersearch" class="btn">Search</button> -->
        <button name="submitsearch" class="btn" style="position:relative; background-color:#FFF; ">Search</button>
      </div>
    </form>
		<div class="navbar-nav ml-auto" style="position:relative; left:-50px">
		<?php
      if($type=="user"){
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

			<a href="view_all_events.php" class="nav-item nav-link"><i class="fa fa-briefcase"></i><span>Blocks</span></a>
			<!-- <a href="view_all_notices.php" class="nav-item nav-link"><i class="fa fa-envelope"></i><span>Notices</span></a>		 -->
			<a href="notification.php" class="nav-item nav-link"><i class="fa fa-bell"></i><span>Notifications</span></a>
      <a href="logout.php" class="nav-item nav-link"><i class="fa-solid fa-right-from-bracket"></i><span>Log Out</span></a>
			<?php
        if ($type=="user") {
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
      
       
        
                 
                
          
        

    </header>

    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <!-- <div class="banner header-text">
      <div class="owl-banner owl-carousel">
      <div class="banner-item-01">
        
        <div class="text-content">
        
          <h2>UIU Student Activity Tracker</h2>
        </div>
      </div>
      <div class="banner-item-02">
        <div class="text-content">
        <h2>UIU Student Activity Tracker</h2>
        </div>
      </div>
      <div class="banner-item-03">
        <div class="text-content">
          
        <h2>UIU Student Activity Tracker</h2>
        </div>
        </div>
      </div>
    </div> -->
    <!-- Banner Ends Here -->
    <div class="team-members">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Blocks</h2>
              <a href="view_all_events.php">View All Blocks<i class="fa fa-angle-right"></i></a>
            </div>
          </div>
          <?php foreach($blocks as $block){ ?>
          <div class="col-md-4">
            <div class="team-member">
              <div class="thumb-container">
                <img src="./block_images/<?php echo $block['image']; ?>" alt=""style="width:350px;height: 250px;border-radius: 10px; border: 3px solid #f08c09; padding: 3px;">
                <div class="hover-effect"style="border-radius: 10px; width:346px;height: 246px;">
                  <div class="hover-content">
                    
                    <div class="para">
                      <h4><p><?php //echo $event['event_date'] ?><br></br>
                            <?php //echo $event['event_time'] ?><br></br>
                            <?php //echo $event['location'] ?>
                          </p>
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="down-content">
              <a href="servicemiddleman.php?b_id=<?php echo $block['b_id'] ?>"><?php echo htmlspecialchars($block['b_id']) ?></a> 
              </div>
            </div>
          </div>
          <?php } ?>

        </div>
        <form action="" method="POST">
        <div class="input-group" style="float: left;">
            <button name="createeventbutton" class="btn1" >Create Block</button>
           <!---style="color: grey; width: 70px" <a style="color: grey; width: 70px" name="submit1" href="edit_profile.php" > Edit Profile</a> -->
        </div>
        </div>
         
        <!-- <div class="latest-products">
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <div class="section-heading">
                    <h2>Latest Notices</h2>
                    <a href="view_all_notices.php">View All Notices<i class="fa fa-angle-right"></i></a>
                  </div>
                </div>
                <?php //foreach($notices as $notice){ ?>
                <div class="col-md-4">
                  <div class="product-item">
                    
                    <div class="down-content"style="width:350px;height: 200px;">
                    <a href="view_specific_notice.php?n_id=<?php //echo $notice['n_id'] ?>"><?php //echo htmlspecialchars($notice['name']) ?></a>
                     
                      <p><?php  //if(strlen($notice['content'])>100){
                                  //$string = substr($notice['content'],0,100);
                                  //echo $string.("...");
                                //}
                                //else{
                                  //echo $notice['content'];
                                //}
                          ?></p>
                      
                    </div>
                  </div>
                </div>
                <?php //} ?> -->
        <!-- </div>
        <form action="" method="POST">
        <div class="input-group" style="float: left;">
            <button name="createnoticebutton" class="btn2" >Create Notice</button>
           
        </div>
          </div> -->
          
    <div class="best-features">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-heading">
              <h2>Our Main Goals:</h2>
            </div>
          </div>
          <div class="services">
            <div class="container">
              <div class="row">
                <div class="col-md-4">
                  <div class="service-item">
                    <div class="icon">
                      <i class="fa fa-gear"></i>
                    </div>
                    <div class="down-content">
                      <h4>Explore The Map</h4>
                      <p>Explore what's going on in your community.</p>
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="service-item">
                    <div class="icon">
                      <i class="fa fa-gear"></i>
                    </div>
                    <div class="down-content">
                      <h4>Organizations Around You</h4>
                      <p>Visit and avail services from the organizations.</p>
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="service-item">
                    <div class="icon">
                      <i class="fa fa-gear"></i>
                    </div>
                    <div class="down-content">
                      <h4>Report Emergencies</h4>
                      <p>Report fires, crimes and accidents.</p>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>


    
      </div>
    </div>

    <div class="footer-dark">
        <!-- <footer>
            <div class="container">
            <div class="row">
                    <div class="col-sm-6 col-md-3 item">
                        <h3>UIUSAT</h3>
                        <ul>
                            <li><a href="#">Add achievement</a></li>
                            <li><a href="#">Verify</a></li>
                            <li><a href="#">CV recomendation</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3 item">
                        <h3>Team Member</h3>
                        <ul>
                            <li><a href="#">Layla Kader</a></li>
                            <li><a href="#">Abtahi</a></li>
                            <li><a href="#">Shawpnil</a></li>
                            <li><a href="#">Khaled</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 item text">
                        <h3>UIU Activity Tracker</h3>
                        
                    </div>
                    
                </div>
                <p class="copyright">Company Name © 2018</p>
            </div>
        </footer> -->


        <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-3 item">
                        <h3>Smart Society</h3>
                        <ul>
                            <li>Software Engineering Lab</li>
                            <li>Section B</li>

                        </ul>

                    </div>
                    <div class="col-sm-12 col-md-3  item">
                        <h3>Team Member</h3>
                        <ul>
                            <li><a href="#">Abtahi Ahmed</a></li>
                            <li><a href="#">Wasi Uddin</a></li>
                            <li><a href="#">Nurzihan Fatema</a></li>
                            <li><a href="#">Mysun Mashira</a></li>
                        </ul>
                    </div>

                    <!-- <div class="col item social"><a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a><a href="#"><i class="icon ion-social-snapchat"></i></a><a href="#"><i class="icon ion-social-instagram"></i></a></div> -->
                </div>
                <p class="copyright">UIU CSE © 2023</p>
            </div>

            


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <!-- Additional Scripts -->
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/owl.js"></script>
    <script src="assets/js/slick.js"></script>
    <script src="assets/js/isotope.js"></script>
    <script src="assets/js/accordions.js"></script>


    <script language = "text/Javascript"> 
      cleared[0] = cleared[1] = cleared[2] = 0; //set a cleared flag for each field
      function clearField(t){                   //declaring the array outside of the
      if(! cleared[t.id]){                      // function makes it static and global
          cleared[t.id] = 1;  // you could use true and false, but that's more typing
          t.value='';         // with more chance of typos
          t.style.color='#fff';
          }
      }
    </script>


  </body>

</html>
