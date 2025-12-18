<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $role      = $_POST['role'];
    $admin_key = $_POST['admin_key'] ?? '';

    // ======================
    // VALIDASI INPUT
    // ======================
    if (!$username || !$password) {
        die("Username dan password wajib diisi");
    }

    // ======================
    // VALIDASI ADMIN KEY
    // ======================
    $SECRET_ADMIN_KEY = "ADMIN123";
    if ($role === 'admin' && $admin_key !== $SECRET_ADMIN_KEY) {
        die("Admin key salah");
    }

    // ======================
    // CEK USERNAME SUDAH ADA
    // ======================
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Username sudah terdaftar. Silakan gunakan username lain.");
    }

    // ======================
    // HASH PASSWORD
    // ======================
    $hashed = password_hash($password, PASSWORD_ARGON2ID);

    // ======================
    // INSERT USER BARU
    // ======================
    $stmt = $conn->prepare(
        "INSERT INTO users (username, password, role) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $username, $hashed, $role);

    if ($stmt->execute()) {
        header("Location: index.php?register=success");
        exit;
    } else {
        die("Register gagal: " . $stmt->error);
    }
}
?>
