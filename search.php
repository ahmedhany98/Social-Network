<?php

session_start();
header('Cache-Control: no cache');
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


if (!isset($_POST['search']) || $_POST['search']=="") {
  header('Location: discover.php');
  exit();
}



$searchWord = $_POST['search'];






$found = 1;

//search query




$results = $con->query("SELECT id , firstName , lastName , nickName , profilePicture, phone, email, homeTown FROM users 
WHERE (
firstName LIKE '%{$searchWord }%' 
OR lastName LIKE '%{$searchWord }%' 
OR nickName LIKE '%{$searchWord }%' 
OR phone LIKE '%{$searchWord }%' 
OR email LIKE '%{$searchWord }%' 
OR homeTown LIKE '%{$searchWord }%' 
) 
AND id <> $id ORDER BY firstName"    ); 




if (mysqli_num_rows($results) == 0) { 
  $found = 0;
  }  
  

  $friendRequests = $con->query("SELECT id , firstName , lastName , nickName , profilePicture FROM users WHERE id =  ANY (SELECT userB FROM friends WHERE userA = $id AND status = 0 )  ORDER BY firstName"); 


$requestCount = mysqli_num_rows($friendRequests);








?>


<!DOCTYPE html>
<html>
<head>
  <title>Search</title>

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
                    <a class="nav-link" href="homepage.php">Home</a>
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

<?php 
// get the friend's attributes id , firstName , lastName , nickName , profilePicture


if($found == 0)
{  
  ?>


  <div class="container" style="margin : 0 auto;" >
  <h1>Nothing Found :(</h1>
  <div style = "display: block; margin: 5% auto;"><img src="images/sad.png"></div>
  <div class="container">
    <form class="form-inline my-2 my-lg-0" method="POST" action="search.php">
      <input class="form-control mr-sm-2" type="search" name="search" placeholder="Try Again" aria-label="Search" style="width:90%;">
      <button class="btn  my-2 my-sm-0" style="background-color: #00a1ff; border-color: white; color: white;" type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
  </div>
<?php
}

?>



  



<!-- <% campgrounds.forEach(function(campground){ %> -->



<?php 
// get the friend's attributes id , firstName , lastName , nickName , profilePicture


while ($row = $results->fetch_assoc() )
 {


 $firstName = NULL;
 $lastName = NULL;
 $nickName = NULL;




$friendID = $row['id'];
 $firstName =$row['firstName'];
 $lastName = $row['lastName'];
 $nickName =$row['nickName'];
  $friendpp =$row['profilePicture'];
 

$firstName = strtolower($firstName);
$lastName = strtolower($lastName);
$firstName = ucwords($firstName);
$lastName = ucwords($lastName);

$friendName = $firstName." ".$lastName; 

if( $nickName != NULL )
{
 $friendName = $nickName;
}

 ?>
    
  <div class="col-md-3 col-sm-6 " style="margin-top:5px; margin-bottom: 5px; ">
    <div class="img-thumbnail">
        <a href="userprofile.php?id=<?=$friendID?>"> <img src="profilepictures/<?=$friendpp?>" style="  border-radius: 10%; padding-top: 4px; padding-bottom: 4px; margin-bottom: 4px;" class="pp-pic" n> </a>
         <div class="caption">
           <a  href="userprofile.php?id=<?=$friendID?>" >   <h6><?=$friendName?></h6> </a>
         </div>
         <p>
           <a href="userprofile.php?id=<?=$friendID?>" class="btn btn-sm btn-primary" style="background-color: #00a1ff; border-color: #00a1ff; ">View Profile</a>
         </p>
    </div>
   
     
  
  </div>  
    
<?php } ?>


  </div>

</div>


   

<footer style=" display: table;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    margin-top:10%;">
	
  
</footer>

</body>
</html>


<?php

$con->close();

?>