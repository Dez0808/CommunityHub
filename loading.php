<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading</title>
    <style>
        /* Loading Animation */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loading.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(52, 140, 81, 0.2);
            border-radius: 50%;
            border-top-color: rgb(52, 140, 81);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="loading">
        <div class="loading-spinner"></div>
    </div>
    <script>
        window.addEventListener('load', function() {
            const loader = document.querySelector('.loading');
            setTimeout(function() {
                loader.classList.add('hidden');
            }, 500);
        });
    </script>
</body>

</html>