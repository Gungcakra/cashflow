<?php
session_start();
require_once __DIR__ . "/../../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

function addBank($nama, $saldo) {
    $query = "INSERT INTO bank (nama, saldo) VALUES (?, ?)";
    return query($query, [$nama, $saldo]);
}

function deleteBank($idBank) {
    $query = "DELETE FROM bank WHERE idBank = ?";
    return query($query, [$idBank]);
}

function updateBank($idBank, $nama, $saldo) {
    $query = "UPDATE bank SET nama = ?, saldo = ? WHERE idBank = ?";
    return query($query, [$nama, $saldo, $idBank]);
}

function transferBank($idBankAsal, $idBankTujuan, $nominal, $keterangan) {
    $saldoBankAsal = query("SELECT saldo FROM bank WHERE idBank = ?", [$idBankAsal])[0]['saldo'];
    $saldoBankTujuan = query("SELECT saldo FROM bank WHERE idBank = ?", [$idBankTujuan])[0]['saldo'];
    
    $updateSaldoBankAsal = $saldoBankAsal - $nominal;
    $updateSaldoBankTujuan = $saldoBankTujuan + $nominal;

    $query = "INSERT INTO transfer (idBankAsal, idBankTujuan, nominal, keterangan) VALUES (?, ?, ?, ?)";
    $result = query($query, [$idBankAsal, $idBankTujuan, $nominal, $keterangan]);

    if ($result > 0) {
        query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$updateSaldoBankAsal, $idBankAsal]);
        query("UPDATE bank SET saldo = ? WHERE idBank = ?", [$updateSaldoBankTujuan, $idBankTujuan]);
        return true;
    } else {
        return false;
    }
}

// Check if the flagBank is set
if (isset($_POST['flagBank'])) {
    $flagBank = $_POST['flagBank'];

    if ($flagBank === 'add') {
        $nama = $_POST['nama'];
        $saldo = $_POST['saldo'];
        $result = addBank($nama, $saldo);

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
    } else if ($flagBank === 'delete') {
        $idBank = $_POST['idBank'];
        $result = deleteBank($idBank);

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
    } else if ($flagBank === 'update') {
        $idBank = $_POST['idBank'];
        $nama = $_POST['nama'];
        $saldo = $_POST['saldo'];
        $result = updateBank($idBank, $nama, $saldo);

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
    } else if ($flagBank === 'transfer') {
        $idBankAsal = $_POST['idBankAsal'];
        $idBankTujuan = $_POST['idBankTujuan'];
        $nominal = $_POST['nominal'];
        $keterangan = $_POST['keterangan'];
        $result = transferBank($idBankAsal, $idBankTujuan, $nominal, $keterangan);

        if ($result) {
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
?>
