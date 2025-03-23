<?php
session_start();
require_once "Demo_DBConnection.php";

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /CommunityHub/Demo_Login.php");
    exit;
}

// Check if user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: profile.php");
    exit;
}

$user_id = $_GET['id'];

// Get user data
$sql = "SELECT * FROM register WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if user exists
if (mysqli_num_rows($result) == 0) {
    header("Location: profile.php");
    exit;
}

$viewed_user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
    <title>View Profile | Community Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Use the same CSS from your profile.php -->
    <style>
        /* Copy all your CSS from profile.php here */
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

        /* Add a back button style */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--background-color);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>
    <?php include "includes/loading.php" ?>
    <div class="container">
        <?php include "index.php" ?>

        <a href="profile.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Your Profile
        </a>

        <div class="profile-header">
            <div class="profile-intro">
                <h1><?php echo htmlspecialchars($viewed_user["First_Name"] . ' ' . $viewed_user["Last_Name"]); ?>'s Profile</h1>
                <p class="subtitle"><?php echo htmlspecialchars($viewed_user["Grade_Level"]); ?> • <?php echo htmlspecialchars($viewed_user["Strand"]); ?></p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-picture-container">
                    <?php if (!empty($viewed_user["profile_pic"])): ?>
                        <img src="./uploads/<?php echo htmlspecialchars($viewed_user["profile_pic"]); ?>" alt="Profile Picture" class="profile-pic">
                    <?php else: ?>
                        <div class="profile-pic-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-details">
                <div class="profile-section">
                    <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    <div class="info-grid">
                        <div class="info-card">
                            <span class="info-label">First Name</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["First_Name"]); ?></span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">Middle Name</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["Middle_Name"]); ?></span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">Last Name</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["Last_Name"]); ?></span>
                        </div>
                        <!-- Only show limited information for privacy -->
                        <div class="info-card">
                            <span class="info-label">Gender</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["Gender"]); ?></span>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h2><i class="fas fa-school"></i> School Details</h2>
                    <div class="info-grid">
                        <div class="info-card">
                            <span class="info-label">Status at Lagro High School</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["Enrolled"]); ?></span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">Grade Level</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["Grade_Level"]); ?></span>
                        </div>
                        <div class="info-card">
                            <span class="info-label">Strand</span>
                            <span class="info-value"><?php echo htmlspecialchars($viewed_user["Strand"]); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/footer.php" ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>

</html>