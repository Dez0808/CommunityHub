<?php
session_start();


// Check if user is admin
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

error_reporting(E_ALL); // Temporarily enable all errors for debugging
ini_set('display_errors', 1);
$msg = "";

include "Demo_DBConnection.php";

// File to store slide data
$slides_file = __DIR__ . '/Logged/slides_data.json';

// Default slides
$default_slides = [
  ['id' => 1, 'image_path' => '/CommunityHub/photos/castelo1.jpg', 'caption' => 'Welcome to Lagro High School'],
  ['id' => 2, 'image_path' => '/CommunityHub/photos/2.jpg', 'caption' => 'Excellence in Education'],
  ['id' => 3, 'image_path' => '/CommunityHub/photos/Lagrobg.jpg', 'caption' => 'Building Future Leaders'],
  ['id' => 4, 'image_path' => '/CommunityHub/photos/Lagrobg.jpg', 'caption' => 'Join Our Community']
];

// Create slides file with default data if it doesn't exist
if (!file_exists($slides_file)) {
  file_put_contents($slides_file, json_encode($default_slides));
}

// Get slides from file
$slides = json_decode(file_get_contents($slides_file), true);

// Handle slide operations - only for admins
if ($_SERVER["REQUEST_METHOD"] == "POST" && $isAdmin) {
  if (isset($_POST['action'])) {
    // Add new slide
    if ($_POST['action'] == 'add' && isset($_POST['caption']) && isset($_FILES['slide_image'])) {
      $caption = $_POST['caption'];
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/CommunityHub/photos/";
      $target_file = "/CommunityHub/photos/" . basename($_FILES["slide_image"]["name"]);

      // Get image dimensions and positioning from form
      $img_width = isset($_POST['img_width']) ? intval($_POST['img_width']) : 0;
      $img_height = isset($_POST['img_height']) ? intval($_POST['img_height']) : 0;
      $object_position = isset($_POST['object_position']) ? $_POST['object_position'] : 'center';
      $object_fit = isset($_POST['fit_option']) ? $_POST['fit_option'] : 'cover';

      // Generate a new ID
      $max_id = 0;
      foreach ($slides as $slide) {
        if ($slide['id'] > $max_id) {
          $max_id = $slide['id'];
        }
      }
      $new_id = $max_id + 1;

      // Upload file
      if (move_uploaded_file($_FILES["slide_image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
        // Add to slides array with dimensions and positioning
        $slides[] = [
          'id' => $new_id,
          'image_path' => $target_file,
          'caption' => $caption,
          'width' => $img_width,
          'height' => $img_height,
          'object_position' => $object_position,
          'object_fit' => $object_fit
        ];

        // Save to file
        file_put_contents($slides_file, json_encode($slides));

        $msg = "New slide added successfully!";
        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
      } else {
        $msg = "Error uploading file.";
      }
    }

    // Delete slide
    if ($_POST['action'] == 'delete' && isset($_POST['slide_id'])) {
      $slide_id = $_POST['slide_id'];

      // Remove from slides array
      foreach ($slides as $key => $slide) {
        if ($slide['id'] == $slide_id) {
          unset($slides[$key]);
          break;
        }
      }

      // Reindex array
      $slides = array_values($slides);

      // Save to file
      file_put_contents($slides_file, json_encode($slides));

      $msg = "Slide deleted successfully!";
      // Redirect to refresh the page
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    }
  }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !$isAdmin) {
  // Non-admin tried to modify slides
  $msg = "Access denied. Only administrators can manage slides.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .mySlides {
      display: none;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
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

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

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

    @keyframes shimmer {
      0% {
        background-position: -1000px 0;
      }

      100% {
        background-position: 1000px 0;
      }
    }

    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .animate-fade-in {
      animation: fadeIn 0.8s ease forwards;
      opacity: 0;
    }

    .animate-slide-left {
      animation: slideInLeft 0.8s ease forwards;
      opacity: 0;
    }

    .animate-slide-right {
      animation: slideInRight 0.8s ease forwards;
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

    .content {
      padding: 20px;
      background-image: url(/CommunityHub/photos/lagrobg4.jpg);
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      min-height: 400px;
      position: relative;
    }

    .content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.1);
      z-index: 0;
    }

    /* Slideshow */
    .slideshow-container {
      max-width: 85%;
      position: relative;
      margin: auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      z-index: 1;
      height: 450px;
      /* Fixed height instead of aspect ratio */
    }

    .mySlides {
      display: none;
      position: relative;
      width: 100%;
      height: 100%;
    }

    .fadeimg {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* This ensures the image covers the area without distortion */
      object-position: center;
      /* Center the image */
      transition: transform 5s ease;
    }

    .text {
      color: #fff;
      font-size: 1.2rem;
      font-weight: bold;
      padding: 15px;
      position: absolute;
      bottom: 0;
      width: 100%;
      text-align: center;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    }

    .numbertext {
      color: #fff;
      font-size: 0.9rem;
      padding: 8px 12px;
      position: absolute;
      top: 0;
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 0 0 8px 0;
      z-index: 2;
    }

    .dot-container {
      text-align: center;
      margin-top: 15px;
      position: relative;
      z-index: 2;
    }

    .dot {
      height: 12px;
      width: 12px;
      margin: 0 5px;
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      display: inline-block;
      transition: all 0.3s ease;
      cursor: pointer;
      border: 2px solid rgba(255, 255, 255, 0.8);
    }

    .dot:hover {
      transform: scale(1.2);
    }

    .active {
      background-color: rgb(52, 140, 81);
      transform: scale(1.2);
    }

    .fade {
      animation-name: fade;
      animation-duration: 1s;
    }

    @keyframes fade {
      from {
        opacity: 0.4;
        transform: scale(1.02);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* No Posts Message */
    .no-posts {
      background: #fff;
      padding: 30px;
      text-align: center;
      border-radius: 8px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      color: #65676B;
      font-size: 16px;
      animation: fadeIn 0.5s ease forwards;
    }

    /* Divider */
    .divider {
      border: none;
      height: 20px;
      background-color: rgb(32, 91, 62);
      margin: 0;
      position: relative;
      overflow: hidden;
    }

    .divider::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 200%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      animation: shimmer 2s infinite;
    }

    /* Scroll to top button */
    .scroll-top {
      position: fixed;
      bottom: 20px;
      left: 20px;
      width: 40px;
      height: 40px;
      background-color: rgb(52, 140, 81);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      z-index: 99;
    }

    .scroll-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .scroll-top:hover {
      transform: translateY(-3px);
      background-color: rgb(32, 91, 62);
    }

    /* Slide Controls */
    .slide-controls {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      display: flex;
      gap: 5px;
    }

    .slide-delete-btn {
      background-color: rgba(220, 53, 69, 0.8);
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .slide-delete-btn:hover {
      background-color: rgba(220, 53, 69, 1);
      transform: scale(1.1);
    }

    /* Modal Popup */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .modal {
      background-color: white;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
      position: relative;
      animation: scaleIn 0.3s ease forwards;
    }

    .modal-header {
      background: linear-gradient(135deg, rgb(32, 91, 62), rgb(52, 140, 81));
      color: white;
      padding: 15px 20px;
      border-radius: 12px 12px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h3 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .modal-close {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
      transition: all 0.2s ease;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .modal-close:hover {
      background-color: rgba(255, 255, 255, 0.2);
      transform: rotate(90deg);
    }

    .modal-body {
      padding: 20px;
    }

    .modal-footer {
      padding: 15px 20px;
      border-top: 1px solid #eee;
      display: flex;
      justify-content: flex-end;
    }

    /* Form Styling */
    .slide-editor-form {
      display: grid;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group label {
      font-weight: 600;
      color: #333;
      font-size: 0.95rem;
    }

    .form-group input[type="text"] {
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.2s ease;
    }

    .form-group input[type="text"]:focus {
      border-color: rgb(52, 140, 81);
      box-shadow: 0 0 0 3px rgba(52, 140, 81, 0.2);
      outline: none;
    }

    .form-group input[type="file"] {
      padding: 10px;
      border: 1px dashed #ddd;
      border-radius: 8px;
      background-color: #f9f9f9;
      cursor: pointer;
    }

    .form-group input[type="file"]:hover {
      border-color: rgb(52, 140, 81);
      background-color: rgba(52, 140, 81, 0.05);
    }

    .btn {
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    .btn-primary {
      background-color: rgb(52, 140, 81);
      color: white;
    }

    .btn-primary:hover {
      background-color: rgb(32, 91, 62);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(32, 91, 62, 0.2);
    }

    .btn-secondary {
      background-color: #f1f1f1;
      color: #333;
    }

    .btn-secondary:hover {
      background-color: #e5e5e5;
      transform: translateY(-2px);
    }

    .toggle-editor-btn {
      background-color: rgb(52, 140, 81);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 12px 20px;
      margin: 20px auto;
      display: block;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      z-index: 2;
      position: relative;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .toggle-editor-btn:hover {
      background-color: rgb(32, 91, 62);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .hidden {
      display: none;
    }

    .alert {
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert i {
      font-size: 1.2rem;
    }

    .alert-success {
      background-color: rgba(40, 167, 69, 0.1);
      border: 1px solid rgba(40, 167, 69, 0.2);
      color: rgb(30, 126, 52);
    }

    .alert-danger {
      background-color: rgba(220, 53, 69, 0.1);
      border: 1px solid rgba(220, 53, 69, 0.2);
      color: rgb(200, 35, 51);
    }

    /* File upload preview */
    .file-preview {
      margin-top: 15px;
      display: none;
    }

    .file-preview-container {
      position: relative;
      width: 100%;
      height: 300px;
      /* Fixed height to match slideshow proportions */
      overflow: hidden;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      background-color: #f0f0f0;
      margin-bottom: 15px;
    }

    .file-preview-container::before {
      content: 'Preview';
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 12px;
      z-index: 2;
    }

    .file-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }

    /* Image adjustment controls */
    .image-controls {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-top: 15px;
    }

    .image-control-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .image-control-group label {
      font-size: 0.85rem;
      color: #555;
    }

    .image-control-group select,
    .image-control-group input {
      padding: 8px 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .image-position-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 5px;
      margin-top: 10px;
    }

    .position-btn {
      width: 30px;
      height: 30px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      transition: all 0.2s ease;
    }

    .position-btn:hover {
      background-color: #eee;
    }

    .position-btn.active {
      background-color: rgb(52, 140, 81);
      color: white;
      border-color: rgb(32, 91, 62);
    }

    /* Tabs for image adjustment */
    .tabs {
      display: flex;
      border-bottom: 1px solid #ddd;
      margin-bottom: 15px;
    }

    .tab {
      padding: 10px 15px;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s ease;
    }

    .tab.active {
      border-bottom-color: rgb(52, 140, 81);
      color: rgb(52, 140, 81);
      font-weight: 600;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .modal {
        width: 95%;
      }

      .btn {
        padding: 10px 15px;
      }

      .image-controls {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <?php include "includes/header.php" ?>
  <?php include "includes/loading.php" ?>

  <main>
    <section class="content">
      <!-- Debug information - remove in production -->
      <?php if (!empty($msg)): ?>
        <div style="background: white; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
          <strong>Debug:</strong> <?php echo $msg; ?>
        </div>
      <?php endif; ?>


      <div class="slideshow-container animate-fade-in">
        <?php foreach ($slides as $index => $slide): ?>
          <div class="mySlides fade">
            <div class="numbertext"><?php echo ($index + 1) . ' / ' . count($slides); ?></div>
            <img src="<?php echo $slide['image_path']; ?>" alt="Slide <?php echo $index + 1; ?>" class="fadeimg"
              style="<?php
                      echo isset($slide['object_position']) ? 'object-position: ' . $slide['object_position'] . ';' : '';
                      echo isset($slide['object_fit']) ? 'object-fit: ' . $slide['object_fit'] . ';' : '';
                      ?>">
            <div class="text"><?php echo $slide['caption']; ?></div>

            <?php if ($isAdmin): ?>
              <div class="slide-controls">
                <form method="post" class="delete-slide-form">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="slide_id" value="<?php echo $slide['id']; ?>">
                  <button type="submit" class="slide-delete-btn" onclick="return confirm('Are you sure you want to delete this slide?')">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="dot-container">
        <?php foreach ($slides as $index => $slide): ?>
          <span class="dot" onclick="currentSlide(<?php echo $index + 1; ?>)"></span>
        <?php endforeach; ?>
      </div>

      <?php if ($isAdmin): ?>
        <button class="toggle-editor-btn" onclick="openModal()">
          <i class="fas fa-plus-circle"></i> Add New Slide
        </button>
      <?php endif; ?>
    </section>

    <hr class="divider">

  </main>

  <!-- Modal Popup for Slide Editor -->
  <div class="modal-overlay" id="slideEditorModal">
    <div class="modal">
      <div class="modal-header">
        <h3><i class="fas fa-image"></i> Add New Slide</h3>
        <button class="modal-close" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body">
        <?php if (!empty($msg)): ?>
          <div class="alert <?php echo strpos($msg, 'Error') !== false || strpos($msg, 'denied') !== false ? 'alert-danger' : 'alert-success'; ?>">
            <i class="fas <?php echo strpos($msg, 'Error') !== false || strpos($msg, 'denied') !== false ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
            <?php echo $msg; ?>
          </div>
        <?php endif; ?>

        <?php if ($isAdmin): ?>

          <form method="post" enctype="multipart/form-data" class="slide-editor-form">
            <input type="hidden" name="action" value="add">
            <input type="hidden" id="img_width" name="img_width" value="0">
            <input type="hidden" id="img_height" name="img_height" value="0">
            <input type="hidden" id="object_position" name="object_position" value="center">
            <input type="hidden" id="fit_option" name="fit_option" value="cover">

            <div class="form-group">
              <label for="caption">Slide Caption:</label>
              <input type="text" id="caption" name="caption" placeholder="Enter slide caption" required>
            </div>

            <div class="form-group">
              <label for="slide_image">Slide Image:</label>
              <input type="file" id="slide_image" name="slide_image" accept="image/*" required onchange="previewImage(this)">
            </div>

            <div class="file-preview" id="imagePreview">
              <div class="file-preview-container">
                <img id="preview" src="#" alt="Image Preview">
              </div>

              <div class="image-controls">
                <div class="image-control-group">
                  <label for="fit_option">Image Fit:</label>
                  <select id="fit_option" name="fit_option" onchange="updateImagePreview()">
                    <option value="cover" selected>Cover (fill container)</option>
                    <option value="contain">Contain (show full image)</option>
                  </select>
                </div>

                <div class="image-control-group">
                  <label>Image Position:</label>
                  <div class="image-position-grid">
                    <button type="button" class="position-btn" data-position="top left" onclick="setPosition(this)">↖</button>
                    <button type="button" class="position-btn" data-position="top" onclick="setPosition(this)">↑</button>
                    <button type="button" class="position-btn" data-position="top right" onclick="setPosition(this)">↗</button>
                    <button type="button" class="position-btn" data-position="left" onclick="setPosition(this)">←</button>
                    <button type="button" class="position-btn active" data-position="center" onclick="setPosition(this)">•</button>
                    <button type="button" class="position-btn" data-position="right" onclick="setPosition(this)">→</button>
                    <button type="button" class="position-btn" data-position="bottom left" onclick="setPosition(this)">↙</button>
                    <button type="button" class="position-btn" data-position="bottom" onclick="setPosition(this)">↓</button>
                    <button type="button" class="position-btn" data-position="bottom right" onclick="setPosition(this)">↘</button>
                  </div>
                </div>
              </div>

              <p class="text-muted" style="text-align: center; font-size: 0.85rem; color: #666; margin-top: 10px;">
                This is how your image will appear in the slideshow
              </p>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Slide
              </button>
            </div>
          </form>
        <?php else: ?>
          <div class="alert alert-danger">
            <i class="fas fa-lock"></i>
            Access denied. Only administrators can manage slides.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php include "Post.php" ?>

  <?php
  include "includes/footer.php"
  ?>

  <div class="scroll-top">
    <i class="fas fa-arrow-up"></i>
  </div>
</body>
<script>
  // Slideshow functionality
  let slideIndex = 0;
  const dots = document.getElementsByClassName("dot");

  // Show dots
  if (dots.length > 0) {
    for (let i = 0; i < dots.length; i++) {
      dots[i].style.display = "inline-block";
    }
  }

  // Start the slideshow
  showSlides();

  function showSlides() {
    let i;
    const slides = document.getElementsByClassName("mySlides");

    // If no slides, don't proceed
    if (slides.length === 0) {
      return;
    }

    // Hide all slides
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }

    // Increment slide index
    slideIndex++;

    // Reset to first slide if at the end
    if (slideIndex > slides.length) {
      slideIndex = 1;
    }

    // Remove active class from all dots
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }

    // Show current slide and activate corresponding dot
    if (slides.length > 0) {
      slides[slideIndex - 1].style.display = "block";
      if (dots.length > 0 && dots[slideIndex - 1]) {
        dots[slideIndex - 1].className += " active";
      }
    }

    // Change slide every 4 seconds
    setTimeout(showSlides, 4000);
  }

  // Manual slide control
  function currentSlide(n) {
    const slides = document.getElementsByClassName("mySlides");

    // If no slides, don't proceed
    if (slides.length === 0) {
      return;
    }

    // Reset slideIndex
    slideIndex = n - 1;

    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }

    // Remove active class from all dots
    for (let i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }

    // Show selected slide and activate corresponding dot
    if (slides[slideIndex]) {
      slides[slideIndex].style.display = "block";
    }
    if (dots[slideIndex]) {
      dots[slideIndex].className += " active";
    }
  }

  // Modal functionality
  function openModal() {
    document.getElementById('slideEditorModal').classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
  }

  // Simplified closeModal function
  function closeModal() {
    document.getElementById('slideEditorModal').classList.remove('active');
    document.body.style.overflow = ''; // Restore scrolling

    // Reset form
    document.querySelector('.slide-editor-form').reset();
    document.getElementById('imagePreview').style.display = 'none';

    // Reset position buttons
    document.querySelectorAll('.position-btn').forEach(btn => {
      btn.classList.remove('active');
    });
    document.querySelector('.position-btn[data-position="center"]').classList.add('active');

    // Reset fit option
    document.getElementById('fit_option').value = 'cover';
    document.querySelector('input[name="fit_option"]').value = 'cover';
  }

  // Image preview functionality
  function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
        previewContainer.style.display = 'block';

        // Get image dimensions when loaded
        const img = new Image();
        img.onload = function() {
          document.getElementById('img_width').value = this.width;
          document.getElementById('img_height').value = this.height;
          updateImagePreview();
        };
        img.src = e.target.result;
      }

      reader.readAsDataURL(input.files[0]);
    } else {
      previewContainer.style.display = 'none';
    }
  }

  // Update image preview based on selected options
  function updateImagePreview() {
    const preview = document.getElementById('preview');
    const fitOption = document.getElementById('fit_option').value;
    const position = document.getElementById('object_position').value;

    preview.style.objectFit = fitOption;
    preview.style.objectPosition = position;

    // Update hidden input for form submission
    document.querySelector('input[name="fit_option"]').value = fitOption;
  }

  // Set image position
  function setPosition(button) {
    // Remove active class from all position buttons
    document.querySelectorAll('.position-btn').forEach(btn => {
      btn.classList.remove('active');
    });

    // Add active class to clicked button
    button.classList.add('active');

    // Update hidden input and preview
    const position = button.getAttribute('data-position');
    document.getElementById('object_position').value = position;
    updateImagePreview();
  }

  // Tab functionality


  // Close modal when clicking outside
  window.addEventListener('click', function(event) {
    const modal = document.getElementById('slideEditorModal');
    if (event.target === modal) {
      closeModal();
    }
  });

  // Escape key to close modal
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeModal();
    }
  });

  // Hide empty elements when DOM is loaded
  document.addEventListener("DOMContentLoaded", function() {
    // Hide empty paragraphs
    const paragraphs = document.querySelectorAll('.textPost');
    paragraphs.forEach(function(paragraph) {
      if (paragraph.innerHTML.trim() === '') {
        paragraph.style.display = 'none';
      }
    });

    // Hide broken images
    const images = document.querySelectorAll('.postImage');
    images.forEach(function(image) {
      image.onerror = function() {
        this.style.display = 'none';
      };

      if (image.complete && (image.naturalWidth === 0 || image.src === '')) {
        image.style.display = 'none';
      }
    });

    // Add animation to posts on scroll
    const posts = document.querySelectorAll('.post-card');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1
    });

    posts.forEach(post => {
      post.style.opacity = '0';
      post.style.transform = 'translateY(20px)';
      post.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      observer.observe(post);
    });

    // Scroll to top button functionality
    const scrollTopBtn = document.querySelector('.scroll-top');

    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        scrollTopBtn.classList.add('visible');
      } else {
        scrollTopBtn.classList.remove('visible');
      }
    });

    scrollTopBtn.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  });
</script>

</html>