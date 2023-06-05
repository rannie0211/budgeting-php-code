<?php

include('config.php');

$user_id = "";

if(!empty($_POST))
{
    $user_id = $_POST["user_id"];
}

if($user_id === "")
{
    showMessage(0, "Failed", "Invalid request.", "");
    return;
}
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$expense = array();
$income = array();
$expenseIndex = 0;
$incomeIndex = 0;

//category type = 1 = expense
$sql = "SELECT * FROM category where category_status = 0 and category_type = 1";

$result = $conn->query($sql);

//category_type = 2 = income
$sql2 = "SELECT * FROM category where category_status = 0 and category_type = 2";

$result2 = $conn->query($sql2);


//get categort list
if(is_object($result) && is_object($result2))
{
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $expense[$expenseIndex] = $row["category_name"];
            $expenseIndex++;
        }
    }
    else{
        showMessage(0, "Empty", "No expense inside the database. Please contact technician.", "");
        return;
    }

    if($result2->num_rows > 0)
    {
        while($row2 = $result2->fetch_assoc())
        {
            $income[$incomeIndex] = $row2["category_name"];
            $incomeIndex++;
        }
    }
    else{
        showMessage(0, "Empty", "No income inside the database. Please contact technician.", "");
        return;
    }

}
else
{
    showMessage(0, "Failed", "Failed to obtain category", "");
    return;
}

// Output result
$final = array("status" => "1", "expense" => $expense, "income" => $income);
			
$finalJson = json_encode($final);
echo $finalJson;

$conn->close();

?>