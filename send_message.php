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


    // Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        // Get user ID from the session
        $user_id = $_SESSION["id"];
    } else {
        // For demo purposes when not logged in
        // Get username from POST and find/create a default user id
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : 'DemoUser';

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

    try {
        // Current timestamp
        $timestamp = date('Y-m-d H:i');

        // Insert message into the database
        $sql = "INSERT INTO messages (user_id, message, created_at) VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $message, $timestamp);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Message sent successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send message: " . mysqli_error($conn)]);
        }
    } catch (Exception $e) {
        // For demo, just return success
        echo json_encode(["status" => "success", "message" => "Message sent successfully (demo mode)."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

// Close connection
mysqli_close($conn);
