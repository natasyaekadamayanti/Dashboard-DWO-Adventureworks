<?php
$dbHost = "localhost";
$dbDatabase = "dw_purchasing";
$dbUser = "root";
$dbPassword = "";

// Koneksi ke database
$mysqli = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);

// Query Total Pembelian per Tahun
$sql_year = "
    SELECT t.tahun AS Year, SUM(fp.TotalCost) AS TotalCost
    FROM fact_purchasing fp
    JOIN dimdate t ON fp.DateID = t.DateID
    GROUP BY t.tahun
    ORDER BY t.tahun ASC
";
$result_year = mysqli_query($mysqli, $sql_year);

// Data Card & Chart
$year_data = [];
$cards = [];

while ($row = mysqli_fetch_assoc($result_year)) {
    $year = (int)$row['Year'];
    $totalCost = (float)$row['TotalCost'];

    $year_data[] = [$year, $totalCost];
    $cards[] = [
        'Year' => $year,
        'TotalCost' => number_format($totalCost, 0)
    ];
}

$json_year_data = json_encode($year_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Purchasing by Year</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>

<!-- External CSS -->
<link rel="stylesheet" href="style.css">

<style>
body {
    background-color: #f3f0ff;
}

/* ================= SIDEBAR ================= */
.sidebar {
    background-color: #f00797ec;
    width: 230px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    color: white;
    display: flex;
    flex-direction: column;
}

.sidebar h2 {
    text-align: center;
    margin: 20px 0 30px;
    font-weight: 700;
    letter-spacing: 1px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    flex-grow: 1;
}

.sidebar ul li {
    padding: 14px 20px;
}

.sidebar ul li a {
    color: white;
    text-decoration: none;
    display: flex;
    gap: 10px;
    align-items: center;
}

.sidebar ul li:hover {
    background: rgba(255,255,255,0.15);
}

.logout {
    padding: 15px;
}

.logout-btn {
    color: white;
    text-decoration: none;
}

/* ================= CONTENT ================= */
.content {
    margin-left: 250px;
    padding: 30px;
}

.page-title {
    font-size: 22px;
    font-weight: bold;
    color: #f00797ec;
    margin-bottom: 25px;
}

/* ================= CARD ================= */
.card-wrapper {
    display: flex;
    flex-wrap: wrap;
}

.dashboard-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    border-left: 5px solid #f00797ec;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    height: 110px;
}

.dashboard-card h5 {
    color: #f00797ec;
    font-weight: bold;
}

.dashboard-card p {
    font-size: 22px;
    font-weight: bold;
    color: #2c3e50;
    margin: 0;
}

/* ================= CHART ================= */
.chart-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

#container {
    width: 100%;
    height: 420px;
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

<h2 class="page-title">Total Pembelian Berdasarkan Tahun</h2>

<!-- CARD -->
<div class="row card-wrapper mb-4">
<?php foreach ($cards as $card): ?>
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="dashboard-card">
            <h5><?php echo $card['Year']; ?></h5>
            <p>$<?php echo $card['TotalCost']; ?></p>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- CHART -->
<div class="row">
    <div class="col-12">
        <div class="chart-card">
            <div id="container"></div>
        </div>
    </div>
</div>

</div>

<script>
const yearData = <?php echo $json_year_data; ?>;

Highcharts.chart('container', {
    chart: { type: 'area' },
    title: { text: 'Total Cost Pembelian per Tahun' },
    subtitle: { text: 'Source: dw_purchasing database' },
    xAxis: {
        categories: yearData.map(d => d[0]),
        title: { text: 'Tahun' }
    },
    yAxis: {
        title: { text: 'Total Pembelian (USD)' }
    },
    tooltip: {
        pointFormat: '<b>${point.y:,.0f}</b>'
    },
    series: [{
        name: 'Total Pembelian',
        data: yearData.map(d => d[1]),
        color: '#f00797ec'
    }]
});
</script>

</body>
</html>
