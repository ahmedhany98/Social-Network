<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'socialnetwork';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$id=$_SESSION['id'];

$result = $con->query("SELECT firstName , lastName , nickName  FROM users  where id = $id ");

$fName=NULL;
$lName=NULL;
$nickname = NULL;
 while ($row = $result->fetch_assoc())
 {
 $fName =$row['firstName'];
 $lName = $row['lastName'];
 $nickname =$row['nickName'];
 }

$fName = strtolower($fName);
$lName = strtolower($lName);
$fName = ucwords($fName);
$lName = ucwords($lName);

$name = $fName." ".$lName; 

if( $nickname != NULL )
{
 $name = $nickname;
}



//get friends only
$foreverAlone = NULL; //variable to know if user has friends or no
$results = $con->query("SELECT id , firstName , lastName , nickName , profilePicture FROM users WHERE id =  ANY (SELECT userB FROM friends WHERE userA = $id )  ORDER BY firstName"); 



if (mysqli_num_rows($results) == 0) { 
$foreverAlone = 1;
}  



$friendRequests = $con->query("SELECT id , firstName , lastName , nickName , profilePicture FROM users WHERE id =  ANY (SELECT userB FROM friends WHERE userA = $id AND status = 0 )  ORDER BY firstName"); 


$requestCount = mysqli_num_rows($friendRequests);

?>


<!DOCTYPE html>
<html>
<head>
  <title>Discover - <?=$name?></title>

  <link href="css/stylesheet.css" rel="stylesheet" type="text/css">
  
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
  

<style>
	
	.pp-pic {
width: 120px;
height: 120px;
}




</style>



</head>
<body class="loggedin" >
	
    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #00a1ff; margin-bottom: 2%;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
   <h3> <a class="navbar-brand" href="/socialnetwork">The Social Network</a> </h3>
    <ul class="navbar-nav  ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="home.php"><i class="fas fa-home"></i> <?=$name?><span class="sr-only">(current)</span></a>
      </li>
        
                        <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>


 <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="far fa-bell"></i>  Notifications
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="friendrequests.php"><i class="fas fa-users"></i> <?=$requestCount?> Friend Request(s)</a>
        </div>
 </li>
        
      <li class="nav-item active">
        <a class="nav-link" href="discover.php">Discover</a>
      </li>
        
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Account
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="editprofile.php"><i class="fas fa-cog"></i> Settings</a>
          
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="signout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>

 <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
      <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
      <button class="btn  my-2 my-sm-0" style="background-color: #00a1ff; border-color: white; color: white;" type="submit"><i class="fa fa-search"></i></button>
    </form>
    </ul>
   
  </div>
</nav>

<div class="container" >
<div class="row text-center" style="display:flex; wrap:wrap;">
  </div>
</div>


<footer style=" display: table;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    margin-top:10%;">
	
  
</footer>
    
    
    
    
    <?php
    $posts = $con->query("SELECT authorId, caption , state , image , time  FROM posts  
    WHERE authorId != $id AND state='public'
    OR authorId IN (SELECT id FROM users WHERE id =  ANY (SELECT userB FROM friends    WHERE userA = $id AND status = 1 )) AND state='private' 
    ORDER BY time DESC");
      
      
       while(($row = $posts->fetch_assoc()) !== null){
    $caption  = $row['caption'];
           $authorid = $row['authorId'];
                      $time = $row['time'];

        $result2 = $con->query("SELECT id, firstName , lastName , nickName , phone, profilePicture , homeTown , maritalStatus , about FROM users  where id = $authorid ");
           
           
$fName=NULL;
$lName=NULL;
$nickname = NULL;
$profilePicture = NULL;
 while ($row = $result2->fetch_assoc())
 {
 $fName =$row['firstName'];
 $lName = $row['lastName'];
 $nickname =$row['nickName'];
$profilePicture = $row['profilePicture'];

 }

 ?>
           
                        <div class="card gedf-card" style="margin-top: 10px;">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="mr-2">
                                            <img class="rounded-circle" width="45" src="profilepictures/<?= $profilepicture ?>" alt="">
                                        </div>
                                        <div class="ml-2">
                                            <div class="h5 m-0">@<?= $nickname ?></div>
                                            <div class="h7 text-muted"><?php echo ($fName . " " . $lName); ?></div>
                                        </div>
                                    </div>
                                    <div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="text-muted h7 mb-2"> <i class="fa fa-clock"></i> <?= $time ?></div>
                             

                                <p class="card-text">
                                    <?= $caption ?>
                                </p>
                            </div>
                        </div>

                    

            <?php }  ?>


</body>
</html>





<?php

$con->close();

?>