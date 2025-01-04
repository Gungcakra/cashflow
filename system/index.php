<?php
session_start();
require_once ".././library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
checkUserSession($db);

$thisMonthBalance = query("SELECT SUM(saldo) as totalBalance FROM bank")[0];

$thisMonthRevenue = query("SELECT (SUM(CASE WHEN jenis = 'kredit' THEN nominal ELSE 0 END) - SUM(CASE WHEN jenis = 'debet' THEN nominal ELSE 0 END)) as totalRevenue FROM cashflow WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())")[0];

$lastMonthRevenue = query("SELECT (SUM(CASE WHEN jenis = 'kredit' THEN nominal ELSE 0 END) - SUM(CASE WHEN jenis = 'debet' THEN nominal ELSE 0 END)) as totalRevenue FROM cashflow WHERE MONTH(tanggal) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)")[0];

$thisMonthLoss = query("SELECT SUM(CASE WHEN jenis = 'debet' THEN nominal ELSE 0 END) as totalLoss FROM cashflow WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())")[0];

$lastMonthLoss = query("SELECT SUM(CASE WHEN jenis = 'debet' THEN nominal ELSE 0 END) as totalLoss FROM cashflow WHERE MONTH(tanggal) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)")[0];

$thisMonthTotalBalance = $thisMonthBalance['totalBalance'];

$lastMonthTotalRevenue = $lastMonthRevenue['totalRevenue'];
$revenuePrecentance = 0;
if ($lastMonthTotalRevenue == 0 && $thisMonthRevenue['totalRevenue'] == 0) {
    $revenuePrecentance = 0;
} elseif ($lastMonthTotalRevenue == 0 && $thisMonthRevenue['totalRevenue'] > 0) {
    $revenuePrecentance = 100;
} elseif ($lastMonthTotalRevenue > 0) {
    $revenuePrecentance = (($thisMonthRevenue['totalRevenue'] - $lastMonthTotalRevenue) / $lastMonthTotalRevenue) * 100;
}

$lastMonthTotalLoss = $lastMonthLoss['totalLoss'];
$lossPrecentance = 0;
if ($lastMonthTotalLoss == 0 && $thisMonthLoss['totalLoss'] == 0) {
    $lossPrecentance = 0;
} elseif ($lastMonthTotalLoss == 0 && $thisMonthLoss['totalLoss'] > 0) {
    $lossPrecentance = 100;
} elseif ($lastMonthTotalLoss > 0) {
    $lossPrecentance = (($thisMonthLoss['totalLoss'] - $lastMonthTotalLoss) / $lastMonthTotalLoss) * 100;
}


$todayIncome = query("SELECT (SUM(CASE WHEN jenis = 'kredit' THEN nominal ELSE 0 END) - SUM(CASE WHEN jenis = 'debet' THEN nominal ELSE 0 END)) as totalIncome FROM cashflow WHERE DATE(tanggal) = CURRENT_DATE()")[0];

$todayIncomeAmount = $todayIncome['totalIncome'] ?? 0; // Jika hasil query null, set ke 0
$todayIncomeAmount = max($todayIncomeAmount, 0); // Pastikan tidak negatif

$yesterdayIncome = query("SELECT (SUM(CASE WHEN jenis = 'kredit' THEN nominal ELSE 0 END) - SUM(CASE WHEN jenis = 'debet' THEN nominal ELSE 0 END)) as totalIncome FROM cashflow WHERE DATE(tanggal) = CURRENT_DATE() - INTERVAL 1 DAY")[0];

$yesterdayIncomeAmount = $yesterdayIncome['totalIncome'] ?? 0; // Jika hasil query null, set ke 0
$yesterdayIncomeAmount = max($yesterdayIncomeAmount, 0); // Pastikan tidak negatif

$incomePercentage = 0;

// Hitung persentase berdasarkan kondisi
if ($yesterdayIncomeAmount == 0 && $todayIncomeAmount == 0) {
    $incomePercentage = 0; // Jika kemarin dan hari ini 0
} elseif ($yesterdayIncomeAmount == 0 && $todayIncomeAmount > 0) {
    $incomePercentage = 100; // Jika kemarin 0 dan hari ini ada income
} elseif ($yesterdayIncomeAmount > 0 && $todayIncomeAmount == 0) {
    $incomePercentage = 0; // Jika kemarin ada income dan hari ini tidak ada
} elseif ($yesterdayIncomeAmount > 0) {
    // Hitung persentase pertumbuhan dengan formula standar
    $incomePercentage = (($todayIncomeAmount - $yesterdayIncomeAmount) / $yesterdayIncomeAmount) * 100;
}


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
</head>

