<?php

include('config.php');

$user_id = "";
$category = "";
$name = "";
$date = "";
$amount = "";
$type = "";
$payment = "";
$income_tax = "";
$remark = "";

if(!empty($_GET))
{
    $user_id = $_GET["user_id"];
    $category = $_GET["category"];
    $name = $_GET["name"];
    $date = $_GET["date"];
    $amount = $_GET["amount"];
    $type = $_GET["type"];
    $payment = $_GET["payment"];
    $income_tax = $_GET["income_tax"];
    $remark = $_GET["remark"];
}

if(!empty($_POST))
{
    $user_id = $_POST["user_id"];
    $category = $_POST["category"];
    $name = $_POST["name"];
    $date = $_POST["date"];
    $amount = $_POST["amount"];
    $type = $_POST["type"];
    $payment = $_POST["payment"];
    $income_tax = $_POST["income_tax"];
    if($remark!= ""){
        $remark = $_POST["remark"];
    }
}

if ($user_id === "" || $category === "" || $name ==="" || $date ==="" || $amount === "" || $type === "" || $payment === "" || $income_tax === "")
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

$timestamp = date('Y-m-d', strtotime($date));

$sql = "INSERT INTO transaction (trans_user_id, trans_category, trans_report_id, trans_name, trans_date, trans_amount, trans_type, trans_payment, trans_income_tax, trans_remark) 
        values ('".$user_id."', '".$category."', 1, '".$name."', '".$timestamp."', '".$amount."', '".$type."', '".$payment."', '".$income_tax."', '".$remark."')";

$result = $conn->query($sql);

if($result)
{
    showMessage(1,"Success", "The transaction is added successful.", "");
    return;
}
else
{
    showMessage(0, "Error", "Fail to add transaction. Please try again.", "");
    return;
}

$conn->close();

?>