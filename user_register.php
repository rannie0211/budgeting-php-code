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

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error)
{
	showMessage(0, "Failed", "Connection failed: " . $conn->connect_error, "");
    return;
}

//Initializes MySQLi
// $conn = mysqli_init();

// mysqli_ssl_set($conn,NULL,NULL, "C:\ssl\DigiCertGlobalRootCA.crt.pem", NULL, NULL);

// // Establish the connection
// mysqli_real_connect($conn, "budgeting.mysql.database.azure.com", "rannie", "990211Rouyi", "budgeting-db", 3306, NULL, MYSQLI_CLIENT_SSL);

// //If connection failed, show the error
// if ($conn->connect_error)
// {
//     die('Failed to connect to MySQL: '. mysqli_connect_error());
// }
// else{
//     echo "Sucess";
// }
	
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