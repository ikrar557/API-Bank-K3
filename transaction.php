<?php
//koneksi ke database
include "conn.php";
require_once 'auth.php';
try {
    $transaction = array(); // Inisialisasi array $transaction di luar loop

    // memeriksa data query pada database
    if(isset($_GET['norek'])){
        $norek = $_GET['norek'];
        $sql = "SELECT * FROM tblTransaksi WHERE norek = $norek ORDER BY tanggalTransaksi";
    } else{
        $sql = "SELECT * FROM tblTransaksi";
    }

    $result = $conn->query($sql);

    if ($result) {
        // Memeriksa apakah query berhasil dijalankan
        if ($result->num_rows > 0) {
            // Mengambil data dari hasil query
            while ($row = $result->fetch_assoc()) {
                // menambahkan setiap transaksi ke dalam array $transaction
                $transaction[] = array(
                    "Id Transaksi" => $row["idTransaksi"],
                    "No Rekening" => $row["norek"],
                    "Jenis Transaksi" => $row["jenistrans"],
                    "Tanggal Transaksi" => $row["tanggalTransaksi"],
                    "Nominal" => $row["nominal"]
                );
            }
           
        } else {
            // Tidak ada data yang ditemukan
            $transaction = array('Tidak ada transaksi'); // Inisialisasi $transaction sebagai array kosong
        }
        // stastus data
        $response = array(
            'status' => 'OK',
            'Data retrieved sucessfully' =>$transaction
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
// Set the content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Convert to array
echo json_encode($response);
?>
