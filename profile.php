<?php
session_start();
require_once "Demo_DBConnection.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /CommunityHub/Demo_Login.php");
    exit;
}

$sql = "SELECT * FROM register WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle profile picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
    $target_dir = "./uploads/"; // Adjusted path
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create directory if it doesn't exist
    }
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $upload_message = "";

    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check === false) {
        $upload_message = "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["profile_pic"]["size"] > 5000000) { // Limit to 5MB
        $upload_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $upload_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $upload_message = "Sorry, your file was not uploaded. " . $upload_message;
    } else {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic_name = basename($_FILES["profile_pic"]["name"]);
            $update_sql = "UPDATE register SET profile_pic = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "si", $profile_pic_name, $_SESSION["id"]);
            mysqli_stmt_execute($update_stmt);
            $user["profile_pic"] = basename($_FILES["profile_pic"]["name"]);
            $upload_message = "Profile picture updated successfully!";
        } else {
            $upload_message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle profile information update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {
    // Get form data
    $first_name = $_POST["First_Name"];
    $middle_name = $_POST["Middle_Name"];
    $last_name = $_POST["Last_Name"];
    $email = $_POST["Email"];
    $contact = $_POST["Contact"];
    $dob = $_POST["DOB"];
    $age = $_POST["Age"];
    $gender = $_POST["Gender"];
    $enrolled = $_POST["Enrolled"];
    $grade_level = $_POST["Grade_Level"];
    $lrn = $_POST["LRN"];
    $strand = $_POST["Strand"];

    // Update user information in database
    $update_sql = "UPDATE register SET 
                  First_Name = ?, 
                  Middle_Name = ?, 
                  Last_Name = ?, 
                  Email = ?, 
                  Contact = ?, 
                  DOB = ?, 
                  Age = ?, 
                  Gender = ?, 
                  Enrolled = ?, 
                  Grade_Level = ?, 
                  LRN = ?, 
                  Strand = ? 
                  WHERE id = ?";

    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssssisssssi",
        $first_name,
        $middle_name,
        $last_name,
        $email,
        $contact,
        $dob,
        $age,
        $gender,
        $enrolled,
        $grade_level,
        $lrn,
        $strand,
        $_SESSION["id"]
    );

    if (mysqli_stmt_execute($update_stmt)) {
        $update_message = "Profile updated successfully!";
        // Refresh user data
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    } else {
        $update_message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
    <title>Profile | Community Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Profile Page Styling */
        :root {
            --primary-color: #1e8b4d;
            --primary-dark: #176437;
            --primary-light: #c5e8d3;
            --secondary-color: #4c7f66;
            --accent-color: #3498db;
            --text-color: #333333;
            --text-light: #6c757d;
            --background-color: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #dee2e6;
            --success-color: #28a745;
            --error-color: #dc3545;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Base Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        /* Typography */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0 0 1rem;
            font-weight: 600;
            line-height: 1.2;
            color: var(--primary-dark);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        h2 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            margin-bottom: 1.5rem;
        }

        h2 i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-top: 0;
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        /* Profile Content Layout */
        .profile-content {
            display: flex;
            gap: 30px;
        }

        .profile-sidebar {
            flex: 0 0 300px;
        }

        .profile-details {
            flex: 1;
        }

        /* Profile Picture Styling */
        .profile-picture-container {
            position: relative;
            width: 220px;
            height: 220px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            background-color: var(--primary-light);
            box-shadow: var(--shadow);
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .profile-pic-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
        }

        .profile-pic-placeholder i {
            font-size: 5rem;
            color: var(--primary-dark);
        }

        .profile-pic-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            text-align: center;
            opacity: 0;
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-pic-overlay i {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .profile-picture-container:hover .profile-pic-overlay {
            opacity: 1;
        }

        .profile-pic-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-pic-input {
            display: none;
        }

        .upload-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
            margin-top: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .upload-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .upload-message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 0.9rem;
        }

        .upload-message.success {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .upload-message.error {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--error-color);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        /* Profile Sections & Info Cards */
        .profile-section {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-card {
            display: flex;
            flex-direction: column;
            padding: 15px;
            border-radius: 8px;
            background-color: var(--background-color);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
            border-color: var(--primary-light);
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--primary-dark);
        }

        /* Edit Mode Styles */
        .edit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            margin-left: auto;
        }

        .edit-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .edit-btn i {
            font-size: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
        }

        .save-btn {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .save-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .cancel-btn {
            background-color: var(--text-light);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .cancel-btn:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .edit-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .edit-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 139, 77, 0.2);
        }

        .edit-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            background-color: white;
            transition: var(--transition);
        }

        .edit-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 139, 77, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container {
                max-width: 95%;
            }
        }

        @media (max-width: 992px) {
            .profile-content {
                flex-direction: column;
            }

            .profile-sidebar {
                flex: 0 0 auto;
                margin-bottom: 30px;
            }

            .navbar-links {
                gap: 20px;
                margin-right: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 120px;
            }

            .navbar {
                padding: 10px;
            }

            .navbar img {
                height: 50px;
            }

            .navbar-links {
                gap: 15px;
                margin-right: 10px;
            }

            .navbar-links a,
            .drop {
                font-size: 14px;
                padding: 8px;
            }

            .profile-picture-container {
                width: 180px;
                height: 180px;
            }

            h1 {
                font-size: 2rem;
            }

            .info-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 15px;
            }

            .profile-picture-container {
                width: 150px;
                height: 150px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .profile-section {
                padding: 20px 15px;
            }

            .navbar {
                justify-content: center;
                flex-wrap: wrap;
            }

            .navbar img {
                margin-bottom: 10px;
            }

            .navbar-links {
                width: 100%;
                justify-content: center;
                gap: 10px;
                margin-right: 0;
            }

            .navbar-links a,
            .drop {
                font-size: 13px;
                padding: 5px;
            }
        }

        /* Animations and effects */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .profile-picture-container:hover .profile-pic {
            filter: brightness(0.8);
        }

        .upload-btn:active {
            transform: scale(0.95);
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
            background-color: var(--card-bg);
        }

        .search-container input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(30, 139, 77, 0.2);
        }

        .search-container button:hover {
            background-color: var(--primary-dark);
        }

        .toggle-search-btn {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
            width: 100%;
        }

        .toggle-search-btn:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .toggle-search-btn i {
            font-size: 1.1rem;
        }

        .search-container {
            max-height: 0;
            opacity: 0;
            transition: max-height 0.3s ease, opacity 0.3s ease, margin 0.3s ease;
            margin-top: 0;
            overflow: hidden;
        }

        .search-container.show {
            max-height: 500px;
            opacity: 1;
            margin-top: 15px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
                max-height: 0;
            }

            to {
                opacity: 1;
                transform: translateY(0);
                max-height: 500px;
            }
        }

        .user-card {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: var(--background-color);
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
            background-color: var(--card-bg);
            border-color: var(--primary-light);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar i {
            font-size: 1.5rem;
            color: var(--primary-dark);
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 3px;
        }

        .user-details {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .view-profile-btn {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .view-profile-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            background-color: var(--background-color);
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>
    <div class="container">
        <?php include "index.php" ?>


        <div class="profile-header">
            <div class="profile-intro">
                <h1>Welcome, <?php echo htmlspecialchars($user["First_Name"]); ?></h1>
                <p class="subtitle">Manage your personal information</p>
            </div>

            <!-- Edit Profile Button -->
            <button id="editProfileBtn" class="edit-btn">
                <i class="fas fa-edit"></i> Edit Profile
            </button>
        </div>

        <!-- Display update message if exists -->
        <?php if (isset($update_message)): ?>
            <div class="upload-message <?php echo strpos($update_message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $update_message; ?>
            </div>
        <?php endif; ?>

        <div class="profile-section">
            <h2><i class="fas fa-search"></i> Find Other Student</h2>

            <button id="toggleSearchBtn" class="toggle-search-btn">
                <i class="fas fa-search"></i> Click to Search for Users
            </button>

            <div id="searchContainer" class="search-container" style="display: none; margin-top: 15px;">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search by name, grade level, or strand..."
                        style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color);" autocomplete="off">
                    <button
                        id="searchBtn"
                        type="button"
                        style="background-color: var(--primary-color); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div id="searchResults"></div>
                <div id="loadingIndicator" style="display: none; text-align: center; padding: 20px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
                    <p>Searching...</p>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-picture-container">
                    <?php if (!empty($user["profile_pic"])): ?>
                        <img src="./uploads/<?php echo htmlspecialchars($user["profile_pic"]); ?>" alt="Profile Picture" class="profile-pic" id="profileImage">
                    <?php else: ?>
                        <div class="profile-pic-placeholder" id="profileImage">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                    <div class="profile-pic-overlay" id="profileOverlay">
                        <i class="fas fa-camera"></i>
                        <span>Change Photo</span>
                    </div>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" class="profile-pic-form" id="profileForm">
                    <input type="file" name="profile_pic" accept="image/*" required id="profilePicInput" class="profile-pic-input">
                    <button type="submit" class="upload-btn" id="uploadBtn" style="display: none;">
                        <i class="fas fa-upload"></i> Upload Photo
                    </button>
                </form>

                <?php if (isset($upload_message)): ?>
                    <div class="upload-message <?php echo strpos($upload_message, 'Sorry') !== false ? 'error' : 'success'; ?>">
                        <?php echo $upload_message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-details">
                <!-- Profile Information Form -->
                <form id="profileInfoForm" action="" method="POST">
                    <input type="hidden" name="update_profile" value="1">

                    <div class="profile-section">
                        <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                        <div class="info-grid" id="personalInfoGrid">
                            <!-- View Mode -->
                            <div class="info-card" data-field="First_Name">
                                <span class="info-label">First Name</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["First_Name"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Middle_Name">
                                <span class="info-label">Middle Name</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Middle_Name"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Last_Name">
                                <span class="info-label">Last Name</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Last_Name"]); ?></span>
                            </div>
                            <div class="info-card" data-field="DOB">
                                <span class="info-label">Date of Birth</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["DOB"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Age">
                                <span class="info-label">Age</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Age"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Gender">
                                <span class="info-label">Gender</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Gender"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Email">
                                <span class="info-label">Email</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Email"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Contact">
                                <span class="info-label">Contact Number</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Contact"]); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-section">
                        <h2><i class="fas fa-school"></i> School Details</h2>
                        <div class="info-grid" id="schoolInfoGrid">
                            <!-- View Mode -->
                            <div class="info-card" data-field="Enrolled">
                                <span class="info-label">Status at Lagro High School</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Enrolled"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Grade_Level">
                                <span class="info-label">Grade Level</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Grade_Level"]); ?></span>
                            </div>
                            <div class="info-card" data-field="LRN">
                                <span class="info-label">LRN</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["LRN"]); ?></span>
                            </div>
                            <div class="info-card" data-field="Strand">
                                <span class="info-label">Strand</span>
                                <span class="info-value"><?php echo htmlspecialchars($user["Strand"]); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons (Hidden by default) -->
                    <div class="action-buttons" style="display: none;">
                        <button type="button" id="cancelEditBtn" class="cancel-btn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="save-btn">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile picture upload functionality
            const profileOverlay = document.getElementById('profileOverlay');
            const profilePicInput = document.getElementById('profilePicInput');
            const profileImage = document.getElementById('profileImage');
            const profileForm = document.getElementById('profileForm');

            // Trigger file input when clicking on the overlay
            if (profileOverlay) {
                profileOverlay.addEventListener('click', function() {
                    profilePicInput.click();
                });
            }

            // Preview selected image before upload
            if (profilePicInput) {
                profilePicInput.addEventListener('change', function() {
                    const uploadBtn = document.querySelector('.upload-btn');

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            // If profile image is an actual image element
                            if (profileImage.tagName === 'IMG') {
                                profileImage.src = e.target.result;
                            }
                            // If it's the placeholder div
                            else {
                                // Create a new image element
                                const newImg = document.createElement('img');
                                newImg.src = e.target.result;
                                newImg.classList.add('profile-pic');
                                newImg.id = 'profileImage';

                                // Replace the placeholder with the new image
                                profileImage.parentNode.replaceChild(newImg, profileImage);
                            }
                        };

                        reader.readAsDataURL(this.files[0]);

                        // Show file name if there's a selected file
                        if (this.files[0].name) {
                            if (uploadBtn) {
                                uploadBtn.innerHTML = `<i class="fas fa-upload"></i> Upload "${this.files[0].name.substring(0, 15)}${this.files[0].name.length > 15 ? '...' : ''}"`;
                                uploadBtn.style.display = 'flex'; // Show the button
                            }
                        }
                    } else {
                        // Hide the button if no file is selected
                        if (uploadBtn) {
                            uploadBtn.style.display = 'none';
                        }
                    }
                });
            }

            // Auto-hide flash messages after 5 seconds
            const messages = document.querySelectorAll('.upload-message');
            if (messages.length > 0) {
                setTimeout(() => {
                    messages.forEach(msg => {
                        msg.style.opacity = '0';
                        setTimeout(() => {
                            msg.style.display = 'none';
                        }, 500);
                    });
                }, 5000);
            }

            // Add subtle animation to profile sections on page load
            const sections = document.querySelectorAll('.profile-section');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

                // Stagger the animations
                setTimeout(() => {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 100 * index);
            });

            // Add hover effect to info cards
            const infoCards = document.querySelectorAll('.info-card');
            infoCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f0f7f3';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });

            // Edit Profile Functionality
            const editProfileBtn = document.getElementById('editProfileBtn');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const actionButtons = document.querySelector('.action-buttons');
            const personalInfoGrid = document.getElementById('personalInfoGrid');
            const schoolInfoGrid = document.getElementById('schoolInfoGrid');

            // Store original HTML for reverting changes
            let originalPersonalInfo = personalInfoGrid.innerHTML;
            let originalSchoolInfo = schoolInfoGrid.innerHTML;

            // Toggle edit mode
            editProfileBtn.addEventListener('click', function() {
                // Switch to edit mode
                enableEditMode();
                // Show action buttons
                actionButtons.style.display = 'flex';
                // Hide edit button
                editProfileBtn.style.display = 'none';
            });

            // Cancel edit mode
            cancelEditBtn.addEventListener('click', function() {
                // Revert to original HTML
                personalInfoGrid.innerHTML = originalPersonalInfo;
                schoolInfoGrid.innerHTML = originalSchoolInfo;
                // Hide action buttons
                actionButtons.style.display = 'none';
                // Show edit button
                editProfileBtn.style.display = 'flex';

                // Re-add hover effects to the info cards
                const infoCards = document.querySelectorAll('.info-card');
                infoCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f0f7f3';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                    });
                });
            });

            // Function to enable edit mode
            function enableEditMode() {
                // Convert personal info cards to editable fields
                const personalInfoCards = personalInfoGrid.querySelectorAll('.info-card');
                personalInfoCards.forEach(card => {
                    const field = card.getAttribute('data-field');
                    const value = card.querySelector('.info-value').textContent.trim();

                    // Create input field based on field type
                    let inputHTML = '';

                    if (field === 'Gender') {
                        inputHTML = `
                            <span class="info-label">Gender</span>
                            <select name="${field}" class="edit-select">
                                <option value="Male" ${value === 'Male' ? 'selected' : ''}>Male</option>
                                <option value="Female" ${value === 'Female' ? 'selected' : ''}>Female</option>
                                <option value="Other" ${value === 'Other' ? 'selected' : ''}>Other</option>
                            </select>
                        `;
                    } else if (field === 'DOB') {
                        inputHTML = `
                            <span class="info-label">Date of Birth</span>
                            <input type="date" name="${field}" value="${value}" class="edit-input">
                        `;
                    } else {
                        inputHTML = `
                            <span class="info-label">${card.querySelector('.info-label').textContent}</span>
                            <input type="text" name="${field}" value="${value}" class="edit-input">
                        `;
                    }

                    card.innerHTML = inputHTML;
                });

                // Convert school info cards to editable fields
                const schoolInfoCards = schoolInfoGrid.querySelectorAll('.info-card');
                schoolInfoCards.forEach(card => {
                    const field = card.getAttribute('data-field');
                    const value = card.querySelector('.info-value').textContent.trim();

                    // Create input field based on field type
                    let inputHTML = '';

                    if (field === 'Enrolled') {
                        inputHTML = `
                            <span class="info-label">Status at Lagro High School</span>
                            <select name="${field}" class="edit-select">
                                <option value="Enrolled" ${value === 'Enrolled' ? 'selected' : ''}>Enrolled</option>
                                <option value="Alumni" ${value === 'Alumni' ? 'selected' : ''}>Alumni</option>
                                <option value="Transferee" ${value === 'Transferee' ? 'selected' : ''}>Transferee</option>
                            </select>
                        `;
                    } else if (field === 'Grade_Level') {
                        inputHTML = `
                            <span class="info-label">Grade Level</span>
                            <select name="${field}" class="edit-select">
                                <option value="Grade 11" ${value === 'Grade 11' ? 'selected' : ''}>Grade 11</option>
                                <option value="Grade 12" ${value === 'Grade 12' ? 'selected' : ''}>Grade 12</option>
                            </select>
                        `;
                    } else if (field === 'Strand') {
                        inputHTML = `
                            <span class="info-label">Strand</span>
                            <select name="${field}" class="edit-select">
                                <option value="STEM" ${value === 'STEM' ? 'selected' : ''}>STEM</option>
                                <option value="ABM" ${value === 'ABM' ? 'selected' : ''}>ABM</option>
                                <option value="HUMSS" ${value === 'HUMSS' ? 'selected' : ''}>HUMSS</option>
                                <option value="GAS" ${value === 'GAS' ? 'selected' : ''}>GAS</option>
                                <option value="TVL" ${value === 'TVL' ? 'selected' : ''}>TVL</option>
                            </select>
                        `;
                    } else {
                        inputHTML = `
                            <span class="info-label">${card.querySelector('.info-label').textContent}</span>
                            <input type="text" name="${field}" value="${value}" class="edit-input">
                        `;
                    }

                    card.innerHTML = inputHTML;
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle search container
            const toggleSearchBtn = document.getElementById('toggleSearchBtn');
            const searchContainer = document.getElementById('searchContainer');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const searchResults = document.getElementById('searchResults');
            const loadingIndicator = document.getElementById('loadingIndicator');

            toggleSearchBtn.addEventListener('click', function() {
                if (searchContainer.style.display === 'none') {
                    searchContainer.style.display = 'block';
                    searchContainer.classList.add('show');
                    searchInput.focus();
                    toggleSearchBtn.innerHTML = '<i class="fas fa-times"></i> Close Search';
                    toggleSearchBtn.style.backgroundColor = 'var(--primary-color)';
                    toggleSearchBtn.style.color = 'white';
                } else {
                    searchContainer.classList.remove('show');
                    // Use setTimeout to wait for animation to complete before hiding
                    setTimeout(() => {
                        searchContainer.style.display = 'none';
                    }, 300);
                    toggleSearchBtn.innerHTML = '<i class="fas fa-search"></i> Click to Search for Users';
                    toggleSearchBtn.style.backgroundColor = '';
                    toggleSearchBtn.style.color = '';
                }
            });

            // AJAX search functionality
            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            function performSearch() {
                const query = searchInput.value.trim();

                if (query === '') {
                    searchResults.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-info-circle" style="font-size: 2rem; color: var(--text-light); margin-bottom: 10px;"></i>
                        <p>Please enter a search term.</p>
                    </div>
                `;
                    return;
                }

                // Show loading indicator
                searchResults.innerHTML = '';
                loadingIndicator.style.display = 'block';

                // Create and send AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'search-users.php?query=' + encodeURIComponent(query), true);

                xhr.onload = function() {
                    loadingIndicator.style.display = 'none';

                    if (xhr.status === 200) {
                        searchResults.innerHTML = xhr.responseText;

                        // Add hover effects to the newly added user cards
                        const userCards = document.querySelectorAll('.user-card');
                        userCards.forEach(card => {
                            card.addEventListener('mouseenter', function() {
                                this.style.backgroundColor = '#f0f7f3';
                            });

                            card.addEventListener('mouseleave', function() {
                                this.style.backgroundColor = '';
                            });
                        });
                    } else {
                        searchResults.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: var(--error-color); margin-bottom: 10px;"></i>
                            <p>An error occurred while searching. Please try again.</p>
                        </div>
                    `;
                    }
                };

                xhr.onerror = function() {
                    loadingIndicator.style.display = 'none';
                    searchResults.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: var(--error-color); margin-bottom: 10px;"></i>
                        <p>A network error occurred. Please check your connection and try again.</p>
                    </div>
                `;
                };

                xhr.send();
            }
        });
    </script>
</body>

</html>