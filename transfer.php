<?php
include('conn.php');

// Response Structure
$response = array(
    'status' => '',
    'message' => '',
    'data' => []
);

if (isset($_GET['idTransfer'])) {
    $idTransfer = $_GET['idTransfer'];
    $sql = "SELECT * FROM TblTransfer WHERE idTransfer = $idTransfer";
} elseif (isset($_GET['rektujuan'])) {
    $rektujuan = $_GET['rektujuan'];
    $sql = "SELECT * FROM TblTransfer WHERE rektujuan = '$rektujuan'";
} elseif (isset($_GET['rekasal'])) {
    $rekasal = $_GET['rekasal'];
    $sql = "SELECT * FROM TblTransfer WHERE rekasal = '$rekasal'";
} else {
    // If no specific query parameters are provided, retrieve all records
    $sql = "SELECT * FROM TblTransfer";
}

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $data = array(); 

    while ($row = $result->fetch_assoc()) {
        $idTransfer = $row['idTransfer'];
        $nominal = $row['nominal'];
        $tgltf = $row['tgltf'];
        $status = $row['status'];
        $rekasal = $row['rekasal'];
        $rektujuan = $row['rektujuan'];

        $data[] = array(
            'idTransfer' => $idTransfer,
            'nominal' => $nominal,
            'Tanggal Transfer' => $tgltf,
            'status' => $status,
            'Rekening Pengirim' => $rekasal,
            'Rekening Penerima' => $rektujuan
        );
    }

    $response['status'] = 'success';
    $response['message'] = 'Data ditemukan';
    $response['data'] = $data;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Data tidak ditemukan.';
}

// Set the content type as JSON
header('Content-Type: application/json');

$json_response = json_encode($response, JSON_PRETTY_PRINT);

echo $json_response;

$conn->close();
?>
