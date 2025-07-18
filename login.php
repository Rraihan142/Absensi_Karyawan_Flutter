<?php

include 'koneksi.php';
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT id, nama, password FROM karyawan WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        echo json_encode(["status" => "success", "message" => "Login berhasil", "user" => ["id" => $row['id'], "nama" => $row['nama']]]);
    } else {
        echo json_encode(["status" => "error", "message" => "Password salah"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email tidak terdaftar"]);
}

$stmt->close();
$conn->close();
?>