<body class="  ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">

        <!-- SIDEBAR -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/sidebar.php"; ?>

        <!-- NAVBAR -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/navbar.php"; ?>

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="top-block d-flex align-items-center justify-content-between">
                                    <h5>Balance</h5>
                                    <span class="badge badge-primary">All Banks</span>
                                </div>
                                <h3><span class=""><?= rupiah($thisMonthTotalBalance ?? 0) ?></span></h3>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <p class="mb-0">Total Balance</p>
                                    <span class="text-primary">-</span>
                                </div>
                                <div class="iq-progress-bar bg-primary-light mt-2">
                                    <span class="bg-primary iq-progress progress-1" data-percent="100"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="top-block d-flex align-items-center justify-content-between">
                                    <h5>Profit</h5>
                                    <span class="badge badge-success">Monthly</span>
                                </div>
                                <h3><span class=""><?= rupiah($thisMonthRevenue['totalRevenue'] ?? 0) ?></span></h3>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <p class="mb-0">Total Profit</p>
                                    <span class="text-success"><?= bulatkanPresentase($revenuePrecentance) ?>%</span>
                                </div>
                                <div class="iq-progress-bar bg-success-light mt-2">
                                    <span class="bg-success iq-progress progress-1" data-percent="<?= min(bulatkanPresentase($revenuePrecentance), 100) ?>"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="top-block d-flex align-items-center justify-content-between">
                                    <h5>Loss</h5>
                                    <span class="badge badge-danger">Monthly</span>
                                </div>
                                <h3><span class=""><?= rupiah($thisMonthLoss['totalLoss'] ?? 0) ?></span></h3>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <p class="mb-0">Total Loss</p>
                                    <span class="text-danger"><?= bulatkanPresentase($lossPrecentance) ?>%</span>
                                </div>
                                <div class="iq-progress-bar bg-danger-light mt-2">
                                    <span class="bg-danger iq-progress progress-1" data-percent="<?= min(bulatkanPresentase($lossPrecentance), 100) ?>"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="top-block d-flex align-items-center justify-content-between">
                                    <h5>Income</h5>
                                    <span class="badge badge-warning">Today</span>
                                </div>
                                <h3><span class=""><?= rupiah($todayIncomeAmount ?? 0) ?></span></h3>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <p class="mb-0">Total Income</p>
                                    <span class="text-warning"><?= bulatkanPresentase($incomePercentage) ?>%</span>
                                </div>
                                <div class="iq-progress-bar bg-warning-light mt-2">
                                    <span class="bg-warning iq-progress progress-1" data-percent="<?= min(bulatkanPresentase($incomePercentage), 100) ?>"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Page end  -->
                <div class="row">
                    <div class="col-md-12">
                        <canvas id="combinedChart"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const combinedChartCtx = document.getElementById('combinedChart').getContext('2d');

                    new Chart(combinedChartCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Balance', 'Profit', 'Loss', 'Income'],
                            datasets: [{
                                label: 'Total Balance',
                                data: [<?= $thisMonthTotalBalance ?>, 0, 0, 0],
                                backgroundColor: 'rgba(54, 162, 235, 1)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Total Profit',
                                data: [0, <?= $thisMonthRevenue['totalRevenue'] ?>, 0, 0],
                                backgroundColor: 'rgba(75, 192, 192, 1)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Total Loss',
                                data: [0, 0, <?= $thisMonthLoss['totalLoss'] ?>, 0],
                                backgroundColor: 'rgba(255, 99, 132, 1)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Total Income',
                                data: [0, 0, 0, <?= $todayIncomeAmount ?>],
                                backgroundColor: 'rgba(255, 206, 86, 1)',
                                borderColor: 'rgba(255, 206, 86, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Financial Overview'
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>
    <!-- Wrapper End-->

    <!-- Modal list start -->
    <!-- <div class="modal fade" role="dialog" aria-modal="true" id="new-project-modal">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-block text-center pb-3 border-bttom">
                    <h3 class="modal-title" id="exampleModalCenterTitle01">New Project</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText01" class="h5">Project Name*</label>
                                <input type="text" class="form-control" id="exampleInputText01" placeholder="Project Name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Categories *</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Category</option>
                                    <option>Android</option>
                                    <option>IOS</option>
                                    <option>Ui/Ux Design</option>
                                    <option>Development</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText004" class="h5">Due Dates*</label>
                                <input type="date" class="form-control" id="exampleInputText004" value="">
                            </div>                        
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText07" class="h5">Assign Members*</label>
                                <input type="text" class="form-control" id="exampleInputText07">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-2">
                                <div class="btn btn-primary mr-3" data-dismiss="modal">Save</div>
                                <div class="btn btn-primary" data-dismiss="modal">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="new-task-modal">
        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-block text-center pb-3 border-bttom">
                    <h3 class="modal-title" id="exampleModalCenterTitle">New Task</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText02" class="h5">Task Name</label>
                                <input type="text" class="form-control" id="exampleInputText02" placeholder="Enter task Name">
                                <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Assigned to</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Memebers</option>
                                    <option>Kianna Septimus</option>
                                    <option>Jaxson Herwitz</option>
                                    <option>Ryan Schleifer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="exampleInputText05" class="h5">Due Dates*</label>
                                <input type="date" class="form-control" id="exampleInputText05" value="">
                            </div>                        
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Category</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Design</option>
                                    <option>Android</option>
                                    <option>IOS</option>
                                    <option>Ui/Ux Design</option>
                                    <option>Development</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText040" class="h5">Description</label>
                                <textarea class="form-control" id="exampleInputText040" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText005" class="h5">Checklist</label>
                                <input type="text" class="form-control" id="exampleInputText005" placeholder="Add List">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-0">
                                <label for="exampleInputText01" class="h5">Attachments</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile003">
                                    <label class="custom-file-label" for="inputGroupFile003">Upload media</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-4">
                                <div class="btn btn-primary mr-3" data-dismiss="modal">Save</div>
                                <div class="btn btn-primary" data-dismiss="modal">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="new-user-modal">
        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-block text-center pb-3 border-bttom">
                    <h3 class="modal-title" id="exampleModalCenterTitle02">New User</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3 custom-file-small">
                                <label for="exampleInputText01" class="h5">Upload Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile02">
                                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Full Name</label>
                                <input type="text" class="form-control" id="exampleInputText2" placeholder="Enter your full name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText04" class="h5">Phone Number</label>
                                <input type="text" class="form-control" id="exampleInputText04" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText006" class="h5">Email</label>
                                <input type="text" class="form-control" id="exampleInputText006" placeholder="Enter your Email">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Type</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Type</option>
                                    <option>Trainee</option>
                                    <option>Employee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Role</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Role</option>
                                    <option>Designer</option>
                                    <option>Developer</option>
                                    <option>Manager</option>
                                    <option>BDE</option>
                                    <option>SEO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-2">
                                <div class="btn btn-primary mr-3" data-dismiss="modal">Save</div>
                                <div class="btn btn-primary" data-dismiss="modal">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="new-create-modal">
        <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-block text-center pb-3 border-bttom">
                    <h3 class="modal-title" id="exampleModalCenterTitle03">New Task</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText03" class="h5">Task Name</label>
                                <input type="text" class="form-control" id="exampleInputText03" placeholder="Enter task Name">
                                <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Assigned to</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Memebers</option>
                                    <option>Kianna Septimus</option>
                                    <option>Jaxson Herwitz</option>
                                    <option>Ryan Schleifer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Project Name</label>
                                <select name="type" class="selectpicker form-control" data-style="py-0">
                                    <option>Enter your project Name</option>
                                    <option>Ui/Ux Design</option>
                                    <option>Dashboard Templates</option>
                                    <option>Wordpress Themes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText40" class="h5">Description</label>
                                <textarea class="form-control" id="exampleInputText40" rows="2" placeholder="Textarea"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText8" class="h5">Checklist</label>
                                <input type="text" class="form-control" id="exampleInputText8" placeholder="Add List">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-0">
                                <label for="exampleInputText01" class="h5">Attachments</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile01">
                                    <label class="custom-file-label" for="inputGroupFile01">Upload media</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-4">
                                <div class="btn btn-primary mr-3" data-dismiss="modal">Save</div>
                                <div class="btn btn-primary" data-dismiss="modal">Cancel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <?php require_once "{$constant('BASE_URL_PHP')}/system//footer.php"; ?>
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
</body>

</html>