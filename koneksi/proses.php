<?php
require 'koneksi.php';

$id = $_GET['id'];
$data = array();

if ($_GET['id'] == 'login') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $row = $koneksi->prepare("SELECT * FROM login WHERE username = ? AND password = md5(?)");

    $row->execute(array($user, $pass));

    $hitung = $row->rowCount();

    if ($hitung > 0) {

        session_start();
        $hasil = $row->fetch();

        $_SESSION['USER'] = $hasil;

        if ($_SESSION['USER']['level'] == 'admin') {
            echo '<script>alert("Login Sukses");window.location="../admin/index.php";</script>';
        } else {
            echo '<script>alert("Login Sukses");window.location="../index.php";</script>';
        }
    } else {
        echo '<script>alert("Login Gagal");window.location="../index.php";</script>';
    }
}

if ($_GET['id'] == 'daftar') {
    $data[] = $_POST['nama'];
    $data[] = $_POST['user'];
    $data[] = md5($_POST['pass']);
    $data[] = 'pengguna';

    $row = $koneksi->prepare("SELECT * FROM login WHERE username = ?");

    $row->execute(array($_POST['user']));

    $hitung = $row->rowCount();

    if ($hitung > 0) {
        echo '<script>alert("Daftar Gagal, Username Sudah digunakan ");window.location="../index.php";</script>';
    } else {

        $sql = "INSERT INTO `login`(`nama_pengguna`, `username`, `password`, `level`)
                VALUES (?,?,?,?)";
        $row = $koneksi->prepare($sql);
        $row->execute($data);

        echo '<script>alert("Daftar Sukses Silahkan Login");window.location="../index.php";</script>';
    }
}

if ($_GET['id'] == 'booking') {
    // Ambil data dari form
    $id_mobil = $_POST['id_mobil'];
    $lama_sewa = $_POST['lama_sewa'];
    $id_kota = $_POST['id_kota'];

    // Ambil harga mobil dari tabel mobil berdasarkan id_mobil
    $sql = "SELECT harga FROM mobil WHERE id_mobil = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$id_mobil]);
    $mobil = $stmt->fetch();

    // Pastikan harga mobil ditemukan
    if ($mobil) {
        $harga_per_jam = $mobil['harga'] / 24;
        $harga_per_hari = $mobil['harga'];
        $harga_per_minggu = $mobil['harga'] * 7;
        $harga_per_bulan = $mobil['harga'] * 30;

        // Hitung total waktu sewa dalam satuan bulan, minggu, hari, dan jam
        $jumlah_bulan = floor($lama_sewa / 720);
        $sisa_jam_setelah_bulan = $lama_sewa % 720;

        $jumlah_minggu = floor($sisa_jam_setelah_bulan / 168);
        $sisa_jam_setelah_minggu = $sisa_jam_setelah_bulan % 168;

        $jumlah_hari = floor($sisa_jam_setelah_minggu / 24);
        $sisa_jam_setelah_hari = $sisa_jam_setelah_minggu % 24;

        // Hitung biaya sewa berdasarkan lama sewa
        $biaya_bulan = $jumlah_bulan * $harga_per_bulan;
        $biaya_minggu = $jumlah_minggu * $harga_per_minggu;
        $biaya_hari = $jumlah_hari * $harga_per_hari;
        $biaya_jam = $sisa_jam_setelah_hari * $harga_per_jam;

        // Total biaya sewa
        $total_biaya_sewa = $biaya_bulan + $biaya_minggu + $biaya_hari + $biaya_jam;

        // Tambahkan harga tambahan jika id_kota bukan 1 (Keluar kota)
        if ($id_kota != 1) {
            $unik = random_int(100, 999);
            $total_biaya_sewa *= 1.2; // Tambahan 20% jika bukan di kota dengan id 1
            $total_harga = $total_biaya_sewa + $unik;
        }else{
            $unik = random_int(100, 999);
            $total_harga = $harga_per_hari + $unik;
        }
    }

    $data[] = time();
    $data[] = $_POST['id_login'];
    $data[] = $_POST['id_mobil'];
    $data[] = $_POST['ktp'];
    $data[] = $_POST['nama'];
    $data[] = $_POST['alamat'];
    $data[] = $_POST['no_tlp'];
    $data[] = $_POST['tanggal'];
    $data[] = $_POST['lama_sewa'];
    $data[] = $_POST['id_supir'];
    $data[] = $_POST['id_kota'];
    $data[] = $total_harga;
    $data[] = "Belum Bayar";
    $data[] = date('Y-m-d');

    $sql = "INSERT INTO booking (kode_booking, 
    id_login, 
    id_mobil, 
    ktp, 
    nama, 
    alamat, 
    no_tlp, 
    tanggal, lama_sewa, id_supir , id_kota ,total_harga, konfirmasi_pembayaran, tgl_input) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $row = $koneksi->prepare($sql);
    $row->execute($data);

    echo '<script>alert("Anda Sukses Booking silahkan Melakukan Pembayaran");
    window.location="../bayar.php?id=' . time() . '";</script>';
}

