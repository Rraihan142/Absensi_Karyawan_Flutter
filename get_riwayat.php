<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
include 'koneksi.php';

file_put_contents("riwayat_debug.txt", "Script dimulai\n", FILE_APPEND);

$karyawan_id = $_GET['karyawan_id'] ?? '';

file_put_contents("riwayat_debug.txt", "karyawan_id GET: " . print_r($_GET, true) . "\n", FILE_APPEND);
file_put_contents("riwayat_debug.txt", "karyawan_id value: $karyawan_id\n", FILE_APPEND);

if (empty($karyawan_id)) {
    echo json_encode([
        "status" => "error",
        "message" => "Parameter karyawan_id tidak ditemukan."
    ]);
    exit();
}

$sql = "SELECT waktu, latitude, longitude, lokasi FROM absensi WHERE karyawan_id = ? ORDER BY waktu DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Query error: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("i", $karyawan_id);
$stmt->execute();
$result = $stmt->get_result();

file_put_contents("riwayat_debug.txt", "Jumlah baris hasil: " . $result->num_rows . "\n", FILE_APPEND);
file_put_contents("riwayat_debug.txt", "Query berhasil dieksekusi\n", FILE_APPEND);

$riwayat = [];
while ($row = $result->fetch_assoc()) {
    $riwayat[] = $row;
}

file_put_contents("riwayat_debug.txt", "Total data dimasukkan ke riwayat: " . count($riwayat) . "\n", FILE_APPEND);

echo json_encode([
    "status" => "success",
    "data" => $riwayat
]);

$stmt->close();
$conn->close();
