<?php
header("Content-Type: application/json"); // Mengatur header respons untuk menentukan bahwa konten yang dikembalikan adalah dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database untuk menghubungkan script ini dengan database

$query = "SELECT id, title, description, start_date, end_date FROM elections WHERE end_date >= CURDATE() ORDER BY start_date"; // Membuat query SQL untuk memilih pemilihan yang aktif (tanggal akhir belum lewat) dan mengurutkannya berdasarkan tanggal mulai
$result = $conn->query($query); // Mengeksekusi query SQL dan menyimpan hasilnya

$elections = []; // Membuat array kosong untuk menyimpan data pemilihan
if ($result->num_rows > 0) { // Memeriksa apakah ada baris hasil yang dikembalikan dari query
    while ($row = $result->fetch_assoc()) { // Melakukan loop untuk setiap baris hasil query
        $elections[] = $row; // Menambahkan setiap baris (data pemilihan) ke dalam array elections
    }
    echo json_encode(["success" => true, "elections" => $elections]); // Jika ada pemilihan aktif, kirim respons sukses dalam format JSON beserta data pemilihan
} else {
    echo json_encode(["success" => false, "message" => "No active elections found"]); // Jika tidak ada pemilihan aktif, kirim respons dalam format JSON yang menunjukkan bahwa tidak ada pemilihan aktif
}

$conn->close(); // Menutup koneksi database untuk membersihkan sumber daya