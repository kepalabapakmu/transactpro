<?php 'koneksi.php';
include 'koneksi.php';
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $data['id'];
    if (mysqli_query($conn, "DELETE FROM transaksi WHERE id = $id")) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>
    