<?php 
//koneksi ke database
include "conn.php";

if(isset($_GET['idAkun'])){
    $idAkun = $_GET['idAkun'];

    $sql = "SELECT * FROM TblAkun WHERE idAkun = $idAkun";
} else{
    $sql = "SELECT * FROM TblAkun";
}

$result = $conn-> query($sql);

// $sql = "SELECT * FROM TblAkun";
// $query = mysqli_query($conn, $sql);

// if($hasil->$num_row>0){
//     $data = array();
//     while($data = $hasil -> fetch_assoc()){

//         $item[] = array(
//             'idAkun' => $data ["idAkun"],
//             'Nama' => $data ["nama"],
//             'norek' => $data ["norek"],
//             'saldo' => $data ["saldo"],
//         );
//     }
//         $response = array(
//             'status' => 'OK',
//             'data' =>$item
//         );
    
// }
if ($result) {
    // Memeriksa apakah query berhasil dijalankan
    if ($result->num_rows > 0) {
        // Mengambil data dari hasil query
        while ($row = $result->fetch_assoc()) {
            $item[] = array(
                'idAkun' => $row ["idAkun"],
                'Nama' => $row ["nama"],
                'norek' => $row ["norek"],
                'saldo' => $row ["saldo"],
            );
        }
    } $response = array(
        'status' => 'OK',
        'data' =>$item
    );
    
    // Menutup koneksi
    $conn->close();
} else {
    echo "Terjadi kesalahan dalam query: " . $conn->error;
}
    echo json_encode($response);
?>