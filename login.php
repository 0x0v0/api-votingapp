<?php
header("Content-Type: application/json"); // Mengatur header respons sebagai JSON
include 'conn.php'; // Menyertakan file koneksi database

$input = json_decode(file_get_contents('php://input'), true); // Mengambil dan mendekode data JSON yang dikirim
$username = isset($input['username']) ? $input['username'] : null; // Mengambil username dari input, jika ada
$password = isset($input['password']) ? $input['password'] : null; // Mengambil password dari input, jika ada

if ($username === null || $password === null) { // Memeriksa apakah username dan password disediakan
    echo json_encode(["success" => false, "message" => "Username and password are required"]); // Mengirim pesan error jika tidak lengkap
    exit; // Menghentikan eksekusi script
}

$stmt = $conn->prepare("SELECT id, password, level FROM users WHERE username = ?"); // Menyiapkan statement SQL
$stmt->bind_param("s", $username); // Mengikat parameter username ke statement
$stmt->execute(); // Mengeksekusi statement
$result = $stmt->get_result(); // Mendapatkan hasil query

if ($result->num_rows === 1) { // Memeriksa apakah ditemukan satu baris hasil
    $user = $result->fetch_assoc(); // Mengambil data user
    if ($password === $user['password']) { // Memeriksa apakah password sesuai
        $token = bin2hex(random_bytes(16)); // Membuat token acak
        echo json_encode([ // Mengirim respons sukses dengan data user
            "success" => true,
            "token" => $token,
            "user_id" => $user['id'],
            "username" => $username,
            "user_level" => $user['level']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]); // Mengirim pesan error jika password salah
    }
} else {
    echo json_encode(["success" => false, "message" => "Username dan Password Salah!"]); // Mengirim pesan error jika username tidak ditemukan
}

$stmt->close(); // Menutup statement
$conn->close(); // Menutup koneksi database