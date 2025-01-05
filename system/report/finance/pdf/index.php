<?php
session_start();
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";

// Include Dompdf
require_once '../../../../vendor/autoload.php';
use Dompdf\Dompdf;

// CEK USER
checkUserSession($db);

$flagFinance = isset($_POST['flagFinance']) ? $_POST['flagFinance'] : '';
$rentang = isset($_POST['rentang']) ? $_POST['rentang'] : '';
$conditionsCashflow = '';
$params = [];

if ($flagFinance === 'cari') {
    $rangeDate = explode(" - ", $rentang);
    $startDate = date("Y-m-d", strtotime($rangeDate[0]));
    $endDate = date("Y-m-d", strtotime($rangeDate[1]));

    if (!empty($rentang)) {
        $conditionsCashflow .= " WHERE cashflow.tanggal BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
    }
}

$query = "SELECT cashflow.*, 
          bank.idBank,
          bank.nama as bank
          FROM cashflow 
          INNER JOIN bank 
          ON cashflow.idBank = bank.idBank " . $conditionsCashflow . " ORDER BY cashflow.tanggal ASC";

$cashflow = query($query, $params);
// Start generating HTML
ob_start();
?>
<style>
    h4 {
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }

    table {
        border: 1px solid black;
        text-align: left;
        border-collapse: collapse;
        width: 100%;
    }

    table thead th,
    table tbody td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }
</style>

<h4>Finance Report <?= tanggalTerbilang($startDate) . ' - ' . tanggalTerbilang($endDate) ?></h4>
<h4>CashFlow</h4>
<table>
    <thead>
        <tr>
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
        if ($cashflow) {
            foreach ($cashflow as $key => $row) {
                ?>
                <tr>
                    <td><?= ($key + 1) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars(rupiah($row['nominal'])) ?></td>
                    <?php $buttonClass = $row['jenis'] === 'kredit' ? 'success' : 'danger'; ?>
                    <td><span style="color:<?= $buttonClass === 'success' ? 'green' : 'red' ?>;"><?= htmlspecialchars($row['jenis']) ?></span></td>
                    <td><?= htmlspecialchars($row['bank']) ?></td>
                    <td><?= htmlspecialchars(timestampToTanggal($row['tanggal'])) ?></td>
                </tr>
                <?php
                if ($row['jenis'] === 'kredit') {
                    $totalKredit += $row['nominal'];
                } else if ($row['jenis'] === 'debet') {
                    $totalDebet += $row['nominal'];
                }
            }
            ?>
            <tr><td colspan="6"><strong>Total Kredit: <?= rupiah($totalKredit) ?></strong></td></tr>
            <tr><td colspan="6"><strong>Total Debet: <?= rupiah($totalDebet) ?></strong></td></tr>
            <?php
        } else {
            ?>
            <tr><td colspan="6" style="text-align:center;">No data found!</td></tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();

// Generate PDF using Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the PDF
$dompdf->stream('Finance Report ' . tanggalTerbilang($startDate) . ' - ' . tanggalTerbilang($endDate) . '.pdf', ['Attachment' => 1]);
?>
