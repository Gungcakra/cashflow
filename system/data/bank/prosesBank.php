<?php
session_start();
require_once "../../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

// Check if the flagBank is set
if (isset($_POST['flagBank']) && $_POST['flagBank'] === 'add') {
    $nama = $_POST['nama'];
    $saldo = $_POST['saldo'];

    $query = "INSERT INTO bank (nama, saldo) VALUES (?, ?)";

    $result = query($query, [$nama, $saldo]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Bank added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Bank."
        ]);
    }
} else if (isset($_POST['flagBank']) && $_POST['flagBank'] === 'delete') {
    $idBank = $_POST['idBank'];

    $query = "DELETE FROM bank WHERE idBank = ?";
    $result = query($query, [$idBank]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Bank deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Bank: " . mysqli_error($db)
        ]);
    }
} else if ($_POST['flagBank'] && $_POST['flagBank'] === 'update') {
    $idBank = $_POST['idBank'];
    $nama = $_POST['nama'];
    $saldo = $_POST['saldo'];


    $query = "UPDATE bank 
          SET nama = ?, 
              saldo = ?
          WHERE idBank = ?";

    $result = query($query, [$nama, $saldo, $idBank]);


    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Bank updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update Bank: " . mysqli_error($db)
        ]);
    }
} else if ($_POST['flagBank'] && $_POST['flagBank'] === 'transfer'){
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
