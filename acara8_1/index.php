<?php
// Konfigurasi koneksi MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acara8";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Memproses penghapusan jika tombol hapus ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $kecamatan = $_POST['kecamatan']; // Ambil nama kecamatan dari form
    $delete_sql = "DELETE FROM jumlah_penduduk WHERE kecamatan = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("s", $kecamatan); // Bind parameter
    $stmt->execute(); // Eksekusi query
    $stmt->close(); // Tutup statement
}

// Query untuk mengambil data dari tabel 'penduduk'
$sql = "SELECT kecamatan, longitude, latitude, luas, jumlah_penduduk FROM jumlah_penduduk";
$result = $conn->query($sql);

// Memeriksa apakah ada hasil yang dikembalikan
if ($result->num_rows > 0) {
    // Membuat header tabel dengan style CSS untuk nuansa gelap dan merah
    echo "<style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #1c1c1c; /* Latar belakang gelap */
                color: #f2f2f2; /* Teks putih */
                padding: 20px; /* Padding untuk body */
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px; /* Jarak atas tabel */
                font-size: 1.2em; /* Ukuran font lebih besar */
            }
            table, th, td {
                border: 1px solid #ff0000; /* Garis tepi merah */
            }
            th {
                background-color: #ff0000; /* Latar belakang merah untuk header */
                color: white; /* Teks putih */
                padding: 10px; /* Padding dalam header */
                text-align: left;
            }
            td {
                padding: 10px; /* Padding dalam cell */
                text-align: left;
                background-color: #444444; /* Latar belakang cell gelap */
                color: #f2f2f2; /* Teks putih */
            }
            tr:nth-child(even) {
                background-color: #333333; /* Latar belakang baris genap lebih gelap */
            }
            tr:hover {
                background-color: #cc0000; /* Warna saat hover */
            }
        </style>";

    echo "<table>
            <tr>
                <th>Kecamatan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Luas</th>
                <th>Jumlah Penduduk</th>
                <th>Aksi</th> <!-- Kolom Aksi untuk tombol hapus -->
            </tr>";

    // Output data setiap baris
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["kecamatan"]) . "</td>
                <td>" . htmlspecialchars($row["longitude"]) . "</td>
                <td>" . htmlspecialchars($row["latitude"]) . "</td>
                <td>" . htmlspecialchars($row["luas"]) . "</td>
                <td align='right'>" . htmlspecialchars($row["jumlah_penduduk"]) . "</td>
                <td>
                    <form action='' method='post' style='display:inline;'> <!-- Form untuk hapus -->
                        <input type='hidden' name='kecamatan' value='" . htmlspecialchars($row["kecamatan"]) . "'>
                        <input type='submit' name='delete' value='Hapus' style='background-color: #ff0000; color: white; border: none; padding: 5px 10px; cursor: pointer;'>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}

// Menutup koneksi
$conn->close();
?>
