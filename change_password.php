<?php
header("Content-Type: application/json"); // Mengatur header respons HTTP sebagai JSON, sehingga client tahu bahwa respons yang diterima adalah dalam format JSON
include 'conn.php'; // Menyertakan file yang berisi konfigurasi koneksi database. File ini biasanya berisi informasi seperti host, username, password, dan nama database

// Verifikasi token di sini (gunakan metode yang sama seperti di endpoint lainnya)
// Catatan: Implementasi verifikasi token seharusnya ada di sini untuk memastikan keamanan endpoint

$input = json_decode(file_get_contents('php://input'), true); // Membaca data JSON yang dikirim dalam body request dan mengubahnya menjadi array PHP
$user_id = isset($input['user_id']) ? $input['user_id'] : null; // Mengambil user_id dari input, jika tidak ada, set sebagai null
$current_password = isset($input['current_password']) ? $input['current_password'] : null; // Mengambil current_password dari input, jika tidak ada, set sebagai null
$new_password = isset($input['new_password']) ? $input['new_password'] : null; // Mengambil new_password dari input, jika tidak ada, set sebagai null

if ($user_id === null || $current_password === null || $new_password === null) { // Memeriksa apakah semua field yang diperlukan telah diisi
    echo json_encode(["success" => false, "message" => "All fields are required"]); // Jika ada yang kosong, kirim respons error dalam format JSON
    exit; // Hentikan eksekusi script
}

// Verifikasi password saat ini
$verify_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?"); // Menyiapkan prepared statement untuk mengambil password user berdasarkan id
$verify_stmt->bind_param("i", $user_id); // Mengikat parameter user_id ke prepared statement. 'i' menunjukkan bahwa parameter adalah integer
$verify_stmt->execute(); // Mengeksekusi prepared statement
$result = $verify_stmt->get_result(); // Mengambil hasil query

if ($result->num_rows === 1) { // Memeriksa apakah query mengembalikan tepat satu baris (user ditemukan)
    $user = $result->fetch_assoc(); // Mengambil data user sebagai array asosiatif
    if ($current_password === $user['password']) { // Membandingkan password saat ini dengan password yang tersimpan di database
        // Update password
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?"); // Menyiapkan prepared statement untuk mengupdate password
        $update_stmt->bind_param("si", $new_password, $user_id); // Mengikat parameter new_password (string) dan user_id (integer) ke prepared statement

        if ($update_stmt->execute()) { // Mengeksekusi prepared statement dan memeriksa apakah berhasil
            echo json_encode(["success" => true, "message" => "Password changed successfully"]); // Jika berhasil, kirim respons sukses
        } else {
            echo json_encode(["success" => false, "message" => "Failed to change password"]); // Jika gagal, kirim respons error
        }
        $update_stmt->close(); // Menutup prepared statement untuk update
    } else {
        echo json_encode(["success" => false, "message" => "Current password is incorrect"]); // Jika password saat ini tidak cocok, kirim respons error
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]); // Jika user tidak ditemukan, kirim respons error
}

$verify_stmt->close(); // Menutup prepared statement untuk verifikasi
$conn->close(); // Menutup koneksi database