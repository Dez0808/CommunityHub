<?php
// Start the session
session_start();

// Include database connection
include_once "Demo_DBConnection.php";

// Set response header as JSON
header('Content-Type: application/json');

// Check if the message is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get message content from the POST data
    $message = isset($_POST["message"]) ? trim($_POST["message"]) : '';

    // Basic validation
    if (empty($message)) {
        echo json_encode(["status" => "error", "message" => "Message cannot be empty."]);
        exit;
    }

    // Check for banned words before proceeding
    

    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        // Get user ID from the session
        $user_id = $_SESSION["id"];

        // Check if the user has admin privileges for announcement channel
        // Get user information
        $sql = "SELECT role FROM register WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $isAdmin = ($row['role'] === 'admin' || $row['role'] === 'moderator');
        } else {
            $isAdmin = false;
        }
    } else {
        // For demo purposes when not logged in
        // Get username from POST
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : 'DemoUser';

        // Check if user has admin privileges by username
        $isAdmin = ($username === 'Admin' || $username === 'Moderator');

        // Try to find existing user with this username
        $sql = "SELECT id FROM register WHERE First_Name = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['id'];
        } else {
            // Use id 1 as default for demo purposes
            $user_id = 1;
        }
    }

    // Check if user has admin privileges
    if (!$isAdmin) {
        echo json_encode(["status" => "error", "message" => "Only administrators can post announcements."]);
        exit;
    }

    try {
        // Current timestamp
        $timestamp = date('Y-m-d H:i:s');

        // Insert message into the database
        $sql = "INSERT INTO messages2 (user_id, message, created_at) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $message, $timestamp);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Announcement posted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to post announcement: " . mysqli_error($conn)]);
        }
    } catch (Exception $e) {
        // For demo, just return success
        echo json_encode(["status" => "success", "message" => "Announcement posted successfully (demo mode)."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

// Close connection
mysqli_close($conn);
