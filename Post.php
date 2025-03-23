<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = "";

include "Demo_DBConnection.php";
// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to login page if not logged in
    header("Location: /CommunityHub/Demo_Login.php");
    exit;
}

if (isset($_POST['upload'])) {
    // Only process form submission if user is admin
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
        $text = $_POST['text'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO post (Text) VALUES (?)");
        $stmt->bind_param("s", $text);
        $stmt->execute();
        $postId = $conn->insert_id; // Get the last inserted post ID

        // Check if files were actually uploaded (not just empty input)
        $filesUploaded = false;
        if (isset($_FILES['uploadfile']) && is_array($_FILES['uploadfile']['name'])) {
            foreach ($_FILES['uploadfile']['error'] as $error) {
                if ($error !== UPLOAD_ERR_NO_FILE) {
                    $filesUploaded = true;
                    break;
                }
            }
        }

        if ($filesUploaded) {
            $files = $_FILES['uploadfile'];

            for ($i = 0; $i < count($files['name']); $i++) {
                // Skip if no file was uploaded in this slot
                if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $originalFilename = $files["name"][$i];
                    $fileExt = pathinfo($originalFilename, PATHINFO_EXTENSION);
                    $filename = time() . '_' . md5(rand(1000, 9999)) . '.' . $fileExt; // Unique filename
                    $tempname = $files["tmp_name"][$i];
                    $folder = "./uploads/" . $filename;

                    // Determine if file is image or video
                    $isVideo = in_array(strtolower($fileExt), ['mp4', 'webm', 'ogg', 'mov']);
                    $mediaType = $isVideo ? 'video' : 'image';

                    // Insert the media into the media table
                    $mediaStmt = $conn->prepare("INSERT INTO media (post_id, filename, media_type) VALUES (?, ?, ?)");
                    $mediaStmt->bind_param("iss", $postId, $filename, $mediaType);
                    $mediaStmt->execute();

                    // Create uploads directory if it doesn't exist
                    if (!file_exists('./uploads/')) {
                        mkdir('./uploads/', 0777, true);
                    }

                    // Move the uploaded file into the folder
                    if (move_uploaded_file($tempname, $folder)) {
                    } else {
                        echo "<h3>&nbsp; Failed to upload media '$originalFilename'!</h3>";
                    }
                } else {
                    $errorMessage = getUploadErrorMessage($files['error'][$i]);
                    echo "<h3>&nbsp; Error uploading file '{$files['name'][$i]}': $errorMessage</h3>";
                }
            }
        }
    } else {
        // If non-admin tries to post, show an error message
        echo "<div class='alert alert-danger'>You don't have permission to create posts.</div>";
    }
    // Redirect to prevent form resubmission
}

