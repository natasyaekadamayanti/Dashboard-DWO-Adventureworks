<?php
// =======================
// KONEKSI DATABASE
// =======================
$host = 'localhost';
$username = 'root';
$password = '';
$db_sales = 'dw_sales';

$mysqli = new mysqli($host, $username, $password, $db_sales);
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// =======================
// QUERY TOTAL PRODUK TERJUAL PER KATEGORI
// =======================
$sql = "
    SELECT dp.ProductCategory AS Category,
           COUNT(fs.ProductID) AS TotalSold
    FROM fact_sales fs
    JOIN dimproduct dp ON fs.ProductID = dp.ProductID
    GROUP BY dp.ProductCategory
    ORDER BY TotalSold DESC
";
$result = $mysqli->query($sql);

// =======================
// DATA CARD & CHART
// =======================
$categories = [];
$salesData = [];
$cards = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['Category'];
    $salesData[] = (int)$row['TotalSold'];
    $cards[] = $row;
}

$categories_json = json_encode($categories);
$data_json = json_encode($salesData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Total Produk Terjual per Product Category</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>

<style>
/* ================= GLOBAL ================= */
body {
    margin: 0;
    background: #f3f0ff;
    font-family: Arial, sans-serif;
}

/* ================= SIDEBAR FIX ================= */
.sidebar {
    width: 230px;
    height: 100vh;
    background: #f00797ec;
    position: fixed;
    top: 0;
    left: 0;
    color: #fff;

    display: flex;
    flex-direction: column;
    align-items: center;
}

/* JUDUL */
.sidebar h2 {
    font-size: 18px;
    font-weight: 700;
    margin: 25px 0 35px;
    text-align: center;
    letter-spacing: 1px;
    white-space: nowrap;   /* ⬅️ ini penting */
}

/* MENU */
.sidebar ul {
    list-style: none;
    padding: 0;
    width: 100%;
    flex-grow: 1;
}

.sidebar ul li {
    width: 100%;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 12px;

    padding: 14px 25px;
    color: #fff;
    text-decoration: none;
    font-size: 15px;
}

.sidebar ul li a i {
    width: 20px;
    text-align: center;
}

.sidebar ul li:hover {
    background: rgba(255,255,255,0.15);
}

/* LOGOUT DI TENGAH BAWAH */
.logout {
    width: 100%;
    padding: 20px;
    border-top: 1px solid rgba(255,255,255,0.3);
    display: flex;
    justify-content: center;
}

.logout-btn {

    padding: 10px 25px;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-btn:hover {
    background: rgba(255,255,255,0.25);
}

/* ================= CONTENT ================= */
.content {
    margin-left: 230px;
    padding: 30px;
    width: calc(100% - 230px);
}

.page-title {
    font-size: 22px;
    font-weight: bold;
    color: #f00797ec;
    margin-bottom: 25px;
}

/* ================= CARD ================= */
.dashboard-card {
    background: #ffffff;
    border-left: 5px solid #f00797ec;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    height: 110px;
}

.dashboard-card h5 {
    font-size: 16px;
    font-weight: 700;
    color: #f00797ec;
    margin-bottom: 5px;
}

.dashboard-card p {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
    color: #2c3e50;
}

/* ================= CHART ================= */
.chart-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

#chart {
    width: 100%;
    height: 450px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADVENTUREWORKS</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="salesterritory.php"><i class="fas fa-chart-area"></i> Sales Territory</a></li>
        <li><a href="salesorder.php"><i class="fas fa-chart-line"></i> Sales Order</a></li>
        <li><a href="salesproduk.php"><i class="fas fa-chart-pie"></i> Sales Product</a></li>
        <li><a href="purchasetotal.php"><i class="fas fa-shopping-cart"></i> Purchasing</a></li>
        <li><a href="purchaseproduct.php"><i class="fas fa-box"></i> Product Stock</a></li>
        <li><a href="olap.php"><i class="fas fa-layer-group"></i> OLAP</a></li>
    </ul>
    <div class="logout">
    <a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
</div>

<!-- CONTENT -->
<div class="content">

<h2 class="page-title">Total Produk Terjual per Product Category</h2>

<!-- CARDS -->
<div class="row mb-4">
<?php foreach ($cards as $c): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="dashboard-card">
            <h5><?= htmlspecialchars($c['Category']); ?></h5>
            <p><?= number_format($c['TotalSold']); ?> Terjual</p>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- CHART -->
<div class="chart-card">
    <div id="chart"></div>
</div>

</div>

<script>
Highcharts.chart('chart', {
    chart: { type: 'column' },
    title: { text: 'Total Produk Terjual per Product Category' },
    subtitle: { text: 'Source: dw_sales database' },
    xAxis: {
        categories: <?= $categories_json; ?>,
        title: { text: 'Product Category' }
    },
    yAxis: {
        title: { text: 'Total Produk Terjual (Qty)' }
    },
    tooltip: {
        pointFormat: '<b>{point.y}</b> terjual'
    },
    series: [{
        name: 'Total Sold',
        colorByPoint: true,
        data: <?= $data_json; ?>
    }]
});
</script>

</body>
</html>
