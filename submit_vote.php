<?php
header("Content-Type: application/json"); // Mengatur header respons sebagai JSON
include 'conn.php'; // Menyertakan file koneksi database

$input = json_decode(file_get_contents('php://input'), true); // Mengambil dan mendekode data JSON yang dikirim
$user_id = isset($input['user_id']) ? $input['user_id'] : null; // Mengambil user_id dari input, jika ada
$election_id = isset($input['election_id']) ? $input['election_id'] : null; // Mengambil election_id dari input, jika ada
$candidate_id = isset($input['candidate_id']) ? $input['candidate_id'] : null; // Mengambil candidate_id dari input, jika ada

if ($user_id === null || $election_id === null || $candidate_id === null) { // Memeriksa apakah semua data yang diperlukan tersedia
    echo json_encode(["success" => false, "message" => "User ID, Election ID, and Candidate ID are required"]); // Mengirim pesan error jika ada data yang kurang
    exit; // Menghentikan eksekusi script
}

$check_stmt = $conn->prepare("SELECT id FROM votes WHERE user_id = ? AND election_id = ?"); // Menyiapkan query untuk memeriksa apakah user sudah memilih
$check_stmt->bind_param("ii", $user_id, $election_id); // Mengikat parameter ke query
$check_stmt->execute(); // Mengeksekusi query
$check_result = $check_stmt->get_result(); // Mendapatkan hasil query

if ($check_result->num_rows > 0) { // Jika user sudah memilih dalam pemilihan ini
    echo json_encode(["success" => false, "message" => "You have already voted in this election"]); // Mengirim pesan bahwa user sudah memilih
} else { // Jika user belum memilih
    $insert_stmt = $conn->prepare("INSERT INTO votes (user_id, election_id, candidate_id) VALUES (?, ?, ?)"); // Menyiapkan query untuk memasukkan suara
    $insert_stmt->bind_param("iii", $user_id, $election_id, $candidate_id); // Mengikat parameter ke query
    if ($insert_stmt->execute()) { // Jika query berhasil dieksekusi
        echo json_encode(["success" => true, "message" => "Vote submitted successfully"]); // Mengirim pesan sukses
    } else { // Jika query gagal dieksekusi
        echo json_encode(["success" => false, "message" => "Failed to submit vote"]); // Mengirim pesan error
    }
    $insert_stmt->close(); // Menutup statement insert
}

$check_stmt->close(); // Menutup statement pengecekan
$conn->close(); // Menutup koneksi database