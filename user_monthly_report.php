<?php

include('config.php');

$user_id = "";
$month = "";
$year = "";
$type = "";

if (!empty($_GET))
{	
    $user_id = $_GET["user_id"];
    $month = $_GET["month"];
    $type = $_GET["type"];
    $year = $_GET["year"];
}
	
if (!empty($_POST))
{
    $user_id = $_POST["user_id"];
    $month = $_POST["month"];
    $type = $_POST["type"];
    $year = $_POST["year"];
}

if($user_id === "" || $month === "" || $year === "" || $type === "")
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
$status = 0;

$totalExpense = 0;
$totalIncome = 0;

//if user ask for expense or income
if($type === "Expense" || $type === "Income" )
{

    $status = 1;

    $sql = "SELECT trans_category,  SUM(trans_amount) as totalAmount FROM transaction WHERE trans_user_id = '".$user_id."' AND trans_status = 0 
        AND trans_type = '".$type."' AND MONTH(trans_date) = '".$month."' AND YEAR(trans_date) = '".$year."' GROUP BY trans_category";

    
    $result = $conn->query($sql);

    if(is_object($result))
    {
        if($result->num_rows >0)
        {
            while($row = $result->fetch_assoc())
            {
                $transArray = array();
                $transIndex = 0;

                $category = $row["trans_category"];
                $totalAmount = $row["totalAmount"];

                $sql2 = "SELECT * from transaction where trans_category = '".$category."' and trans_status = 0 and trans_income_tax = 1 and trans_type = '".$type."' and MONTH(trans_date) = '".$month."' and YEAR(trans_date) = '".$year."'";

                $result2 = $conn->query($sql2);

                if(is_object($result2))
                {
                    if($result2->num_rows > 0 )
                    {
                        while($row2 = $result2->fetch_assoc())
                        {
                            $transaction = 
                            array(
                                "id" => $row2["trans_id"],
                                "name" => $row2["trans_name"],
                                "date" => $row2["trans_date"],
                                "amount" => $row2["trans_amount"]
                            );

                            $transArray[$transIndex] = $transaction;
                            $transIndex++;
                        }
                    }
                }

                $data = 
                array(
                    "category" => $category,
                    "total_amount" => $totalAmount,
                    "transactions" => $transArray 
                );

                $arrayResult[$arrayIndex] = $data;
                $arrayIndex++;

            }
        }
        else
        {
            showMessage(0, "Failed", "Empty result.", "");
            return;
        }
    }
    else
    {
        showMessage(0, "Failed", "Failed to obtain data. Please inform admin.", "");
        return;
    }
}
//else if the user is ask for balance.
else
{

    $status = 2;

    $sql = "SELECT SUM(trans_amount) as totalExpense FROM transaction where trans_user_id = '".$user_id."' AND trans_status = 0 AND trans_type = 'Expense'";

    $result = $conn->query($sql);

    if(is_object($result))
    {
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $totalExpense = $row["totalExpense"];

            }
        }
        else
        {
            showMessage(0, "Empty", "Empty result.", "");
            return;
        }
    }
    else
    {
        showMessage(0, "Failed", "Failed to obtain data. Please inform admin.", "");
        return;
    }

    $sql2 = "SELECT SUM(trans_amount) as totalIncome FROM transaction where trans_user_id = '".$user_id."' AND trans_status = 0 AND trans_type = 'Expense'";

    $result2 = $conn->query($sql2);

    if(is_object($result2))
    {
        if($result2->num_rows > 0)
        {
            while($row2 = $result2->fetch_assoc())
            {
                $totalIncome = $row["totalIncome"];
            }
            
            $arrayResult[0] = array("Expense"=>$totalExpense, "Income"=>$totalIncome); 
        }
        else
        {
            showMessage(0, "Empty", "Empty result.", "");
            return;
        }
    }
    else
    {
        showMessage(0, "Failed", "Failed to obtain data. Please inform admin.", "");
        return;
    }
}

// Output result
$final = array("status" => $status, "result" => $arrayResult);
			
$finalJson = json_encode($final);
echo $finalJson;

$conn->close();


?>