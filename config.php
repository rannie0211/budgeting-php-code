<?php
    $servername = "budgeting.mysql.database.azure.com";

    $dbusername = "rannie";
    $dbpassword = "990211Rouyi";
    $dbname = "budgeting-db";

    //Initializes MySQLi
    $conn = mysqli_init();

    mysqli_ssl_set($conn,NULL,NULL, "/var/www/html/DigiCertGlobalRootCA.crt.pem", NULL, NULL);

    // Establish the connection
    mysqli_real_connect($conn, "budgeting.mysql.database.azure.com", "rannie", "990211Rouyi", "budgeting-db", 3306, NULL, MYSQLI_CLIENT_SSL);

    //If connection failed, show the error
    if (mysqli_connect_errno())
    {
        die('Failed to connect to MySQL: '. mysqli_connect_error());
    }
    else 
    {
        echo "Connect Successfully";
    }

    function showMessage($status, $title, $message, $url)
    {
        $final = 
        array(
            "status" => $status,
            "title" => $title,
            "message" => $message,
            "url" => $url
        );
        $finalJson = json_encode($final);
        echo $finalJson;
    }

    function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateRandomNumber($length = 5)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') 
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date)
        { 
            $array[] = $date->format($format); 
        }

        return $array;
    }
?>