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
$receipt = "";

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
    $receipt = $_POST["receipt"];
    
}

if ($user_id === "" || $category === "" || $name ==="" || $date ==="" || $amount === "" || $type === "" || $payment === "" || $income_tax === "")
{
	showMessage(0, "Invalid request.", "");
    return;
}
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$timestamp = date('Y-m-d', strtotime($date));

if($receipt === "")
{
    
    $sql = "INSERT INTO transaction (trans_user_id, trans_category, trans_name, trans_date, trans_amount, trans_type, trans_payment, trans_income_tax, trans_remark) 
        values ('".$user_id."', '".$category."', '".$name."', '".$timestamp."', '".$amount."', '".$type."', '".$payment."', '".$income_tax."', '".$remark."')";

    $result = $conn->query($sql);


    if($result)
    {
        showMessage(1, "The transaction is added successful.", "");
        return;
    }
    else
    {
        showMessage(0, "Fail to add transaction. Please try again.", "");
        return;
    }
}
else
{
    $tempFile = tempnam(sys_get_temp_dir(), 'image/');
    $imageStore = rand()."-".time().".jpeg";
    $tempFile = $tempFile . $imageStore;
    file_put_contents($tempFile, base64_decode($receipt));
    $imageData = addslashes(file_get_contents($tempFile));

    $sql = "INSERT INTO transaction (trans_user_id, trans_category, trans_receipt, trans_name, trans_date, trans_amount, trans_type, trans_payment, trans_income_tax, trans_remark) 
        values ('".$user_id."', '".$category."', '".$imageData."', '".$name."', '".$timestamp."', '".$amount."', '".$type."', '".$payment."', '".$income_tax."', '".$remark."')";

    $result = $conn->query($sql);

    if($result)
    {
        showMessage(1, "The transaction is added successful.", "");
        return;
    }
    else
    {
        showMessage(0, "Fail to add transaction. Please try again.", "");
        return;
    }

    echo $conn->error;
}

$conn->close();

?>