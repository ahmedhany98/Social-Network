<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'socialnetwork';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}


if (!isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['birthdate'], $_POST['gender'])) {
	// Could not get the data that should have been sent.
	die ('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password1'])|| empty($_POST['password2'])|| empty($_POST['birthdate'])|| empty($_POST['gender'])) {
	// One or more values are empty.
	die ('Please complete the registration form');
}

if ($_POST['password1'] != $_POST['password2']) {
	die ('Passwords not matching !');
}


if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	die ('Email is not valid!');
}


if (strlen($_POST['password1']) > 30 || strlen($_POST['password2']) < 8) {
	die ('Password must be between 8 and 30 characters long!');
}

if ($stmt = $con->prepare('SELECT id, password FROM users WHERE email = ?') ) {
	// Bind parameters (s = string, i = int), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();


	
	if ($stmt->num_rows > 0) {

		echo '<script>';
echo 'alert("E-mail exists !\nenter another one");';
echo 'location.href="index.php"';
echo '</script>';;
	}

	
	 else {
if ($stmt = $con->prepare('INSERT INTO users (firstName, lastName, password , email , birthdate , gender) VALUES (?, ?, ?, ?, ?, ?)')) {
	$password = password_hash($_POST['password1'], PASSWORD_DEFAULT);


	$stmt->bind_param('ssssss', $_POST['firstname'], $_POST['lastname'], $password, $_POST['email'] , $_POST['birthdate'] , $_POST['gender']);
	$stmt->execute();
	



if ($stmt = $con->prepare('SELECT id, password FROM users WHERE email = ?')) {
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $password);
	$stmt->fetch();
	
	if (password_verify($_POST['password1'], $password)) {
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		header('Location: completeyourprofile.php');
	} else {
		echo 'Incorrect password!';
	}
} else {
	echo 'Email already used !';
}
$stmt->close();
}

} else {
	echo 'Could not prepare statement!';
}
	}
	$stmt->close();
} 

else {
	echo 'Could not prepare statement!';
}


$con->close();
?>