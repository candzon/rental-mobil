    <?php
    require '../../koneksi/koneksi.php';
    $title_web = 'Tambah Supir';
    include '../header.php';

    if (empty($_SESSION['USER'])) {
        session_start();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id_supir = $_GET['id'];

        $sql = "DELETE FROM supir WHERE id_supir = ?";
        $stmt = $koneksi->prepare($sql);

        if ($stmt->execute([$id_supir])) {
            echo '<script>alert("Data Supir berhasil dihapus"); window.location.href="../supir/supir.php";</script>';
        } else {
            echo '<script>alert("Gagal menghapus data supir"); window.location.href="../supir/supir.php";</script>';
        }
    }

    if ($_GET['aksi'] == 'tambah') {
        $nama = $_POST['nama'];
        $no_hp = $_POST['no_hp'];


        $sql = "INSERT INTO supir (nama, no_hp) VALUES (?, ?)";
        $stmt = $koneksi->prepare($sql);

        if ($stmt->execute([$nama, $no_hp])) {
            echo '<script>alert("Data Supir berhasil ditambahkan"); window.location.href="../supir/supir.php";</script>';
        } else {
            echo '<script>alert("Gagal menambahkan data supir"); window.location.href="../supir/supir.php";</script>';
        }
    }
