<?php
require '../../koneksi/koneksi.php';
require '../../vendor/autoload.php'; // Ensure this path is correct to the Composer autoload file

session_start();
if (empty($_SESSION['USER'])) {
    echo '<script>alert("Harap Login");window.location="../index.php"</script>';
    exit();
}

$title_web = 'Cetak PDF';
$kode_booking = $_GET['id'];
$hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();

$id_booking = $hasil['id_booking'];
$hsl = $koneksi->query("SELECT * FROM pembayaran WHERE id_booking = '$id_booking'")->fetch();
$c = $koneksi->query("SELECT * FROM pembayaran WHERE id_booking = '$id_booking'")->rowCount();


$id = $hasil['id_mobil'];
$isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();

$getAllData = $koneksi->query("SELECT * FROM booking 
                              JOIN mobil ON booking.id_mobil = mobil.id_mobil 
                              JOIN pembayaran ON booking.id_booking = pembayaran.id_booking 
                              WHERE booking.kode_booking = '$kode_booking'")->fetch();
//   var_dump($getAllData); die;

$id_kota = $hasil['id_kota'];
$nama_kota = $koneksi->query("SELECT * FROM tb_kota WHERE id_kota = '$id_kota'")->fetch();


$infoWeb = $koneksi->query("SELECT * FROM infoweb")->fetch();
// var_dump($infoWeb); die;


// Start buffering HTML output
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 0;
            font-size: 14px;
        }

        .content {
            margin: 20px 0;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }

        .content table,
        .content th,
        .content td {
            border: 1px solid black;
        }

        .content th,
        .content td {
            padding: 10px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><?= $infoWeb['nama_rental'] ?></h1>
            <p><?= $infoWeb['alamat'] ?></p>
            <p>Tel: <?= $infoWeb['telp'] ?> | Email: <?= $infoWeb['email'] ?></p>
        </div>
        <div class="content">
            <h2>Kwitansi</h2>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Penyewa</th>
                    <td><?= $getAllData['nama'] ?></td>
                </tr>
                <tr>
                    <th>Model Mobil</th>
                    <td><?= $getAllData['merk'] ?></td>
                </tr>
                <tr>
                    <th>Nomor Plat</th>
                    <td><?= $getAllData['no_plat'] ?></td>
                </tr>
                <tr>
                    <th>Tanggal Sewa</th>
                    <td><?= $hasil['tanggal'] ?></td>
                </tr>
                <tr>
                    <th>Lama Sewa</th>
                    <td>
                        <?php 
                            if ($hasil['lama_sewa'] >= 24) {
                                echo floor($hasil['lama_sewa'] / 24) . ' Hari';
                            } else {
                                echo $hasil['lama_sewa'] . ' Jam';
                            }                        
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Tujuan</th>
                    <td><?= $nama_kota['nama'] ?></td>
                </tr>
                <tr>
                    <th>Biaya per Hari</th>
                    <td><?= 'Rp ' . number_format($getAllData['harga'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Total Biaya</th>
                    <td><?= 'Rp ' . number_format($getAllData['total_harga'], 0, ',', '.') ?></td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Terima kasih telah menggunakan layanan kami.</p>
            <p>Mobil Serba Rent</p>
        </div>
    </div>
</body>

</html>
<?php
// Get HTML content from buffer and clean buffer
$html = ob_get_clean();

// Generate PDF using mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('receipt.pdf', 'I'); // 'I' to display in browser, 'D' to download directly
?>