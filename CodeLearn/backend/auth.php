<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();
require_once 'config.php';

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

if ($action === 'login') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
}

else if ($action === 'logout') {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    echo json_encode(['success' => true]);
}

else if ($action === 'check_session') {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'username' => $_SESSION['username']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}

else if ($action === 'signup') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }

    // Hash password and create user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    
    try {
        $stmt->execute([$username, $hashedPassword]);
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error creating account']);
    }
}
// ... existing code ...

else if ($action === 'login') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, username, password, avatar FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['avatar'] = $user['avatar'];
        echo json_encode(['success' => true, 'avatar' => $user['avatar']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
}

else if ($action === 'check_session') {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'username' => $_SESSION['username'],
            'avatar' => $_SESSION['avatar'] ?? 'avatar1.png'
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}

else if ($action === 'update_avatar') {
    if (isset($_SESSION['user_id'])) {
        $avatar = $data['avatar'] ?? '';
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        try {
            $stmt->execute([$avatar, $_SESSION['user_id']]);
            $_SESSION['avatar'] = $avatar;
            echo json_encode(['success' => true]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error updating avatar']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
    }
}
?>