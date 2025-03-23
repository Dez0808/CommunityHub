<?php
session_start();

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
    <title>History of Lagro High School</title>
    <meta name="description" content="Explore the rich history of Lagro High School from its founding to present day">
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* Color Variables - Updated to match Vision & Mission */
            --primary: rgb(32, 91, 62);
            /* School green */
            --primary-dark: rgb(24, 68, 47);
            --primary-light: rgba(32, 91, 62, 0.1);
            --secondary: rgb(52, 140, 81);
            /* Light green */
            --secondary-light: rgba(52, 140, 81, 0.15);
            --gold: #D4AF37;
            /* Gold accent */
            --gold-light: rgba(212, 175, 55, 0.15);
            --accent-color: #a0e4ff;
            --dark: #2d3436;
            --light: #f8f9fa;
            --gray-light: #f1f2f6;
            --gray: #636e72;
            --white: #ffffff;
            --off-white: #f8f9fa;

            /* Typography */
            --font-heading: 'Cormorant Garamond', serif;
            --font-body: 'Poppins', sans-serif;

            /* Spacing */
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 2rem;
            --space-lg: 4rem;
            --space-xl: 8rem;

            /* Transitions */
            --transition-fast: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --transition-medium: 0.5s ease;
            --transition-slow: 0.8s ease;
            --box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
            overflow-x: hidden;
        }



        a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition-fast);
        }

        a:hover {
            color: var(--primary-light);
        }

        /* Typography */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-heading);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: var(--space-sm);
        }

        h1 {
            font-size: 3.5rem;
        }

        h2 {
            font-size: 2.5rem;
        }

        h3 {
            font-size: 1.75rem;
        }

        p {
            margin-bottom: var(--space-sm);
        }

        .section-title {
            text-align: center;
            margin-bottom: var(--space-lg);
            position: relative;
            padding-bottom: var(--space-sm);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary);
            border-radius: 2px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 35px;
            border-radius: 50px;
            font-weight: 600;
            text-align: center;
            transition: var(--transition-fast);
            cursor: pointer;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            box-shadow: 0 4px 15px rgba(32, 91, 62, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(32, 91, 62, 0.4);
        }

        /* Layout */
        .page-wrapper {
            width: 100%;
            overflow: hidden;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--space-md) 0;
        }

        section {
            padding: var(--space-lg) 0;
        }


        /* Hero Section */
        .hero {
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to right, rgba(24, 68, 47, 0.8), rgba(52, 140, 81, 0.7)), url('https://source.unsplash.com/random/1920x1080/?school') no-repeat center center/cover;
            color: var(--white);
            text-align: center;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            padding: 0 var(--space-md);
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: var(--space-md);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: var(--space-lg);
            opacity: 0.9;
        }

        .hero-cta {
            margin-top: var(--space-md);
        }

        /* Timeline Section */
        .timeline-section {
            padding: var(--space-xl) 0;
            background-color: var(--white);
        }

        .timeline-container {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
        }

        .timeline-container::before {
            content: '';
            position: absolute;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary-dark), var(--secondary));
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }

        .timeline-item {
            padding: var(--space-md) 0;
            position: relative;
            width: 50%;
            opacity: 0;
            transform: translateY(50px);
            transition: var(--transition-medium);
        }

        .timeline-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .timeline-item:nth-child(odd) {
            left: 0;
            padding-right: var(--space-lg);
        }

        .timeline-item:nth-child(even) {
            left: 50%;
            padding-left: var(--space-lg);
        }

        .timeline-dot {
            position: absolute;
            width: 24px;
            height: 24px;
            background: var(--gold);
            border-radius: 50%;
            top: 30px;
            z-index: 2;
            box-shadow: 0 0 0 4px var(--gold-light);
        }

        .timeline-item:nth-child(odd) .timeline-dot {
            right: -12px;
        }

        .timeline-item:nth-child(even) .timeline-dot {
            left: -12px;
        }

        .timeline-content {
            position: relative;
            padding: var(--space-md);
            background: var(--light);
            border-radius: 12px;
            box-shadow: var(--box-shadow);
            transition: var(--transition-fast);
            border-left: 4px solid var(--primary);
        }

        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .timeline-year {
            position: absolute;
            top: -10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-weight: 700;
            font-family: var(--font-heading);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-weight: 700;
        }

        .timeline-item:nth-child(odd) .timeline-year {
            right: 30px;
        }

        .timeline-item:nth-child(even) .timeline-year {
            left: 30px;
        }

        .timeline-title {
            margin-top: var(--space-sm);
            color: var(--primary);
        }

        /* Milestone Section */
        .milestone-section {
            background-color: var(--light);
            padding: var(--space-xl) 0;
        }

        .milestones-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: var(--space-md);
            max-width: 1200px;
            margin: 0 auto;
        }

        .milestone-card {
            flex: 1 1 300px;
            max-width: 350px;
            padding: var(--space-md);
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: var(--transition-fast);
            position: relative;
            overflow: hidden;
        }

        .milestone-card::before {
            content: attr(data-year);
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--gold);
            color: var(--dark);
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .milestone-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-top: 4px solid var(--primary);
        }

        .milestone-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--space-sm);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.75rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Present Day Section */
        .present-day-section {
            background: linear-gradient(rgba(24, 68, 47, 0.9), rgba(52, 140, 81, 0.9)), url('https://source.unsplash.com/random/1920x1080/?classroom') no-repeat center center/cover;
            color: var(--white);
            padding: var(--space-xl) 0;
            position: relative;
        }

        .present-content {
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 var(--space-md);
        }

        .present-text {
            text-align: center;
            max-width: 800px;
        }

        .present-text h2 {
            margin-bottom: var(--space-md);
            position: relative;
            padding-bottom: var(--space-sm);
            display: inline-block;
        }

        .present-text h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary);
            border-radius: 2px;
        }

        /* Animation Classes */
        .animate-fade-in {
            opacity: 0;
            animation: fadeIn 1s ease forwards;
        }

        .animate-fade-in-delay {
            opacity: 0;
            animation: fadeIn 1s ease 0.3s forwards;
        }

        .animate-fade-in-delay-2 {
            opacity: 0;
            animation: fadeIn 1s ease 0.6s forwards;
        }

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

        .animate-slide-up {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .animate-slide-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            html {
                font-size: 14px;
            }

            .timeline-container::before {
                left: 30px;
            }

            .timeline-item {
                width: 100%;
                left: 0 !important;
                padding-left: 70px !important;
                padding-right: 0 !important;
            }

            .timeline-dot {
                left: 20px !important;
                right: auto !important;
            }

            .timeline-year {
                left: 70px !important;
                right: auto !important;
            }
        }

        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: var(--white);
                flex-direction: column;
                align-items: center;
                padding: var(--space-md) 0;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
                transition: var(--transition-medium);
            }

            .nav-menu.active {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            }

            .nav-item {
                margin: 0.5rem 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .hero h1 {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .milestone-card {
                flex: 1 1 100%;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <?php include_once('includes/header.php'); ?>

        <main class="main-content">
            <section class="hero">
                <div class="hero-content">
                    <h1 class="animate-fade-in">History of Lagro High School</h1>
                    <p class="hero-subtitle animate-fade-in-delay">A legacy of growth, innovation, and excellence since 1974</p>
                    <div class="hero-cta animate-fade-in-delay-2">
                        <a href="#timeline" class="btn btn-primary">Explore Our History</a>
                    </div>
                </div>
            </section>

            <section id="timeline" class="timeline-section">
                <h2 class="section-title">Our Journey Through Time</h2>
                <div class="timeline-container">
                    <?php
                    // Structured timeline content based on the history text

                    // Timeline array with year, title, and content
                    $timelineEvents = [
                        [
                            'year' => '1970s',
                            'title' => 'The Beginning',
                            'content' => 'Lagro High School traces its roots back to the early 1970s when the growing population of the GSIS La Mesa Homeowners Association (GLAMEHA) created an urgent need for a secondary school in Lagro Subdivision. The officers of GLAMEHA petitioned for the establishment of a high school adjacent to Lagro Elementary School.'
                        ],
                        [
                            'year' => '1974',
                            'title' => 'Official Establishment',
                            'content' => 'On June 13, 1974, Lagro High School began operations with 87 students, utilizing two modest housing units in Block 59 as classrooms. Students even provided their own chairs. By August 26 of the same year, the annex was moved to the Lagro Elementary School compound, where students attended classes in a sawali-walled makeshift building. Mr. Crispulo A. Pilar served as the head, assisted by pioneering teachers Mr. Narciso M. Caingat, Mrs. Nilfa C. Caingat, and Mrs. Greta Manlapig.'
                        ],
                        [
                            'year' => '1976',
                            'title' => 'First Graduates',
                            'content' => 'Over the next two years, enrollment rose to 249 students spread across six sections. The school, operating with only four classrooms and nine teachers, saw its first batch of graduates in 1976.'
                        ],
                        [
                            'year' => '1977-1978',
                            'title' => 'Rapid Growth',
                            'content' => 'By 1977-1978, the student population reached 774, with 15 sections sharing just seven classrooms. Recognizing the space constraints, Mr. Dumlao sought national government support for a new school building. Due to the relentless efforts of school leaders and the PTA, the current 1.3-hectare site was acquired.'
                        ],
                        [
                            'year' => '1981',
                            'title' => 'Infrastructure Expansion',
                            'content' => 'Under the leadership of Mrs. Virginia H. Cerrudo in 1981, a 10-room vocational building was built, followed by a two-room Home Economics building donated by the Metro Manila Professional and Business Ladies Circle.'
                        ],
                        [
                            'year' => '1988-1992',
                            'title' => 'Further Development',
                            'content' => 'Between 1988 and 1992, the school received significant infrastructure developments, including an administration building, a library, classrooms, and a large laboratory. The Payatas Annex expanded, and in 1992, the Maligaya Park Annex was opened.'
                        ],
                        [
                            'year' => '1993',
                            'title' => 'Modernization',
                            'content' => 'Under Mr. Gil T. Magbanua\'s leadership from 1993, additional improvements were made, such as enhanced drainage, repainting of buildings, and acquisition of a school vehicle through the PTA.'
                        ],
                        [
                            'year' => '1998',
                            'title' => 'Technological Advancement',
                            'content' => 'Further advancements came in the late 1990s, with Mrs. Sheridan Evangelista\'s return as principal in 1998, introducing technological improvements.'
                        ],
                        [
                            'year' => '2003',
                            'title' => 'Program Expansion',
                            'content' => 'In 2003, Dr. Fernando C. Javier led the construction of new buildings and renovated existing facilities, transforming the Social Hall into a multi-purpose conference room. The school also expanded its programs, establishing the Bureau of Alternative Learning System, Open High School, and Special Education Program.'
                        ],
                        [
                            'year' => '2012',
                            'title' => 'Leadership Transition',
                            'content' => 'Following Dr. Javier\'s retirement in 2012, Dr. Crispin Duka briefly took over before Dr. Maria Noemi M. Moncada assumed leadership. Dr. Moncada reformed the enrollment system to reduce dropout rates, ensuring that even Students-At-Risk of Drop-Outs (SARDOs) received proper guidance.'
                        ],
                        [
                            'year' => '2015',
                            'title' => 'Recognition & Excellence',
                            'content' => 'By 2015, Lagro High School was recognized for exemplary School-Based Management practices. The school also earned accolades from the Schools Division of Quezon City through PRAISE (DepEd Program on Awards and Incentives for Service Excellence), securing first place in the annual Teachers\' Day celebration in 2016, 2017, and 2019, and second place in 2018.'
                        ],
                        [
                            'year' => '2020',
                            'title' => 'Pandemic Response',
                            'content' => 'The COVID-19 pandemic in 2020 brought new challenges, but the school quickly adapted by migrating records, meetings, and trainings to a virtual format. Lagro High School became the first in the division to submit a Learning Continuity Plan, which was later adopted and improved by other regions.'
                        ],
                        [
                            'year' => '2021',
                            'title' => 'Continued Adaptation',
                            'content' => 'In 2021, Dr. Diego M. Amid took over following Dr. Moncada\'s retirement. Amid the pandemic, he reinforced the school\'s role in the community by supporting local vaccination programs and ensuring quality education despite the crisis. Under his leadership, the Science, Technology, and Engineering (STE) program gained more recognition.'
                        ],
                        [
                            'year' => '2023',
                            'title' => 'New Leadership & Vision',
                            'content' => 'In 2023, after the passing of Dr. Amid, Dr. Agapito T. Lera assumed the role of principal. Bringing vast experience and a vision for continuous growth, Dr. Lera implemented digital learning tools and strengthened the school\'s partnership with the local barangay. His leadership ensured that Lagro High School remained a model institution, adapting to the evolving educational landscape while maintaining its tradition of excellence.'
                        ]
                    ];

                    // Display timeline events
                    foreach ($timelineEvents as $index => $event) {
                        $position = $index % 2 === 0 ? 'left' : 'right';

                        echo '<div class="timeline-item">';
                        echo '    <div class="timeline-dot"></div>';
                        echo '    <div class="timeline-content">';
                        echo '        <span class="timeline-year">' . $event['year'] . '</span>';
                        echo '        <h3 class="timeline-title">' . $event['title'] . '</h3>';
                        echo '        <p>' . $event['content'] . '</p>';
                        echo '    </div>';
                        echo '</div>';
                    }
                    ?>

                </div>
            </section>

            <section class="milestone-section">
                <h2 class="section-title">Key Milestones</h2>
                <div class="milestones-container">
                    <div class="milestone-card" data-year="1974">
                        <div class="milestone-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <h3>Foundation</h3>
                        <p>Lagro High School began operations with 87 students in two modest housing units</p>
                    </div>

                    <div class="milestone-card" data-year="1976">
                        <div class="milestone-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3>First Graduates</h3>
                        <p>First batch of graduates from Lagro High School</p>
                    </div>

                    <div class="milestone-card" data-year="1981">
                        <div class="milestone-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Expansion</h3>
                        <p>Construction of 10-room vocational building under Mrs. Virginia H. Cerrudo</p>
                    </div>

                    <div class="milestone-card" data-year="2003">
                        <div class="milestone-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3>Modern Programs</h3>
                        <p>Establishment of Bureau of Alternative Learning System, Open High School, and Special Education Program</p>
                    </div>

                    <div class="milestone-card" data-year="2015">
                        <div class="milestone-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Recognition</h3>
                        <p>Recognized for exemplary School-Based Management practices</p>
                    </div>

                    <div class="milestone-card" data-year="2020">
                        <div class="milestone-icon">
                            <i class="fas fa-laptop-house"></i>
                        </div>
                        <h3>Digital Adaptation</h3>
                        <p>Pioneering digital transformation during the COVID-19 pandemic</p>
                    </div>
                </div>
            </section>

            <section class="present-day-section">
                <div class="present-content">
                    <div class="present-text">
                        <h2>Lagro High School Today</h2>
                        <p>Today, Lagro High School stands as a beacon of education, resilience, and community spirit. Its history reflects a legacy of growth, innovation, and unwavering dedication to providing quality education for all students in Lagro Subdivision and beyond.</p>
                        <p>Under current leadership, the school continues to adapt to the evolving educational landscape while maintaining its tradition of excellence.</p>
                    </div>
                </div>
            </section>
        </main>

    </div>
    <?php include "index.php" ?>
    <?php include 'includes/footer.php'; ?>
    <?php include "includes/scroll-up.php" ?>

    <script>
        /**
         * Main JavaScript file for Lagro High School History Page
         * Handles interactive elements, navigation, and other functionality
         */

        document.addEventListener('DOMContentLoaded', function() {
            // Navigation & Header functionality
            const header = document.querySelector('.site-header');
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const navMenu = document.querySelector('.nav-menu');

            // Scroll effect for header
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Mobile menu toggle
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');

                    // Change icon based on menu state
                    const icon = mobileMenuToggle.querySelector('i');
                    if (navMenu.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }

            // Close mobile menu when clicking on a link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');

                    // Reset icon
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        const headerHeight = header.offsetHeight;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Initialize timeline animation
            initTimelineAnimation();

            // Initialize milestone cards animation
            initMilestoneAnimation();
        });

        // Function to initialize timeline animation
        function initTimelineAnimation() {
            const timelineItems = document.querySelectorAll('.timeline-item');

            // Check if timeline items exist
            if (timelineItems.length === 0) return;

            // Initial check for items in viewport
            checkTimelineItems();

            // Add scroll event listener
            window.addEventListener('scroll', checkTimelineItems);

            function checkTimelineItems() {
                timelineItems.forEach(item => {
                    const itemTop = item.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    // If item is in viewport
                    if (itemTop < windowHeight * 0.85) {
                        item.classList.add('visible');
                    }
                });
            }
        }

        // Function to initialize milestone cards animation
        function initMilestoneAnimation() {
            const milestoneCards = document.querySelectorAll('.milestone-card');

            // Check if milestone cards exist
            if (milestoneCards.length === 0) return;

            // Add animation class to all milestone cards
            milestoneCards.forEach(card => {
                card.classList.add('animate-slide-up');
            });

            // Initial check for cards in viewport
            checkMilestoneCards();

            // Add scroll event listener
            window.addEventListener('scroll', checkMilestoneCards);

            function checkMilestoneCards() {
                milestoneCards.forEach(card => {
                    const cardTop = card.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    // If card is in viewport
                    if (cardTop < windowHeight * 0.85) {
                        card.classList.add('visible');
                    }
                });
            }
        }

        /**
         * Animations for Lagro High School History Page
         * Handles scroll-based animations and interactive elements
         */

        document.addEventListener('DOMContentLoaded', function() {
            // Animation on scroll for various elements
            const animatedElements = document.querySelectorAll('.animate-on-scroll');

            // Initial check for elements in viewport
            checkAnimatedElements();

            // Add scroll event listener
            window.addEventListener('scroll', checkAnimatedElements);

            function checkAnimatedElements() {
                animatedElements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    // If element is in viewport
                    if (elementTop < windowHeight * 0.85) {
                        element.classList.add('visible');
                    }
                });
            }

            // Parallax effect for hero section
            const hero = document.querySelector('.hero');

            if (hero) {
                window.addEventListener('scroll', function() {
                    const scrollPosition = window.pageYOffset;
                    hero.style.backgroundPositionY = `${scrollPosition * 0.4}px`;
                });
            }

            // Text animation for present day section
            const presentSection = document.querySelector('.present-day-section');

            if (presentSection) {
                window.addEventListener('scroll', function() {
                    const sectionTop = presentSection.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (sectionTop < windowHeight * 0.7) {
                        presentSection.classList.add('animate-text');
                    }
                });
            }

            // Interactive milestone cards
            const milestoneCards = document.querySelectorAll('.milestone-card');

            milestoneCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    // Add pulsing effect to the icon
                    const icon = this.querySelector('.milestone-icon');
                    icon.classList.add('pulse');
                });

                card.addEventListener('mouseleave', function() {
                    // Remove pulsing effect
                    const icon = this.querySelector('.milestone-icon');
                    icon.classList.remove('pulse');
                });
            });

            // Add CSS for the pulse animation
            const style = document.createElement('style');
            style.textContent = `
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .milestone-icon.pulse {
            animation: pulse 1s ease infinite;
        }
    `;
            document.head.appendChild(style);

            // Timeline animation enhancements
            const timelineItems = document.querySelectorAll('.timeline-item');

            timelineItems.forEach((item, index) => {
                // Add staggered animation delay based on index
                const delay = 0.05 * index;
                item.style.transitionDelay = `${delay}s`;

                // Add hover effect for timeline content
                const content = item.querySelector('.timeline-content');

                content.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.2)';
                });

                content.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.15)';
                });
            });

            // Typewriter effect for hero subtitle
            const heroSubtitle = document.querySelector('.hero-subtitle');

            if (heroSubtitle) {
                const originalText = heroSubtitle.textContent;
                heroSubtitle.textContent = '';

                let charIndex = 0;
                const typeSpeed = 50; // milliseconds per character

                function typeWriter() {
                    if (charIndex < originalText.length) {
                        heroSubtitle.textContent += originalText.charAt(charIndex);
                        charIndex++;
                        setTimeout(typeWriter, typeSpeed);
                    }
                }

                // Start typewriter effect after a short delay
                setTimeout(typeWriter, 1000);
            }
        });
    </script>
</body>

</html>