<?php
header("Content-Type: application/json"); // Mengatur header respons sebagai JSON, sehingga browser tahu bahwa respons ini berformat JSON
include 'conn.php'; // Menyertakan file koneksi database, diasumsikan berisi konfigurasi dan koneksi ke database

// Query SQL kompleks untuk mengambil data pemilihan, kandidat, dan jumlah suara
$query = "SELECT 
    e.id, 
    e.title, 
    e.start_date, 
    e.end_date,
    c.id as candidate_id, 
    c.name as candidate_name, 
    COUNT(v.id) as vote_count
FROM elections e
LEFT JOIN candidates c ON e.id = c.election_id
LEFT JOIN votes v ON c.id = v.candidate_id
GROUP BY e.id, c.id
ORDER BY e.start_date DESC, vote_count DESC";
// Query ini menggabungkan tabel elections, candidates, dan votes menggunakan LEFT JOIN
// Menghitung jumlah suara untuk setiap kandidat dengan COUNT(v.id)
// Mengelompokkan hasil berdasarkan pemilihan dan kandidat
// Mengurutkan berdasarkan tanggal mulai pemilihan (terbaru dulu) dan jumlah suara (terbanyak dulu)

$result = $conn->query($query); // Mengeksekusi query dan menyimpan hasilnya

$elections = []; // Inisialisasi array untuk menyimpan data pemilihan
if ($result->num_rows > 0) { // Jika ada hasil dari query
    while ($row = $result->fetch_assoc()) { // Loop melalui setiap baris hasil query
        $election_id = $row['id']; // Mengambil ID pemilihan dari baris saat ini
        if (!isset($elections[$election_id])) { // Jika pemilihan ini belum ada di array $elections
            // Menambahkan pemilihan baru ke array $elections
            $elections[$election_id] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'candidates' => [] // Array kosong untuk menyimpan kandidat
            ];
        }
        $candidate_id = $row['candidate_id']; // Mengambil ID kandidat dari baris saat ini
        if ($candidate_id !== null) { // Jika ada kandidat (bukan NULL)
            // Menambahkan kandidat ke array candidates dalam pemilihan yang sesuai
            $elections[$election_id]['candidates'][] = [
                'id' => $candidate_id,
                'name' => $row['candidate_name'],
                'vote_count' => $row['vote_count']
            ];
        }
    }
}

// Mengambil detail pemilih secara terpisah untuk setiap kandidat dalam setiap pemilihan
foreach ($elections as &$election) { // Loop melalui setiap pemilihan
    foreach ($election['candidates'] as &$candidate) { // Loop melalui setiap kandidat dalam pemilihan
        // Query untuk mengambil detail pemilih untuk kandidat tertentu dalam pemilihan tertentu
        $voter_query = "SELECT u.full_name, v.voted_at
                        FROM votes v
                        JOIN users u ON v.user_id = u.id
                        WHERE v.election_id = ? AND v.candidate_id = ?";
        $stmt = $conn->prepare($voter_query); // Menyiapkan statement SQL
        $stmt->bind_param("ii", $election['id'], $candidate['id']); // Mengikat parameter ke query
        $stmt->execute(); // Mengeksekusi query
        $voter_result = $stmt->get_result(); // Mendapatkan hasil query
        $candidate['voters'] = []; // Inisialisasi array untuk menyimpan data pemilih
        while ($voter = $voter_result->fetch_assoc()) { // Loop melalui setiap pemilih
            // Menambahkan data pemilih ke array voters dalam kandidat
            $candidate['voters'][] = [
                'full_name' => $voter['full_name'],
                'voted_at' => $voter['voted_at']
            ];
        }
        $stmt->close(); // Menutup prepared statement
    }
}

// Mengirim respons JSON dengan data pemilihan
echo json_encode(["success" => true, "results" => array_values($elections)]);
// array_values() digunakan untuk mengubah array asosiatif menjadi array numerik

$conn->close(); // Menutup koneksi database