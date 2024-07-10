<?php
header("Content-Type: application/json"); // Mengatur tipe konten respons menjadi JSON untuk memastikan output yang dikirim ke client adalah dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database untuk menghubungkan script ini dengan database

$input = json_decode(file_get_contents('php://input'), true); // Mengambil dan mengurai data JSON yang dikirim melalui body request
$title = $input['title'] ?? null; // Mengambil nilai 'title' dari input JSON, jika tidak ada maka diisi dengan null
$description = $input['description'] ?? null; // Mengambil nilai 'description' dari input JSON, jika tidak ada maka diisi dengan null
$start_date = $input['start_date'] ?? null; // Mengambil nilai 'start_date' dari input JSON, jika tidak ada maka diisi dengan null
$end_date = $input['end_date'] ?? null; // Mengambil nilai 'end_date' dari input JSON, jika tidak ada maka diisi dengan null

if (!$title || !$start_date || !$end_date) { // Memeriksa apakah title, start_date, dan end_date telah diisi (tidak null)
    echo json_encode(["success" => false, "message" => "Title, start date, and end date are required"]); // Jika ada yang kosong, kirim respons error dalam format JSON
    exit; // Hentikan eksekusi script jika ada data yang tidak lengkap
}

$stmt = $conn->prepare("INSERT INTO elections (title, description, start_date, end_date) VALUES (?, ?, ?, ?)"); // Menyiapkan query SQL untuk memasukkan data pemilihan baru ke dalam tabel elections
$stmt->bind_param("ssss", $title, $description, $start_date, $end_date); // Mengikat parameter ke prepared statement untuk mencegah SQL injection

if ($stmt->execute()) { // Mengeksekusi prepared statement dan memeriksa apakah berhasil
    echo json_encode(["success" => true, "message" => "Election added successfully"]); // Jika berhasil, kirim respons sukses dalam format JSON
} else {
    echo json_encode(["success" => false, "message" => "Failed to add election"]); // Jika gagal, kirim respons error dalam format JSON
}

$stmt->close(); // Menutup prepared statement untuk membersihkan resources
$conn->close(); // Menutup koneksi database untuk membersihkan resources