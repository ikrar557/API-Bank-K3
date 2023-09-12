<?php 
//koneksi ke database
include "conn.php";

$sql = "SELECT * FROM TblAkun";
$query = mysqli_query($conn, $sql);

?>