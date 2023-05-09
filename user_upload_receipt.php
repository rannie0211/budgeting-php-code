<?php

include('config.php');

$user_id = "";
$receipt = "";

if(!empty($_POST))
{
    $user_id = $_POST["user_id"];
    $receipt = $_POST["receipt"];
}

if($user_id === "" || $receipt === "")
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
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$uploadSuccess = 0;
$imageStore = "";
$target_dir = "";

if(isset($receipt)){

    $target_dir = "upload/";
    $imageStore = rand()."-".time().".jpeg";
    $target_dir = $target_dir . $imageStore;
    file_put_contents($target_dir, base64_decode($receipt));

    $uploadSuccess = 1;
}

if($uploadSuccess == 1) {

    receiptOcr($target_dir);

    echo "hello" . $target_dir;

}

?>