<?php
$host = 'localhost';
$username = 'root';
$password = '';

// ===============================
// DATABASE AUTH (LOGIN / USER)
// ===============================
$db_auth = 'dashboarddwo'; // ← DB yang ada tabel users
$conn = new mysqli($host, $username, $password, $db_auth);
if ($conn->connect_error) {
    die("Koneksi gagal ke DB AUTH: " . $conn->connect_error);
}

// ===============================
// DATABASE DATA WAREHOUSE
// ===============================
$db_sales = 'dw_sales';
$db_purchasing = 'dw_purchasing';

$conn_sales = new mysqli($host, $username, $password, $db_sales);
if ($conn_sales->connect_error) {
    die("Koneksi gagal ke dw_sales: " . $conn_sales->connect_error);
}

$conn_purchasing = new mysqli($host, $username, $password, $db_purchasing);
if ($conn_purchasing->connect_error) {
    die("Koneksi gagal ke dw_purchasing: " . $conn_purchasing->connect_error);
}
?>
