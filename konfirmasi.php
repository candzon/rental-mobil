<?php
session_start();
require 'koneksi/koneksi.php';
include 'header.php';
if (empty($_SESSION['USER'])) {
    echo '<script>alert("Harap Login");window.location="index.php"</script>';
}
$kode_booking = $_GET['id'];
$hasil = $koneksi->query("SELECT * FROM booking WHERE kode_booking = '$kode_booking'")->fetch();

$id = $hasil['id_mobil'];
$isi = $koneksi->query("SELECT * FROM mobil WHERE id_mobil = '$id'")->fetch();
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
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="" id="paymentForm">
                        <table class="table">
                            <tr>
                                <td>Kode Booking </td>
                                <td> :</td>
                                <td><?php echo $hasil['kode_booking']; ?></td>
                            </tr>
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td> :</td>
                                <td>
                                    <select name="metode" id="metode" class="form-control" onchange="toggleRekeningInput()">
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="rekeningRow" class="hidden">
                                <td>No Rekening </td>
                                <td> :</td>
                                <td><input type="text" name="no_rekening" id="no_rekening" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Atas Nama </td>
                                <td> :</td>
                                <td><input type="text" name="nama" required class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Nominal </td>
                                <td> :</td>
                                <td><input type="text" name="nominal" required class="form-control" value="<?php echo $hasil['total_harga']; ?>"></td>
                            </tr>
                            <tr>
                                <td id="tanggalLabel">Tanggal Pembayaran</td>
                                <td> :</td>
                                <td><input type="datetime-local" name="tanggal" required class="form-control"></td>
                            </tr>
                            <!-- <tr>
                                <td>Diantarkan</td>
                                <td> :</td>
                                <td>
                                    <select name="diantarkan" id="" class="form-control">
                                        <option value="" disabled selected>Pilih Opsi</option>
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                            </tr> -->
                            <tr>
                                <td>Total yg Harus di Bayar </td>
                                <td> :</td>
                                <td>Rp. <?php echo number_format($hasil['total_harga']); ?></td>
                            </tr>
                        </table>
                        <input type="hidden" name="id_booking" value="<?php echo $hasil['id_booking']; ?>">
                        <button type="submit" class="btn btn-primary float-right">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>

<?php include 'footer.php'; ?>