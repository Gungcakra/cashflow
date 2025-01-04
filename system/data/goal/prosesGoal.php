<?php
session_start();
require_once __DIR__ . "/../../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

function addGoal($db) {
    $idBank = $_POST['idBank'];
    $nama = $_POST['nama'];
    $nominal = $_POST['nominal'];
    $tanggalMulai = date('Y-m-d');
    $tanggalGoal = $_POST['tanggalGoal'];
    $status = 0;

    $query = "INSERT INTO goal (idBank, nama, nominal, tanggalMulai, tanggalGoal, status) VALUES (?, ?, ?, ?, ?, ?)";
    $result = query($query, [$idBank, $nama, $nominal, $tanggalMulai, $tanggalGoal, $status]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Goal added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Goal."
        ]);
    }
}

function deleteGoal($db) {
    $idGoal = $_POST['idGoal'];

    $query = "DELETE FROM goal WHERE idGoal = ?";
    $result = query($query, [$idGoal]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "Goal deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Goal: " . mysqli_error($db)
        ]);
    }
}

function updateGoal($db) {
    $idGoal = $_POST['idGoal'];
    $nama = $_POST['nama'];
    $saldo = $_POST['saldo'];

    $query = "UPDATE goal SET nama = ?, saldo = ? WHERE idGoal = ?";
    $result = query($query, [$nama, $saldo, $idGoal]);

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "Goal updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update Goal: " . mysqli_error($db)
        ]);
    }
}

function transferGoal($db) {
    $idGoalAsal = $_POST['idGoalAsal'];
    $idGoalTujuan = $_POST['idGoalTujuan'];
    $nominal = $_POST['nominal'];
    $keterangan = $_POST['keterangan'];

    $saldoGoalAsal = query("SELECT saldo FROM goal WHERE idGoal = ?", [$idGoalAsal])[0]['saldo'];
    $saldoGoalTujuan = query("SELECT saldo FROM goal WHERE idGoal = ?", [$idGoalTujuan])[0]['saldo'];

    $updateSaldoGoalAsal = $saldoGoalAsal - $nominal;
    $updateSaldoGoalTujuan = $saldoGoalTujuan + $nominal;

    $query = "INSERT INTO transfer (idGoalAsal, idGoalTujuan, nominal, keterangan) VALUES (?, ?, ?, ?)";
    $result = query($query, [$idGoalAsal, $idGoalTujuan, $nominal, $keterangan]);

    if ($result > 0) {
        $updateGoalAsal = query("UPDATE goal SET saldo = ? WHERE idGoal = ?", [$updateSaldoGoalAsal, $idGoalAsal]);
        $updateGoalTujuan = query("UPDATE goal SET saldo = ? WHERE idGoal = ?", [$updateSaldoGoalTujuan, $idGoalTujuan]);
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

// Determine which function to call based on the flagGoal value
if (isset($_POST['flagGoal'])) {
    switch ($_POST['flagGoal']) {
        case 'add':
            addGoal($db);
            break;
        case 'delete':
            deleteGoal($db);
            break;
        case 'update':
            updateGoal($db);
            break;
        case 'transfer':
            transferGoal($db);
            break;
        default:
            echo json_encode([
                "status" => false,
                "pesan" => "Invalid flagGoal value."
            ]);
            break;
    }
} else {
    echo json_encode([
        "status" => false,
        "pesan" => "flagGoal is not set."
    ]);
}
?>
