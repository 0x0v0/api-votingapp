<?php
header("Content-Type: application/json"); // Mengatur header respons untuk menentukan bahwa konten yang dikembalikan adalah dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database untuk menghubungkan script ini dengan database

$input = json_decode(file_get_contents('php://input'), true); // Membaca dan mendekode data JSON yang dikirimkan dalam body permintaan HTTP
$id = $input['id'] ?? null; // Mengambil nilai 'id' dari input JSON, jika tidak ada maka akan diatur sebagai null

if (!$id) { // Memeriksa apakah 'id' telah disediakan (tidak null atau kosong)
    echo json_encode(["success" => false, "message" => "Election ID is required"]); // Jika 'id' tidak ada, kirim respons error dalam format JSON
    exit; // Menghentikan eksekusi script jika 'id' tidak disediakan
}

$stmt = $conn->prepare("DELETE FROM elections WHERE id = ?"); // Menyiapkan pernyataan SQL untuk menghapus pemilihan berdasarkan ID
$stmt->bind_param("i", $id); // Mengikat parameter 'id' ke pernyataan SQL yang telah disiapkan ('i' menunjukkan bahwa parameternya adalah integer)

if ($stmt->execute()) { // Mengeksekusi pernyataan SQL yang telah disiapkan dan memeriksa hasilnya
    echo json_encode(["success" => true, "message" => "Election deleted successfully"]); // Jika penghapusan berhasil, kirim respons sukses dalam format JSON
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete election"]); // Jika penghapusan gagal, kirim respons error dalam format JSON
}

$stmt->close(); // Menutup pernyataan yang telah disiapkan untuk membersihkan sumber daya
$conn->close(); // Menutup koneksi database untuk membersihkan sumber daya