<?php
session_start();
require_once "Demo_DBConnection.php";

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "Unauthorized access";
    exit;
}

// Check if query parameter is provided
if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo '<div class="no-results">
            <i class="fas fa-info-circle" style="font-size: 2rem; color: #6c757d; margin-bottom: 10px;"></i>
            <p>Please enter a search term.</p>
          </div>';
    exit;
}

$search_query = '%' . $_GET['query'] . '%';

// Prepare the search query - searching in relevant fields
$search_sql = "SELECT id, First_Name, Middle_Name, Last_Name, Grade_Level, Strand, profile_pic 
              FROM register 
              WHERE (First_Name LIKE ? OR Middle_Name LIKE ? OR Last_Name LIKE ? OR Grade_Level LIKE ? OR Strand LIKE ?) 
              AND id != ? 
              LIMIT 8";

$search_stmt = mysqli_prepare($conn, $search_sql);
mysqli_stmt_bind_param($search_stmt, "sssssi", $search_query, $search_query, $search_query, $search_query, $search_query, $_SESSION["id"]);
mysqli_stmt_execute($search_stmt);
$search_result = mysqli_stmt_get_result($search_stmt);

// Display search results
if (mysqli_num_rows($search_result) > 0) {
    echo '<h3 style="margin: 15px 0; font-size: 1.1rem;">Search Results</h3>';
    echo '<div class="user-results">';
    
    while ($user_result = mysqli_fetch_assoc($search_result)) {
        $full_name = htmlspecialchars($user_result["First_Name"] . ' ' . $user_result["Middle_Name"] . ' ' . $user_result["Last_Name"]);
        $profile_pic = !empty($user_result["profile_pic"]) ? './uploads/' . htmlspecialchars($user_result["profile_pic"]) : '';
        
        echo '<div class="user-card">';
        
        // Profile picture
        echo '<div class="user-avatar">';
        if (!empty($profile_pic)) {
            echo '<img src="' . $profile_pic . '" alt="Profile">';
        } else {
            echo '<i class="fas fa-user"></i>';
        }
        echo '</div>';
        
        // User info
        echo '<div class="user-info">';
        echo '<div class="user-name">' . $full_name . '</div>';
        echo '<div class="user-details">' . htmlspecialchars($user_result["Grade_Level"]) . ' • ' . htmlspecialchars($user_result["Strand"]) . '</div>';
        echo '</div>';
        
        // View profile button
        echo '<a href="view-profile.php?id=' . $user_result["id"] . '" class="view-profile-btn">View Profile</a>';
        
        echo '</div>';
    }
    
    echo '</div>'; // End user-results
} else {
    echo '<div class="no-results">
            <i class="fas fa-user-slash" style="font-size: 2rem; color: #6c757d; margin-bottom: 10px;"></i>
            <p>No users found matching your search criteria.</p>
          </div>';
}
?>