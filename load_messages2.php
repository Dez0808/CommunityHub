<?php
// Start the session
session_start();

// Include database connection
include_once "Demo_DBConnection.php";

// Set response header as JSON
header('Content-Type: application/json');

// Check database connection
if (!$conn) {
    echo json_encode([
        ["sender" => "Admin", "message" => "Database connection failed.", "time" => date('m/d/Y h:i A')]
    ]);
    exit;
}

// Query to get the latest announcement messages (limited to 50)
$sql = "SELECT m.message, u.First_Name as sender, m.created_at as timestamp
        FROM messages2 m 
        JOIN register u ON m.user_id = u.id 
        ORDER BY m.created_at ASC 
        LIMIT 50";

// For demo purposes, if the table doesn't exist, we'll handle it with sample data
try {
    $result = mysqli_query($conn, $sql);
    $messages = array();

    if ($result && mysqli_num_rows($result) > 0) {
        // Process each message
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = [
                "sender" => $row['sender'],
                "message" => $row['message'],
                "time" => date('m/d/Y h:i A', strtotime($row['timestamp']))
            ];
        }
    } else {
        // Sample data for demo purposes
        $messages[] = ["sender" => "Admin", "message" => "Welcome to the Announcement Channel!", "time" => date('m/d/Y h:i A')];
        $messages[] = ["sender" => "Admin", "message" => "Our next community event will be held on March 20th, 2025.", "time" => date('m/d/Y h:i A', strtotime('-1 day'))];
        $messages[] = ["sender" => "Admin", "message" => "Make sure to check out the new resources in the library section!", "time" => date('m/d/Y h:i A', strtotime('-2 hours'))];
    }

    // Return messages as JSON
    echo json_encode($messages);
} catch (Exception $e) {
    // Handle exceptions with sample data
    $messages = [
        ["sender" => "Admin", "message" => "Welcome to the Announcement Channel!", "time" => date('m/d/Y h:i A')],
        ["sender" => "Admin", "message" => "Our next community event will be held on March 20th, 2025.", "time" => date('m/d/Y h:i A', strtotime('-1 day'))],
        ["sender" => "Admin", "message" => "Make sure to check out the new resources in the library section!", "time" => date('m/d/Y h:i A', strtotime('-2 hours'))]
    ];

    echo json_encode($messages);
}

// Close connection
mysqli_close($conn);
