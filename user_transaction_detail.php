<?php

include('config.php');

$trans_id = "";
$user_id = "";

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
    showMessage(0,"Failed", "Invalid request.", "");
    return;
}
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$arrayResult = array();
$arrayIndex = 0;

$sql = "SELECT * from transaction where trans_id = '".$trans_id."' and trans_user_id = '".$user_id."' and trans_status = 0 LIMIT 1";

$result = $conn->query($sql);

if(is_object($result))
{
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {   
            if(empty($row["trans_receipt"])){
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
            }
            else if (!empty($row["trans_receipt"])){
                $transaction = array(
                "trans_id" => $row["trans_id"],
                "name" => $row["trans_name"],
                "payment" => $row["trans_payment"],
                "amount" =>$row["trans_amount"],
                "type" => $row["trans_type"],
                "category" => $row["trans_category"],
                "date" => $row["trans_date"],
                "income_tax" => $row["trans_income_tax"],
                "remark" => $row["trans_remark"],
                "receipt" => base64_encode($row["trans_receipt"])
                );
            }
            

            $arrayResult[$arrayIndex] = $transaction;
            $arrayIndex++;
        }
    }
    else
    {
        showMessage(0, "Empty", "No record. ", "");
        return;
    }
}
else
{
    showMessage(0, "Failed", "Failed to obtain data. ", "");
	return;
}

// Output result
$final = array("status" => "1", "result" => $arrayResult);
			
$finalJson = json_encode($final);
echo $finalJson;

$conn->close();

?>