<?php

include('config.php');

$username = "";
$email = "";
$password = "";
$loginToken = generateRandomString();


if (!empty($_GET))
{
	$username = $_GET["username"];
    $email = $_GET["email"];
	$password = $_GET["password"];
}
	
if (!empty($_POST))
{
	$username = $_POST["username"];
    $email = $_POST["email"];
	$password = $_POST["password"];
}
	
if ($username === "" || $email === "" || $password === "")
{
	showMessage(0, "Failed", "Invalid request.", "");
    return;
}

// // Create connection
// $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// // Check connection
// if ($conn->connect_error)
// {
// 	showMessage(0, "Failed", "Connection failed: " . $conn->connect_error, "");
// }
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$sql = "INSERT INTO user (user_username, user_email, user_password) VALUES ('". $username ."', '". $email ."', '". $password ."')";

$result = $conn->query($sql);

if($result)
{
    showMessage(1, "Success", "The account is registered successfully!","");
    return;
}
else
{
    showMessage(0, "Error", "Fail to register","");
    return;
}

$conn->close();

?>