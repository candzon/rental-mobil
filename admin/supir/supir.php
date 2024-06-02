<?php

require '../../koneksi/koneksi.php';
$title_web = 'Supir';
include '../header.php';
if (empty($_SESSION['USER'])) {
    session_start();
}
?>

<br>
<div class="container">
    <div class="card">
        <div class="card-header text-white bg-primary">
            <div class="row">
                <div class="col">
                    <h5 class="card-title pt-2">
                        Supir
                    </h5>
                </div>
                <div class="col text-right">
                    <a class="btn btn-success" href="tambah.php" role="button">Tambah</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Supir</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = "SELECT * FROM supir ORDER BY id_supir DESC";
                        $row = $koneksi->prepare($sql);
                        $row->execute();
                        $hasil = $row->fetchAll(PDO::FETCH_OBJ);
                        foreach ($hasil as $r) {
                        ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $r->nama; ?></td>
                                <td><?= $r->no_hp; ?></td>
                                <td>
                                    <a href="javascript:void(0);" onclick="confirmDelete('<?= $url; ?>admin/supir/proses.php?id=<?= $r->id_supir; ?>')" class="btn btn-danger btn-sm">Delete Supir</a>
                                </td>
                            </tr>
                        <?php $no++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?php include '../footer.php'; ?>