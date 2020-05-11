<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'socialnetwork';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}


if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}



$userID = $_SESSION['id']; 
$friendID = $_GET['id'];


if ($stmt = $con->prepare('REPLACE INTO friends
SET userA = ?,
userB = ?,
status = 1') ) {
	

	$stmt->bind_param('ii', $userID ,$friendID);
	$stmt->execute();
}



if ($stmt = $con->prepare('REPLACE INTO friends
SET userA = ?,
userB = ?,
status = 1') ) {
	

	$stmt->bind_param('ii', $friendID, $userID);
	$stmt->execute();
}


header("Location: userprofile.php?id=".$friendID);

?>