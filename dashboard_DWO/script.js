// ==========================
// TOGGLE FORM
// ==========================
function showRegister() {
    document.getElementById("login-form").style.display = "none";
    document.getElementById("register-form").style.display = "block";
}

function showLogin() {
    document.getElementById("register-form").style.display = "none";
    document.getElementById("login-form").style.display = "block";
}

// ==========================
// LOGIN → auth.php
// (ROLE DIATUR DI SERVER)
// ==========================
function login() {
    const username = document.getElementById("login-username").value;
    const password = document.getElementById("login-password").value;

    if (!username || !password) {
        alert("Username dan Password harus diisi!");
        return;
    }

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "auth.php";

    const u = document.createElement("input");
    u.type = "hidden";
    u.name = "username";
    u.value = username;

    const p = document.createElement("input");
    p.type = "hidden";
    p.name = "password";
    p.value = password;

    form.appendChild(u);
    form.appendChild(p);
    document.body.appendChild(form);
    form.submit();
}

// ==========================
// REGISTER → register.php
// (KIRIM ROLE + ADMIN KEY)
// ==========================
function register() {
    const username = document.getElementById("register-username").value;
    const password = document.getElementById("register-password").value;
    const role     = document.getElementById("register-role").value;
    const adminKey = document.getElementById("register-admin-key").value;

    if (!username || !password || !role) {
        alert("Semua field wajib diisi!");
        return;
    }

    // kalau pilih admin tapi admin key kosong
    if (role === "admin" && adminKey === "") {
        alert("Admin wajib mengisi Admin Key!");
        return;
    }

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "register.php";

    const fields = {
        username: username,
        password: password,
        role: role,
        admin_key: adminKey
    };

    for (let key in fields) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = key;
        input.value = fields[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

