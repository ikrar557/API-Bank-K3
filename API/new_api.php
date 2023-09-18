<?php 
include "../config/connect.php";
$conn = getConnect();
header('Content-Type: application/json; charset=utf-8');

$idTF= rand(10,1000);
$idRek = $_POST['norek'];
$toRek = $_POST['rektujuan'];
$amount = $_POST['saldo'];
$date = date('Y-m-d');    
$status = "400";

try {
    $conn->begin_transaction();
    if($sql = $conn->prepare("INSERT INTO TblTransfer (idTransfer,rektujuan,rekasal,nominal,tgltf,status) VALUES (?,?,?,?,?,?)")) {
        $sql->bind_param("iiiiss", $idTF,$toRek, $idRek, $amount, $date, $status);
        $stmt = $sql->execute();
        if ($stmt) {
        
            // Checking amount 
            $check_balance_sql = "SELECT saldo FROM TblAkun WHERE norek = ?";
            $stmt = $conn->prepare($check_balance_sql);
            $stmt->bind_param("i", $toRek);
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
                    $stmt->bind_param("ii", $new_balance_source, $idRek);
                    $stmt->execute();

                    // Here, you should add the transfer amount to the destination account
                    // You also need to insert transfer data into the transfer table

                    $conn->commit();
                    $conn->close();


                    $response = array(
                        'status' => 'success',
                        'message' => 'Transfer berhasil',
                        'data' => array(
                            'rekasal' => $idRek,
                            'Rekening Pengirim'=> $idRek,
                            'Rekening Penerima' => $toRek,
                            'nominal' => 'Rp.'.$amount,
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