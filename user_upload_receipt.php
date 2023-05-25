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
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$tempFile = tempnam(sys_get_temp_dir(), 'image/');
$imageStore = rand()."-".time().".jpeg";
$tempFile = $tempFile . $imageStore;
file_put_contents($tempFile, base64_decode($receipt));

$imageData = addslashes(file_get_contents($tempFile));

$sql = "INSERT INTO image (image_blob) VALUES ('".$imageData."')";

$result = $conn->query($sql);

if($result)
{
    showMessage(1,"Success", "The image is upload successful.", "");
    return;
}
else
{
    showMessage(0, "Error", "Fail to upload image. Please try again.", "");
    return;
}

$conn->close();

unlink($tempFile);

?>