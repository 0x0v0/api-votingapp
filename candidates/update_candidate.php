<?php
header("Content-Type: application/json"); // Mengatur tipe konten respons menjadi JSON
include '../conn.php'; // Menyertakan file koneksi database

$input = json_decode(file_get_contents('php://input'), true); // Mengurai input JSON dari body permintaan
$id = $input['id'] ?? null; // Mengambil 'id' dari input atau menetapkan null jika tidak ada
$name = $input['name'] ?? null; // Mengambil 'name' dari input atau menetapkan null jika tidak ada
$description = $input['description'] ?? null; // Mengambil 'description' dari input atau menetapkan null jika tidak ada
$image_url = $input['image_url'] ?? null; // Mengambil 'image_url' dari input atau menetapkan null jika tidak ada

if (!$id || !$name) { // Memeriksa apakah 'id' dan 'name' telah disediakan
    echo json_encode(["success" => false, "message" => "ID and name are required"]); // Mengembalikan pesan error jika 'id' atau 'name' tidak ada
    exit; // Menghentikan eksekusi script
}

$stmt = $conn->prepare("UPDATE candidates SET name = ?, description = ?, image_url = ? WHERE id = ?"); // Menyiapkan pernyataan SQL untuk memperbarui kandidat
$stmt->bind_param("sssi", $name, $description, $image_url, $id); // Mengikat parameter ke pernyataan yang disiapkan

if ($stmt->execute()) { // Mengeksekusi pernyataan yang disiapkan
    echo json_encode(["success" => true, "message" => "Candidate updated successfully"]); // Mengembalikan pesan sukses jika pembaruan berhasil
} else {
    echo json_encode(["success" => false, "message" => "Failed to update candidate"]); // Mengembalikan pesan error jika pembaruan gagal
}

$stmt->close(); // Menutup pernyataan yang disiapkan
$conn->close(); // Menutup koneksi database