<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';
if (empty($_SESSION['USER'])) {
    echo '<script>alert("Harap login !");window.location="index.php"</script>';
}
$kode_booking = $_GET['id'];
$hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();

$id = $hasil['id_mobil'];
$isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();

$id_booking = $hasil['id_booking'];

$metode_pembayaran = $koneksi->query("SELECT * FROM pembayaran WHERE id_booking = '$id_booking'")->fetch();

$unik  = random_int(100, 999);

?>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4">

            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h5>Pembayaran Dapat Melalui :</h5>
                        <hr />
                        <p> <?= $info_web->no_rek; ?> </p>
                        <h5>Atau</h5>
                        <p>Cash</p>
                    </div>
                </div>
            </div>
            <br />
            <div class="card">
                <div class="card-body" style="background:#ddd">
                    <h5 class="card-title"><?php echo $isi['merk']; ?></h5>
                </div>
                <ul class="list-group list-group-flush">

                    <?php if ($isi['status'] == 'Tersedia') { ?>

                        <li class="list-group-item bg-primary text-white">
                            <i class="fa fa-check"></i> Available
                        </li>

                    <?php } else { ?>

                        <li class="list-group-item bg-danger text-white">
                            <i class="fa fa-close"></i> Not Available
                        </li>

                    <?php } ?>


                    <li class="list-group-item bg-info text-white"><i class="fa fa-check"></i> Free E-toll 50k</li>
                    <li class="list-group-item bg-dark text-white">
                        <i class="fa fa-money"></i> Rp. <?php echo number_format($isi['harga']); ?>/ day
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Kode Booking </td>
                            <td> :</td>
                            <td><?php echo $hasil['kode_booking']; ?></td>
                        </tr>
                        <tr>
                            <td>KTP </td>
                            <td> :</td>
                            <td><?php echo $hasil['ktp']; ?></td>
                        </tr>
                        <tr>
                            <td>Nama </td>
                            <td> :</td>
                            <td><?php echo $hasil['nama']; ?></td>
                        </tr>
                        <tr>
                            <td>telepon </td>
                            <td> :</td>
                            <td><?php echo $hasil['no_tlp']; ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Sewa </td>
                            <td> :</td>
                            <td><?php echo $hasil['tanggal']; ?></td>
                        </tr>
                        <tr>
                            <td>Lama Sewa</td>
                            <td> :</td>
                            <td>
                                <!-- Logic untuk memformat lama sewa jam menjadi hari -->
                                <?php
                                $lama_sewa = $hasil['lama_sewa'];
                                if ($lama_sewa < 24) {
                                    echo $lama_sewa . " Jam";
                                } else {
                                    $total_hari = floor($lama_sewa / 24);
                                    $sisa_jam = $lama_sewa % 24;
                                    echo $total_hari . " Hari";
                                    if ($sisa_jam > 0) {
                                        echo " " . $sisa_jam . " Jam";
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Supir </td>
                            <td> :</td>
                            <td>
                                <?php
                                if ($hasil['id_supir'] == 0) {
                                    echo "Tidak Memakai Supir";
                                } else {
                                    $id_supir = $hasil['id_supir'];
                                    $supir = $koneksi->query("SELECT * FROM supir WHERE id_supir = '$id_supir'")->fetch();
                                    echo $supir['nama'];
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kota Tujuan </td>
                            <td> :</td>
                            <td>
                                <?php
                                $id_kota = $hasil['id_kota'];
                                $kota = $koneksi->query("SELECT * FROM tb_kota WHERE id_kota = '$id_kota'")->fetch();
                                echo $kota['nama'];
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Metode Pembayaran </td>
                            <td> :</td>
                            <td><?php echo $metode_pembayaran['metode'] ?? 'null'; ?></td>
                        </tr>
                        <tr>
                            <td>Total Harga </td>
                            <td> :</td>
                            <td>Rp. <?php echo number_format($hasil['total_harga']); ?></td>
                        </tr>
                        <tr>
                            <td>Status </td>
                            <td> :</td>
                            <td><?php echo $hasil['konfirmasi_pembayaran']; ?></td>
                        </tr>
                    </table>

                    <?php if ($hasil['konfirmasi_pembayaran'] == 'Belum Bayar') { ?>
                        <a href="konfirmasi.php?id=<?php echo $kode_booking; ?>" class="btn btn-primary float-right">Konfirmasi Pembayaran</a>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>

<?php include 'footer.php'; ?>