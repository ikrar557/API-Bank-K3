<?php 
//koneksi ke database
include "conn.php";

$sql = "SELECT * FROM TblAkun";
$query = mysqli_query($conn, $sql);

while($data = mysqli_fetch_array($query)){

    $item[] = array(
        'idAkun' => $data ["idAkun"],
        'Nama' => $data ["nama"],
        'norek' => $data ["norek"],
        'saldo' => $data ["saldo"],
    );
}
    $response = array(
        'status' => 'OK',
        'data' =>$item
    );

    echo json_encode($response);
?>