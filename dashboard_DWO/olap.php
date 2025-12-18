<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            margin-top: 10px;
        }
        .card {
            background: #ffffff;
            border-radius: 5px;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #f00797ec;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            margin: 2px;
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

</head>
<body>
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
<div class="content">
    <h2>Tampilan OLAP DATABASE DWO - Using Mondrian</h2>
    <!-- Cards -->
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-body">
                    <iframe src="http://localhost:8080/mondrian/" style="width: 75vw; height: 500px;" class="mt-5"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Section Chart -->
    <!-- <div id="container" style="width: 100%; height: 500px;" class="mt-5"></div> -->
</div>

</body>

</html>
