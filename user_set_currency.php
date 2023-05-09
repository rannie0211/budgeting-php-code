<?php

include('config.php');

$user_id = "";
$currency = "";

if(!empty($_GET))
{
    $user_id = $_GET["user_id"];
    $currency = $_GET["currency"];
}

if(!empty($_POST))
{
    $user_id = $_POST["user_id"];
    $currency = $_POST["currency"];
}

if($user_id === "" || $currency === "")
{
    showMessage(0,"Failed", "Invalid request.", "");
}

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error)
{
	showMessage(0, "Failed", "Connection failed: " . $conn->connect_error, "");
}

$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$sql = "UPDATE user SET user_currency = '". $currency ."' where user_status = 0 and user_id = '".$user_id."'";

$result = $conn->query($sql);

if($result)
{
    showMessage(1,"Success", "The currency is set successfully.", "");
    return;
}
else
{
    showMessage(0, "Error", "Technical error.", "");
}

$conn->close();

?>