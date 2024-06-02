<?php

require '../../koneksi/koneksi.php';
$title_web = 'Tambah Supir';
include '../header.php';
if (empty($_SESSION['USER'])) {
    session_start();
}
?>


<br>
<div class="container">
    <div class="card">
        <div class="card-header text-white bg-primary">
            <h4 class="card-title">
                Tambah Supir
                <div class="float-right">
                    <a class="btn btn-warning" href="supir.php" role="button">Kembali</a>
                </div>
            </h4>
        </div>
        <div class="card-body">
            <div class="container">
                <form method="post" action="proses.php?aksi=tambah" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nama">Nama Supir</label>
                                <input type="text" class="form-control" name="nama" id="nama" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="no_hp">No HP</label>
                                <input type="text" class="form-control" name="no_hp" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="float-right">
                        <button class="btn btn-primary" role="button" type="submit">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>