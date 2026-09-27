<?php
// Menyembunyikan warning bawaan PHP agar struktur JSON balikan tidak rusak
error_reporting(0); 
include 'koneksi.php';

// Menangkap dan menerjemahkan data JSON dari request
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

// Mengecek apakah data sukses diterima dari form
if (!$data) {
    echo json_encode(["status" => "error", "message" => "Data kosong atau format JSON salah"]);
    exit;
}

$tanggal  = $data['date'];
$jenis    = $data['type'];
$kategori = $data['category'];
$nama     = mysqli_real_escape_string($conn, $data['name']);
$harga    = (int)$data['price'];
$qty      = (int)$data['qty'];
$total    = (int)$data['total'];

// Query eksekusi ke database
$sql = "INSERT INTO transaksi (tanggal, jenis, kategori, nama_barang, harga, qty, total) 
        VALUES ('$tanggal', '$jenis', '$kategori', '$nama', '$harga', '$qty', '$total')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["status" => "success"]);
} else {
    // Akan mengembalikan detail error langsung dari MySQL jika gagal
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}
?>