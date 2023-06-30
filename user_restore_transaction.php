<?php

include('config.php');

$trans_id = "";
$user_id = "";

if(!empty($_POST))
{
    $trans_id = $_POST["trans_id"];
    $user_id = $_POST["user_id"];
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

$sql = "UPDATE transaction SET trans_status = 0 where trans_id = '".$trans_id."' and trans_user_id = '".$user_id."'";

$result = $conn->query($sql);

if($result)
{
    showMessage(1, "Transaction is restored successfully.", "");
    return;
}
else
{
    showMessage(0, "Failed to restore the deleted transaction. Technical error occur.","");
    return;
}

$conn->close();

?>