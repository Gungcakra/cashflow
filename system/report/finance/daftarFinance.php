<?php
session_start();
require_once "../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";

//CEK USER
checkUserSession($db);

$flagFinance = isset($_POST['flagFinance']) ? $_POST['flagFinance'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$rentang = isset($_POST['rentang']) ? $_POST['rentang'] : '';
$conditionsCashflow = '';
$params = [];


if ($flagFinance === 'cari') {
    $rangeDate = explode(" - ", $rentang);
    $startDate = date("Y-m-d", strtotime($rangeDate[0]));
    $endDate = date("Y-m-d", strtotime($rangeDate[1]));

    if (!empty($rentang)) {
        $searchQuery = '';
        $conditionsCashflow .= " WHERE cashflow.tanggal BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
    }
    else if (!empty($searchQuery)) {
        $rangeDate = '';
        $conditionsCashflow .= " AND cashflow.nama LIKE ?";
        $params[] = "%$searchQuery%";
    }
}

$query = "SELECT cashflow.*, 
          bank.idBank,
          bank.nama as bank
          FROM cashflow 
          INNER JOIN bank 
          ON cashflow.idBank = bank.idBank " . $conditionsCashflow . " ORDER BY cashflow.tanggal ASC";

$cashflow = query($query, $params);
?>
<h4 class="mt-4">CashFlow</h4>
<table id="cashflow-list-table" class="table table-striped dataTable mt-4" role="grid"
    aria-describedby="cashflow-list-page-info">
    <thead>
        <tr class="ligth">
            <th>#</th>
            <th>Name</th>
            <th>Nominal</th>
            <th>Type</th>
            <th>Bank</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalKredit = 0;
        $totalDebet = 0;
        if ($cashflow) { ?>
            <?php foreach ($cashflow as $key => $row): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= rupiah($row['nominal']) ?></td>
                    <?php $buttonClass = $row['jenis'] === 'kredit' ? 'success' : 'danger'; ?>
                    <td><a class="btn p-1 text-white btn-<?= $buttonClass ?>"><?= $row['jenis'] ?></a></td>
                    <td><?= $row['bank'] ?></td>
                    <td><?= timestampToTanggal($row['tanggal']) ?></td>
                </tr>
                <?php if ($row['jenis'] === 'kredit') {
                    $totalKredit += $row['nominal'];
                } ?>
                <?php if ($row['jenis'] === 'debet') {
                    $totalDebet += $row['nominal'];
                } ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="6" class="text-right"><strong>Total Kredit: <?= rupiah($totalKredit) ?></strong></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><strong>Total Debet: <?= rupiah($totalDebet) ?></strong></td>
            </tr>
        <?php } else { ?>
            <tr>
                <td colspan="6" class="text-center">No data found!</td>
            </tr>
        <?php } ?>

    </tbody>
</table>