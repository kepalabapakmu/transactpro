<?php
include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY tanggal DESC, id DESC");
$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    // Kita petakan agar namanya sesuai dengan variabel JS kamu sebelumnya
    $data[] = [
        'id'       => $row['id'],
        'date'     => $row['tanggal'],
        'type'     => $row['jenis'],
        'category' => $row['kategori'],
        'name'     => $row['nama_barang'],
        'price'    => (int)$row['harga'],
        'qty'      => (int)$row['qty'],
        'total'    => (int)$row['total']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>