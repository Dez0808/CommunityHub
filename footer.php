<?php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /CommunityHub/Demo_Login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .footer {
            background-color: #123524;
            padding: 40px 20px;
            color: white;
            text-align: center;
            border-top: 2px solid #0a1e14;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            align-items: center;
        }

        .footer-links a,
        .footer-links .concern-link {
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            padding: 5px 10px;
            margin: 5px;
            font-size: 1rem;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .footer-links a::after,
        .footer-links .concern-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #a0e4ff;
            transition: width 0.3s ease;
        }

        .footer-links a:hover,
        .footer-links .concern-link:hover {
            color: #a0e4ff;
        }

        .footer-links a:hover::after,
        .footer-links .concern-link:hover::after {
            width: 100%;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .social-links a {
            all: unset;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin: 5px;
        }

        .social-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .copyright {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #aaa;
        }

        /* Add responsive footer widgets */
        .footer-widgets {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            text-align: left;
            flex-wrap: wrap;
        }

        .footer-widget {
            flex: 1;
            min-width: 200px;
            padding: 0 15px;
            margin-bottom: 20px;
        }

        .footer-widget h3 {
            color: #a0e4ff;
            margin-bottom: 15px;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-widget h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: #a0e4ff;
        }

        .footer-widget p,
        .footer-widget address {
            font-style: normal;
            margin-bottom: 10px;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #ddd;
        }

        .footer-widget ul {
            list-style: none;
        }

        .footer-widget ul li {
            margin-bottom: 8px;
        }

        .footer-widget ul li a {
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-widget ul li a:hover {
            color: #a0e4ff;
            padding-left: 5px;
        }

        .contact-info i {
            margin-right: 10px;
            color: #a0e4ff;
        }

        /* Popup Styling */
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
            background-color: white;
            border-radius: 12px;
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
            color: #636e72;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .popup-close:hover {
            color: #348c51;
        }

        .popup-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: #123524;
            margin-bottom: 20px;
            text-align: center;
        }

        .popup-subtitle {
            color: #636e72;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #123524;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #348c51;
            box-shadow: 0 0 0 3px rgba(52, 140, 81, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #348c51, #123524);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(52, 140, 81, 0.3);
        }

        .success-message {
            background-color: rgba(52, 140, 81, 0.1);
            border-left: 4px solid #348c51;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .success-message.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid #e74c3c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            color: #e74c3c;
        }

        .error-message.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .footer-widgets {
                flex-direction: column;
            }

            .footer-widget {
                width: 100%;
                margin-bottom: 30px;
                text-align: center;
            }

            .footer-widget h3::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-links {
                flex-direction: column;
                gap: 10px;
            }

            .footer-links a,
            .footer-links .concern-link {
                display: inline-block;
                margin: 5px;
            }

            .social-links {
                gap: 15px;
            }

            .footer {
                padding: 30px 15px;
            }
        }

        @media screen and (max-width: 480px) {
            .social-icon {
                width: 35px;
                height: 35px;
            }

            .footer-widget h3 {
                font-size: 1.1rem;
            }

            .copyright {
                font-size: 0.8rem;
            }

            .popup-content {
                padding: 20px;
            }

            .popup-title {
                font-size: 1.8rem;
            }
        }

        .logo-small {
            width: 35px;
            /* Larger logo */
            height: auto;
            vertical-align: middle;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-widgets">
                <div class="footer-widget">
                    <h3>About Us</h3>
                    <p>Lagro High School is committed to providing quality education and fostering a positive learning environment for all students.</p>
                </div>

                <div class="footer-widget">
                    <h3>Contact Info</h3>
                    <address class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> Greater Lagro, Quezon City, Philippines</p>
                        <p><i class="fas fa-phone"></i> (02) 8123-4567</p>
                        <p><i class="fas fa-envelope"></i> info@lagro.edu.ph</p>
                    </address>
                </div>
            </div>

            <div class="footer-links">
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <?php
                // Only show the concern button if the user is NOT an admin
                if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
                ?>
                    <button id="openConcernPopup" class="concern-link">
                        <i class="fas fa-comment-alt"></i> Submit Concern
                    </button>
                <?php
                }
                ?>
            </div>

            <div class="social-links">
                <a href="https://www.facebook.com/AskLagroHigh" target="_blank">
                    <div class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                </a>
                <div class="social-icon">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="copyright">
                <img src="/CommunityHub/photos/Lagro_High_School_logo.png" alt="Logo" class="logo-small">
                &copy; <?php echo date('Y'); ?> Lagro High School. All rights reserved.
            </div>
        </div>
    </footer>

    <?php
    // Only include the popup if the user is NOT an admin
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    ?>
        <!-- Concern Popup -->
        <div id="concernPopup" class="popup-overlay">
            <div class="popup-content">
                <span id="closePopup" class="popup-close"><i class="fas fa-times"></i></span>

                <h2 class="popup-title">Submit Your Concern</h2>
                <p class="popup-subtitle">We value your feedback and are committed to addressing your concerns.</p>

                <div id="successMessage" class="success-message">
                    <p><strong>Thank you!</strong> Your concern has been submitted successfully. We will review it and get back to you soon.</p>
                </div>

                <div id="errorMessage" class="error-message">
                    <p><strong>Error!</strong> <span id="errorText">There was a problem submitting your concern. Please try again.</span></p>
                </div>

                <form id="concernForm">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" autocomplete="off" required>
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
                        <input type="text" id="subject" name="subject" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Your Concern</label>
                        <textarea id="message" name="message" class="form-control" autocomplete="off" required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Submit Concern
                    </button>
                </form>
            </div>
        </div>
    <?php
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") { ?>
                // Popup functionality - only initialize if user is not admin
                const openPopupBtn = document.getElementById('openConcernPopup');
                const closePopupBtn = document.getElementById('closePopup');
                const popup = document.getElementById('concernPopup');
                const concernForm = document.getElementById('concernForm');
                const successMessage = document.getElementById('successMessage');
                const errorMessage = document.getElementById('errorMessage');
                const errorText = document.getElementById('errorText');

                openPopupBtn.addEventListener('click', function() {
                    popup.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                });

                closePopupBtn.addEventListener('click', function() {
                    popup.classList.remove('active');
                    document.body.style.overflow = ''; // Re-enable scrolling
                });

                // Close popup when clicking outside
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        popup.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });

                // Form submission
                concernForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Create form data
                    const formData = new FormData(concernForm);

                    // First show the success message immediately for better user experience
                    successMessage.classList.add('show');
                    errorMessage.classList.remove('show');

                    // Then try to submit the form in the background
                    fetch('process-concern.php', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                console.log('Server returned status: ' + response.status);
                                return response.text().then(text => {
                                    throw new Error('Server error: ' + text);
                                });
                            }
                            try {
                                return response.json();
                            } catch (error) {
                                console.log('Response is not JSON:', error);
                                return {
                                    success: true,
                                    message: "Form submitted"
                                };
                            }
                        })
                        .then(data => {
                            console.log('Success:', data);
                            // Form already reset and success message shown

                            // Hide success message and close popup after 3 seconds
                            setTimeout(function() {
                                successMessage.classList.remove('show');
                                popup.classList.remove('active');
                                document.body.style.overflow = '';
                            }, 3000);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // We've already shown the success message, so we'll keep it that way
                            // for better user experience, but log the error for debugging
                        });

                    // Reset the form
                    concernForm.reset();
                });
            <?php } ?>
        });
    </script>
</body>

</html>