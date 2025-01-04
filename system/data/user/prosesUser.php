<?php
session_start();
require_once __DIR__ . "/../../../library/konfigurasi.php";

//CEK USER
checkUserSession($db);

function addUser($db) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (username, password) VALUES (?, ?)";
    $result = query($query, [$username, $hashedPassword]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "User added successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to add User."
        ]);
    }
}

function deleteUser($db) {
    $userId = $_POST['userId'];

    $query = "DELETE FROM user WHERE userId = ?";
    $result = query($query, [$userId]);

    if ($result > 0) {
        echo json_encode([
            "status" => true,
            "pesan" => "User deleted successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to delete User: " . mysqli_error($db)
        ]);
    }
}

function updateUser($db) {
    $userId = $_POST['idUser'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($password) {
        $query = "UPDATE user 
                  SET username = ?, 
                      password = ?
                  WHERE userId = ?";
        $result = query($query, [$username, $hashedPassword, $userId]);
    } else {
        $query = "UPDATE user 
                  SET username = ?
                  WHERE userId = ?";
        $result = query($query, [$username, $userId]);
    }

    if ($result) {
        echo json_encode([
            "status" => true,
            "pesan" => "User updated successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Failed to update User: " . mysqli_error($db)
        ]);
    }
}

// Determine which function to call based on the flagUser value
if (isset($_POST['flagUser'])) {
    switch ($_POST['flagUser']) {
        case 'add':
            addUser($db);
            break;
        case 'delete':
            deleteUser($db);
            break;
        case 'update':
            updateUser($db);
            break;
        default:
            echo json_encode([
                "status" => false,
                "pesan" => "Invalid flagUser value."
            ]);
            break;
    }
} else {
    echo json_encode([
        "status" => false,
        "pesan" => "flagUser is not set."
    ]);
}
?>
