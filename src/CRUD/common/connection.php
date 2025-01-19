<?php 
$servername = "mysql";
$username = "CRUDuser";
$password = "Admin01!";

try{
    $conn = new mysqli($servername, $username, $password, 'PHPdatabase');
    if ($conn->connect_error) {
        error_log($conn->connect_error);
        exit("Connection DB failed");
      }
}catch(Exception $e){
    error_log($e);
    exit("Connection DB failed");
}

return $conn;
?>