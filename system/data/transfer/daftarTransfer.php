<?php
session_start();
require_once "../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";

//CEK USER
checkUserSession($db);

$flagTransfer = isset($_POST['flagTransfer']) ? $_POST['flagTransfer'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$rentang = isset($_POST['rentang']) ? $_POST['rentang'] : '';
$roleId = isset($_POST['roleId']) ? $_POST['roleId'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];


if ($flagTransfer === 'cari') {

    $rangeDate = explode(" - ", $rentang);
    $startDate = date("Y-m-d", strtotime($rangeDate[0]));
    $endDate = date("Y-m-d", strtotime($rangeDate[1]));

    if (!empty($rangeDate)) {
        $searchQuery = '';
        $conditions .= " WHERE transfer.tanggal BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
    }
    if (!empty($searchQuery)) {
        $rangeDate = '';
        $conditions .= " WHERE nama LIKE ?";
        $params[] = "%$searchQuery%";
    }
}

$totalQuery = "SELECT COUNT(*) as total FROM transfer  
               INNER JOIN bank AS bankAsal ON transfer.idBankAsal = bankAsal.idBank
               INNER JOIN bank AS bankTujuan ON transfer.idBankTujuan = bankTujuan.idBank" . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT transfer.*,
                 transfer.idTransfer,
                 bankAsal.nama AS namaBankAsal,
                 bankTujuan.nama AS namaBankTujuan
          FROM transfer 
          INNER JOIN bank AS bankAsal ON transfer.idBankAsal = bankAsal.idBank
          INNER JOIN bank AS bankTujuan ON transfer.idBankTujuan = bankTujuan.idBank" . $conditions . " LIMIT ? OFFSET ?";


$params[] = $limit;
$params[] = $offset;
$transfer = query($query, $params);
?>

<table id="bank-list-table" class="table table-striped dataTable mt-4" role="grid"
    aria-describedby="bank-list-page-info">
    <thead>
        <tr class="ligth">
            <th>#</th>
            <th style="min-width: 100px">Action</th>
            <th>Origin Bank</th>
            <th>Destination Bank</th>
            <th>Nominal</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if($transfer){ ?>
        <tr>
            <?php foreach ($transfer as $key => $row): ?>
                <td><?= $key + 1 ?></td>
                <td>
                    <div class="btn-group" role="group"></div>
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <!-- <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Add">
                            <i class="ri-bank-add-line mr-0"></i> Add
                        </a> -->
                        <!-- <a class="dropdown-item" href="form/?data=<?= $row['idTransfer'] ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="ri-pencil-line mr-0"></i> Edit
                        </a> -->
                        <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteTransfer(<?= $row['idTransfer'] ?>)">
                            <i class="ri-delete-bin-line mr-0"></i> Delete
                        </a>
                    </div>
                    </div>
                </td>
                <td><?= $row['namaBankAsal'] ?></td>
                <td><?= $row['namaBankTujuan'] ?></td>
                <td><?= rupiah($row['nominal']) ?></td>
                <td><?= $row['keterangan'] ?></td>
                <td><?= timestampToTanggal($row['tanggal']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php } else{ ?>
        <tr>
            <td colspan="10" class="text-center">No data found!</td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div id="bank-list-page-info" class="col-md-6" id="pagination">
    <span>Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRecords) ?> of <?= $totalRecords ?> entries</span>
</div>
<div class="col-md-6" id="pagination">
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <button class="page-link" onclick="loadPage(<?= $page - 1 ?>)">Previous</button>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <button class="page-link" onclick="loadPage(<?= $i ?>)"><?= $i ?></button>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <button class="page-link" onclick="loadPage(<?= $page + 1 ?>)">Next</button>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>