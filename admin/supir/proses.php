<?php
require '../../koneksi/koneksi.php';

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
