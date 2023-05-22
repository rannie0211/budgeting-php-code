<?php

include('config.php');

$username = "";
$email = "";
$password = "";
$loginToken = generateRandomString(12);


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
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

//Prevent to register with same email.
$sql = "SELECT * FROM user where user_email = '".$email."'";

$result = $conn->query($sql);
if(is_object($result))
{
    if($result->num_rows > 0)
    {
        showMessage(0, "Fail", "The email is registerd, please use another email.", "");
    }
    else
    {
        $sql2 = "INSERT INTO user (user_username, user_email, user_password, user_login_token) VALUES ('". $username ."', '". $email ."', '". $password ."', '".$loginToken."')";
        $result2 = $conn->query($sql2);
        
        if($result2)
        {
            showMessage(1, "Success", "The account is registered successfully!", "");
            return;
        }
        else
        {
            showMessage(0, "Error", "Fail to register. Please try again","");
            return;
        }
    }
}

$conn->close();

?>