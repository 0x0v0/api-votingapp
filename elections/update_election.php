<?php
header("Content-Type: application/json"); // Mengatur header respons untuk menentukan bahwa konten yang dikembalikan adalah dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database untuk menghubungkan script ini dengan database

$input = json_decode(file_get_contents('php://input'), true); // Membaca dan mendekode data JSON yang dikirimkan dalam body permintaan HTTP
$id = $input['id'] ?? null; // Mengambil nilai 'id' dari input JSON, jika tidak ada maka akan diatur sebagai null
$title = $input['title'] ?? null; // Mengambil nilai 'title' dari input JSON, jika tidak ada maka akan diatur sebagai null
$description = $input['description'] ?? null; // Mengambil nilai 'description' dari input JSON, jika tidak ada maka akan diatur sebagai null
$start_date = $input['start_date'] ?? null; // Mengambil nilai 'start_date' dari input JSON, jika tidak ada maka akan diatur sebagai null
$end_date = $input['end_date'] ?? null; // Mengambil nilai 'end_date' dari input JSON, jika tidak ada maka akan diatur sebagai null

if (!$id || !$title || !$start_date || !$end_date) { // Memeriksa apakah semua field yang diperlukan telah disediakan
    echo json_encode(["success" => false, "message" => "ID, title, start date, and end date are required"]); // Jika ada yang kurang, kirim respons error dalam format JSON
    exit; // Menghentikan eksekusi script jika ada data yang tidak lengkap
}

$stmt = $conn->prepare("UPDATE elections SET title = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?"); // Menyiapkan pernyataan SQL untuk memperbarui data pemilihan
$stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $id); // Mengikat parameter ke pernyataan SQL yang telah disiapkan

if ($stmt->execute()) { // Mengeksekusi pernyataan SQL yang telah disiapkan dan memeriksa hasilnya
    echo json_encode(["success" => true, "message" => "Election updated successfully"]); // Jika pembaruan berhasil, kirim respons sukses dalam format JSON
} else {
    echo json_encode(["success" => false, "message" => "Failed to update election"]); // Jika pembaruan gagal, kirim respons error dalam format JSON
}

$stmt->close(); // Menutup pernyataan yang telah disiapkan untuk membersihkan sumber daya
$conn->close(); // Menutup koneksi database untuk membersihkan sumber daya