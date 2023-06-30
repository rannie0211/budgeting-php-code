<?php

include('config.php');

$user = "";
$password = "";

if(!empty($_GET))
{
    $user = $_GET["username"];
    $password = $_GET["password"];
}

if(!empty($_POST))
{
    $user = $_POST["username"];
    $password = $_POST["password"];
}

if ($user === "" || $password === "")
{
	showMessage(0, "Invalid request.", "");
    return;
}
	
$conn->query('set character_set_client=utf8');
$conn->query('set character_set_connection=utf8');
$conn->query('set character_set_results=utf8');
$conn->query('set character_set_server=utf8');

$sql = "SELECT * FROM user where user_username = '". $user ."' and user_password = '". $password ."' and user_status = 0 LIMIT 1";

$result = $conn->query($sql);

if(is_object($result))
{
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $id = $row["user_id"];
            $currency = $row["user_currency"];

            $sql2 = "UPDATE user SET user_last_login = NOW() where user_id = '" . $id . "'";

            $result2 = $conn->query($sql2);

            if($result2) 
            {
                showMessage(1, "You are login successfully!", '{"user_id":"'.$id.'", "currency":"'.$currency.'"}');
                return;
            }
            else
            {
                showMessage(0, "Technical Problem.", "");
                return;
            }
        }
    }
    else
    {
        showMessage(0, "Invalid username and password. ", "");
        return;
    }
}
else
{
    showMessage(0, "No user record.", "");
    return;
}

$conn->close();

?>