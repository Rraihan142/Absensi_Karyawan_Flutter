<?php

include 'koneksi.php';
if (!isset($_POST['nama']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap. Pastikan nama, email, dan password terisi."]);

    exit();
}

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO karyawan (nama, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Tambahkan pengecekan ini: apakah prepare berhasil?
if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Gagal menyiapkan statement: " . $conn->error]);
    $conn->close();
    exit();
}

$stmt->bind_param("sss", $nama, $email, $password);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registrasi berhasil"]);
} else {
    // Cek kode error spesifik untuk duplikat entri (error code 1062)
    // Gunakan $conn->errno atau $stmt->errno
    if ($conn->errno == 1062) {
        echo json_encode(["status" => "error", "message" => "Email ini sudah terdaftar. Silakan gunakan email lain."]);
    } else {
        // Untuk semua jenis error lainnya, tampilkan pesan error dari database
        echo json_encode(["status" => "error", "message" => "Registrasi gagal karena kesalahan server: " . $stmt->error . " (Error Code: " . $stmt->errno . ")"]);
    }
}

$stmt->close();
$conn->close();
?>