<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 

    $host = "127.0.0.1";
    $username = "root";
    $password = "R0bb3d03s";
    $database = "test";
    $port = 3306;

    try{
        $conn = new mysqli($host, $username, $password, $database, $port);
        if ($conn->connect_error) {
            error_log($conn->connect_error);
            exit("Connection DB failed");
          }
    }catch(Exception $e){
        error_log($e);
        exit("Connection DB failed");
    }

    echo "<table>";
    echo "<tr><th>Id</th><th>Age</th><th>score</th></tr>";

    ?>
    
</body>
</html>