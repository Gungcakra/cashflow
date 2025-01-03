<?php
session_start();
require_once "../../../../library/konfigurasi.php";
checkUserSession($db);

$flagbank = 'transfer';
$bank = query("SELECT * FROM bank", []);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= PAGE_TITLE ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL_HTML ?>/assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/remixicon/fonts/remixicon.css">

    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-calendar/dist/tui-calendar.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-date-picker/dist/tui-date-picker.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-time-picker/dist/tui-time-picker.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>

<body class=" color-light ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Sidebar  -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/sidebar.php" ?>

        <!-- NAVBAR  -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/navbar.php" ?>

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 bg-white p-2">
                        <form id="formBankInput">
                            <div class="form-row">
                                <input type="hidden" name="flagBank" id="flagBank" value="<?= $flagbank ?>">
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="cashflowname">Origin Bank</label>
                                    <select class="form-control" id="idBankAsal" name="idBankAsal">
                                        <option value="">Select Bank</option>
                                        <?php
                                        foreach ($bank as $row) : ?>
                                            <option value="<?= $row['idBank'] ?>" <?= isset($data['idBank']) && $row['idBank'] === $data['idBank'] ? 'selected' : '' ?>><?= $row['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="cashflowname">Destination Bank</label>
                                    <select class="form-control" id="idBankTujuan" name="idBankTujuan">
                                        <option value="">Select Bank</option>
                                        <?php
                                        foreach ($bank as $row) : ?>
                                            <option value="<?= $row['idBank'] ?>" <?= isset($data['idBank']) && $row['idBank'] === $data['idBank'] ? 'selected' : '' ?>><?= $row['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="bankname">Nominal</label>
                                    <input type="number" class="form-control" id="nominal" name="nominal" autocomplete="off" placeholder="Transfer Nominal" min="0" step="0.01" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" style="appearance: textfield;" value="<?= $data['nominal'] ?? '' ?>">

                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="keterangan">Description</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" autocomplete="off" placeholder="Transfer Description">

                                </div>
                    
                            </div>
                        </form>

                        <button type="button" class="btn btn-<?= $flagbank === 'add' ? 'update' : 'info' ?> btn-primary m-1 mt-3" onclick="prosesBank()"><i class="ri-save-3-line"></i>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End-->

    <!-- Modal list start -->

    <!-- Footer  -->
    <?php require_once "{$constant('BASE_URL_PHP')}/system/footer.php" ?>

    <!-- Backend Bundle JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/backend-bundle.min.js"></script>

    <!-- Table Treeview JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/table-treeview.js"></script>

    <!-- Chart Custom JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/customizer.js"></script>

    <!-- Chart Custom JavaScript -->
    <script async src="<?= BASE_URL_HTML ?>/assets/js/chart-custom.js"></script>
    <!-- Chart Custom JavaScript -->
    <script async src="<?= BASE_URL_HTML ?>/assets/js/slider.js"></script>

    <!-- app JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/app.js"></script>

    <script src="<?= BASE_URL_HTML ?>/assets/vendor/moment.min.js"></script>

    <!-- MAIN JS -->
    <script src="<?= BASE_URL_HTML ?>/system/data/bank/bank.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>