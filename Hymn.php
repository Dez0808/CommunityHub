<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <title>School Hymn - Lagro High School</title>
    <style>
        :root {
            --primary-color: #348c51;
            --primary-dark: #123524;
            --primary-light: rgba(52, 140, 81, 0.1);
            --secondary-color: #4c7f66;
            --accent-color2: #ffd700;
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
            height: 60vh;
            background: linear-gradient(rgba(18, 53, 36, 0.8), rgba(18, 53, 36, 0.8)),
                url('/CommunityHub/photos/lagrobg4.jpg');
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
            background-color: var(--accent-color2);
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

        /* Introduction Card */
        .intro-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            transform: translateY(0);
            transition: var(--transition);
            opacity: 0;
            animation: fadeIn 1s forwards 0.5s;
        }

        .intro-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .intro-card::before {
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
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--accent-color2);
            border-radius: 2px;
        }

        .intro-text {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Hymn Box */
        .hymn-box {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px 30px;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fadeIn 1s forwards 0.8s;
        }

        .hymn-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .hymn-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: var(--primary-dark);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .hymn-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 3px;
            background-color: var(--accent-color2);
            border-radius: 2px;
        }

        .hymn-subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        /* Verses Styling */
        .verses-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .verse-card {
            background-color: var(--gray-light);
            border-radius: 10px;
            padding: 25px;
            position: relative;
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
            transform: translateY(30px);
            opacity: 0;
        }

        .verse-card.appear {
            transform: translateY(0);
            opacity: 1;
        }

        .verse-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .verse-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-color);
            text-align: center;
        }

        /* Musical Elements */
        .music-icon {
            position: absolute;
            color: var(--primary-color);
            opacity: 0.05;
            font-size: 3rem;
            z-index: 0;
        }

        .icon-1 {
            top: 50px;
            right: 40px;
            transform: rotate(15deg);
        }

        .icon-2 {
            bottom: 50px;
            left: 40px;
            transform: rotate(-10deg);
        }

        .icon-3 {
            top: 150px;
            left: 60px;
            transform: rotate(20deg);
        }

        .icon-4 {
            bottom: 150px;
            right: 60px;
            transform: rotate(-15deg);
        }

        /* Action Button */
        .action-container {
            text-align: center;
            margin-top: 40px;
        }

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
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .btn-secondary {
            background-color: var(--accent-color2);
            color: var(--primary-dark);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52, 140, 81, 0.4);
        }

        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }

        /* Audio Player */
        .audio-player {
            width: 100%;
            max-width: 500px;
            margin: 0 auto 30px;
            display: none;
        }

        .audio-player.active {
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
                height: 50vh;
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

            .section-title,
            .hymn-title {
                font-size: 2rem;
            }

            .page-container {
                margin-top: -40px;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                height: 45vh;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title,
            .hymn-title {
                font-size: 1.8rem;
            }

            .intro-card,
            .hymn-box {
                padding: 25px 20px;
            }

            .verse-content {
                font-size: 1rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php include "includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">School Hymn</h1>
            <p class="hero-subtitle">The official anthem that celebrates the values and traditions of Lagro High School</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="page-container">
        <!-- Introduction Card -->
        <div class="intro-card">
            <h2 class="section-title">About Our School Hymn</h2>
            <p class="intro-text">The Lagro High School Hymn is a musical embodiment of our school's heritage, values, and aspirations. Sung with pride at important events, ceremonies, and gatherings, it represents the spirit of unity that binds all students, faculty, and alumni. The hymn is a testament to our commitment to academic excellence, character formation, and service to the community.</p>
        </div>

        <!-- Hymn Box -->
        <div class="hymn-box">
            <i class="music-icon fas fa-music icon-1"></i>
            <i class="music-icon fas fa-music icon-2"></i>
            <i class="music-icon fas fa-music icon-3"></i>
            <i class="music-icon fas fa-music icon-4"></i>

            <div class="hymn-header">
                <h2 class="hymn-title">Lagro High School Hymn</h2>
                <p class="hymn-subtitle">Words and Music by Lagro High School</p>
            </div>

            <!-- Hidden audio element instead of a player div -->
            <audio id="hymnAudio" style="display: none;">
                <source src="Music/Lagro High School Hymn Orchestral Version.mp3" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>

            <div class="action-container" style="margin-bottom: 30px;">
                <button id="playBtn" class="btn btn-secondary">
                    <i class="fas fa-play"></i> Play Hymn
                </button>
            </div>

            <div class="verses-container">
                <div class="verse-card">
                    <p class="verse-content">We are yours our dearest Alma Mater<br>We love we praise we honor you forever</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">Lagro High School dear Alma Mater<br>The crowning glory of our dreams<br>We offer you our treasures rare<br>Our hearts and minds for you to rear</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">With bright hopes for greater knowledge<br>And fervent prayers for our success<br>We delve deep into your wisdom<br>We seek our Lord's ennobling grace</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">When our dreams burst into glory<br>And we rise radiant but humble</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">To you dearest Alma Mater<br>Goes our tribute of love and praise</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">We are yours our dearest Alma Mater<br>We love we praise we honor you forever</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">Lagro High School dear Alma Mater<br>You set our hearts and minds aglow<br>For your honor we praise our best</p>
                </div>

                <div class="verse-card">
                    <p class="verse-content">We are yours through all the years</p>
                </div>
            </div>
        </div>
    </div>

    <?php include "index.php" ?>
    <?php include "includes/footer.php" ?>
    <?php include "includes/scroll-up.php" ?>


    <script>
        // Animation for verse cards
        document.addEventListener('DOMContentLoaded', function() {
            const verseCards = document.querySelectorAll('.verse-card');

            // Function to check if element is in viewport
            function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            // Function to check for scroll and animate visible elements
            function checkScroll() {
                verseCards.forEach(card => {
                    if (isInViewport(card)) {
                        card.classList.add('appear');
                    }
                });
            }

            // Initial check
            checkScroll();

            // Check on scroll
            window.addEventListener('scroll', checkScroll);

            // Audio player functionality
            const playBtn = document.getElementById('playBtn');
            const hymnAudio = document.getElementById('hymnAudio');
            let isPlaying = false;

            playBtn.addEventListener('click', function() {
                if (isPlaying) {
                    hymnAudio.pause();
                    playBtn.innerHTML = '<i class="fas fa-play"></i> Play Hymn';
                    isPlaying = false;
                } else {
                    hymnAudio.play();
                    playBtn.innerHTML = '<i class="fas fa-pause"></i> Pause Hymn';
                    isPlaying = true;
                }
            });

            // Reset button when audio ends
            hymnAudio.addEventListener('ended', function() {
                playBtn.innerHTML = '<i class="fas fa-play"></i> Play Hymn';
                isPlaying = false;
            });
        });
    </script>
</body>

</html>