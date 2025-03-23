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
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <title>Vision & Mission | Lagro High School</title>
    <style>
        :root {
            --primary-color: rgb(32, 91, 62);
            --primary-dark: rgb(24, 68, 47);
            --primary-light: rgba(32, 91, 62, 0.1);
            --secondary-color: rgb(52, 140, 81);
            --secondary-light: rgba(52, 140, 81, 0.15);
            --accent-color: #a0e4ff;
            --gold: #D4AF37;
            --gold-light: rgba(212, 175, 55, 0.15);
            --text-color: #2d3436;
            --text-light: #636e72;
            --white: #ffffff;
            --off-white: #f8f9fa;
            --gray-light: #f1f2f6;
            --box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 60vh;
            background-image: linear-gradient(to right, rgba(24, 68, 47, 0.8), rgba(52, 140, 81, 0.7)), url('/CommunityHub/photos/Lagrobg4.jpg');
            background-size: cover;
            background-position: center;
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 20px;
            margin-bottom: 130px;
        }

        .hero-content {
            max-width: 900px;
            z-index: 1;
        }

        .hero-subtitle {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 300;
            font-size: 1rem;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 1s forwards 0.3s;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 25px;
            line-height: 1.2;
            opacity: 0;
            animation: fadeInUp 1s forwards;
        }

        .hero-description {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0;
            animation: fadeInUp 1s forwards 0.6s;
        }

        .page-container {
            max-width: 1200px;
            margin: -100px auto 0;
            padding: 0 20px;
            position: relative;
        }

        /* Modern Cards with Accent Colors - LARGER SIZE */
        .cards-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .card {
            background-color: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            transform: translateY(0);
            position: relative;
            min-height: 500px;
            /* Increased min-height */
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-accent {
            position: absolute;
            top: 0;
            left: 0;
            height: 8px;
            /* Thicker accent line */
            width: 100%;
        }

        .accent-vision {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .accent-mission {
            background: linear-gradient(to right, var(--gold), #FFD700);
        }

        .accent-values {
            background: linear-gradient(to right, var(--accent-color), #48dbfb);
        }

        .card-icon-wrapper {
            width: 100px;
            /* Larger icon */
            height: 100px;
            /* Larger icon */
            border-radius: 50%;
            margin: -50px auto 0;
            /* Adjusted for larger size */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .vision-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .mission-icon {
            background: linear-gradient(135deg, #D4AF37, #FFD700);
        }

        .values-icon {
            background: linear-gradient(135deg, #0984e3, #74b9ff);
        }

        .card-icon {
            color: white;
            font-size: 40px;
            /* Larger icon */
        }

        .card-content {
            padding: 40px 50px;
            /* More padding */
        }

        .card-title {
            font-family: 'Cormorant Garamond', serif;
            margin: 20px 0 15px;
            font-size: 2.5rem;
            /* Larger font size */
            font-weight: 700;
            text-align: center;
            color: var(--primary-dark);
        }

        .card-subtitle {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 25px;
            font-size: 1.1rem;
            /* Larger font size */
            font-weight: 400;
        }

        .divider {
            width: 80px;
            /* Wider divider */
            height: 4px;
            /* Thicker divider */
            background-color: var(--secondary-color);
            margin: 0 auto 30px;
            /* More space below */
        }

        .vision-statement {
            font-size: 1.2rem;
            /* Larger font size */
            line-height: 1.8;
            text-align: center;
            margin-bottom: 30px;
            /* More space below */
            color: var(--text-color);
            font-weight: 500;
            padding: 25px;
            /* More padding */
            background-color: var(--secondary-light);
            border-radius: 12px;
            /* Larger radius */
        }

        .institution-statement {
            font-size: 1.1rem;
            /* Larger font size */
            color: var(--text-light);
            line-height: 1.7;
            text-align: center;
            font-style: italic;
            padding: 0 20px;
            /* Added padding */
        }

        .mission-list {
            list-style-type: none;
            margin: 30px 0 25px;
            /* More space */
            padding: 0 20px;
            /* Added padding */
        }

        .mission-list li {
            position: relative;
            padding-left: 40px;
            /* More space for icon */
            margin-bottom: 20px;
            /* More space between items */
            color: var(--text-color);
            font-size: 1.1rem;
            /* Larger font size */
        }

        .mission-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            width: 24px;
            /* Larger bullet */
            height: 24px;
            /* Larger bullet */
            background-color: var(--gold-light);
            border-radius: 50%;
        }

        .mission-list li::after {
            content: '✓';
            position: absolute;
            left: 7px;
            /* Adjusted for larger bullet */
            top: 7px;
            color: var(--gold);
            font-size: 0.9rem;
            /* Larger checkmark */
            font-weight: bold;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            /* More gap between items */
            margin-top: 35px;
            /* More top margin */
            padding: 0 15px;
            /* Added padding */
        }

        .value-item {
            text-align: center;
            padding: 30px 20px;
            /* More padding */
            border-radius: 12px;
            /* Larger radius */
            background-color: var(--gray-light);
            transition: var(--transition);
            border-bottom: 4px solid transparent;
            /* Thicker border */
        }

        .value-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border-bottom: 4px solid var(--accent-color);
            /* Thicker border */
        }

        .value-icon {
            font-size: 2.5rem;
            /* Larger icon */
            color: var(--accent-color);
            margin-bottom: 20px;
            /* More space below */
        }

        .value-title {
            font-weight: 600;
            margin-bottom: 15px;
            /* More space below */
            font-size: 1.3rem;
            /* Larger font size */
            color: var(--primary-dark);
        }

        .value-description {
            font-size: 1rem;
            /* Larger font size */
            color: var(--text-light);
        }

        /* Action Buttons */
        .actions {
            text-align: center;
            margin-top: 60px;
            /* More top margin */
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 35px;
            /* Larger button */
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1rem;
            /* Larger font size */
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(32, 91, 62, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(32, 91, 62, 0.4);
        }

        /* Animations */
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

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .hero {
                height: 50vh;
            }

            .hero-title {
                font-size: 3.5rem;
            }

            .card-content {
                padding: 35px 30px;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.8rem;
            }

            .hero-description {
                font-size: 1.1rem;
            }

            .page-container {
                margin-top: -70px;
            }

            .cards-container {
                grid-template-columns: 1fr;
                gap: 60px;
                /* More gap on mobile */
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .card-content {
                padding: 30px 25px;
            }

            .card-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 480px) {
            .hero {
                height: 45vh;
                padding: 0 15px;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 0.9rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .page-container {
                padding: 0 15px;
            }

            .card-content {
                padding: 25px 20px;
            }

            .card-title {
                font-size: 2rem;
            }

            .vision-statement {
                font-size: 1.1rem;
                padding: 20px;
            }

            .btn {
                padding: 15px 30px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <p class="hero-subtitle">Our Guiding Principles</p>
            <h1 class="hero-title">Vision & Mission</h1>
            <p class="hero-description">Discover the driving force behind Lagro High School's commitment to excellence in education and character formation</p>
        </div>
    </section>

    <div class="page-container">
        <div class="cards-container">
            <!-- Vision Card -->
            <div class="card">
                <div class="card-accent accent-vision"></div>
                <div class="card-icon-wrapper vision-icon">
                    <i class="fas fa-eye card-icon"></i>
                </div>
                <div class="card-content">
                    <h2 class="card-title">Our Vision</h2>
                    <p class="card-subtitle">Looking to the Future</p>
                    <div class="divider"></div>

                    <div class="vision-statement">
                        We dream of Filipinos who passionately love their country and whose values and competencies enables them to realize their full potential and contribute meaningfully to building the nation.
                    </div>

                    <p class="institution-statement">
                        As a learner-centered public institution, the Department of Education continuously improves itself to better serve its stakeholders.
                    </p>
                </div>
            </div>

            <!-- Mission Card -->
            <div class="card">
                <div class="card-accent accent-mission"></div>
                <div class="card-icon-wrapper mission-icon">
                    <i class="fas fa-bullseye card-icon"></i>
                </div>
                <div class="card-content">
                    <h2 class="card-title">Our Mission</h2>
                    <p class="card-subtitle">Our Purpose & Commitment</p>
                    <div class="divider"></div>

                    <p style="margin-bottom: 20px; font-size: 1.1rem; text-align: center;">
                        To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete basic education where:
                    </p>

                    <ul class="mission-list">
                        <li>Students learn in a child-friendly, gender-sensitive, safe, and motivating environment.</li>
                        <li>Teachers facilitate learning and constantly nurture every learner.</li>
                        <li>Administrators and staff, as stewards of the institution, ensure an enabling and supportive environment for effective learning to happen.</li>
                        <li>Family, community, and other stakeholders are actively engaged and share responsibility for developing life-long learners.</li>
                    </ul>
                </div>
            </div>

            <!-- Core Values Card -->
            <div class="card">
                <div class="card-accent accent-values"></div>
                <div class="card-icon-wrapper values-icon">
                    <i class="fas fa-heart card-icon"></i>
                </div>
                <div class="card-content">
                    <h2 class="card-title">Core Values</h2>
                    <p class="card-subtitle">What We Believe In</p>
                    <div class="divider"></div>

                    <div class="values-grid">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-praying-hands"></i>
                            </div>
                            <h3 class="value-title">Maka-Diyos</h3>
                            <p class="value-description">Expresses faith in God and respects different spiritual beliefs</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="value-title">Maka-tao</h3>
                            <p class="value-description">Respects rights and dignity of every person</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h3 class="value-title">Makakalikasan</h3>
                            <p class="value-description">Cares for the environment and natural resources</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <h3 class="value-title">Makabansa</h3>
                            <p class="value-description">Takes pride in being a Filipino and contributes to national development</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    include "index.php"
    ?>
    <?php include "includes/footer.php" ?>
    <?php include "includes/scroll-up.php" ?>

</body>

</html>