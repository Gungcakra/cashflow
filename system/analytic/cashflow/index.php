<?php
session_start();
require_once "../../../library/konfigurasi.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiRupiah.php";
require_once "{$constant('BASE_URL_PHP')}/library/fungsiTanggal.php";


// CEK USER
checkUserSession($db);

$thisMonthIncome = query("SELECT * FROM cashflow WHERE jenis = ? AND MONTH(tanggal) = MONTH(CURRENT_DATE()) ORDER BY tanggal ASC", ['kredit']);
$thisMonthOutcome = query("SELECT * FROM cashflow WHERE jenis = ? AND MONTH(tanggal) = MONTH(CURRENT_DATE()) ORDER BY tanggal ASC", params: ['debet']);

if (!empty($thisMonthIncome)) {
    foreach ($thisMonthIncome as $income) {
        $incomeAmount[] = $income['nominal'];
        $incomeDate[] = timestampToTanggal($income['tanggal']);
        $incomeName[] = $income['nama'];
    }
}

if (!empty($thisMonthOutcome)) {
    foreach ($thisMonthOutcome as $outcome) {
        $outcomeAmount[] = $outcome['nominal'];
        $outcomeDate[] = timestampToTanggal($outcome['tanggal']);
        $outcomeName[] = $outcome['nama'];
    }
}

// Berikan nilai default jika array kosong
if (empty($incomeDate) && empty($outcomeDate)) {
    $defaultDate = timestampToTanggal(date('Y-m-d'));
    $incomeDate = [$defaultDate];
    $outcomeDate = [$defaultDate];
    $incomeAmount = [0];
    $outcomeAmount = [0];
    $incomeName = ['No Data'];
    $outcomeName = ['No Data'];
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

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
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

        <!-- Sidebar  -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/sidebar.php" ?>

        <!-- NAVBAR  -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/navbar.php" ?>

        <div class="content-page">
            <canvas id="profitLossIncomeChart" width="400" height="200"></canvas>

        </div>
    </div>
    <!-- Wrapper End-->

    <!-- Modal list start -->


    <?php require_once "{$constant('BASE_URL_PHP')}/system/footer.php" ?>

    <!-- JQUERY -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('profitLossIncomeChart').getContext('2d');

        const data = {
            labels: <?= json_encode(array_unique(array_merge($incomeDate, $outcomeDate))) ?>,
            datasets: [{
                    label: 'Income',
                    data: <?= json_encode(array_map(function ($date) use ($incomeDate, $incomeAmount, $incomeName) {
                                $index = array_search($date, $incomeDate);
                                return $index !== false
                                    ? ['x' => $date, 'y' => $incomeAmount[$index], 'name' => $incomeName[$index]]
                                    : ['x' => $date, 'y' => 0, 'name' => 'No Data'];
                            }, array_unique(array_merge($incomeDate, $outcomeDate)))) ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4,
                    parsing: {
                        xAxisKey: 'x',
                        yAxisKey: 'y'
                    }
                },
                {
                    label: 'Outcome',
                    data: <?= json_encode(array_map(function ($date) use ($outcomeDate, $outcomeAmount, $outcomeName) {
                                $index = array_search($date, $outcomeDate);
                                return $index !== false
                                    ? ['x' => $date, 'y' => $outcomeAmount[$index], 'name' => $outcomeName[$index]]
                                    : ['x' => $date, 'y' => 0, 'name' => 'No Data'];
                            }, array_unique(array_merge($incomeDate, $outcomeDate)))) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                    tension: 0.4,
                    parsing: {
                        xAxisKey: 'x',
                        yAxisKey: 'y'
                    }
                }
            ]
        };

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Income & Outcome (This Month)'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (context.raw.name) {
                                label += ': ' + context.raw.name + ' - ' + context.raw.y;
                            } else {
                                label += ': ' + context.raw.y;
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount Rp'
                    }
                }
            }
        };

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    </script>

    <script src="<?= BASE_URL_HTML ?>/system/analytic/cashflow/cashflow.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


</body>

</html>