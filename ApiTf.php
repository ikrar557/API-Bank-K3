<?php 
include "../config/connect.php";
$conn = getConnect();
header('Content-Type: application/json; charset=utf-8');

$idRek = $_GET['norek'];
$toRek = $_POST['rektujuan'];
$amount = $_POST['saldo'];
$date = date('Y-m-d');    
$status = "success";

try {
    $conn->begin_transaction();
    if($sql = $conn->prepare("INSERT INTO TblTransfer (rektujuan,rekasal,nominal,tgltf,status) VALUES (?,?,?,?,?)")) {
        $sql->bind_param("ssdss", $toRek, $idRek, $amount, $date, $status);
        if ($sql->execute()) {
            $transfer_id = $sql->insert_id;
            
            // Checking amount 
            $check_balance_sql = "SELECT saldo FROM TblAkun WHERE norek = ?";
            $stmt = $conn->prepare($check_balance_sql);
            $stmt->bind_param("s", $idRek);
            $stmt->execute();
            $balance_result = $stmt->get_result();
            
            if ($balance_result->num_rows > 0) {
                $row = $balance_result->fetch_assoc();
                $current_balance = $row['saldo'];
                
                if ($current_balance >= $amount) {
                    // Deduct the transfer amount from the source account
                    $new_balance_source = $current_balance - $amount;

                    // Update the balance of the source account
                    $update_balance_source_sql = "UPDATE TblAkun SET saldo = ? WHERE norek = ?";
                    $stmt = $conn->prepare($update_balance_source_sql);
                    $stmt->bind_param("ds", $new_balance_source, $idRek);
                    $stmt->execute();

                    // Here, you should add the transfer amount to the destination account
                    // You also need to insert transfer data into the transfer table

                    $conn->commit();

                    $response = array(
                        'status' => 'success',
                        'message' => 'Transfer berhasil',
                        'data' => array(
                            'transfer_id' => $transfer_id,
                            'nominal' => $amount,
                            'rekasal' => $idRek,
                            'rektujuan' => $toRek
                        )
                    );

                    echo json_encode($response);
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Saldo tidak mencukupi dalam rekening pengirim.'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Rekening pengirim tidak ditemukan.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gagal memasukkan data transfer.'));
        }
    }
} catch (mysqli_sql_exception $e) {
    echo "error : " . $e->getMessage();
}
?>
