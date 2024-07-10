<?php
header("Content-Type: application/json"); // Mengatur header respons sebagai JSON untuk memberi tahu client bahwa respons akan dalam format JSON
include '../conn.php'; // Menyertakan file koneksi database, biasanya berisi konfigurasi seperti host, username, password, dan nama database

$input = json_decode(file_get_contents('php://input'), true); // Membaca dan mendekode data JSON yang dikirim dalam body request HTTP, mengubahnya menjadi array asosiatif PHP
$name = $input['name'] ?? null; // Mengambil nilai 'name' dari input. Jika tidak ada, set sebagai null. Operator ?? adalah null coalescing operator
$description = $input['description'] ?? null; // Mengambil nilai 'description' dari input. Jika tidak ada, set sebagai null
$image_url = $input['image_url'] ?? null; // Mengambil nilai 'image_url' dari input. Jika tidak ada, set sebagai null
$election_id = $input['election_id'] ?? null; // Mengambil nilai 'election_id' dari input. Jika tidak ada, set sebagai null

if (!$name || !$election_id) { // Memeriksa apakah name dan election_id telah disediakan, keduanya wajib diisi
    echo json_encode(["success" => false, "message" => "Name and election_id are required"]); // Jika salah satu kosong, kirim respons error dalam format JSON
    exit; // Hentikan eksekusi script
}

$stmt = $conn->prepare("INSERT INTO candidates (name, description, image_url, election_id) VALUES (?, ?, ?, ?)"); // Menyiapkan prepared statement SQL untuk menyisipkan data kandidat baru. Tanda tanya (?) adalah placeholder untuk nilai yang akan dimasukkan
$stmt->bind_param("sssi", $name, $description, $image_url, $election_id); // Mengikat parameter ke prepared statement. 'sssi' menunjukkan tipe data: string, string, string, integer

if ($stmt->execute()) { // Mengeksekusi prepared statement dan memeriksa apakah berhasil
    echo json_encode(["success" => true, "message" => "Candidate added successfully"]); // Jika berhasil, kirim respons sukses dalam format JSON
} else {
    echo json_encode(["success" => false, "message" => "Failed to add candidate"]); // Jika gagal, kirim respons error dalam format JSON
}

$stmt->close(); // Menutup prepared statement untuk membersihkan resources
$conn->close(); // Menutup koneksi database untuk membersihkan resources