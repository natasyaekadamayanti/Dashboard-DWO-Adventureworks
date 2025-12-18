<?php
session_start();          // ← INI YANG HILANG
include 'koneksi.php';

// PROTEKSI LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// PROTEKSI ROLE (HANYA EKSEKUTIF)
if ($_SESSION['role'] !== 'eksekutif') {
    header("Location: dashboardadmin.php");
    exit;
}


// Query untuk mengambil total penjualan dari dw_sales
$totalPenjualanData = $conn_sales->query("SELECT SUM(SalesAmount) AS total FROM fact_sales")->fetch_assoc();
$totalPenjualan = $totalPenjualanData ? $totalPenjualanData['total'] : 0;

// Query untuk mengambil total pembelian dari dw_purchasing
$totalPembelianData = $conn_purchasing->query("SELECT SUM(TotalCost) AS total FROM fact_purchasing")->fetch_assoc();
$totalPembelian = $totalPembelianData ? $totalPembelianData['total'] : 0;

// Ambil data lainnya seperti jumlah vendor, produk, dll.
$jumlahVendorData = $conn_purchasing->query("SELECT COUNT(*) AS total FROM dimsupplier")->fetch_assoc();
$jumlahVendor = $jumlahVendorData ? $jumlahVendorData['total'] : 0;

$jumlahProdukData = $conn_sales->query("SELECT COUNT(*) AS total FROM dimproduct")->fetch_assoc();
$jumlahProduk = $jumlahProdukData ? $jumlahProdukData['total'] : 0;

// Rata-rata per tahun
$rataPenjualanTahun = ($totalPenjualan > 0) ? $totalPenjualan / 12 : 0;
$rataPembelianTahun = ($totalPembelian > 0) ? $totalPembelian / 12 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />



    <body class="dashboard-body">
    <!-- Sidebar -->
    <div class="sidebar">
    <h2>ADVENTUREWORKS</h2>
    <ul>
    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="salesterritory.php"><i class="fas fa-chart-area"></i> Sales Territory</a></li>
            <li><a href="salesorder.php"><i class="fas fa-chart-line"></i> Sales Order</a></li>
            <li><a href="salesproduk.php"><i class="fas fa-chart-pie"></i> Sales Product</a></li>
            <li><a href="purchasetotal.php"><i class="fas fa-shopping-cart"></i>Purchasing</a></li>
            <li><a href="purchaseproduct.php"><i class="fas fa-box"></i>Product Stock</a></li>
        <li><a href="olap.php"><i class="fab fa-dropbox"></i> OLAP</a></li>
        </ul>
    <div class="logout">
    <a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
</div>

    <!-- Main Content -->

    <div class="content">
        <h2>Dashboard</h2>

        <!-- Dashboard Section -->
        <div class="dashboard">
            <div class="card">
                <h3>Total Pembelian</h3>
                <p>$<?php echo number_format($totalPembelian, 2); ?></p>
                
            </div>
            <div class="card">
                <h3>Total Penjualan</h3>
                <p>$<?php echo number_format($totalPenjualan, 2); ?></p>
                
            </div>
            <div class="card">
                <h3>Jumlah Vendor</h3>
                <p><?php echo $jumlahVendor; ?></p>
                
            </div>
            <div class="card">
                <h3>Jumlah Produk Terjual</h3>
                <p><?php echo $jumlahProduk; ?></p>
                
            </div>
            <div class="card">
                <h3>Rata-Rata Pembelian per Tahun</h3>
                <p>$<?php echo number_format($rataPembelianTahun, 2); ?></p>
                
            </div>
            <div class="card">
                <h3>Rata-Rata Penjualan per Tahun</h3>
                <p>$<?php echo number_format($rataPenjualanTahun, 2); ?></p>
                
            </div>
        </div>
    </div>

    <footer>
        <p>Copyright &copy; Kelompok 5 2025</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
