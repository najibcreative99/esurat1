<?php
// Konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "disposisiuam";

// Koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

// Deklarasi variabel
$id     = "";
$idjab  = "";
$nama   = "";
$kodejab = "";
$atasan = "";
$error    = "";
$sukses = "";
$q        = "";

// Mengambil operasi yang diminta dari URL
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Proses penghapusan data
if ($op == 'delete') {
    $id   = $_GET['id'];
    $sql  = "DELETE FROM jabatan WHERE idjab = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    $pesan = $q ? "Sukses berhasil hapus data" : "Gagal melakukan delete data";
    $sukses = $pesan;
}

// Proses pengeditan data
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql  = "SELECT * FROM jabatan WHERE idjab = '$id'";
    $q    = mysqli_query($koneksi, $sql);
    $r    = mysqli_fetch_array($q);

    if ($r) {
        $idjab      = $r['idjab'];
        $nama     = $r['nama'];
        // Periksa apakah kodejab sudah terdefinisi
        $kodejab   = isset($r['kodejab']) ? $r['kodejab'] : '';
        // Periksa apakah atasan sudah terdefinisi
        $atasan = isset($r['atasan']) ? $r['atasan'] : '';
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses tambah data
if (isset($_POST['simpan'])) {
    $idjab      = $_POST['idjab_tambah'];
    $atasan = "";

    switch ($idjab) {
        case 2:
            $nama = "Rektor";
            $kodejab = "RK";
            $atasan = 0; // Tidak ada atasan
            break;
        case 3:
            $nama = "Wakil Rektor 1";
            $kodejab = "WR1";
            $atasan = 2; // ID atasan Rektor
            break;
        case 4:
            $nama = "Dekan FST";
            $kodejab = "DK_FST";
            $atasan = 3; // ID atasan Wakil Rektor 1
            break;
        case 5:
            $nama = "Dekan FEB";
            $kodejab = "DK_FEB";
            $atasan = 3; // ID atasan Wakil Rektor 1
            break;
        case 6:
            $nama = "Dekan FIK";
            $kodejab = "DK_FIK";
            $atasan = 3; // ID atasan Wakil Rektor 1
            break;
        default:
            $error = "ID Jabatan tidak valid";
            break;
    }

    if ($idjab && $nama && $kodejab && $atasan !== "") {
        $sql = "INSERT INTO jabatan (idjab, nama, kodejab, atasan, date) VALUES ('$idjab', '$nama', '$kodejab', '$atasan', NOW())";
        $q   = mysqli_query($koneksi, $sql);
        $pesan = $q ? "Data berhasil disimpan" : "Data gagal disimpan";
        $sukses = $pesan;
    } else {
        $error = "Silahkan masukkan semua data";
    }
}

// Proses update data
if (isset($_POST['update'])) {
    $idjab      = $_POST['idjab'];
    $nama     = $_POST['nama'];
    $kodejab   = isset($_POST['kodejab']) ? $_POST['kodejab'] : '';
    $atasan = isset($_POST['atasan']) ? $_POST['atasan'] : '';

    if ($idjab && $nama && $kodejab && $atasan !== "") {
        $sql = "UPDATE jabatan SET nama='$nama', kodejab='$kodejab', atasan='$atasan', date=NOW() WHERE idjab = '$idjab'";
        $q   = mysqli_query($koneksi, $sql);
        $pesan = $q ? "Data berhasil diupdate" : "Data gagal diupdate";
        $sukses = $pesan;
    } else {
        $error = "Silahkan masukkan semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Data Jabatan UAM</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Favicon -->
    <link rel="shortcut icon" href="unnamed.png" type="image/x-icon">
    <!-- Bootstrap JavaScript and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Custom CSS -->
    <style>
        /* Gaya kustom */
        body {
            background-color: #f8f9fa;
        }

        .header-name {
            background-color: #343a40;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .header-name span {
            font-size: 25px;
            font-family: 'Poppins', sans-serif;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .search-form {
            margin-bottom: 10px;
        }

        .header-name .btn-success {
            margin: 0 10px;
            padding: 8px 15px;
            font-size: 14px;
        }

        .table {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media only screen and (max-width: 767px) {
            /* Responsif CSS */
            .mx-auto {
                padding: 0 10px;
            }

            .input-group {
                width: 100%;
                margin-bottom: 10px;
            }

            .header-name .btn-success {
                margin: 10px 0;
            }

            .edit-btn,
            .delete-btn {
                margin: 5px 0;
            }

            .btn {
                margin: 0 5px;
            }
            .btn-tambah {
                margin: 0 5px;
                padding: 4px 8px;
                font-size: 14px;
                height: auto;
                line-height: 1.5;
                min-width: auto; /* Menjadikan lebar minimum otomatis */
            }
        }
    </style>
</head>
<body>
    <div class="mx-auto">
        <!-- Modal Tambah Data -->
        <div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <!-- Konten modal -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Isi Modal -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="idjab_tambah" class="form-label">Pilih Jabatan</label>
                                <select class="form-select" id="idjab_tambah" name="idjab_tambah" required>
                                    <option value="2">Rektor</option>
                                    <option value="3">Wakil Rektor 1</option>
                                    <option value="4">Dekan FST</option>
                                    <option value="5">Dekan FEB</option>
                                    <option value="6">Dekan FIK</option>
                                </select>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten utama -->
        <div class="card kertu-as">
            <div class="card-header header-name">
                <!-- Logo dan judul -->
                <img src="https://uam.ac.id/wp-content/uploads/2022/07/logo_horizontal-1024x410.png" width="150px" height="60px">
                <span>Data Jabatan UAM</span>
                <!-- Form pencarian -->
                <form class="search-form form-inline" method="GET" action="index.php">
                    <div class="input-group">
                        <input type="text" class="form-control search-input" placeholder="Cari Nama...." name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
                        <button class="btn btn-primary" type="submit">🔎 Cari</button>
                    </div>
                </form>
                <!-- Tombol Tambah Data -->
                <button type="button" class="btn btn-success btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambahData">➕ Tambah Data</button>
            </div>

            <!-- Tabel -->
            <div class="card-body card-wrap table-responsive">
                <table class="table table-light table-bordered table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">ID Jabatan</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Kode Jabatan</th>
                            <th scope="col">Atasan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="wrap-table-data">
                        <?php
                        // Loop untuk menampilkan data
                        $q_data = isset($_GET['q']) ? $_GET['q'] : '';
                        if ($q_data != '') {
                            $q_data = "SELECT * FROM jabatan WHERE nama LIKE '%$q_data%' ORDER BY idjab";
                        } else {
                            $q_data = "SELECT * FROM jabatan ORDER BY idjab";
                        }

                        $query = mysqli_query($koneksi, $q_data);
                        $no = 1;
                        while ($r_data = mysqli_fetch_array($query)) {
                            $idjab = $r_data['idjab'];
                            $nama = $r_data['nama'];
                            $kodejab = $r_data['kodejab'];
                            $atasan = $r_data['atasan'];
                            $tanggal = date("d M Y H:i:s", strtotime($r_data['date']));
                            ?>
                            <!-- Baris data -->
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $idjab ?></td>
                                <td><?php echo $nama ?></td>
                                <td><?php echo $kodejab ?></td>
                                <td><?php echo $atasan ?></td>
                                <td><?php echo $tanggal ?></td>
                                <td>
                                    <!-- Tombol Aksi -->
                                    <button type="button" class="btn btn-info btn-sm print-btn" data-idjab="<?php echo $idjab ?>">🖨️ Print Data Jabatan</button>
                                    <button type="button" class="btn btn-success btn-sm copy-info">Copy Nomor Surat</button>
                                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#modalEditData" data-idjab="<?php echo $idjab ?>" data-nama="<?php echo $nama ?>" data-kodejab="<?php echo $kodejab ?>" data-atasan="<?php echo $atasan ?>">✏️ Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $idjab ?>">🗑️ Hapus</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        // Script untuk mengisi nilai input pada modal edit
        $('.edit-btn').click(function(){
            var idjab = $(this).data('idjab');
            var nama = $(this).data('nama');
            var kodejab = $(this).data('kodejab');
            var atasan = $(this).data('atasan');

            $('#idjab_edit').val(idjab); // Masukkan ID Jabatan ke input tersembunyi
            $('#idjab_edit_display').val(idjab); // Tampilkan ID Jabatan di input display
            $('#nama_edit').val(nama); // Masukkan nama ke input
            $('#kodejab_edit').val(kodejab); // Masukkan kode jabatan ke input
            $('#atasan_edit').val(atasan); // Masukkan atasan ke input
        });

        // Script untuk konfirmasi sebelum menghapus data
        $('.delete-btn').click(function(){
            var id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman delete.php dengan membawa parameter id
                    window.location.href = 'index.php?op=delete&id='+id;
                }
            });
        });
    </script>
</body>
</html>
