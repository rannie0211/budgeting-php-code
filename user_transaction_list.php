<?php

include('config.php');

$date = "";
$user_id = "";

if (!empty($_GET))
{	
	$date = $_GET["date"];
    $user_id = $_GET["user_id"];
}
	
if (!empty($_POST))
{
	$date = $_POST["date"];
    $user_id = $_POST["user_id"];
}

if($date === "" || $user_id === "")
{
    showMessage(0,"Failed", "Invalid request.", "");
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

$arrayResult = array();
$arrayIndex = 0;

$timestamp = date('Y-m-d', strtotime($date));

$sql = "SELECT * from transaction where trans_date = '".$timestamp."' and trans_user_id = '".$user_id."' and trans_status = 0";

$result = $conn->query($sql);

if(is_object($result))
{
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $transaction = array(
                "trans_id" => $row["trans_id"],
                "name" => $row["trans_name"],
                "payment" => $row["trans_payment"],
                "amount" =>$row["trans_amount"],
                "type" => $row["trans_type"],
                "category" => $row["trans_category"],
                "date" => $row["trans_date"],
                "income_tax" => $row["trans_income_tax"],
                "remark" => $row["trans_remark"]
            );

            $arrayResult[$arrayIndex] = $transaction;
            $arrayIndex++;
        }
    }
    else
    {
        showMessage(0, "Empty", "No record. Create your first record of the day! ", "");
        return;
    }
}
else
{
    showMessage(0, "Failed", "Failed to obtain data. ", "");
	return;
}

// Output result
$final = array("status" => "1", "user_id"=> $user_id,"result" => $arrayResult);
			
$finalJson = json_encode($final);
echo $finalJson;

$conn->close();

?>