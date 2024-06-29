<?php

require '../../koneksi/koneksi.php';
$title_web = 'Tambah Mobil';
include '../header.php';
if (empty($_SESSION['USER'])) {
    session_start();
}

if ($_GET['aksi'] == 'tambah') {

    $allowedImageType = array("image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp');
    $filepath = $_FILES['gambar']['tmp_name'];
    $fileSize = filesize($filepath);
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $filetype = finfo_file($fileinfo, $filepath);
    $allowedTypes = [
        'image/png'   => 'png',
        'image/jpeg'  => 'jpg',
        'image/gif'   => 'gif',
        'image/jpg'   => 'jpeg',
        'image/webp'  => 'webp'
    ];

    if (!in_array($filetype, array_keys($allowedTypes))) {
        echo '<script>alert("You can only upload JPG, PNG, and GIF files");window.location="tambah.php"</script>';
        exit();
    } else if ($_FILES['gambar']["error"] > 0) {
        echo '<script>alert("Error file");history.go(-1)</script>';
        exit();
    } elseif (!in_array($_FILES['gambar']["type"], $allowedImageType)) {
        echo '<script>alert("You can only upload JPG, PNG, and GIF files");window.location="tambah.php"</script>';
        exit();
    } elseif (round($_FILES['gambar']["size"] / 1024) > 4096) {
        echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB !");window.location="tambah.php"</script>';
        exit();
    } else {
        $dir = '../../assets/image/';
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $temp = explode(".", $_FILES["gambar"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $target_path = $dir . basename($newfilename);

        if (move_uploaded_file($tmp_name, $target_path)) {
            $data = [
                $_POST['no_plat'],
                $_POST['merk'],
                $_POST['harga'],
                $_POST['tipe'],
                $_POST['deskripsi'],
                $_POST['status'],
                $newfilename // Pastikan ini adalah elemen terakhir
            ];

            // Perhatikan urutan kolom dalam query harus sesuai dengan urutan elemen dalam array $data
            $sql = "INSERT INTO `mobil`(`no_plat`, `merk`, `harga`, `tipe`, `deskripsi`, `status`, `gambar`) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $row = $koneksi->prepare($sql);
            $row->execute($data);
            var_dump($row->errorInfo());
            echo '<script>alert("sukses");window.location="mobil.php"</script>';
        } else {
            echo '<script>alert("Harap Upload Gambar !");window.location="tambah.php"</script>';
        }
    }
}


if ($_GET['aksi'] == 'edit') {

    $id = $_GET['id'];
    $gambar = $_POST['gambar_cek'];

    // Siapkan data untuk di-update
    $data = [
        $_POST['no_plat'],
        $_POST['merk'],
        $_POST['harga'],
        $_POST['tipe'],
        $_POST['deskripsi'],
        $_POST['status']
    ];

    // Mengatur tipe file yang diperbolehkan
    $allowedImageType = [
        "image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/webp"
    ];

    // Cek apakah ada file gambar yang di-upload
    if ($_FILES['gambar']['size'] > 0) {
        $filepath = $_FILES['gambar']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);

        if (!in_array($filetype, $allowedImageType)) {
            echo '<script>alert("You can only upload JPG, PNG, GIF, and WEBP files");window.location="tambah.php"</script>';
            exit();
        } elseif ($_FILES['gambar']["size"] > 4096 * 1024) {
            echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB !");history.go(-1)</script>';
            exit();
        } else {
            $dir = '../../assets/image/';
            $temp = explode(".", $_FILES["gambar"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $target_path = $dir . basename($newfilename);

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
                if (file_exists('../../assets/image/' . $gambar)) {
                    unlink('../../assets/image/' . $gambar);
                }
                $data[] = $newfilename;
            } else {
                echo '<script>alert("Error uploading file");history.go(-1)</script>';
                exit();
            }
        }
    } else {
        $data[] = $gambar;
    }

    // Tambahkan ID ke data
    $data[] = $id;

    // Query update
    $sql = "UPDATE mobil SET no_plat = ?, merk = ?, harga = ?, tipe = ?, deskripsi = ?, status = ?, gambar = ? WHERE id_mobil = ?";
    $row = $koneksi->prepare($sql);
    $row->execute($data);

    echo '<script>alert("sukses");window.location="mobil.php"</script>';
}

if (!empty($_GET['aksi'] == 'hapus')) {
    $id = $_GET['id'];
    $gambar = $_GET['gambar'];

    unlink('../../assets/image/' . $gambar);

    $sql = "DELETE FROM mobil WHERE id_mobil = ?";
    $row = $koneksi->prepare($sql);
    $row->execute(array($id));

    echo '<script>alert("sukses hapus");window.location="mobil.php"</script>';
}



