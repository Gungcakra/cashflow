<?php
session_start();
require_once __DIR__ . "/../../../library/konfigurasi.php";


//CEK USER
checkUserSession($db);

function addCashflow($db) {
    $nama = $_POST['nama'];
    $nominal = $_POST['nominal'];
    $jenis = $_POST['jenis'];
    $idBank = $_POST['idBank'];

    $dataBank = query("SELECT * FROM bank WHERE idBank = ?", [$idBank])[0];

    if($jenis === 'kredit'){
        $saldo = $dataBank['saldo'] + $nominal;
    } else {
        $saldo = $dataBank['saldo'] - $nominal;
    }
    
    $query = "INSERT INTO cashflow (idBank, nama, nominal, jenis) VALUES (?, ?, ?, ?)";
    $result = query($query, [$idBank, $nama, $nominal, $jenis]);

    if ($result > 0) {
        $query = "UPDATE bank SET saldo = ? WHERE idBank = ?";
        $result = query($query, [$saldo, $idBank]);

        echo json_encode([
            "status" => true,
            "pesan" => "Cashflow added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add Cashflow."
        ]);
    }
}

function deleteCashflow($db) {
    $idCashflow = $_POST['idCashflow'];
    $data = query("SELECT * FROM cashflow WHERE idCashflow = ?", [$idCashflow])[0];
    $dataBank = query("SELECT * FROM bank WHERE idBank = ?", [$data['idBank']])[0];

    if($data['jenis'] === 'kredit'){
        $updateSaldo = $dataBank['saldo'] - $data['nominal'];
    } else {
        $updateSaldo = $dataBank['saldo'] + $data['nominal'];
    }

    $query = "UPDATE bank SET saldo = ? WHERE idBank = ?";
    $result = query($query, [$updateSaldo, $data['idBank']]);

    if ($result > 0) {
        $query = "DELETE FROM cashflow WHERE idCashflow = ?";
        $result = query($query, [$idCashflow]);
        echo json_encode([
            "status" => true,
            "pesan" => "Cashflow deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete Cashflow: " . mysqli_error($db)
        ]);
    }
}

function updateCashflow($db) {
    $idCashflow = $_POST['idCashflow'];

    $nama = $_POST['nama'];
    $nominal = $_POST['nominal'];
    $jenis = $_POST['jenis'];
    $idBank = $_POST['idBank'];
    
    $cekData = query("SELECT * FROM cashflow WHERE idCashflow = ?", [$idCashflow])[0];
    $dataBank = query("SELECT * FROM bank WHERE idBank = ?", [$idBank])[0];

    // Revert the previous transaction
    if ($cekData['jenis'] === 'kredit') {
        $dataBank['saldo'] -= $cekData['nominal'];
    } else {
        $dataBank['saldo'] += $cekData['nominal'];
    }

    // Apply the new transaction
    if ($jenis === 'kredit') {
        $saldo = $dataBank['saldo'] + $nominal;
    } else {
        $saldo = $dataBank['saldo'] - $nominal;
    }

    $query = "UPDATE cashflow SET idBank = ?, nama = ?, nominal = ?, jenis = ? WHERE idCashflow = ?";
    $result = query($query, [$idBank, $nama, $nominal, $jenis, $idCashflow]);

    if ($result) {
        $query = "UPDATE bank SET saldo = ? WHERE idBank = ?";
        $result = query($query, [$saldo, $idBank]);
        echo json_encode([
            "status" => true,
            "pesan" => "Cashflow updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update Cashflow: " . mysqli_error($db)
        ]);
    }
}

// Check the flagCashflow and call the appropriate function
if (isset($_POST['flagCashflow'])) {
    if ($_POST['flagCashflow'] === 'add') {
        addCashflow($db);
    } else if ($_POST['flagCashflow'] === 'delete') {
        deleteCashflow($db);
    } else if ($_POST['flagCashflow'] === 'update') {
        updateCashflow($db);
    }
}
?>
