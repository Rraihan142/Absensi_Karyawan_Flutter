<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include 'koneksi.php';

// Debugging log ke file untuk memastikan input masuk
file_put_contents("debug_log.txt", print_r($_POST, true));

$karyawan_id = $_POST['karyawan_id'] ?? null;
$jenis_absensi = $_POST['jenis_absensi'] ?? null;
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$lokasi = $_POST['lokasi'] ?? null;

if (!$karyawan_id || !$jenis_absensi || !$latitude || !$longitude || !$lokasi) {
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak lengkap",
        "received" => $_POST
    ]);
    exit;
}

$sql = "INSERT INTO absensi (karyawan_id, jenis_absensi, latitude, longitude, lokasi) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Prepare statement gagal: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("issdd", $karyawan_id, $jenis_absensi, $latitude, $longitude, $lokasi);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Absensi $jenis_absensi berhasil"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan absensi: " . $stmt->error]);
}

$stmt->close();
$conn->close();
