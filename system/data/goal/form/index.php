<?php
session_start();
require_once "../../../../library/konfigurasi.php";

checkUserSession($db);

$flagGoal = 'add';
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
                        <form id="formGoalInput">
                            <div class="form-row">
                                <input type="hidden" name="flagGoal" id="flagGoal" value="<?= $flagGoal ?>">
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="cashflowname">Name</label>
                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Goal Name" autocomplete="off" value="<?= $data['nama'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="cashflowname">Bank</label>
                                    <select class="form-control" id="idBank" name="idBank">
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
                                    <input type="number" class="form-control" id="nominal" name="nominal" autocomplete="off" placeholder="Goal Nominal" min="0" step="0.01" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" style="appearance: textfield;" value="<?= $data['nominal'] ?? '' ?>">
    
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="keterangan">Target Date</label>
                                    <input type="date" class="form-control" id="tanggalGoal" name="tanggalGoal" autocomplete="off" placeholder="Goal Description">
                                </div>
                    
                            </div>
                        </form>

                        <button type="button" class="btn btn-<?= $flagGoal === 'add' ? 'update' : 'info' ?> btn-primary m-1 mt-3" onclick="prosesGoal()"><i class="ri-save-3-line"></i>Simpan</button>
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
    <script src="<?= BASE_URL_HTML ?>/system/data/goal/goal.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>