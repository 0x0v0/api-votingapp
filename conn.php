<?php
$servername = "localhost"; // Menentukan nama server (biasanya "localhost" untuk pengembangan lokal)
$username = "root"; // Menetapkan nama pengguna MySQL (default "root" untuk pengaturan lokal)
$password = ""; // Menetapkan kata sandi MySQL (string kosong untuk pengaturan lokal default)
$dbname = "api_voteapp_faiz"; // Menentukan nama database yang akan dihubungkan

$conn = new mysqli($servername, $username, $password, $dbname); // Membuat koneksi MySQL baru menggunakan kredensial yang ditentukan

if ($conn->connect_error) { // Memeriksa apakah ada kesalahan dalam koneksi
    die("Connection failed: " . $conn->connect_error); // Jika ada kesalahan, hentikan skrip dan tampilkan pesan kesalahan
}
