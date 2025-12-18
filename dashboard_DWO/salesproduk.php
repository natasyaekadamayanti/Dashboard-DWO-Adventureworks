<?php
include 'koneksi.php';

// =======================
// QUERY TOTAL SALES PER CATEGORY
// =======================
$sql = "
    SELECT p.ProductCategory AS kategori,
           SUM(f.SalesAmount) AS total_penjualan
    FROM fact_sales f
    JOIN dimproduct p ON f.ProductID = p.ProductID
    GROUP BY p.ProductCategory
";
$result = $conn_sales->query($sql);

$product_sales = [];
while ($row = $result->fetch_assoc()) {
    $product_sales[] = $row;
}

// =======================
// DATA PIE CHART
// =======================
$data_pie = [];
foreach ($product_sales as $row) {
    $data_pie[] = [
        'name' => $row['kategori'],
        'y' => (float)$row['total_penjualan'],
        'drilldown' => $row['kategori']
    ];
}
$json_pie = json_encode($data_pie);

// =======================
// DRILLDOWN PER BULAN
// =======================
$sql_detail = "
    SELECT p.ProductCategory AS kategori,
           t.bulan,
           SUM(f.SalesAmount) AS total
    FROM fact_sales f
    JOIN dimproduct p ON f.ProductID = p.ProductID
    JOIN dimtime t ON f.TimeID = t.TimeID
    GROUP BY p.ProductCategory, t.bulan
";
$result_detail = $conn_sales->query($sql_detail);

$tmp = [];
while ($r = $result_detail->fetch_assoc()) {
    $tmp[$r['kategori']][] = [$r['bulan'], (float)$r['total']];
}

$drilldown = [];
foreach ($tmp as $k => $v) {
    $drilldown[] = [
        'name' => $k,
        'id' => $k,
        'data' => $v
    ];
}
$json_drilldown = json_encode($drilldown);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Sales by Category</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>

<style>
/* ===================== GLOBAL ===================== */
body {
    background: #f3f0ff;
    margin: 0;
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
    color: #ffff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-btn:hover {
    background: rgba(255,255,255,0.25);
}

/* ===================== CONTENT ===================== */
.content {
    margin-left: 230px;
    padding: 30px;
    width: calc(100% - 230px);
}

.page-title {
    color: #f00797ec;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 25px;
}

/* ===================== CARDS ===================== */
.card-wrapper {
    width: 100%;
}

.dashboard-card {
    background: white;
    border-left: 5px solid #f00797ec;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    height: 110px;
}

.dashboard-card h5 {
    color: #f00797ec;
    font-weight: 700;
    margin-bottom: 5px;
}

.dashboard-card p {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
    color: #2c3e50;
}

/* ===================== CHART ===================== */
.chart-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

#piechart {
    width: 100%;
    height: 450px;
}
</style>
</head>

<body>

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

<!-- CONTENT -->
<div class="content">

<h2 class="page-title">Product Sales by Category</h2>

<!-- CARDS -->
<div class="row card-wrapper mb-4">
<?php foreach ($product_sales as $s): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="dashboard-card">
            <h5><?= $s['kategori']; ?></h5>
            <p><?= number_format($s['total_penjualan'],0); ?></p>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- PIE CHART -->
<div class="row">
    <div class="col-12">
        <div class="chart-card">
            <div id="piechart"></div>
        </div>
    </div>
</div>

</div>

<script>
Highcharts.chart('piechart', {
    chart: { type: 'pie' },
    title: { text: 'Persentase Penjualan per Kategori' },
    subtitle: { text: 'Klik kategori untuk melihat detail per bulan' },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.percentage:.1f}%'
            }
        }
    },
    series: [{
        name: 'Sales',
        colorByPoint: true,
        data: <?= $json_pie; ?>
    }],
    drilldown: {
        series: <?= $json_drilldown; ?>
    }
});
</script>

</body>
</html>
