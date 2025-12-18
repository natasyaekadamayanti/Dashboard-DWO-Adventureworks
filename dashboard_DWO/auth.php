<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, username, password, role FROM users WHERE username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // ⬇️ AMBIL DATA SATU KALI SAJA
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        // SET SESSION
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // REDIRECT SESUAI ROLE
        if ($user['role'] === 'admin') {
            header("Location: dashboardadmin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    }

    // JIKA GAGAL
    header("Location: index.php");
    exit;
}
?>
