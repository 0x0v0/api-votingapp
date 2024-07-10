<?php
header("Content-Type: application/json"); // Mengatur header respons HTTP sebagai JSON, memberitahu client bahwa respons akan dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database, biasanya berisi konfigurasi seperti host, username, password, dan nama database

$input = json_decode(file_get_contents('php://input'), true); // Membaca data JSON dari body request HTTP, mengubahnya menjadi array asosiatif PHP
$id = $input['id'] ?? null; // Mengambil nilai 'id' dari input. Jika tidak ada, set sebagai null. Operator ?? adalah null coalescing operator

if (!$id) { // Memeriksa apakah 'id' telah disediakan, karena ini adalah field wajib
    echo json_encode(["success" => false, "message" => "Candidate ID is required"]); // Jika 'id' tidak ada, kirim respons error dalam format JSON
    exit; // Menghentikan eksekusi script
}

$stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?"); // Menyiapkan prepared statement SQL untuk menghapus kandidat berdasarkan ID. Tanda tanya (?) adalah placeholder untuk nilai yang akan dimasukkan
$stmt->bind_param("i", $id); // Mengikat parameter 'id' ke prepared statement. 'i' menunjukkan bahwa parameter adalah integer

if ($stmt->execute()) { // Mengeksekusi prepared statement dan memeriksa apakah berhasil
    echo json_encode(["success" => true, "message" => "Candidate deleted successfully"]); // Jika berhasil menghapus, kirim respons sukses dalam format JSON
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete candidate"]); // Jika gagal menghapus, kirim respons error dalam format JSON
}

$stmt->close(); // Menutup prepared statement untuk membersihkan resources
$conn->close(); // Menutup koneksi database untuk membersihkan resources