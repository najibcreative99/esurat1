<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "disposisiuam";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Periksa apakah parameter ID diberikan dalam URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Query untuk mengambil data jabatan berdasarkan ID
    $query = "SELECT * FROM jabatan WHERE idjab = ?";
    
    // Persiapkan statement
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameter
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Eksekusi statement
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Periksa apakah data ditemukan
    if(mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_assoc($result);
    } else {
        echo "Data jabatan tidak ditemukan.";
        exit; // Keluar dari skrip jika data tidak ditemukan
    }
} else {
    echo "ID jabatan tidak diberikan.";
    exit; // Keluar dari skrip jika ID tidak diberikan
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jabatan</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
        }
        .table thead th {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
            cursor: pointer;
        }
        .table tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        .table-hover tbody tr:hover {
            background-color: #e2e6ea;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .table-light {
            background-color: #fdfdfe;
        }
        .table-warning th, .table-warning td {
            background-color: #ffeeba;
        }
        .print-btn {
            text-align: center;
            margin-top: 20px;
        }
        @media print {
            h2 {
                text-align: left;
            }
            .table th, .table td {
                border-color: #000 !important;
            }
            .table thead th {
                background-color: #000 !important;
            }
            .table-hover tbody tr:hover {
                background-color: #ccc !important;
            }
            .table-warning th, .table-warning td {
                background-color: #ffe !important;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>DATA JABATAN</h2>
        <div class="table-responsive">
            <table class="table table-light table-bordered table-hover table-warning">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">ID Jabatan</th>
                        <th onclick="sortTable(1)">Nama Jabatan</th>
                        <th onclick="sortTable(2)">Kode Jabatan</th>
                        <th onclick="sortTable(3)">Atasan</th>
                        <th onclick="sortTable(4)">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['idjab']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['kodejab']; ?></td>
                        <td><?php echo $data['atasan']; ?></td>
                        <td><?php echo $data['date']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="print-btn">
            <button class="btn btn-primary" onclick="window.print()">Print Sekarang</button>
        </div>
    </div>

    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector('table');
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[n];
                    y = rows[i + 1].getElementsByTagName("td")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++; 
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</body>
</html>

