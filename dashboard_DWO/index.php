<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="container">

    <!-- LOGIN -->
    <div id="login-form">
        <h2>Login</h2>
        <input type="text" id="login-username" placeholder="Username">
        <input type="password" id="login-password" placeholder="Password">
        <button onclick="login()">Login</button>
        <p>Belum punya akun? <a href="#" onclick="showRegister()">Daftar</a></p>
    </div>

    <!-- REGISTER -->
    <div id="register-form" style="display:none;">
        <h2>Register</h2>

        <input type="text" id="register-username" placeholder="Username">
        <input type="password" id="register-password" placeholder="Password">

        <!-- LEVEL -->
        <select id="register-role">
            <option value="eksekutif">Eksekutif</option>
            <option value="admin">Admin</option>
        </select>

        <!-- ADMIN KEY -->
        <input type="password" id="register-admin-key"
               placeholder="Admin Key (khusus admin)">

        <button onclick="register()">Register</button>

        <p>Sudah punya akun? <a href="#" onclick="showLogin()">Login</a></p>
    </div>

</div>

<script src="script.js"></script>
</body>
</html>
