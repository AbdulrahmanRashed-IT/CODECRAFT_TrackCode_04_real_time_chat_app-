<?php
include '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
        exit;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password cannot be empty.']);
        exit;
    }

    // Check if username already exists
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists.']);
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    
    if ($insert_stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        $conn->close();
        exit;
    }

    $insert_stmt->bind_param("ss", $username, $hashed_password);

    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $insert_stmt->error]);
    }
    $insert_stmt->close();
}

$conn->close();
?>