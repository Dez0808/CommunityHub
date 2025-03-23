<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <title>Submit Concern - Lagro High School</title>
    <style>
        :root {
            --primary-color: #348c51;
            --primary-dark: #123524;
            --primary-light: rgba(52, 140, 81, 0.1);
            --secondary-color: #4c7f66;
            --accent-color: #ffd700;
            --text-color: #2d3436;
            --text-light: #636e72;
            --white: #ffffff;
            --off-white: #f8f9fa;
            --gray-light: #f1f2f6;
            --box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--off-white);
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 40vh;
            background: linear-gradient(rgba(18, 53, 36, 0.8), rgba(18, 53, 36, 0.8)),
                url('photos/lagrobg4.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }

        .hero-content {
            max-width: 800px;
            padding: 0 20px;
            animation: fadeUp 1.2s ease-out;
            z-index: 1;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            position: relative;
            display: inline-block;
            opacity: 0;
            animation: fadeInUp 1s forwards;
        }

        .hero-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 25px;
            opacity: 0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s forwards 0.3s;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-container {
            max-width: 1000px;
            margin: -60px auto 80px;
            padding: 0 20px;
            position: relative;
            margin-top: 50px;
        }

        /* Form Card */
        .form-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 40px;
            position: relative;
            transform: translateY(0);
            transition: var(--transition);
            opacity: 0;
            animation: fadeIn 1s forwards 0.5s;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .intro-text {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto 30px;
            line-height: 1.8;
            text-align: center;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* Button Styling */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(52, 140, 81, 0.3);
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .btn-secondary {
            background-color: var(--accent-color);
            color: var(--primary-dark);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52, 140, 81, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        /* Popup Styling */
        .popup-trigger {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .popup-content {
            background-color: var(--white);
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 600px;
            padding: 30px;
            position: relative;
            transform: scale(0.8);
            transition: all 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .popup-overlay.active .popup-content {
            transform: scale(1);
        }

        .popup-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
        }

        .popup-close:hover {
            color: var(--primary-color);
        }

        /* Success Message */
        .success-message {
            background-color: rgba(52, 140, 81, 0.1);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .success-message.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-section {
                height: 35vh;
            }

            .hero-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .page-container {
                margin-top: -40px;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                height: 30vh;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .form-card {
                padding: 25px 20px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .popup-trigger {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Student Concerns</h1>
            <p class="hero-subtitle">We value your feedback and are here to address your concerns</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="page-container">
        <!-- Form Card -->
        <div class="form-card">
            <h2 class="section-title">Submit Your Concern</h2>
            <p class="intro-text">If you have any concerns, suggestions, or issues you'd like to bring to our attention, please fill out the form below. Your feedback is important to us, and we are committed to addressing your concerns promptly.</p>
            
            <div id="successMessage" class="success-message">
                <p><strong>Thank you!</strong> Your concern has been submitted successfully. We will review it and get back to you soon.</p>
            </div>
            
            <form id="concernForm" action="process-concern.php" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="category" class="form-label">Concern Category</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">Select a category</option>
                        <option value="Academic">Academic</option>
                        <option value="Facilities">Facilities</option>
                        <option value="Staff">Staff</option>
                        <option value="Student Affairs">Student Affairs</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="message" class="form-label">Your Concern</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Concern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup Trigger Button -->
    <div class="popup-trigger">
        <button id="openPopup" class="btn btn-secondary">
            <i class="fas fa-comment-alt"></i> Quick Concern
        </button>
    </div>

    <!-- Popup Overlay -->
    <div id="popupOverlay" class="popup-overlay">
        <div class="popup-content">
            <span id="closePopup" class="popup-close"><i class="fas fa-times"></i></span>
            
            <h2 class="section-title">Submit a Quick Concern</h2>
            <p class="intro-text">Use this form to quickly submit your concern or feedback.</p>
            
            <div id="popupSuccessMessage" class="success-message">
                <p><strong>Thank you!</strong> Your concern has been submitted successfully.</p>
            </div>
            
            <form id="popupConcernForm" action="process-concern.php" method="POST">
                <div class="form-group">
                    <label for="popupName" class="form-label">Full Name</label>
                    <input type="text" id="popupName" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="popupEmail" class="form-label">Email Address</label>
                    <input type="email" id="popupEmail" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="popupCategory" class="form-label">Concern Category</label>
                    <select id="popupCategory" name="category" class="form-control" required>
                        <option value="">Select a category</option>
                        <option value="Academic">Academic</option>
                        <option value="Facilities">Facilities</option>
                        <option value="Staff">Staff</option>
                        <option value="Student Affairs">Student Affairs</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="popupMessage" class="form-label">Your Concern</label>
                    <textarea id="popupMessage" name="message" class="form-control" required></textarea>
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/scroll-up.php" ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Popup functionality
            const openPopupBtn = document.getElementById('openPopup');
            const closePopupBtn = document.getElementById('closePopup');
            const popupOverlay = document.getElementById('popupOverlay');
            
            openPopupBtn.addEventListener('click', function() {
                popupOverlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling when popup is open
            });
            
            closePopupBtn.addEventListener('click', function() {
                popupOverlay.classList.remove('active');
                document.body.style.overflow = ''; // Re-enable scrolling
            });
            
            // Close popup when clicking outside
            popupOverlay.addEventListener('click', function(e) {
                if (e.target === popupOverlay) {
                    popupOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            // Form submission handling
            const mainForm = document.getElementById('concernForm');
            const popupForm = document.getElementById('popupConcernForm');
            const successMessage = document.getElementById('successMessage');
            const popupSuccessMessage = document.getElementById('popupSuccessMessage');
            
            mainForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would use AJAX to submit the form
                // For demonstration, we'll just show the success message
                successMessage.classList.add('show');
                mainForm.reset();
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth' });
                
                // Hide success message after 5 seconds
                setTimeout(function() {
                    successMessage.classList.remove('show');
                }, 5000);
            });
            
            popupForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a real application, you would use AJAX to submit the form
                // For demonstration, we'll just show the success message
                popupSuccessMessage.classList.add('show');
                popupForm.reset();
                
                // Hide success message and close popup after 3 seconds
                setTimeout(function() {
                    popupSuccessMessage.classList.remove('show');
                    popupOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }, 3000);
            });
        });
    </script>
</body>

</html>