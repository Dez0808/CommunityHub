<?php
session_start();
include 'Demo_DBConnection.php'; // Using your database connection

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $category = htmlspecialchars($_POST['category']);
    $subject = isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '';
    $message = htmlspecialchars($_POST['message']);
    $status = "Open"; // Default status for new concerns
    $date_submitted = date("Y-m-d H:i:s");
    
    // First, check if the concerns table exists, if not create it
    $check_table = "SHOW TABLES LIKE 'concerns'";
    $table_exists = mysqli_query($conn, $check_table);
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO concerns (name, email, category, subject, message, status, date_submitted) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $category, $subject, $message, $status, $date_submitted);
    
    // Execute the statement
    if ($stmt->execute()) {
        // If AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(["success" => true, "message" => "Concern submitted successfully"]);
            exit;
        } else {
            // If regular form submission
            $_SESSION['success_message'] = "Your concern has been submitted successfully. We will review it and get back to you soon.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        // If AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
            exit;
        } else {
            // If regular form submission
            $_SESSION['error_message'] = "Error: " . $stmt->error;
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    
    $stmt->close();
} else {
    // If accessed directly without form submission
    header("Location: Demo_Index.php");
    exit;
}

$conn->close();
?>