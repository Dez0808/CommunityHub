<?php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("Location: /CommunityHub/Demo_Login.php");
  exit;
}
// Check if user is admin
$is_admin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $is_admin = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lagro High School - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f0f2f5;
            /* Facebook-like background color */
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease forwards;
            opacity: 0;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        /* Navigation */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, rgb(52, 140, 81) 0%, rgb(32, 91, 62) 30%);
            padding: 10px 20px;
            color: white;
            position: sticky;
            top: 0;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 100;
            transition: all 0.3s ease;
            flex-wrap: wrap;
        }

        .navbar:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .navbar img {
            max-height: 80px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .navbar img:hover {
            transform: scale(1.05);
        }

        .navbar-links {
            display: flex;
            gap: 20px;
            margin-right: 80px;
            align-items: center;
            height: auto;
            flex-wrap: wrap;
        }

        .navbar-links a,
        .drop {
            color: white;
            text-decoration: none;
            font-size: 15px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 24px;
            color: white;
        }

        /* Dropdown Menu */
        .dropbtn {
            background: none;
            border: none;
            outline: none;
            color: white;
            font-size: 15px;
            cursor: pointer;
            padding: 10px;
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
        }

        .drop {
            position: relative;
            display: inline-block;
        }

        .dropdown {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
            transform-origin: top center;
            transition: transform 0.3s ease, opacity 0.3s ease;
            transform: translateY(10px);
            opacity: 0;
        }

        .drop:hover .dropdown {
            display: block;
            transform: translateY(0);
            opacity: 1;
        }

        .dropdown a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .dropdown a:hover {
            background-color: #f5f5f5;
            color: rgb(32, 91, 62);
            border-left: 3px solid rgb(52, 140, 81);
            padding-left: 20px;
        }

        .dropbtn:hover {
            color: #a0e4ff;
            transform: translateY(-2px);
        }

        #home {
            background: none;
            border: none;
            outline: none;
            color: white;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        #home:hover {
            color: #a0e4ff;
            transform: translateY(-2px);
        }

        /* Admin Badge */
        .admin-badge {
            background-color: #ffd700;
            color: #123524;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }

        /* Responsive Styles */
        @media screen and (max-width: 992px) {
            .navbar-links {
                gap: 15px;
                margin-right: 10px;
            }

            .navbar img {
                max-height: 70px;
            }
        }

        @media screen and (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .navbar {
                justify-content: space-between;
            }

            .navbar-links {
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                margin-top: 15px;
                gap: 0;
            }

            .navbar-links.show {
                display: flex;
            }

            .drop {
                width: 100%;
            }

            .dropdown {
                position: static;
                box-shadow: none;
                border-radius: 0;
                min-width: 100%;
                background-color: rgba(255, 255, 255, 0.1);
                padding-left: 15px;
                transform: none;
                opacity: 1;
            }

            .dropdown a {
                color: white;
            }

            .dropdown a:hover {
                background-color: rgba(255, 255, 255, 0.2);
            }

            /* Mobile: override hover behaviors */
            .drop:hover .dropdown {
                display: none;
            }

            .dropdown.show {
                display: block;
            }
        }

        @media screen and (max-width: 480px) {
            .navbar {
                padding: 10px;
            }

            .navbar img {
                max-height: 60px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.createElement('div');
            menuToggle.classList.add('menu-toggle');
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';

            const navbar = document.querySelector('.navbar');
            const navbarLinks = document.querySelector('.navbar-links');

            navbar.insertBefore(menuToggle, navbarLinks);

            // Toggle menu on mobile
            menuToggle.addEventListener('click', function() {
                navbarLinks.classList.toggle('show');
                this.innerHTML = navbarLinks.classList.contains('show') ?
                    '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });

            // Set up dropdown functionality
            const dropButtons = document.querySelectorAll('.dropbtn');

            dropButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Only for mobile devices
                    if (window.innerWidth <= 768) {
                        e.preventDefault();

                        // Get the dropdown menu
                        const dropdown = this.nextElementSibling;

                        // Close all other dropdowns first
                        document.querySelectorAll('.dropdown').forEach(menu => {
                            if (menu !== dropdown) {
                                menu.classList.remove('show');
                            }
                        });

                        // Toggle the clicked dropdown
                        dropdown.classList.toggle('show');
                    }
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!e.target.closest('.drop') && !e.target.closest('.menu-toggle')) {
                        document.querySelectorAll('.dropdown').forEach(dropdown => {
                            dropdown.classList.remove('show');
                        });

                        // Also close the main menu
                        if (navbarLinks.classList.contains('show')) {
                            navbarLinks.classList.remove('show');
                            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                        }
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    navbarLinks.classList.remove('show');
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';

                    document.querySelectorAll('.dropdown').forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                }
            });
        });
    </script>
</head>

<body>
    <header id="navhead">
        
        <nav class="navbar">
            <img src="/CommunityHub/photos/Lagro_High_School_logo.png" alt="Lagro High School Logo" class="animate-fade-in" />
            <div class="navbar-links">
                <div class="drop animate-fade-in delay-100">
                    <a href="Demo_Index.php" id="home">Home</a>
                </div>

                <div class="drop animate-fade-in delay-200">
                    <button class="dropbtn">About</button>
                    <div class="dropdown">
                        <a href="History.php">History</a>
                        <a href="Vision&Mission.php">Vision and Mission</a>
                        <a href="Hymn.php">Lagro Hymn</a>
                        <a href="facilities.php">Facilities</a>
                    </div>
                </div>

                <div class="drop animate-fade-in delay-300">
                    <button class="dropbtn">Services</button>
                    <div class="dropdown">
                        <a href="profile.php">Profile</a>
                        <?php if ($is_admin): ?>
                            <a href="admin-concerns.php">Manage Concerns <span class="admin-badge">Admin</span></a>
                        <?php endif; ?>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>

                <div class="drop animate-fade-in delay-400">
                    <button class="dropbtn">Contact</button>
                    <div class="dropdown">
                        <a href="#">Contact Us</a>
                        <a href="#">Location</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</body>

</html>