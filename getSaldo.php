<?php 
//koneksi ke database
include "conn.php";

try {
    // memeriksa data query pada database
if(isset($_GET['idAkun'])){
    $idAkun = $_GET['idAkun'];
    $sql = "SELECT * FROM TblAkun WHERE idAkun = $idAkun";
} else{
    $sql = "SELECT * FROM TblAkun";
}

$result = $conn-> query($sql);

    if ($result) {
        // Memeriksa apakah query berhasil dijalankan
        if ($result->num_rows > 0) {
            // Mengambil data dari hasil query
            while ($row = $result->fetch_assoc()) {
                // menampilkan data pada database
                $item[] = array(
                    'Id Account' => $row ["idAkun"],
                    'Nama' => $row ["nama"],
                    'No Rekening' => $row ["norek"],
                    'Balance' => $row ["saldo"],
                );
            }
           
        } else {
            // Tidak ada data yang ditemukan
            $item = array('Data tidak ditemukan'); // Inisialisasi $item sebagai array kosong
        }
        // stastus data
        $response = array(
            'status' => 'OK',
            'Data retrieved sucessfully' =>$item
        );
    
    } else {
        // Penanganan kesalahan jika query tidak berhasil
        throw new Exception("Kesalahan dalam menjalankan query.");
    }
} catch (Exception $e) {
    // Tangani pengecualian yang terjadi
    $response = array(
        'status' => 'Error',
        'message' => $e->getMessage()
    );
}
    // Convert to array
    //echo json_encode($response);
    // Set the content type as JSON
header('Content-Type: application/json');

$json_response = json_encode($response, JSON_PRETTY_PRINT);

echo $json_response;

$conn->close();
?>