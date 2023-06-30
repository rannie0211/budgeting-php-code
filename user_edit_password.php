<?php

include('config.php');

$user_id = "";
$password = "";

if(!empty($_GET))
{
    $user_id = $_GET["user_id"];
    $password = $_GET["password"];
}

if(!empty($_POST))
{
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];
}

if($user_id === "" || $password === "")
{
    showMessage(0, "Invalid request.", "");
}

$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$sql = "UPDATE user SET user_password = '". $password ."' where user_status = 0 and user_id = '".$user_id."'";

$result = $conn->query($sql);

if($result)
{
    showMessage(1, "The password is changed successfully.", "");
    return;
}
else
{
    showMessage(0, "Technical error.", "");
}

$conn->close();

?>