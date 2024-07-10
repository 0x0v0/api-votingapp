<?php
header("Content-Type: application/json"); // Mengatur header respons untuk menentukan bahwa konten yang dikembalikan adalah dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database untuk menghubungkan script ini dengan database

$query = "SELECT id, title FROM elections ORDER BY start_date DESC"; // Membuat query SQL untuk memilih id dan judul semua pemilihan, diurutkan berdasarkan tanggal mulai secara descending (terbaru dulu)
$result = $conn->query($query); // Mengeksekusi query SQL dan menyimpan hasilnya dalam variabel $result

$elections = []; // Membuat array kosong untuk menyimpan data pemilihan
if ($result->num_rows > 0) { // Memeriksa apakah ada baris hasil yang dikembalikan dari query (apakah ada pemilihan yang ditemukan)
    while ($row = $result->fetch_assoc()) { // Melakukan loop untuk setiap baris hasil query
        $elections[] = $row; // Menambahkan setiap baris (data pemilihan) ke dalam array $elections
    }
    echo json_encode(["success" => true, "elections" => $elections]); // Jika ada pemilihan, kirim respons sukses dalam format JSON beserta data pemilihan
} else {
    echo json_encode(["success" => false, "message" => "No elections found"]); // Jika tidak ada pemilihan ditemukan, kirim respons dalam format JSON yang menunjukkan bahwa tidak ada pemilihan
}

$conn->close(); // Menutup koneksi database untuk membersihkan sumber daya dan mengakhiri script