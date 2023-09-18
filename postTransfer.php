<?php 
include "conn.php";
header('Content-Type: application/json; charset=utf-8');

$idTF = rand(1, 10000);
$idRek = $_POST['norek'];
$toRek = $_POST['rektujuan'];
$amount = $_POST['saldo'];
$date = date('Y-m-d');    
$status = "success";
$code = 400; 

try {
    $conn->begin_transaction();


    if ($idRek === $toRek) {
        $response = array(
            'status' => 'error',
            'code' => 403, 
            'message' => 'Rekening pengirim dan tujuan tidak boleh sama.',
        );
    } else {
        // Check if account exist
        $check_source_account_sql = "SELECT norek FROM TblAkun WHERE norek = ?";
        $stmt = $conn->prepare($check_source_account_sql);
        $stmt->bind_param("i", $idRek);
        $stmt->execute();
        $source_account_result = $stmt->get_result();

        if ($source_account_result->num_rows === 0) {
            $response = array(
                'status' => 'error',
                'code' => 402, 
                'message' => 'Rekening pengirim tidak ditemukan.',
            );
        } else {
            if ($sql = $conn->prepare("INSERT INTO TblTransfer (idTransfer, rektujuan, rekasal, nominal, tgltf, status) VALUES (?, ?, ?, ?, ?, ?)")) {
                $sql->bind_param("iiiiss", $idTF, $toRek, $idRek, $amount, $date, $status);
                $stmt = $sql->execute();
                if ($stmt) {
                    // Cek saldo 
                    $check_balance_sql = "SELECT saldo, nama FROM TblAkun WHERE norek = ?";
                    $stmt = $conn->prepare($check_balance_sql);
                    $stmt->bind_param("i", $toRek);
                    $stmt->execute();
                    $balance_result = $stmt->get_result();

                    if ($balance_result->num_rows > 0) {
                        $row = $balance_result->fetch_assoc();
                        $current_balance = $row['saldo'];
                        $receiverName = $row['nama'];

                        if ($current_balance >= $amount) {
                            $new_balance_source = $current_balance - $amount;

                            $update_balance_source_sql = "UPDATE TblAkun SET saldo = ? WHERE norek = ?";
                            $stmt = $conn->prepare($update_balance_source_sql);
                            $stmt->bind_param("ii", $new_balance_source, $idRek);
                            $stmt->execute();

                            $conn->commit();
                            $conn->close();

                            $response = array(
                                'status' => 'success',
                                'code' => 400, 
                                'message' => 'Transfer berhasil',
                                'data' => array(
                                    'Nama Penerima' => $receiverName,
                                    'Rekening Pengirim' => $idRek,
                                    'Rekening Penerima' => $toRek,
                                    'nominal' => 'Rp.'.$amount,
                                )
                            );

                        } else {
                            $response = array(
                                'status' => 'error',
                                'code' => 401, 
                                'message' => 'Saldo tidak mencukupi dalam rekening pengirim.',
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 'error',
                            'code' => 402, 
                            'message' => 'Rekening pengirim tidak ditemukan.',
                        );
                    }
                } else {
                    var_dump($conn);
                    $response = array(
                        'status' => 'error',
                        'code' => 500,
                        'message' => 'Gagal memasukkan data transfer.',
                    );
                }
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    echo "error : " . $e->getMessage();
}

// Send the JSON response without changing the HTTP response code
echo json_encode($response);
?>
