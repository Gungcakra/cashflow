<?php
session_start();
require_once "../../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

if ($_POST['flagTransfer'] && $_POST['flagTransfer'] === 'transfer'){
        $idBankAsal = $_POST['idBankAsal'];
        $idBankTujuan = $_POST['idBankTujuan'];
        $nominal = $_POST['nominal'];
        $keterangan = $_POST['keterangan'];
        
        $saldoBankAsal = query("SELECT saldo FROM bank WHERE idBank = ?", [$idBankAsal])[0]['saldo'];
        $saldoBankTujuan = query("SELECT saldo FROM bank WHERE idBank = ?", [$idBankTujuan])[0]['saldo'];
        
        $updateSaldoBankAsal = $saldoBankAsal - $nominal;
        $updateSaldoBankTujuan = $saldoBankTujuan + $nominal;

        $query = "INSERT INTO transfer (idBankAsal, idBankTujuan, nominal, keterangan) VALUES (?, ?, ?, ?)";

        $result = query($query, [$idBankAsal, $idBankTujuan, $nominal, $keterangan]);

        if ($result > 0) {
            $updateBankAsal = query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$updateSaldoBankAsal, $idBankAsal]);
            $updateBankTujuan = query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$updateSaldoBankTujuan, $idBankTujuan]);
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
