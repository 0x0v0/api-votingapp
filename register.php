<?php
header("Content-Type: application/json"); // Mengatur header respons sebagai JSON
include 'conn.php'; // Menyertakan file koneksi database

$input = json_decode(file_get_contents('php://input'), true); // Mengambil dan mendekode data JSON yang dikirim

$username = $input['username'] ?? null; // Mengambil username dari input, jika tidak ada set null
$password = $input['password'] ?? null; // Mengambil password dari input, jika tidak ada set null
$email = $input['email'] ?? null; // Mengambil email dari input, jika tidak ada set null
$full_name = $input['full_name'] ?? null; // Mengambil nama lengkap dari input, jika tidak ada set null

if (!$username || !$password || !$email || !$full_name) { // Memeriksa apakah semua field terisi
    echo json_encode(["success" => false, "message" => "All fields are required"]); // Mengirim pesan error jika ada field yang kosong
    exit; // Menghentikan eksekusi script
}

// Memeriksa apakah username sudah ada
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?"); // Menyiapkan query untuk cek username
$stmt->bind_param("s", $username); // Mengikat parameter username ke query
$stmt->execute(); // Mengeksekusi query
$result = $stmt->get_result(); // Mendapatkan hasil query
if ($result->num_rows > 0) { // Jika username sudah ada
    echo json_encode(["success" => false, "message" => "Username already exists"]); // Mengirim pesan error
    $stmt->close(); // Menutup statement
    exit; // Menghentikan eksekusi script
}
$stmt->close(); // Menutup statement

// Memeriksa apakah email sudah ada
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?"); // Menyiapkan query untuk cek email
$stmt->bind_param("s", $email); // Mengikat parameter email ke query
$stmt->execute(); // Mengeksekusi query
$result = $stmt->get_result(); // Mendapatkan hasil query
if ($result->num_rows > 0) { // Jika email sudah ada
    echo json_encode(["success" => false, "message" => "Email already exists"]); // Mengirim pesan error
    $stmt->close(); // Menutup statement
    exit; // Menghentikan eksekusi script
}
$stmt->close(); // Menutup statement

// Memasukkan user baru
$stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, level) VALUES (?, ?, ?, ?, 2)"); // Menyiapkan query untuk insert user baru
$stmt->bind_param("ssss", $username, $password, $email, $full_name); // Mengikat parameter ke query

if ($stmt->execute()) { // Jika eksekusi query berhasil
    echo json_encode(["success" => true, "message" => "User registered successfully"]); // Mengirim pesan sukses
} else { // Jika eksekusi query gagal
    echo json_encode(["success" => false, "message" => "Failed to register user"]); // Mengirim pesan error
}

$stmt->close(); // Menutup statement
$conn->close(); // Menutup koneksi database