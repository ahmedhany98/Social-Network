<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'socialnetwork';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}


if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}



    $userId = $_SESSION['id']; 
    $authorId = $_POST['authorId'];
    $caption = $_POST['caption'];
    $state = $_POST['state'];
    if($_POST['image']==''){
        $image = NULL;
    }
    else{
        $image = $_POST['image'];
    }


    $sql = "INSERT INTO posts (authorId, caption, state, image)
        VALUES ('$authorId','$caption','$state','$image')";
        if(mysqli_query($con, $sql)){
            echo "Records inserted successfully.";
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
        }

    header('Location: home.php');
?>