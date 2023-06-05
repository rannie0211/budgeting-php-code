<?php

$user_id = "";
$trans_id = "";
$category = "";
$name = "";
$amount = "";
$type = "";
$payment = "";
$income_tax = "";
$remark = "";

include('config.php');

if (!empty($_GET))
{	
	$trans_id = $_GET["trans_id"];
    $user_id = $_GET["user_id"];
    $category = $_GET["category"];
    $name = $_GET["name"];
    $amount = $_GET["amount"];
    $type = $_GET["type"];
    $payment = $_GET["payment"];
    $income_tax = $_GET["income_tax"];
    $remark = $_GET["remark"];
}
	
if (!empty($_POST))
{
	$trans_id = $_POST["trans_id"];
    $user_id = $_POST["user_id"];
    $category = $_POST["category"];
    $name = $_POST["name"];
    $amount = $_POST["amount"];
    $type = $_POST["type"];
    $payment = $_POST["payment"];
    $income_tax = $_POST["income_tax"];
    $remark = $_POST["remark"];
}

if($trans_id === "" || $user_id === "" || $name ==="" || $category === "" || $amount === "" || $type === "" || $payment === "" || $income_tax === "")
{
    showMessage(0,"Failed", "Invalid request.", "");
    return;
}
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$category = addslashes($category);
$name = addslashes($name);
$amount = addslashes($amount);
$type = addslashes($type);
$payment = addslashes($payment);
$income_tax = addslashes($income_tax);
$remark = addslashes($remark);

$sql = "UPDATE transaction SET trans_name = '".$name."', trans_category = '".$category."', trans_amount = '".$amount."', trans_type = '".$type."', 
trans_payment = '".$payment."', trans_income_tax = '".$income_tax."', trans_remark = '".$remark."' where trans_id = '".$trans_id."'";

$result = $conn->query($sql);

if($result)
{
    showMessage(1,"Success", "Transaction is edited successfully.", "");
    return;
}
else
{
    showMessage(0, "Failed", "Failed to edit the transaction. Technical error occur.","");
    return;
}

$conn->close();

?>