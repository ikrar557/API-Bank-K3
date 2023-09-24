<?php
include('conn.php');
header('Content-Type: application/json; charset=utf-8');

$statusCodes = array(
    'success' => 400,
    'error_creating_transfer' => 401,
    'error_updating_account_balances' => 402,
    'insufficient_funds' => 403,
    'source_account_not_found' => 404,
    'incomplete_form_data' => 405
);

$response = array(
    'status' => '',
    'code' => '',
    'message' => '',
    'data' => null
);

if (
    isset($_POST['nominal']) &&
    isset($_POST['rekasal']) &&
    isset($_POST['rektujuan'])
) {
    $nominal = $_POST['nominal'];
    $rekasal = $_POST['rekasal'];
    $rektujuan = $_POST['rektujuan'];

    if ($rekasal === $rektujuan) {
        $response['status'] = 'error';
        $response['code'] = $statusCodes['error_creating_transfer'];
        $response['message'] = 'Sender and receiver account numbers cannot be the same.';
    } else {
        $status = 'success';

        $check_balance_sql = "SELECT saldo FROM TblAkun WHERE norek = '$rekasal'";
        $balance_result = $conn->query($check_balance_sql);

        if ($balance_result->num_rows > 0) {
            $row = $balance_result->fetch_assoc();
            $current_balance = $row['saldo'];

            if ($current_balance >= $nominal) {
                $receiver_name_sql = "SELECT nama FROM TblAkun WHERE norek = '$rektujuan'";
                $receiver_name_result = $conn->query($receiver_name_sql);

                if ($receiver_name_result->num_rows > 0) {
                    $receiver_row = $receiver_name_result->fetch_assoc();
                    $receiver_name = $receiver_row['nama'];
                } else {
                    // Set a specific error message for destination account not found
                    $response['status'] = 'error';
                    $response['code'] = $statusCodes['source_account_not_found'];
                    $response['message'] = 'Akun penerima tidak ditemukan.';
                    // Stop further processing since the destination account was not found
                    echo json_encode($response);
                    exit;
                }

                $idTransfer = mt_rand(100000, 999999);

                $new_balance_source = $current_balance - $nominal;

                $add_balance_destination = "UPDATE TblAkun SET saldo = saldo + $nominal WHERE norek = '$rektujuan'";
                $update_balance_source = "UPDATE TblAkun SET saldo = $new_balance_source WHERE norek = '$rekasal'";

                $conn->begin_transaction();

                if ($conn->query($add_balance_destination) === TRUE && $conn->query($update_balance_source) === TRUE) {

                    $sql = "INSERT INTO TblTransfer (idTransfer, nominal, rekasal, rektujuan, status, tgltf) 
                            VALUES ('$idTransfer', '$nominal', '$rekasal', '$rektujuan', '$status', NOW())";

                    if ($conn->query($sql) === TRUE) {

                        $conn->commit();


                        $response['status'] = 'success';
                        $response['code'] = $statusCodes['success'];
                        $response['message'] = 'Transfer berhasil';
                        $response['data'] = array(
                            'idTransfer' => $idTransfer,
                            'Nominal' => $nominal,
                            'Rekening Asal' => $rekasal,
                            'Rekening Tujuan' => $rektujuan,
                            'Nama Penerima' => $receiver_name,
                            'Waktu Transfer' => date('Y-m-d H:i:s') 
                        );
                    } else {
                        $conn->rollback();
                        $response['status'] = 'error';
                        $response['code'] = $statusCodes['error_creating_transfer'];
                        $response['message'] = 'Gagal membuat transfer.';
                    }
                } else {
                    $conn->rollback();
                    $response['status'] = 'error';
                    $response['code'] = $statusCodes['error_updating_account_balances'];
                    $response['message'] = 'Gagal mengupdate saldo.';
                }
            } else {
                $response['status'] = 'error';
                $response['code'] = $statusCodes['insufficient_funds'];
                $response['message'] = 'Saldo tidak cukup pada rekening pengirim.';
            }
        } else {
            $response['status'] = 'error';
            $response['code'] = $statusCodes['source_account_not_found'];
            $response['message'] = 'Akun pengirim tidak ditemukan.';
        }
    }
} else {
    $response['status'] = 'error';
    $response['code'] = $statusCodes['incomplete_form_data'];
    $response['message'] = 'Form payload belum lengkap.';
}

$json_response = json_encode($response);

// Output the JSON data
echo $json_response;

// Close the connection
$conn->close();
?>
