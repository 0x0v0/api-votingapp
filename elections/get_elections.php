<?php
header("Content-Type: application/json"); // Mengatur header respons untuk menentukan bahwa konten yang dikembalikan adalah dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database untuk menghubungkan script ini dengan database

$query = "SELECT id, title, description, start_date, end_date, 
           CASE WHEN end_date >= CURDATE() THEN 'active' ELSE 'ended' END AS status 
           FROM elections 
           ORDER BY start_date DESC"; // Membuat query SQL untuk memilih data pemilihan, termasuk status yang dihitung berdasarkan tanggal akhir, diurutkan berdasarkan tanggal mulai secara descending
$stmt = $conn->prepare($query); // Menyiapkan statement SQL untuk dieksekusi
$stmt->execute(); // Mengeksekusi statement SQL yang telah disiapkan
$result = $stmt->get_result(); // Mendapatkan hasil dari eksekusi query

$elections = []; // Membuat array kosong untuk menyimpan data pemilihan
if ($result->num_rows > 0) { // Memeriksa apakah ada baris hasil yang dikembalikan dari query (apakah ada pemilihan yang ditemukan)
    while ($row = $result->fetch_assoc()) { // Melakukan loop untuk setiap baris hasil query
        $elections[] = $row; // Menambahkan setiap baris (data pemilihan) ke dalam array $elections
    }
    echo json_encode(["success" => true, "elections" => $elections]); // Jika ada pemilihan, kirim respons sukses dalam format JSON beserta data pemilihan
} else {
    echo json_encode(["success" => false, "message" => "No active elections found"]); // Jika tidak ada pemilihan ditemukan, kirim respons dalam format JSON yang menunjukkan bahwa tidak ada pemilihan aktif
}

$stmt->close(); // Menutup prepared statement untuk membersihkan sumber daya
$conn->close(); // Menutup koneksi database untuk membersihkan sumber daya dan mengakhiri script