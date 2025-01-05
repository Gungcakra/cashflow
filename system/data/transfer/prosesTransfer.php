<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";

checkUserSession($db);

function getBankSaldo($idBank)
{
    return query("SELECT saldo FROM bank WHERE idBank = ?", [$idBank])[0]['saldo'];
    return $updateSaldoBankTujuan;
}

function updateBankSaldo($idBank, $saldo)
{
    return query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$saldo, $idBank]);
}

function insertTransfer($idBankAsal, $idBankTujuan, $nominal, $keterangan)
{
    $query = "INSERT INTO transfer (idBankAsal, idBankTujuan, nominal, keterangan) VALUES (?, ?, ?, ?)";
    return query($query, [$idBankAsal, $idBankTujuan, $nominal, $keterangan]);
}
function deleteTransfer($idBankAsal)
{
    $dataTransfer = query("SELECT * FROM transfer WHERE idBankAsal = ?", [$idBankAsal])[0];
    $nominalTransfer = $dataTransfer['nominal'];
    $idBankTujuan = $dataTransfer['idBankTujuan'];
    $dataBankAsal = query("SELECT * FROM bank WHERE idBank = ?", [$idBankAsal])[0];
    $dataBankTujuan = query("SELECT * FROM bank WHERE idBank = ?", [$idBankTujuan])[0];
    $kurangSaldoBankTujuan = $dataBankTujuan['saldo'] - $nominalTransfer;
    $tambahSaldoBankAsal = $dataBankAsal['saldo'] + $nominalTransfer;
    $updateSaldoBankAsal = query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$tambahSaldoBankAsal, $idBankAsal]);

    if ($updateSaldoBankAsal > 0) {
        $updateSaldoBankTujuan = query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$kurangSaldoBankTujuan, $idBankTujuan]);
        if ($updateSaldoBankTujuan > 0) {
            return query("DELETE FROM transfer WHERE idTransfer = ?", [$dataTransfer['idTransfer']]);
        }
    }

}

function processTransfer($db)
{


    if ($_POST['flagTransfer'] && $_POST['flagTransfer'] === 'transfer') {
        $idBankAsal = $_POST['idBankAsal'];
        $idBankTujuan = $_POST['idBankTujuan'];
        $nominal = $_POST['nominal'];
        $keterangan = $_POST['keterangan'];

        $saldoBankAsal = getBankSaldo($idBankAsal);
        $saldoBankTujuan = getBankSaldo($idBankTujuan);

        $updateSaldoBankAsal = $saldoBankAsal - $nominal;
        $updateSaldoBankTujuan = $saldoBankTujuan + $nominal;

        $result = insertTransfer($idBankAsal, $idBankTujuan, $nominal, $keterangan);

        if ($result > 0) {
            updateBankSaldo($idBankAsal, $updateSaldoBankAsal);
            updateBankSaldo($idBankTujuan, $updateSaldoBankTujuan);
            echo json_encode([
                "status" => true,
                "pesan" => "Transfer successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed Transfer."
            ]);
        }
    } else if ($_POST['flagTransfer'] && $_POST['flagTransfer'] === 'delete') {
        $idTrasfer = $_POST['idTransfer'];
        $dataTranfer = query("SELECT * FROM transfer WHERE idTransfer = ?", [$idTrasfer])[0];
        $idBankAsal = $dataTranfer['idBankAsal'];
        $result = deleteTransfer($idBankAsal);
        if ($result > 0) {
            echo json_encode([
                "status" => true,
                "pesan" => "Transfer deleted successfully!"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "pesan" => "Failed to delete Transfer."
            ]);
        }
    }
}

processTransfer($db);
