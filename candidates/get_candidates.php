<?php
header("Content-Type: application/json"); // Mengatur header respons HTTP sebagai JSON, memberitahu client bahwa respons akan dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database, biasanya berisi konfigurasi seperti host, username, password, dan nama database

$election_id = isset($_GET['election_id']) ? $_GET['election_id'] : null; // Mengambil nilai 'election_id' dari parameter GET. Jika tidak ada, set sebagai null

if ($election_id === null) { // Memeriksa apakah 'election_id' telah disediakan
    // Jika election_id tidak disediakan, siapkan query untuk mengambil semua kandidat
    $stmt = $conn->prepare("SELECT id, name, description, image_url, election_id FROM candidates");
} else {
    // Jika election_id disediakan, siapkan query untuk mengambil kandidat untuk pemilihan tersebut
    $stmt = $conn->prepare("SELECT id, name, description, image_url, election_id FROM candidates WHERE election_id = ?");
    $stmt->bind_param("i", $election_id); // Mengikat parameter 'election_id' ke prepared statement. 'i' menunjukkan bahwa parameter adalah integer
}

$stmt->execute(); // Mengeksekusi prepared statement
$result = $stmt->get_result(); // Mendapatkan hasil query

$candidates = []; // Inisialisasi array kosong untuk menyimpan data kandidat
if ($result->num_rows > 0) { // Memeriksa apakah ada baris hasil yang dikembalikan
    while ($row = $result->fetch_assoc()) { // Loop melalui setiap baris hasil
        $candidates[] = $row; // Menambahkan setiap baris (data kandidat) ke array $candidates
    }
    echo json_encode(["success" => true, "candidates" => $candidates]); // Mengirim respons sukses dengan data kandidat dalam format JSON
} else {
    echo json_encode(["success" => false, "message" => "No candidates found"]); // Jika tidak ada kandidat ditemukan, kirim respons error
}

$stmt->close(); // Menutup prepared statement untuk membersihkan resources
$conn->close(); // Menutup koneksi database untuk membersihkan resources