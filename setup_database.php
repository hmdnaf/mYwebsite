<?php
$host = "localhost";
$user = "root";
$pass = "";

$koneksi_server = mysqli_connect($host, $user, $pass);

if (!$koneksi_server) {
    die("Koneksi ke MySQL server gagal: " . mysqli_connect_error());
}

$nama_database = "db_travel";

$sql_cek_db = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$nama_database'";
$result_cek_db = mysqli_query($koneksi_server, $sql_cek_db);

if (mysqli_num_rows($result_cek_db) == 0) {
    $sql_create_db = "CREATE DATABASE $nama_database";
    if (mysqli_query($koneksi_server, $sql_create_db)) {
        echo "Database '$nama_database' berhasil dibuat.<br>";
    } else {
        echo "Error membuat database '$nama_database': " . mysqli_error($koneksi_server) . "<br>";
        mysqli_close($koneksi_server);
        exit();
    }
} else {
    echo "Database '$nama_database' sudah ada.<br>";
}

mysqli_close($koneksi_server);

$koneksi = mysqli_connect($host, $user, $pass, $nama_database);

if (!$koneksi) {
    die("Koneksi ke database '$nama_database' gagal: " . mysqli_connect_error());
}

$nama_tabel = "tiket";

$sql_cek_table = "SHOW TABLES LIKE '$nama_tabel'";
$result_cek_table = mysqli_query($koneksi, $sql_cek_table);

if (mysqli_num_rows($result_cek_table) == 0) {
    $sql_create_table = "
    CREATE TABLE $nama_tabel (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tujuan VARCHAR(255) NOT NULL,
        tanggal DATE NOT NULL,
        jumlah INT NOT NULL,
        harga DECIMAL(10, 2) NOT NULL
    );";

    if (mysqli_query($koneksi, $sql_create_table)) {
        echo "Tabel '$nama_tabel' berhasil dibuat dalam database '$nama_database'.<br>";
    } else {
        echo "Error membuat tabel '$nama_tabel': " . mysqli_error($koneksi) . "<br>";
    }
} else {
    echo "Tabel '$nama_tabel' sudah ada dalam database '$nama_database'.<br>";
}

mysqli_close($koneksi);
echo "Setup database selesai.";
?>