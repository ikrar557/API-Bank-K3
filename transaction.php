<?php
//koneksi ke database
include "conn.php";
try {
    $transaction = array(); // Inisialisasi array $transaction di luar loop

    // memeriksa data query pada database
    if(isset($_GET['idTransaksi'])){
        $idTransaksi = $_GET['idTransaksi'];
        $sql = "SELECT * FROM tblTransaksi WHERE idTransaksi = $idTransaksi";
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
                    "idTransaksi" => $row["idTransaksi"],
                    "idAkun" => $row["idAkun"],
                    "jenistrans" => $row["jenistrans"],
                    "tanggalTransaksi" => $row["tanggalTransaksi"],
                    "transmasuk" => $row["transmasuk"],
                    "transkeluar" => $row["transkeluar"]
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
// Convert to array
echo json_encode($response);
?>