// Function to get meaningful error messages for file upload errors
function getUploadErrorMessage($errorCode)
{
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return "The uploaded file exceeds the upload_max_filesize directive in php.ini";
        case UPLOAD_ERR_FORM_SIZE:
            return "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form";
        case UPLOAD_ERR_PARTIAL:
            return "The uploaded file was only partially uploaded";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Missing a temporary folder";
        case UPLOAD_ERR_CANT_WRITE:
            return "Failed to write file to disk";
        case UPLOAD_ERR_EXTENSION:
            return "A PHP extension stopped the file upload";
        default:
            return "Unknown upload error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Post Feature</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Content Container */
        .content-container {
            max-width: 800px;
            margin: 30px auto 40px;
            padding: 0 20px;
        }

        /* Post Creation Styles */
        .write-post-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            resize: none;
            font-family: inherit;
            font-size: 16px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .upload-btn,
        .post-btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            flex: 1;
            font-size: 14px;
            font-weight: bold;
        }

        .upload-btn {
            background-color: #f0f0f0;
            color: #333;
        }

        .upload-btn:hover {
            background-color: #e0e0e0;
        }

        .post-btn {
            background-color: #4c7f66;
            color: white;
        }

        .post-btn:hover {
            background-color: #3a6b52;
        }

        .hidden {
            display: none;
        }

        /* Preview Container */
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .preview-item {
            position: relative;
            width: 120px;
            margin-bottom: 5px;
        }

        .preview-item img,
        .preview-item video {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .file-name {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .remove-preview {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ff5555;
            color: white;
            border: none;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        /* Posts Display */
        .posts-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .post-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.3s ease;
        }

        .post-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .post-text {
            margin: 0 0 20px;
            line-height: 1.6;
            font-size: 16px;
            color: #333;
            white-space: pre-wrap;
            word-wrap: break-word;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Single image display */
        .single-image-container {
            width: 100%;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
        }

        .single-image-container img {
            max-width: 100%;
            max-height: 600px;
            width: auto;
            height: auto;
            cursor: pointer;
            display: inline-block;
        }

        .single-image-container video {
            max-width: 100%;
            max-height: 600px;
            width: auto;
            height: auto;
            cursor: pointer;
            display: inline-block;
        }

        /* Multiple images display - IMPROVED GRID LAYOUT */
        .media-grid {
            display: grid;
            gap: 4px;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Two images side by side */
        .media-grid.grid-2 {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 250px;
        }

        /* Three images: one on top, two below */
        .media-grid.grid-3 {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: auto auto;
        }

        .media-grid.grid-3 .media-item:first-child {
            grid-column: 1 / span 2;
        }

        /* Four images in a 2x2 grid */
        .media-grid.grid-4 {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 250px;
        }

        /* Five or more images */
        .media-grid.grid-many {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 250px;
        }

        .media-item {
            position: relative;
            cursor: pointer;
            overflow: hidden;
            border-radius: 4px;
            min-height: 200px;
            max-height: 400px;
        }

        .media-item img, 
        .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Changed from contain to cover */
            display: block;
        }

        /* Special handling for tall/long images */
        .media-item.tall-image img {
            object-position: top; /* Show the top part of tall images */
        }

        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 15px 0 15px 30px;
            border-color: transparent transparent transparent rgba(255, 255, 255, 0.8);
            z-index: 2;
        }

        .more-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* Post Actions */
        .post-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .delete-btn {
            background-color: #ff5555;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #ff3333;
        }

        /* Lightbox - Shows full image/video */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            overflow: auto;
        }

        .lightbox-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .lightbox-media {
            max-width: 90%;
            max-height: 90%;
            width: auto;
            height: auto;
            object-fit: contain; /* Show the entire image in lightbox */
            display: none;
        }

        .close-lightbox {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
        }

        /* Lightbox Navigation Arrows */
        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .lightbox-nav:hover {
            background-color: rgba(0, 0, 0, 0.6);
        }

        .lightbox-prev {
            left: 20px;
        }

        .lightbox-next {
            right: 20px;
        }

        /* Hide navigation arrows when there's only one image */
        .lightbox.single-media .lightbox-nav {
            display: none;
        }

        /* Admin message */
        .admin-only-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-links {
                gap: 20px;
                margin-right: 20px;
            }

            .content-container {
                padding: 0 15px;
            }

            .button-group {
                flex-direction: column;
            }
            
            .media-grid.grid-2,
            .media-grid.grid-3,
            .media-grid.grid-4,
            .media-grid.grid-many {
                grid-auto-rows: 200px;
            }

            .lightbox-nav {
                font-size: 30px;
                width: 40px;
                height: 40px;
            }

            .lightbox-prev {
                left: 10px;
            }

            .lightbox-next {
                right: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="content-container">
        <?php
        // Check if user is admin before showing the post form
        if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
        ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="write-post-container">
                    <textarea id="postText" placeholder="What's on your mind?" name="text"></textarea>
                    <div id="previewContainer" class="preview-container"></div>
                    <div class="button-group">
                        <input type="button" class="upload-btn" onclick="document.getElementById('fileInput').click();" value="Upload Media">
                        <button class="post-btn" id="post" name="upload" type="submit">Post</button>
                    </div>
                    <!-- Important: value="" ensures the input is cleared after submission -->
                    <input type="file" id="fileInput" class="hidden" accept="image/*,video/*" multiple onchange="previewFiles()" name="uploadfile[]" value="">
                </div>
            </form>
        <?php
        } else {
            // Only show this message if the user is logged in but not an admin
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                echo '<div class="admin-only-message">Only administrators can create new posts.</div>';
            }
        }
        ?>

        <div class="posts-container">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM post ORDER BY id DESC");

            if (mysqli_num_rows($result) > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $postId = $data['id'];
            ?>
                    <div class="post-card" data-post-id="<?php echo $postId; ?>">
                        <?php if (!empty(trim($data['Text']))) { ?>
                            <p class="post-text"><?php echo nl2br(htmlspecialchars($data['Text'])); ?></p>
                        <?php } ?>

                        <?php
                        // Check if we need to use the new media table or the old images table
                        $mediaQuery = "SELECT * FROM media WHERE post_id = '$postId'";
                        $mediaResult = mysqli_query($conn, $mediaQuery);
                        $mediaCount = mysqli_num_rows($mediaResult);

                        if ($mediaCount > 0) {
                            // Store media items in an array for processing
                            $mediaItems = [];
                            while ($mediaData = mysqli_fetch_assoc($mediaResult)) {
                                $mediaItems[] = $mediaData;
                            }

                            // Different display based on number of media items
                            if ($mediaCount == 1) {
                                // Single image/video display
                                $media = $mediaItems[0];
                                $filename = htmlspecialchars($media['filename']);
                                $mediaType = $media['media_type'];

                                echo '<div class="single-image-container">';
                                if ($mediaType == 'video') {
                                    echo '<video src="./uploads/' . $filename . '" onclick="openLightbox(\'' . $filename . '\', \'' . $mediaType . '\', 0, ' . $postId . ')"></video>';
                                    echo '<div class="play-icon"></div>';
                                } else {
                                    echo '<img src="./uploads/' . $filename . '" alt="Post image" onclick="openLightbox(\'' . $filename . '\', \'' . $mediaType . '\', 0, ' . $postId . ')">';
                                }
                                echo '</div>';
                            } else {
                                // Multiple images/videos grid
                                $gridClass = 'grid-' . min($mediaCount, 4);
                                if ($mediaCount > 4) {
                                    $gridClass = 'grid-many';
                                }

                                echo '<div class="media-grid ' . $gridClass . '">';
                                
                                // Display up to 4 media items
                                $displayCount = min($mediaCount, 4);
                                for ($i = 0; $i < $displayCount; $i++) {
                                    $media = $mediaItems[$i];
                                    $filename = htmlspecialchars($media['filename']);
                                    $mediaType = $media['media_type'];

                                    echo '<div class="media-item" onclick="openLightbox(\'' . $filename . '\', \'' . $mediaType . '\', ' . $i . ', ' . $postId . ')">';
                                    
                                    if ($mediaType == 'video') {
                                        echo '<video src="./uploads/' . $filename . '" data-index="' . $i . '"></video>';
                                        echo '<div class="play-icon"></div>';
                                    } else {
                                        echo '<img src="./uploads/' . $filename . '" alt="Post image" data-index="' . $i . '" onload="detectTallImage(this)">';
                                    }

                                    // Add +X overlay to the last visible item if there are more than 4
                                    if ($i == 3 && $mediaCount > 4) {
                                        echo '<div class="more-overlay">+' . ($mediaCount - 4) . '</div>';
                                    }
                                    
                                    echo '</div>';
                                }
                                echo '</div>';
                                
                                // Add hidden data attribute with all media items for this post
                                echo '<div class="hidden-media-data" style="display: none;" data-media-items=\'' . json_encode($mediaItems) . '\'></div>';
                            }
                        } else {
                            // Try the old images table as fallback
                            $imageResult = mysqli_query($conn, "SELECT * FROM images WHERE post_id = '$postId'");
                            $imageCount = mysqli_num_rows($imageResult);
                            
                            if ($imageCount > 0) {
                                // Store image items in an array for processing
                                $imageItems = [];
                                while ($imageData = mysqli_fetch_assoc($imageResult)) {
                                    $imageItems[] = $imageData;
                                }

                                // Different display based on number of image items
                                if ($imageCount == 1) {
                                    // Single image display
                                    $image = $imageItems[0];
                                    $filename = htmlspecialchars($image['filename']);

                                    echo '<div class="single-image-container">';
                                    echo '<img src="/CommunityHub/image/' . $filename . '" alt="Post image" onclick="openLightbox(\'' . $filename . '\', \'image\', 0, ' . $postId . ')">';
                                    echo '</div>';
                                } else {
                                    // Multiple images grid
                                    $gridClass = 'grid-' . min($imageCount, 4);
                                    if ($imageCount > 4) {
                                        $gridClass = 'grid-many';
                                    }

                                    echo '<div class="media-grid ' . $gridClass . '">';
                                    
                                    // Display up to 4 image items
                                    $displayCount = min($imageCount, 4);
                                    for ($i = 0; $i < $displayCount; $i++) {
                                        $image = $imageItems[$i];
                                        $filename = htmlspecialchars($image['filename']);

                                        echo '<div class="media-item" onclick="openLightbox(\'' . $filename . '\', \'image\', ' . $i . ', ' . $postId . ')">';
                                        echo '<img src="/CommunityHub/image/' . $filename . '" alt="Post image" data-index="' . $i . '" onload="detectTallImage(this)">';
                                        
                                        // Add +X overlay to the last visible item if there are more than 4
                                        if ($i == 3 && $imageCount > 4) {
                                            echo '<div class="more-overlay">+' . ($imageCount - 4) . '</div>';
                                        }
                                        
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                    
                                    // Add hidden data attribute with all image items for this post
                                    echo '<div class="hidden-media-data" style="display: none;" data-media-items=\'' . json_encode($imageItems) . '\'></div>';
                                }
                            }
                        }
                        ?>

                        <?php
                        // Only show delete button to admins
                        if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
                        ?>
                            <div class="post-actions">
                                <form method="POST" action="postDelete.php" style="display:inline;">
                                    <input type="hidden" name="post_id" value="<?php echo $data['id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
            <?php
                }
            } else {
                echo '<div class="post-card"><p class="post-text">No posts available.</p></div>';
            }
            ?>
        </div>
    </div>

    <!-- Lightbox for viewing media in full size with navigation arrows -->
    <div id="lightbox" class="lightbox">
        <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-image" class="lightbox-media">
            <video id="lightbox-video" class="lightbox-media" controls></video>
            <div class="lightbox-nav lightbox-prev" onclick="navigateLightbox('prev')">&lt;</div>
            <div class="lightbox-nav lightbox-next" onclick="navigateLightbox('next')">&gt;</div>
        </div>
    </div>

    <script>
        // Global variables to track current lightbox state
        let currentPostId = null;
        let currentMediaIndex = 0;
        let currentMediaItems = [];

        // Function to preview files before upload
        function previewFiles() {
            const fileInput = document.getElementById('fileInput');
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = '';

            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach(file => {
                    const reader = new FileReader();

                    // Create preview item container
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';

                    // Create remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-preview';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function(e) {
                        e.preventDefault();
                        previewItem.remove();
                        // Note: We can't actually remove items from the FileList,
                        // but we can hide them from the user and handle this server-side
                    };

                    reader.onload = function(e) {
                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            previewItem.appendChild(img);
                        } else if (file.type.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.src = e.target.result;
                            video.setAttribute('muted', 'true');
                            video.setAttribute('loop', 'true');
                            video.onmouseover = function() {
                                this.play();
                            };
                            video.onmouseout = function() {
                                this.pause();
                            };
                            previewItem.appendChild(video);
                        }

                        // Add file name
                        const fileName = document.createElement('span');
                        fileName.className = 'file-name';
                        fileName.textContent = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;
                        previewItem.appendChild(fileName);

                        // Add remove button
                        previewItem.appendChild(removeBtn);

                        // Add to preview container
                        previewContainer.appendChild(previewItem);
                    };

                    reader.readAsDataURL(file);
                });
            }
        }

        // Function to detect tall images and apply special styling
        function detectTallImage(img) {
            // Get natural dimensions of the image
            const naturalWidth = img.naturalWidth;
            const naturalHeight = img.naturalHeight;
            
            // Calculate aspect ratio
            const aspectRatio = naturalWidth / naturalHeight;
            
            // If the image is significantly taller than it is wide (portrait orientation)
            if (aspectRatio < 0.75) {
                // Add a class to the parent container
                img.closest('.media-item').classList.add('tall-image');
            }
        }

        // Function to get all media items for a post
        function getMediaItemsForPost(postId) {
            const postElement = document.querySelector(`.post-card[data-post-id="${postId}"]`);
            if (!postElement) return [];
            
            const hiddenDataElement = postElement.querySelector('.hidden-media-data');
            if (!hiddenDataElement) return [];
            
            try {
                return JSON.parse(hiddenDataElement.getAttribute('data-media-items')) || [];
            } catch (e) {
                console.error('Error parsing media items:', e);
                return [];
            }
        }

        // Function to open lightbox with navigation
        function openLightbox(filename, type, index, postId) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxVideo = document.getElementById('lightbox-video');

            // Update global tracking variables
            currentPostId = postId;
            currentMediaIndex = index;
            currentMediaItems = getMediaItemsForPost(postId);
            
            // If we couldn't get media items from data attribute, create a single item array
            if (currentMediaItems.length === 0) {
                currentMediaItems = [{
                    filename: filename,
                    media_type: type
                }];
            }
            
            // Show/hide navigation arrows based on number of media items
            if (currentMediaItems.length <= 1) {
                lightbox.classList.add('single-media');
            } else {
                lightbox.classList.remove('single-media');
            }

            // Display the current media item
            displayCurrentMedia();
            
            // Show the lightbox
            lightbox.style.display = 'block';
            
            // Prevent scrolling when lightbox is open
            document.body.style.overflow = 'hidden';
        }

        // Function to display the current media item in the lightbox
        function displayCurrentMedia() {
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxVideo = document.getElementById('lightbox-video');
            
            // Hide both media elements initially
            lightboxImage.style.display = 'none';
            lightboxVideo.style.display = 'none';
            
            // Get the current media item
            const currentItem = currentMediaItems[currentMediaIndex];
            if (!currentItem) return;
            
            const filename = currentItem.filename;
            const type = currentItem.media_type;
            
            if (type === 'image') {
                // Check if the path includes the CommunityHub folder
                if (filename.includes('/CommunityHub/')) {
                    lightboxImage.src = filename;
                } else {
                    lightboxImage.src = './uploads/' + filename;
                }
                lightboxImage.style.display = 'block';
            } else if (type === 'video') {
                lightboxVideo.src = './uploads/' + filename;
                lightboxVideo.style.display = 'block';
                // Auto-play videos in lightbox
                lightboxVideo.play();
            }
        }

        // Function to navigate to previous or next media item
        function navigateLightbox(direction) {
            // Pause any playing video
            const lightboxVideo = document.getElementById('lightbox-video');
            lightboxVideo.pause();
            
            if (direction === 'prev') {
                currentMediaIndex = (currentMediaIndex - 1 + currentMediaItems.length) % currentMediaItems.length;
            } else {
                currentMediaIndex = (currentMediaIndex + 1) % currentMediaItems.length;
            }
            
            displayCurrentMedia();
        }

        // Function to close lightbox
        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            const lightboxVideo = document.getElementById('lightbox-video');

            lightbox.style.display = 'none';

            // Pause video when closing lightbox
            lightboxVideo.pause();

            // Re-enable scrolling
            document.body.style.overflow = 'auto';
            
            // Reset tracking variables
            currentPostId = null;
            currentMediaIndex = 0;
            currentMediaItems = [];
        }

        // Close lightbox when clicking outside the media
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('lightbox').style.display === 'block') {
                closeLightbox();
            }
            
            // Navigate with arrow keys
            if (document.getElementById('lightbox').style.display === 'block') {
                if (e.key === 'ArrowLeft') {
                    navigateLightbox('prev');
                } else if (e.key === 'ArrowRight') {
                    navigateLightbox('next');
                }
            }
        });

        // Reset file input when form is submitted
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const fileInput = document.getElementById('fileInput');

            if (form) {
                form.addEventListener('submit', function() {
                    // We'll let the server handle the upload, but we'll clear the preview
                    setTimeout(function() {
                        document.getElementById('previewContainer').innerHTML = '';
                    }, 100);
                });
            }

            // Initialize videos in single image containers
            document.querySelectorAll('.single-image-container video').forEach(video => {
                // Set attributes for better user experience
                video.setAttribute('controls', '');
                video.setAttribute('preload', 'metadata');
                
                // Get natural dimensions and set appropriate size
                video.addEventListener('loadedmetadata', function() {
                    const aspectRatio = this.videoWidth / this.videoHeight;
                    if (aspectRatio > 1) {
                        // Landscape video
                        this.style.width = '100%';
                        this.style.height = 'auto';
                    } else {
                        // Portrait video
                        this.style.width = 'auto';
                        this.style.height = '400px';
                    }
                });
            });
            
            // Process any images that might already be loaded
            document.querySelectorAll('.media-grid img').forEach(img => {
                if (img.complete) {
                    detectTallImage(img);
                }
            });
        });
    </script>
</body>

</html>