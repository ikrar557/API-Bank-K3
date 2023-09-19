<?php 
include "conn.php";
header('Content-Type: application/json; charset=utf-8');

$idTF= rand(1,10000);
$idRek = $_POST['norek'];
$toRek = $_POST['rektujuan'];
$amount = $_POST['saldo'];
$date = date('Y-m-d');    
$status = "success";

try {
    $conn->begin_transaction();
    if($sql = $conn->prepare("INSERT INTO TblTransfer (idTransfer,rektujuan,rekasal,nominal,tgltf,status) VALUES (?,?,?,?,?,?)")) {
        $sql->bind_param("iiiiss", $idTF,$toRek, $idRek, $amount, $date, $status);
        $stmt = $sql->execute();
        if ($stmt) {
        
            // Check sending amount
            $check_balance_sql = "SELECT saldo,nama FROM TblAkun WHERE norek = ?";
            $stmt = $conn->prepare($check_balance_sql);
            $stmt->bind_param("i", $idRek);
            $stmt->execute();
            $balance_result = $stmt->get_result();
            
            //get Receiver balance
            $check_balance_sql = "SELECT nama,saldo FROM TblAkun WHERE norek = ?";
            $stmt = $conn->prepare($check_balance_sql);
            $stmt->bind_param("i", $toRek);
            $stmt->execute();
            $name_result = $stmt->get_result();
            $row = $name_result->fetch_assoc();
            $receiverName = $row['nama'];
            $receiverBalance = $row['saldo']; 
            $new_receiver_balance = $receiverBalance + $amount ;

            if ($balance_result->num_rows > 0) {
                $row = $balance_result->fetch_assoc();
                $senderBalance = $row['saldo'];
                $senderName= $row['nama'];

                if ($senderBalance >= $amount) {
                    // Deduct the transfer amount from the source account
                    $new_balance_source = $senderBalance - $amount;

                    // Update saldo Sender
                    $update_balance_sender = "UPDATE TblAkun SET saldo = ? WHERE norek = ?";
                    $stmt = $conn->prepare($update_balance_sender);
                    $stmt->bind_param("ii", $new_balance_source, $idRek);
                    $stmt->execute();

                    // Update saldo Receiver
                    $update_balance_receiver = "UPDATE TblAkun SET saldo = ? WHERE norek = ?";
                    $stmt = $conn->prepare($update_balance_receiver);
                    $stmt->bind_param("ii", $new_receiver_balance, $toRek);
                    $stmt->execute();

                    
                    // Here, you should add the transfer amount to the destination account
                    // You also need to insert transfer data into the transfer table

                    $conn->commit();
                    $conn->close();


                    $response = array(
                        'status' => 'success',
                        'message' => 'Transfer berhasil',
                        'data' => array(
                            'Nama Pengirim' => $senderName,
                            'Rekening Pengirim'=> $idRek,
                            'Nama Penerima' => $receiverName,
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
            var_dump($conn);
            echo json_encode(array('status' => 'error', 'message' => 'Gagal memasukkan data transfer.'));
        }
    }
} catch (mysqli_sql_exception $e) {
    echo "error : " . $e->getMessage();
}
?>