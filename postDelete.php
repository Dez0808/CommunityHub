<?php
include "Demo_DBConnection.php";

if (isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];

    // Get all media files associated with this post
    $mediaQuery = "SELECT * FROM media WHERE post_id = ?";
    $stmt = $conn->prepare($mediaQuery);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Delete the files from the server
    while ($row = $result->fetch_assoc()) {
        $filePath = "./uploads/" . $row['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Also check the old images table
    $oldImagesQuery = "SELECT * FROM images WHERE post_id = ?";
    $stmt = $conn->prepare($oldImagesQuery);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Delete the files from the server
    while ($row = $result->fetch_assoc()) {
        $filePath = "./image/" . $row['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete the media records
    $deleteMediaQuery = "DELETE FROM media WHERE post_id = ?";
    $stmt = $conn->prepare($deleteMediaQuery);
    $stmt->bind_param("i", $postId);
    $stmt->execute();

    // Delete the old image records
    $deleteImagesQuery = "DELETE FROM images WHERE post_id = ?";
    $stmt = $conn->prepare($deleteImagesQuery);
    $stmt->bind_param("i", $postId);
    $stmt->execute();

    // Delete the post
    $deletePostQuery = "DELETE FROM post WHERE id = ?";
    $stmt = $conn->prepare($deletePostQuery);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
}

// Redirect back to the posts page
header("Location: Demo_Index.php");
exit();
