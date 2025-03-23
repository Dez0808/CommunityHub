<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Facilities - Lagro High School</title>
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
    <style>
        :root {
            --primary: #185135;
            --primary-light: #2a7d4f;
            --primary-dark: #0e3520;
            --accent: #ffcd3c;
            --text-dark: #333;
            --text-light: #777;
            --white: #fff;
            --bg-light: #f8f9fa;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .banner {
            background: linear-gradient(to right, rgba(24, 81, 53, 0.9), rgba(14, 53, 32, 0.85)), url('/CommunityHub/photos/lagrobg4.jpg');
            background-size: cover;
            background-position: center;
            color: var(--white);
            text-align: center;
            padding: 60px 20px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .banner::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom left, transparent 49%, var(--bg-light) 50%);
        }

        .banner-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .banner h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease;
        }

        .banner p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }

        .facilities-nav {
            background-color: var(--white);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            position: relative;
            z-index: 2;
        }

        .facility-btn {
            background: none;
            border: 2px solid transparent;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .facility-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: rgba(24, 81, 53, 0.1);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .facility-btn:hover::before {
            width: 100%;
        }

        .facility-btn.active {
            background-color: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .facilities-grid {
            position: relative;
        }

        .facility-section {
            display: none;
            animation: fadeIn 0.8s ease;
        }

        .facility-section.active {
            display: block;
        }

        .facility-header {
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .facility-header h2 {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 10px;
            display: inline-block;
            position: relative;
        }

        .facility-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent);
        }

        .facility-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .facility-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: var(--white);
            transition: var(--transition);
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .facility-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .facility-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .facility-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .facility-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .facility-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        .facility-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .facility-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .facility-item:hover .facility-image img {
            transform: scale(1.1);
        }

        .facility-content {
            padding: 20px;
        }

        .facility-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .facility-desc {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .facility-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .feature-tag {
            background-color: rgba(24, 81, 53, 0.1);
            color: var(--primary);
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .facility-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
            align-items: center;
        }

        .details-image {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            height: 350px;
        }

        .details-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .details-content h3 {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .details-content p {
            color: var(--text-light);
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .details-list {
            margin-bottom: 20px;
        }

        .details-list li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 25px;
            list-style: none;
        }

        .details-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        .cta-banner {
            background: linear-gradient(45deg, var(--primary), var(--primary-light));
            color: var(--white);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            margin: 50px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .cta-banner h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .cta-banner p {
            max-width: 700px;
            margin: 0 auto 20px;
            opacity: 0.9;
        }

        .cta-btn {
            display: inline-block;
            background-color: var(--white);
            color: var(--primary);
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: 2px solid var(--white);
        }

        .cta-btn:hover {
            background-color: transparent;
            color: var(--white);
        }

        .key-features {
            background-color: var(--white);
            padding: 50px 0;
            margin: 60px 0;
            position: relative;
        }

        .key-features::before,
        .key-features::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 50px;
        }

        .key-features::before {
            top: -50px;
            background: linear-gradient(to bottom right, transparent 49%, var(--white) 50%);
        }

        .key-features::after {
            bottom: -50px;
            background: linear-gradient(to top left, transparent 49%, var(--white) 50%);
        }

        .features-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .features-header h2 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .features-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--accent);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background-color: var(--bg-light);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .feature-card p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* Image Gallery for Virtual Tour */
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 200px);
            gap: 15px;
            margin-top: 30px;
        }

        .gallery-item {
            overflow: hidden;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
        }

        .gallery-item.large {
            grid-column: span 2;
            grid-row: span 2;
        }

        .gallery-item.vertical {
            grid-row: span 2;
        }

        .gallery-item.horizontal {
            grid-column: span 2;
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover .gallery-image {
            transform: scale(1.1);
        }

        .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            color: var(--white);
            padding: 15px;
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .gallery-item:hover .gallery-caption {
            opacity: 1;
            transform: translateY(0);
        }

        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            padding: 50px;
            box-sizing: border-box;
            align-items: center;
            justify-content: center;
        }

        .lightbox.active {
            display: flex;
        }

        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }

        .lightbox-img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            display: block;
            border: 5px solid var(--white);
            border-radius: 5px;
        }

        .close-lightbox {
            position: absolute;
            top: 20px;
            right: 30px;
            color: var(--white);
            font-size: 40px;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-lightbox:hover {
            color: var(--accent);
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        /* Media Queries */
        @media (max-width: 992px) {
            .facility-details {
                grid-template-columns: 1fr;
            }

            .details-image {
                height: 300px;
            }

            .image-gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .banner h1 {
                font-size: 2.2rem;
            }

            .banner p {
                font-size: 1rem;
            }

            .facility-gallery {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .features-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .gallery-item.large,
            .gallery-item.vertical,
            .gallery-item.horizontal {
                grid-column: span 1;
                grid-row: span 1;
            }

            .image-gallery {
                grid-template-rows: repeat(8, 200px);
            }
        }

        @media (max-width: 576px) {
            .facility-btn {
                font-size: 0.9rem;
                padding: 8px 15px;
            }

            .image-gallery {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>

    <div class="banner">
        <div class="banner-content">
            <h1>Our Campus Facilities</h1>
            <p>Explore the modern facilities that enhance the educational experience at Lagro High School, designed to nurture academic excellence and personal growth.</p>
        </div>
    </div>

    <div class="container">
        <div class="facilities-nav">
            <button class="facility-btn active" data-target="simon">Simon Bldg</button>
            <button class="facility-btn" data-target="castelo">Castelo Bldg</button>
            <button class="facility-btn" data-target="vargas">Vargas Bldg</button>
            <button class="facility-btn" data-target="mathay">Mathay Bldg</button>
            <button class="facility-btn" data-target="sb">SB Bldg</button>
            <button class="facility-btn" data-target="bautista">Bautista Bldg</button>
        </div>

        <div class="facilities-grid">

            <!-- Simon Facilities -->
            <div class="facility-section active" id="simon-section">
                <div class="facility-header">
                    <h2>Simon Building Facilities</h2>
                    <p>Serves as the main administrative hub of the school</p>
                </div>

                <div class="facility-gallery">

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/image/principal.jpg" alt="Principal Office">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Principal's Office</h3>
                            <p class="facility-desc">Main administrative center of the school where key management and executive functions are carried out.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Administrative Leadership Hub</span>
                                <span class="feature-tag">Decision-Making Center</span>
                                <span class="feature-tag">Meeting Area</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/image/registrar.jpg" alt="Registrar">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Registrar</h3>
                            <p class="facility-desc">Responsible for maintaining and managing important records of the school.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Records Management</span>
                                <span class="feature-tag">Enrollment and Registration</span>
                                <span class="feature-tag">Support Services</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="facility-details">
                    <div class="details-content">
                        <h3>Athletic Excellence & Physical Well-being</h3>
                        <p>Our comprehensive sports facilities support both competitive athletics and general physical education. We believe in the importance of physical activity for overall well-being and provide spaces that inspire students to stay active and develop their athletic abilities.</p>
                        <div class="details-list">
                            <ul>
                                <li>Indoor gymnasium with professional-grade flooring and equipment</li>
                                <li>Well-maintained outdoor field for various team sports</li>
                                <li>Dedicated indoor courts for racquet sports and table tennis</li>
                                <li>Modern fitness center with cardio and strength training equipment</li>
                                <li>Changing rooms with shower facilities and lockers</li>
                                <li>Trained staff to supervise and maintain safety protocols</li>
                            </ul>
                        </div>
                        <p>Our sports facilities host both regular physical education classes and competitive sports events throughout the academic year, fostering team spirit and promoting a healthy lifestyle.</p>
                    </div>
                    <div class="details-image">
                        <img src="/CommunityHub/photos/lagrobg.jpg" alt="Sports Facility Detail">
                    </div>
                </div>
            </div>

            <!-- Castelo Facilities 
            <div class="facility-section" id="castelo-section">
                <div class="facility-header">
                    <h2>Castelo Building Facilities</h2>
                    <p></p>
                </div>

                <div class="facility-gallery">
                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/bistro.jpg" alt="Modern Classrooms">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Bistro</h3>
                            <p class="facility-desc">Spacious, well-lit classrooms equipped with the latest teaching technology and comfortable seating.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Interactive Boards</span>
                                <span class="feature-tag">Ergonomic Furniture</span>
                                <span class="feature-tag">Climate Control</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Science Laboratories">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Science Laboratories</h3>
                            <p class="facility-desc">Fully equipped labs for Physics, Chemistry, and Biology with modern apparatus for practical learning.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Modern Equipment</span>
                                <span class="feature-tag">Safety Protocols</span>
                                <span class="feature-tag">30 Workstations</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Computer Labs">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Computer Laboratories</h3>
                            <p class="facility-desc">State-of-the-art computer labs with high-speed internet access and the latest software.</p>
                            <div class="facility-features">
                                <span class="feature-tag">40 Computers</span>
                                <span class="feature-tag">High-speed Internet</span>
                                <span class="feature-tag">Multimedia Tools</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="facility-details">
                    <div class="details-image">
                        <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Science Laboratory Detail">
                    </div>
                    <div class="details-content">
                        <h3>Next-Generation Learning Spaces</h3>
                        <p>Our academic facilities are designed to foster an innovative learning environment where students can thrive. Every classroom and specialized learning space incorporates modern technology and ergonomic design to enhance the educational experience.</p>
                        <div class="details-list">
                            <ul>
                                <li>Classroom technology including interactive whiteboards and projectors</li>
                                <li>Spacious science laboratories with modern safety equipment</li>
                                <li>Computer labs with the latest hardware and educational software</li>
                                <li>Flexible learning spaces that can be reconfigured for different activities</li>
                                <li>Natural lighting and climate control for optimal learning conditions</li>
                                <li>Acoustically optimized rooms for better focus and communication</li>
                            </ul>
                        </div>
                        <p>All academic facilities are maintained to the highest standards, ensuring a safe and conducive environment for learning and teaching excellence.</p>
                    </div>
                </div>
            </div>
            -->

            <!-- Vargas Facilities 
            <div class="facility-section" id="vargas-section">
                <div class="facility-header">
                    <h2>Vargas Building Facilities</h2>
                    <p>Comprehensive resources for research, reading, and independent learning</p>
                </div>

                <div class="facility-gallery">

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Study Areas">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Study Areas</h3>
                            <p class="facility-desc">Quiet individual study carrels and group study rooms for focused learning.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Individual Carrels</span>
                                <span class="feature-tag">Group Rooms</span>
                                <span class="feature-tag">WiFi Access</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Digital Resource Center">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Digital Resource Center</h3>
                            <p class="facility-desc">Computer stations for accessing online databases, e-books, and digital learning materials.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Computer Stations</span>
                                <span class="feature-tag">E-Book Access</span>
                                <span class="feature-tag">Research Tools</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Media Room">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Multimedia Room</h3>
                            <p class="facility-desc">Specialized room for audiovisual materials and multimedia presentations.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Audio Collection</span>
                                <span class="feature-tag">Video Resources</span>
                                <span class="feature-tag">Presentation Equipment</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="facility-details">
                    <div class="details-image">
                        <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Library Detail">
                    </div>
                    <div class="details-content">
                        <h3>Knowledge Resources & Quiet Reflection</h3>
                        <p>Our library serves as the intellectual hub of the school, offering a wealth of resources for research, reading, and independent learning. The thoughtfully designed spaces encourage both individual study and collaborative learning.</p>
                        <div class="details-list">
                            <ul>
                                <li>Extensive collection of books, periodicals, and reference materials</li>
                                <li>Digital resources including e-books and online research databases</li>
                                <li>Dedicated quiet zones for focused individual study</li>
                                <li>Collaborative spaces for group projects and discussions</li>
                                <li>Professional librarians to assist with research and information literacy</li>
                                <li>Extended hours during examination periods for additional study time</li>
                            </ul>
                        </div>
                        <p>The library hosts regular events including book clubs, research workshops, and author visits to promote a culture of reading and intellectual curiosity throughout the school community.</p>
                    </div>
                </div>
            </div>
-->

            <!-- Mathay Facilities 
            <div class="facility-section" id="mathay-section">
                <div class="facility-header">
                    <h2>Mathay Building Facilities</h2>
                    <p>Comprehensive resources for research, reading, and independent learning</p>
                </div>

                <div class="facility-gallery">

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Study Areas">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Study Areas</h3>
                            <p class="facility-desc">Quiet individual study carrels and group study rooms for focused learning.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Individual Carrels</span>
                                <span class="feature-tag">Group Rooms</span>
                                <span class="feature-tag">WiFi Access</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Digital Resource Center">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Digital Resource Center</h3>
                            <p class="facility-desc">Computer stations for accessing online databases, e-books, and digital learning materials.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Computer Stations</span>
                                <span class="feature-tag">E-Book Access</span>
                                <span class="feature-tag">Research Tools</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Media Room">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Multimedia Room</h3>
                            <p class="facility-desc">Specialized room for audiovisual materials and multimedia presentations.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Audio Collection</span>
                                <span class="feature-tag">Video Resources</span>
                                <span class="feature-tag">Presentation Equipment</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="facility-details">
                    <div class="details-image">
                        <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Library Detail">
                    </div>
                    <div class="details-content">
                        <h3>Knowledge Resources & Quiet Reflection</h3>
                        <p>Our library serves as the intellectual hub of the school, offering a wealth of resources for research, reading, and independent learning. The thoughtfully designed spaces encourage both individual study and collaborative learning.</p>
                        <div class="details-list">
                            <ul>
                                <li>Extensive collection of books, periodicals, and reference materials</li>
                                <li>Digital resources including e-books and online research databases</li>
                                <li>Dedicated quiet zones for focused individual study</li>
                                <li>Collaborative spaces for group projects and discussions</li>
                                <li>Professional librarians to assist with research and information literacy</li>
                                <li>Extended hours during examination periods for additional study time</li>
                            </ul>
                        </div>
                        <p>The library hosts regular events including book clubs, research workshops, and author visits to promote a culture of reading and intellectual curiosity throughout the school community.</p>
                    </div>
                </div>
            </div>
-->
            <!-- SB Facilities 
            <div class="facility-section" id="sb-section">
                <div class="facility-header">
                    <h2>SB Building Facilities</h2>
                    <p>Comprehensive resources for research, reading, and independent learning</p>
                </div>

                <div class="facility-gallery">

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Study Areas">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Study Areas</h3>
                            <p class="facility-desc">Quiet individual study carrels and group study rooms for focused learning.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Individual Carrels</span>
                                <span class="feature-tag">Group Rooms</span>
                                <span class="feature-tag">WiFi Access</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Digital Resource Center">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Digital Resource Center</h3>
                            <p class="facility-desc">Computer stations for accessing online databases, e-books, and digital learning materials.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Computer Stations</span>
                                <span class="feature-tag">E-Book Access</span>
                                <span class="feature-tag">Research Tools</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Media Room">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Multimedia Room</h3>
                            <p class="facility-desc">Specialized room for audiovisual materials and multimedia presentations.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Audio Collection</span>
                                <span class="feature-tag">Video Resources</span>
                                <span class="feature-tag">Presentation Equipment</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="facility-details">
                    <div class="details-image">
                        <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Library Detail">
                    </div>
                    <div class="details-content">
                        <h3>Knowledge Resources & Quiet Reflection</h3>
                        <p>Our library serves as the intellectual hub of the school, offering a wealth of resources for research, reading, and independent learning. The thoughtfully designed spaces encourage both individual study and collaborative learning.</p>
                        <div class="details-list">
                            <ul>
                                <li>Extensive collection of books, periodicals, and reference materials</li>
                                <li>Digital resources including e-books and online research databases</li>
                                <li>Dedicated quiet zones for focused individual study</li>
                                <li>Collaborative spaces for group projects and discussions</li>
                                <li>Professional librarians to assist with research and information literacy</li>
                                <li>Extended hours during examination periods for additional study time</li>
                            </ul>
                        </div>
                        <p>The library hosts regular events including book clubs, research workshops, and author visits to promote a culture of reading and intellectual curiosity throughout the school community.</p>
                    </div>
                </div>
            </div>
-->
            <!-- Bautista Facilities 
            <div class="facility-section" id="bautista-section">
                <div class="facility-header">
                    <h2>Bautista Building Facilities</h2>
                    <p>Comprehensive resources for research, reading, and independent learning</p>
                </div>

                <div class="facility-gallery">

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Study Areas">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Study Areas</h3>
                            <p class="facility-desc">Quiet individual study carrels and group study rooms for focused learning.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Individual Carrels</span>
                                <span class="feature-tag">Group Rooms</span>
                                <span class="feature-tag">WiFi Access</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Digital Resource Center">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Digital Resource Center</h3>
                            <p class="facility-desc">Computer stations for accessing online databases, e-books, and digital learning materials.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Computer Stations</span>
                                <span class="feature-tag">E-Book Access</span>
                                <span class="feature-tag">Research Tools</span>
                            </div>
                        </div>
                    </div>

                    <div class="facility-item">
                        <div class="facility-image">
                            <img src="/CommunityHub/photos/lagrobg.jpg" alt="Media Room">
                        </div>
                        <div class="facility-content">
                            <h3 class="facility-title">Multimedia Room</h3>
                            <p class="facility-desc">Specialized room for audiovisual materials and multimedia presentations.</p>
                            <div class="facility-features">
                                <span class="feature-tag">Audio Collection</span>
                                <span class="feature-tag">Video Resources</span>
                                <span class="feature-tag">Presentation Equipment</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="facility-details">
                    <div class="details-image">
                        <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Library Detail">
                    </div>
                    <div class="details-content">
                        <h3>Knowledge Resources & Quiet Reflection</h3>
                        <p>Our library serves as the intellectual hub of the school, offering a wealth of resources for research, reading, and independent learning. The thoughtfully designed spaces encourage both individual study and collaborative learning.</p>
                        <div class="details-list">
                            <ul>
                                <li>Extensive collection of books, periodicals, and reference materials</li>
                                <li>Digital resources including e-books and online research databases</li>
                                <li>Dedicated quiet zones for focused individual study</li>
                                <li>Collaborative spaces for group projects and discussions</li>
                                <li>Professional librarians to assist with research and information literacy</li>
                                <li>Extended hours during examination periods for additional study time</li>
                            </ul>
                        </div>
                        <p>The library hosts regular events including book clubs, research workshops, and author visits to promote a culture of reading and intellectual curiosity throughout the school community.</p>
                    </div>
                </div>
            </div>
        </div>
-->
            <div class="key-features">
                <div class="container">
                    <div class="features-header">
                        <h2>Key Campus Features</h2>
                        <p>Discover what makes our campus facilities stand out</p>
                    </div>
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">🌿</div>
                            <h3>Eco-Friendly Design</h3>
                            <p>Sustainable architecture and energy-efficient systems that reduce our environmental footprint.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">♿</div>
                            <h3>Accessibility</h3>
                            <p>All facilities are designed to be accessible to students and staff with diverse mobility needs.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">🔒</div>
                            <h3>Safety & Security</h3>
                            <p>Comprehensive security measures to ensure a safe learning environment for everyone.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">📶</div>
                            <h3>Technology Integration</h3>
                            <p>Cutting-edge technology integrated throughout the campus to enhance learning experiences.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="facility-header">
                <h2>Virtual Campus Tour</h2>
                <p>Explore our campus facilities through these images</p>
            </div>

            <div class="image-gallery">
                <div class="gallery-item large" onclick="openLightbox('/CommunityHub/photos/lagrobg4.jpg', 'Main Academic Building')">
                    <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Main Academic Building" class="gallery-image">
                    <div class="gallery-caption">Main Academic Building</div>
                </div>
                <div class="gallery-item" onclick="openLightbox('/CommunityHub/photos/lagrobg.jpg', 'Library Interior')">
                    <img src="/CommunityHub/photos/lagrobg.jpg" alt="Library Interior" class="gallery-image">
                    <div class="gallery-caption">Library Interior</div>
                </div>
                <div class="gallery-item" onclick="openLightbox('/CommunityHub/photos/lagrobg4.jpg', 'Science Laboratory')">
                    <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Science Laboratory" class="gallery-image">
                    <div class="gallery-caption">Science Laboratory</div>
                </div>
                <div class="gallery-item vertical" onclick="openLightbox('/CommunityHub/photos/lagrobg.jpg', 'Computer Laboratory')">
                    <img src="/CommunityHub/photos/lagrobg.jpg" alt="Computer Laboratory" class="gallery-image">
                    <div class="gallery-caption">Computer Laboratory</div>
                </div>
                <div class="gallery-item horizontal" onclick="openLightbox('/CommunityHub/photos/lagrobg4.jpg', 'Sports Field')">
                    <img src="/CommunityHub/photos/lagrobg4.jpg" alt="Sports Field" class="gallery-image">
                    <div class="gallery-caption">Sports Field</div>
                </div>
                <div class="gallery-item" onclick="openLightbox('/CommunityHub/photos/lagrobg.jpg', 'Arts Studio')">
                    <img src="/CommunityHub/photos/lagrobg.jpg" alt="Arts Studio" class="gallery-image">
                    <div class="gallery-caption">Arts Studio</div>
                </div>
            </div>

            <div class="lightbox" id="lightbox">
                <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
                <div class="lightbox-content">
                    <img src="" alt="" id="lightbox-img" class="lightbox-img">
                </div>
            </div>

            <div class="cta-banner">
                <h3>Experience Our Facilities in Person</h3>
                <p>We invite you to visit our campus and see our world-class facilities firsthand. Schedule a campus tour to learn more about how our facilities enhance the educational experience at Lagro High School.</p>
                <a href="contact.php" class="cta-btn">Schedule a Visit</a>
            </div>
        </div>
    </div>
        <?php include "index.php" ?>
        <?php include "includes/footer.php" ?>
        <?php include "includes/scroll-up.php" ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Facility navigation
                const facilityButtons = document.querySelectorAll('.facility-btn');
                const facilitySections = document.querySelectorAll('.facility-section');

                facilityButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const target = this.getAttribute('data-target');

                        // Update active button
                        facilityButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        // Update active section
                        facilitySections.forEach(section => section.classList.remove('active'));
                        document.getElementById(target + '-section').classList.add('active');

                        // Animate the new section content
                        animateFacilityItems();
                    });
                });

                // Initial animation of visible facility items
                function animateFacilityItems() {
                    const activeSection = document.querySelector('.facility-section.active');
                    const facilityItems = activeSection.querySelectorAll('.facility-item');

                    facilityItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.animation = 'fadeInUp 0.6s ease forwards';
                        }, 100 * index);
                    });
                }

                // Run initial animation
                animateFacilityItems();

                // Lightbox functionality
                window.openLightbox = function(src, alt) {
                    const lightbox = document.getElementById('lightbox');
                    const lightboxImg = document.getElementById('lightbox-img');

                    lightboxImg.src = src;
                    lightboxImg.alt = alt;
                    lightbox.classList.add('active');
                    document.body.style.overflow = 'hidden';
                };

                window.closeLightbox = function() {
                    const lightbox = document.getElementById('lightbox');
                    lightbox.classList.remove('active');
                    document.body.style.overflow = 'auto';
                };

                // Close lightbox when clicking outside image
                document.getElementById('lightbox').addEventListener('click', function(e) {
                    if (e.target !== document.getElementById('lightbox-img')) {
                        closeLightbox();
                    }
                });

                // Animate on scroll
                const featureCards = document.querySelectorAll('.feature-card');

                function checkScroll() {
                    featureCards.forEach((card, index) => {
                        const cardTop = card.getBoundingClientRect().top;
                        const windowHeight = window.innerHeight;

                        if (cardTop < windowHeight * 0.8) {
                            setTimeout(() => {
                                card.style.transform = 'translateY(0)';
                                card.style.opacity = '1';
                            }, 100 * index);
                        }
                    });
                }

                window.addEventListener('scroll', checkScroll);
                checkScroll(); // Initial check
            });
        </script>
</body>

</html>