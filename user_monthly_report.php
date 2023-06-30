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
    showMessage(0,"Invalid request.", "");
    return;
}

$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$arrayResult = array();
$arrayIndex = 0;

$status = 0;

$totalExpense = 0.00;
$totalIncome = 0.00;
$income = 0;
$balanceAmount = 0;

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

                $sql2 = "SELECT * from transaction where trans_user_id = '".$user_id."' and trans_category = '".$category."' and trans_status = 0 and trans_type = '".$type."' and MONTH(trans_date) = '".$month."' and YEAR(trans_date) = '".$year."'";

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
            showMessage(0, "Empty result.", "");
            return;
        }
    }
    else
    {
        showMessage(0, "Failed to obtain data. Please inform admin.", "");
        return;
    }
}
//else if the user is ask for balance.
else
{

    $status = 2;

    $sql = "SELECT SUM(trans_amount) as totalExpense FROM transaction where trans_user_id = '".$user_id."' AND trans_status = 0 AND trans_type = 'Expense' and MONTH(trans_date) = '".$month."' and YEAR(trans_date) = '".$year."'";

    $result = $conn->query($sql);

    if(is_object($result))
    {
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                if($row["totalExpense"] == null)
                {
                    $totalExpense = 0;
                }else
                {
                    $totalExpense = $row["totalExpense"];
                }
            }
        }
    }

    $sql2 = "SELECT SUM(trans_amount) as totalIncome FROM transaction where trans_user_id = '".$user_id."' AND trans_status = 0 AND trans_type = 'Income' and MONTH(trans_date) = '".$month."' and YEAR(trans_date) = '".$year."'";

    $result2 = $conn->query($sql2);

    if(is_object($result2))
    {
        if($result2->num_rows > 0)
        {
            while($row2 = $result2->fetch_assoc())
            {
                if($row2["totalIncome"] == null)
                {
                    $totalIncome = 0;
                }else
                {
                    $totalIncome = $row2["totalIncome"];
                }
            }
        }
    }

    $balanceAmount = $totalIncome - $totalExpense;
    $arrayResult[0] = array("Expense"=>$totalExpense, "Income"=>$totalIncome, "Balance"=>$balanceAmount); 
}

// Output result
$final = array("status" => $status, "result" => $arrayResult);
			
$finalJson = json_encode($final);
echo $finalJson;

$conn->close();


?>