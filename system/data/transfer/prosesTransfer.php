<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";

checkUserSession($db);

function getBankSaldo($idBank) {
    return query("SELECT saldo FROM bank WHERE idBank = ?", [$idBank])[0]['saldo'];
}

function updateBankSaldo($idBank, $saldo) {
    return query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$saldo, $idBank]);
}

function insertTransfer($idBankAsal, $idBankTujuan, $nominal, $keterangan) {
    $query = "INSERT INTO transfer (idBankAsal, idBankTujuan, nominal, keterangan) VALUES (?, ?, ?, ?)";
    return query($query, [$idBankAsal, $idBankTujuan, $nominal, $keterangan]);
}

function processTransfer($db) {
    

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
    }
}

processTransfer($db);
