<?php
header("Content-Type: application/json"); // Mengatur header respons sebagai JSON

include 'conn.php'; // Menyertakan file koneksi database

// Mengambil jumlah pemilihan aktif
$elections_query = "SELECT COUNT(*) as active_elections FROM elections WHERE end_date >= CURDATE()"; // Query untuk menghitung pemilihan aktif
$elections_result = $conn->query($elections_query); // Mengeksekusi query
$active_elections = $elections_result->fetch_assoc()['active_elections']; // Mengambil jumlah pemilihan aktif

// Mengambil jumlah total kandidat
$candidates_query = "SELECT COUNT(*) as total_candidates FROM candidates"; // Query untuk menghitung total kandidat
$candidates_result = $conn->query($candidates_query); // Mengeksekusi query
$total_candidates = $candidates_result->fetch_assoc()['total_candidates']; // Mengambil jumlah total kandidat

// Mengambil jumlah total suara
$votes_query = "SELECT COUNT(*) as total_votes FROM votes"; // Query untuk menghitung total suara
$votes_result = $conn->query($votes_query); // Mengeksekusi query
$total_votes = $votes_result->fetch_assoc()['total_votes']; // Mengambil jumlah total suara

$dashboard_data = [ // Menyiapkan data dashboard dalam bentuk array
    "active_elections" => $active_elections, // Untuk menampilkan pemilihan yang aktif
    "total_candidates" => $total_candidates, // Menampilkan total seluruh kandidat
    "total_votes" => $total_votes // Menampilkan keseluruhan total votes
];

echo json_encode(["success" => true, "data" => $dashboard_data]); // Mengirim respons JSON dengan data dashboard

$conn->close(); // Menutup koneksi database