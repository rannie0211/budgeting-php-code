<?php

include('config.php');

$user_id = "";

if (!empty($_GET))
{	
    $user_id = $_GET["user_id"];
}

if(!empty($_POST))
{
    $user_id = $_POST["user_id"];
}

if($user_id === "")
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

$sql = "SELECT * from transaction where trans_user_id = '". $user_id ."' and trans_status = 1 and trans_deleted_date > now() - INTERVAL 15 day";

$result = $conn->query($sql);

if(is_object($result))
{
    if($result->num_rows>0)
    {
        while($row = $result->fetch_assoc())
        {
            $transaction = array(
                "trans_id" => $row["trans_id"],
                "name" => $row["trans_name"],
                "amount" =>$row["trans_amount"],
                "type" => $row["trans_type"],
                "date" => $row["trans_date"]
            );

            $arrayResult[$arrayIndex] = $transaction;
            $arrayIndex++;
        }
    }
    else
    {
        showMessage(0, "Empty", "No record! ", "");
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