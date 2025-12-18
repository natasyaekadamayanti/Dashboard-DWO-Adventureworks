<?php
$dbHost = "localhost";
$dbDatabase = "dw_purchasing";
$dbUser = "root";
$dbPassword = "";

$mysqli = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);

// ======================
// TOTAL PURCHASING
// ======================
$sql = "SELECT SUM(TotalCost) AS tot FROM fact_purchasing";
$tot = mysqli_query($mysqli, $sql);
$tot_amount = mysqli_fetch_assoc($tot);
$totalAll = $tot_amount['tot'] ?? 0;

// ======================
// TOTAL PER CATEGORY
// ======================
$sql = "
    SELECT 
        p.ProductCategory AS Category,
        SUM(fp.TotalCost) AS TotalCost
    FROM fact_purchasing fp
    JOIN dimproduct p 
        ON fp.ProductID = p.ProductID
    GROUP BY p.ProductCategory
    ORDER BY TotalCost DESC
";
$result = mysqli_query($mysqli, $sql);

// ======================
// PIE & CARD DATA
// ======================
$data = [];
$cards = [];

while ($row = mysqli_fetch_assoc($result)) {
    $category = $row['Category'];
    $totalCost = (float)$row['TotalCost'];

    $percentage = ($totalAll > 0)
        ? round(($totalCost / $totalAll) * 100, 2)
        : 0;

    $data[] = [
        'name' => $category,
        'y' => $percentage,
        'drilldown' => $category
    ];

    $cards[] = [
        'Category' => $category,
        'TotalCost' => number_format($totalCost, 0)
    ];
}

// ======================
// DRILLDOWN PER BULAN
// ======================
$sql = "
    SELECT 
        p.ProductCategory AS Category,
        t.bulan AS Month,
        SUM(fp.TotalCost) AS TotalCost
    FROM fact_purchasing fp
    JOIN dimproduct p 
        ON fp.ProductID = p.ProductID
    JOIN dimdate t 
        ON fp.DateID = t.DateID
    GROUP BY p.ProductCategory, t.bulan
";
$result_drilldown = mysqli_query($mysqli, $sql);

$drilldown_data = [];
while ($row = mysqli_fetch_assoc($result_drilldown)) {
    $drilldown_data[$row['Category']][] = [
        $row['Month'],
        (float)$row['TotalCost']
    ];
}

$final_drilldown = [];
foreach ($drilldown_data as $category => $values) {
    $final_drilldown[] = [
        'name' => $category,
        'id' => $category,
        'data' => $values
    ];
}

$json_data = json_encode($data);
$json_drilldown = json_encode($final_drilldown);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Pembelian per Product Category</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f3f0ff;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            background: #ffffff;
            border-radius: 5px;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #f00797ec;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 8px;
        }
        .card-title {
            font-size: 1.2rem;
            color: #f00797ec;
            text-align: left;
            font-weight: bold;
        }
        .card-text {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: left;
            color: #ffffff;
        }
        h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f00797ec;
            text-align: left;
            margin-bottom: 20px;
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

    <!-- Main Content -->
    <!-- Content Section -->
<!-- Content Section -->
<div class="content">
    <h2>Total Pembelian per Product Category</h2>

    <!-- Section Card -->
    <div class="row">
        <?php if (!empty($cards)): ?>
            <?php foreach ($cards as $card): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($card['Category']); ?></h5>
                            <p class="card-text">$<?php echo number_format((float)str_replace(',', '', $card['TotalCost']), 0); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-danger">Data tidak tersedia.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Section Chart -->
    <div id="container" style="width: 100%; height: 500px;" class="mt-5"></div>
</div>

    <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Persentase Nilai Pembelian per Product Category'
            },
            subtitle: {
                text: 'Klik pada kategori untuk melihat detail per bulan'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y:.2f}%'
                    }
                }
            },
            series: [{
                name: 'Pembelian',
                colorByPoint: true,
                data: <?php echo $json_data; ?>
            }],
            drilldown: {
                series: <?php echo $json_drilldown; ?>
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> dari total<br/>'
            }
        });
    </script>

</body>
</html>