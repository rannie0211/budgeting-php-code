<?php

$user_id = "";
$trans_id = "";

include('config.php');

date_default_timezone_set("Asia/Kuala_Lumpur");

if (!empty($_GET))
{	
    $user_id = $_GET["user_id"];
    $trans_id = $_GET["trans_id"];
}
	
if (!empty($_POST))
{
    $user_id = $_POST["user_id"];
    $trans_id = $_POST["trans_id"];
}

if($user_id === "" || $trans_id === "")
{
    showMessage(0, "Invalid request.", "");
    return;
}
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$timestamp = date('Y-m-d');

$sql = "UPDATE transaction SET trans_status = 1, trans_deleted_date = '".$timestamp."' where trans_id = '".$trans_id."' and trans_user_id = '".$user_id."'";

$result = $conn->query($sql);


if($result)
{
    showMessage(1, "Transaction is deleted successfully.", "");
    return;
}
else
{
    showMessage(0, "Failed to delete the transaction. Technical error occur.","");
    return;
}

$conn->close();

?>