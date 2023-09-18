<?php 
$port = 3306;
$hostname = "api.raksakabanyu.my.id";
$username = "user_bank_api";
$password = "YyDyKWpa7WxN5kY8";
$database = "tsa_bank";

$conn = new mysqli($hostname, $username, $password, $database, $port);

if($conn->connect_errno){
    die("connection failed:" . $conn->connect_error);
}
?>
