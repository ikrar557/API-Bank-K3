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
} else {
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
            'tgltf' => $tgltf,
            'status' => $status,
            'rekasal' => $rekasal,
            'rektujuan' => $rektujuan
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
