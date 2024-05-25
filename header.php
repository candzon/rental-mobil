<!doctype html>
<html lang="en">

<head>
    <title>Rental Mobil</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!-- Datetime picker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
    </script>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>

    <div class="jumbotron pt-4 pb-4">
        <div class="row">
            <div class="col-sm-8">
                <h2><b style="text-transform:uppercase;"><?= $info_web->nama_rental; ?> </b></h2>
            </div>
            <div class="col-sm-4">
                <form class="form-inline" method="get" action="blog.php">
                    <input class="form-control mr-sm-2" type="search" name="cari" placeholder="Cari Nama Mobil" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
    <div style="margin-top:-2pc"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#"></a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="blog.php">Daftar Mobil</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="kontak.php">Kontak Kami</a>
                </li>
                <?php if (!empty($_SESSION['USER'])) { ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="history.php">History</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="profil.php">Profil</a>
                    </li>
                <?php } ?>
            </ul>
            <?php if (!empty($_SESSION['USER'])) { ?>
                <ul class="navbar-nav my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-user"> </i> Hallo, <?php echo $_SESSION['USER']['nama_pengguna']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="return confirm('Apakah anda ingin logout ?');" href="<?php echo $url; ?>admin/logout.php">Logout</a>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </nav>

    <script>
        function toggleRekeningInput() {
            var metode = document.getElementById('metode').value;
            var rekeningRow = document.getElementById('rekeningRow');
            var form = document.getElementById('paymentForm');

            if (metode === 'Bank Transfer') {
                rekeningRow.classList.remove('hidden');
                document.getElementById('no_rekening').setAttribute('required', 'required');
                form.action = "koneksi/proses.php?id=bayar_transfer";
            } else if (metode === 'Cash') {
                rekeningRow.classList.add('hidden');
                document.getElementById('no_rekening').removeAttribute('required');
                form.action = "koneksi/proses.php?id=bayar_cash";
            } else {
                rekeningRow.classList.add('hidden');
                form.action = "";
            }
        }

        // Ensure the correct initial state when the page loads
        window.onload = function() {
            toggleRekeningInput();
        }
    </script>
    <!-- <script>
        function toggleRekeningInput() {
            var metode = document.getElementById('metode').value;
            var rekeningRow = document.getElementById('rekeningRow');
            if (metode === 'Transfer') {
                rekeningRow.classList.remove('hidden');
            } else {
                rekeningRow.classList.add('hidden');
            }
        }

        // Ensure the correct initial state when the page loads
        window.onload = function() {
            toggleRekeningInput();
        }
    </script> -->
</body>