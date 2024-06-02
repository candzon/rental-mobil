<?php
require 'koneksi.php';
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

    // Ambil harga mobil dari tabel mobil berdasarkan id_mobil
    $sql = "SELECT harga FROM mobil WHERE id_mobil = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->execute([$id_mobil]);
    $mobil = $stmt->fetch();

    // Pastikan harga mobil ditemukan
    if ($mobil) {
        $harga_per_hari = $mobil['harga'];

        // Atur harga per 3 jam sebagai 40% dari harga harian
        $harga_per_3_jam = 0.4 * $harga_per_hari;

        // Tentukan diskon berdasarkan lama sewa
        // Diskon 10% untuk setiap 3 jam setelah 3 jam pertama
        $diskon = 0.1;

        // Hitung total harga berdasarkan lama sewa
        if ($lama_sewa <= 3) {
            $total = $lama_sewa * ($harga_per_3_jam / 3);
        } else {
            $harga_awal = 3 * ($harga_per_3_jam / 3); // Harga 3 jam pertama
            $harga_diskon = ($lama_sewa - 3) * ($harga_per_3_jam / 3) * (1 - $diskon); // Harga setelah diskon
            $total = $harga_awal + $harga_diskon;
        }

        // Pastikan total tidak lebih murah dari harga harian
        if ($total > $harga_per_hari) {
            $total = $harga_per_hari;
        }

        // Tambahkan biaya unik
        $unik = random_int(100, 999);
        $total_harga = $total + $unik;
    } else {
        echo "Mobil tidak ditemukan.";
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
    tanggal, lama_sewa, id_supir ,total_harga, konfirmasi_pembayaran, tgl_input) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
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

if ($_GET['id'] == 'bayar_transfer') {
    $data[] = $_POST['id_booking'];
    $data[] = $_POST['metode'];
    $data[] = $_POST['no_rekening'];
    $data[] = $_POST['nama'];
    $data[] = $_POST['nominal'];
    $data[] = $_POST['tgl'];

    // Mengecek data apakah sudah lengkap
    // var_dump($data);

    $sql = "INSERT INTO `pembayaran`(`id_booking`, `metode`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) 
    VALUES (?,?,?,?,?,?)";
    $row = $koneksi->prepare($sql);
    if ($row->execute($data)) {
        $data2[] = 'Sedang di proses';
        $data2[] = $_POST['id_booking'];
        $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE id_booking=?";
        $row2 = $koneksi->prepare($sql2);
        $row2->execute($data2);

        echo '<script>alert("Pembayaran Sukses , Silahkan Tunggu Konfirmasi dari Admin");window.location="../history.php";</script>';
    } else {
        // Log error if insertion fails
        echo '<script>alert("Pembayaran Gagal");history.go(-1);</script>';
    }
} elseif ($_GET['id'] == 'bayar_cash') {
    $data[] = $_POST['id_booking'];
    $data[] = $_POST['metode'];
    $data[] = $_POST['nama'];
    $data[] = $_POST['nominal'];
    $data[] = $_POST['tgl'];

    $sql = "INSERT INTO `pembayaran`(`id_booking`, `metode`, `nama_rekening`, `nominal`, `tanggal`) 
    VALUES (?,?,?,?,?)";
    $row = $koneksi->prepare($sql);
    if ($row->execute($data)) {
        $data2[] = 'Sedang di proses';
        $data2[] = $_POST['id_booking'];
        $sql2 = "UPDATE `booking` SET `konfirmasi_pembayaran`=? WHERE id_booking=?";
        $row2 = $koneksi->prepare($sql2);
        $row2->execute($data2);

        echo '<script>alert("Pembayaran Sukses , Silahkan Tunggu Konfirmasi dari Admin");window.location="../history.php";</script>';
    } else {
        // Jika data gagal diinput
        echo '<script>alert("Pembayaran Gagal");history.go(-1);</script>';
    }
} else {
    echo '<script>alert("Pembayaran Gagal");history.go(-1);</script>';
}