if ($_GET['id'] == 'konfirmasi') {

    $data2[] = 'Sedang di proses';
    $data2[] = $_POST['id_booking'];
    $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE id_booking=?";
    $row2 = $koneksi->prepare($sql2);
    $row2->execute($data2);

    echo '<script>alert("Kirim Sukses , Pembayaran anda sedang diproses");history.go(-2);</script>';
}


if ($id == 'bayar_transfer') {
    if (isset($_POST['id_booking'], $_POST['metode'], $_POST['no_rekening'], $_POST['nama'], $_POST['nominal'], $_POST['tanggal'])) {
        $data[] = $_POST['id_booking'];
        $data[] = $_POST['metode'];
        $data[] = $_POST['no_rekening'];
        $data[] = $_POST['nama'];
        $data[] = $_POST['nominal'];
        $data[] = $_POST['tanggal'];

        $sql = "INSERT INTO `pembayaran`(`id_booking`, `metode`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) 
        VALUES (?,?,?,?,?,?)";
        $row = $koneksi->prepare($sql);
        if ($row->execute($data)) {
            $data2 = array();
            $data2[] = 'Sedang di proses';
            $data2[] = $_POST['id_booking'];

            $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE `id_booking`=?";
            $row2 = $koneksi->prepare($sql2);
            $row2->execute($data2);
            // var_dump($data2);die; 

            echo '<script>alert("Pembayaran Sukses, Silahkan Tunggu Konfirmasi dari Admin");window.location="../history.php";</script>';
        } else {
            echo '<script>alert("Pembayaran Gagal");history.go(-1);</script>';
        }
    } else {
        echo '<script>alert("Data tidak lengkap");history.go(-1);</script>';
    }
} elseif ($id == 'bayar_cash') {
    if (isset($_POST['id_booking'], $_POST['metode'], $_POST['nama'], $_POST['nominal'], $_POST['tanggal'])) {
        $data[] = $_POST['id_booking'];
        $data[] = $_POST['metode'];
        $data[] = $_POST['nama'];
        $data[] = $_POST['nominal'];
        $data[] = $_POST['tanggal'];

        $sql = "INSERT INTO `pembayaran`(`id_booking`, `metode`, `nama_rekening`, `nominal`, `tanggal`) 
        VALUES (?,?,?,?,?)";
        $row = $koneksi->prepare($sql);
        if ($row->execute($data)) {
            $data2 = array();
            $data2[] = 'Sedang di proses';
            $data2[] = $_POST['id_booking'];

            $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE `id_booking`=?";
            $row2 = $koneksi->prepare($sql2);
            $row2->execute($data2);
            // var_dump($data2);die;

            echo '<script>alert("Pembayaran Sukses, Silahkan Tunggu Konfirmasi dari Admin");window.location="../history.php";</script>';
        } else {
            echo '<script>alert("Pembayaran Gagal");history.go(-1);</script>';
        }
    } else {
        echo '<script>alert("Data tidak lengkap");history.go(-1);</script>';
    }
} else {
    echo '<script>alert("Aksi tidak dikenal");history.go(-1);</script>';
}
