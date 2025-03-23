<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="/CommunityHub/photos/Lagro_High_School_logo.png" />
    <title>Student Registration | Lagro High School</title>
    <style>
        :root {
            --primary: #348c51;
            --primary-dark: #123524;
            --secondary: #f8f9fa;
            --accent: #ffc107;
            --text-dark: #212529;
            --text-light: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f0f8ff;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .form-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
            margin-top: 20px;

        }

        .form-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 25px 40px;
            text-align: center;
            position: relative;
        }

        .form-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 28px;
        }

        .form-header p {
            margin-top: 10px;
            opacity: 0.9;
            font-size: 16px;
        }

        .form-body {
            padding: 40px;
        }

        .progress-container {
            margin-bottom: 30px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 30px;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 4px;
            width: 100%;
            background-color: #e9ecef;
            z-index: 0;
        }

        .progress-bar {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 4px;
            background-color: var(--primary);
            z-index: 1;
            transition: width 0.3s ease;
        }

        .step {
            width: 40px;
            height: 40px;
            background-color: white;
            border: 4px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--text-light);
            position: relative;
            z-index: 2;
            transition: var(--transition);
        }

        .step.active {
            border-color: var(--primary);
            color: var(--primary);
        }

        .step.completed {
            border-color: var(--primary);
            background-color: var(--primary);
            color: white;
        }

        .step-label {
            position: absolute;
            top: 45px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-light);
            width: 120px;
            text-align: center;
            left: 50%;
            transform: translateX(-50%);
        }

        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
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

        .form-group {
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .form-col {
            flex: 1;
            padding: 0 15px;
            min-width: 250px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            line-height: 1.5;
            color: var(--text-dark);
            background-color: #fff;
            background-clip: padding-box;
            border: 2px solid #ced4da;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(52, 140, 81, 0.25);
            outline: 0;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 16px;
            color: var(--text-light);
        }

        .input-with-icon {
            padding-left: 45px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 16px;
            color: var(--text-light);
            cursor: pointer;
            z-index: 10;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px 12px;
            padding-right: 45px;
        }

        .radio-group,
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }

        .radio-item,
        .checkbox-item {
            position: relative;
            display: flex;
            align-items: center;
        }

        .radio-input,
        .checkbox-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .radio-label,
        .checkbox-label {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border: 2px solid #ced4da;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: normal;
        }

        .radio-input:checked+.radio-label,
        .checkbox-input:checked+.checkbox-label {
            border-color: var(--primary);
            background-color: rgba(52, 140, 81, 0.1);
        }

        .radio-label::before,
        .checkbox-label::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 2px solid #ced4da;
            border-radius: 50%;
            transition: var(--transition);
        }

        .checkbox-label::before {
            border-radius: 4px;
        }

        .radio-input:checked+.radio-label::before {
            border-color: var(--primary);
            background-color: var(--primary);
            box-shadow: inset 0 0 0 4px white;
        }

        .checkbox-input:checked+.checkbox-label::before {
            border-color: var(--primary);
            background-color: var(--primary);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }

        .form-card {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary);
        }

        .form-card-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 2px solid transparent;
            padding: 12px 30px;
            font-size: 16px;
            line-height: 1.5;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-primary {
            color: #fff;
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-secondary {
            color: var(--text-dark);
            background-color: #e9ecef;
            border-color: #e9ecef;
        }

        .btn-secondary:hover {
            background-color: #dee2e6;
            border-color: #dee2e6;
        }

        .btn-outline {
            background-color: transparent;
            border-color: #ced4da;
            color: var(--text-dark);
        }

        .btn-outline:hover {
            background-color: #f8f9fa;
        }

        .alert {
            padding: 16px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: var(--border-radius);
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .form-text {
            display: block;
            margin-top: 8px;
            font-size: 14px;
            color: var(--text-light);
        }

        .password-strength {
            height: 5px;
            margin-top: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .password-strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }

        .password-strength-text {
            font-size: 14px;
            margin-top: 5px;
            font-weight: 500;
        }

        .weak {
            width: 25%;
            background-color: var(--danger);
        }

        .medium {
            width: 50%;
            background-color: var(--accent);
        }

        .strong {
            width: 75%;
            background-color: #74c69d;
        }

        .very-strong {
            width: 100%;
            background-color: var(--success);
        }

        .error-message {
            color: var(--danger);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .has-error .form-control {
            border-color: var(--danger);
        }

        .has-error .error-message {
            display: block;
        }

        .has-error .form-label {
            color: var(--danger);
        }

        .form-summary {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 30px;
        }

        .summary-item {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
        }

        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            width: 40%;
            font-weight: 500;
            color: var(--text-light);
        }

        .summary-value {
            width: 60%;
            font-weight: 500;
        }

        @media (max-width: 992px) {
            .form-body {
                padding: 30px 20px;
            }

            .form-col {
                flex: 100%;
                margin-bottom: 15px;
            }

            .step-label {
                display: none;
            }

            .form-actions {
                flex-direction: column;
                gap: 15px;
            }

            .form-actions .btn {
                width: 100%;
            }
        }

        /* Custom styles for specific elements */
        #extraForm {
            margin-top: 20px;
        }

        #hs,
        #shs,
        #strand {
            display: none;
        }

        #gradeLevel {
            margin-top: 20px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .loading-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(52, 140, 81, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary);
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
    <?php include "includes/header.php" ?>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="page-container">
        <div class="form-container">
            <div class="form-header">
                <h2>Student Registration</h2>
                <p>Join the Lagro High School community by completing this registration form</p>
            </div>

            <div class="form-body">
                <?php
                if (isset($_POST["submit"])) {
                    $fname = $_POST["fname"];
                    $mname = $_POST["mname"];
                    $lname = $_POST["lname"];
                    $email = $_POST["email"];
                    $age = $_POST["age"];
                    $lrn = $_POST["lrn"];
                    $gender = $_POST["gender"];
                    $dob = date('Y-m-d', strtotime($_POST["birth"]));
                    $contact = $_POST["contact"];
                    $yesnt = $_POST["yesnt"];
                    $password = $_POST["password"];
                    $cpass = $_POST["passconfirm"];
                    $passhash = password_hash($password, PASSWORD_DEFAULT);
                    $for = $_POST["level"];
                    $level = $_POST["gradeLevel"];
                    $strands = isset($_POST["strand"]) ? $_POST["strand"] : "No Strand";

                    $errors = array();

                    if (empty($fname) or empty($lname) or empty($email) or empty($password) or empty($cpass)) {
                        array_push($errors, "All fields are required");
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push($errors, "Email is not valid!");
                    }

                    if (strlen($password) < 8) {
                        array_push($errors, "Password should be at least 8 characters long");
                    }

                    if ($password !== $cpass) {
                        array_push($errors, "Password does not match");
                    }

                    if (empty($gender)) {
                        array_push($errors, "Please select a gender.");
                    }

                    if (empty($yesnt)) {
                        array_push($errors, "Please select if you are enrolled or not.");
                    }

                    if (empty($for)) {
                        array_push($errors, "Select Grade Level.");
                    }

                    require_once "Demo_DBConnection.php";
                    $sql = "SELECT * FROM register WHERE Email = '$email'";
                    $result = mysqli_query($conn, $sql);
                    $rowCount = mysqli_num_rows($result);
                    if ($rowCount > 0) {
                        array_push($errors, "Email already exists!");
                    }

                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo "<div class='alert alert-danger'><i class='fas fa-exclamation-circle'></i> $error</div>";
                        }
                    } else {
                        $sql = "INSERT INTO register (First_Name, Middle_Name, Last_Name, Email, Password, RevealPass, Age, LRN, Gender, DOB, Contact, Enrolled, Enrolling_For, Grade_Level, Strand) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt, "ssssssisssissss", $fname, $mname, $lname, $email, $passhash, $password, $age, $lrn, $gender, $dob, $contact, $yesnt, $for, $level, $strands);
                            mysqli_stmt_execute($stmt);
                            echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> You've successfully registered! You can now <a href='Demo_Login.php'>login</a> to your account.</div>";
                        } else {
                            die("<div class='alert alert-danger'><i class='fas fa-exclamation-triangle'></i> Something went wrong with the database operation.</div>");
                        }
                    }
                }
                ?>

                <div class="progress-container">
                    <div class="progress-steps">
                        <div class="progress-bar" id="progressBar"></div>
                        <div class="step active" id="step1">
                            1
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step" id="step2">
                            2
                            <div class="step-label">Account Details</div>
                        </div>
                        <div class="step" id="step3">
                            3
                            <div class="step-label">Academic Info</div>
                        </div>
                        <div class="step" id="step4">
                            4
                            <div class="step-label">Review & Submit</div>
                        </div>
                    </div>
                </div>

                <form action="Demo_Registration.php" method="post" id="registrationForm">
                    <input type="hidden" name="gender" id="genderHidden">
                    <input type="hidden" name="yesnt" id="yesntHidden">
                    <input type="hidden" name="level" id="levelHidden">
                    <input type="hidden" name="" id="hiddenStrand" value="No Strand">

                    <!-- Step 1: Personal Information -->
                    <div class="form-section active" id="section1">
                        <div class="form-card">
                            <div class="form-card-title">Personal Information</div>
                            <p>Please provide your personal details as they appear on official documents.</p>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="fname">First Name</label>
                                    <div class="input-group">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" name="fname" id="fname" placeholder="Enter your first name" class="form-control input-with-icon">
                                    </div>
                                    <div class="error-message" id="fnameError">Please enter your first name</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="mname">Middle Name <small>(optional)</small></label>
                                    <input type="text" name="mname" id="mname" placeholder="Enter your middle name" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="lname">Last Name</label>
                                    <div class="input-group">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" name="lname" id="lname" placeholder="Enter your last name" class="form-control input-with-icon">
                                    </div>
                                    <div class="error-message" id="lnameError">Please enter your last name</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="age">Age</label>
                                    <div class="input-group">
                                        <i class="fas fa-calendar-alt input-icon"></i>
                                        <input type="number" name="age" id="age" placeholder="Enter your age" class="form-control input-with-icon">
                                    </div>
                                    <div class="error-message" id="ageError">Please enter a valid age</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="birth">Date of Birth</label>
                                    <div class="input-group">
                                        <i class="fas fa-birthday-cake input-icon"></i>
                                        <input type="date" name="birth" id="birth" class="form-control input-with-icon">
                                    </div>
                                    <div class="error-message" id="birthError">Please select your date of birth</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" name="gender" id="male" value="Male" class="radio-input">
                                            <label for="male" class="radio-label">Male</label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" name="gender" id="female" value="Female" class="radio-input">
                                            <label for="female" class="radio-label">Female</label>
                                        </div>
                                    </div>
                                    <div class="error-message" id="genderError">Please select your gender</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="contact">Contact Number</label>
                                    <div class="input-group">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="text" name="contact" id="contact" placeholder="e.g. 09123456789" class="form-control input-with-icon">
                                    </div>
                                    <div class="error-message" id="contactError">Please enter a valid contact number</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='Demo_Login.php'">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </button>
                            <button type="button" class="btn btn-primary" id="nextToStep2">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Account Details -->
                    <div class="form-section" id="section2">
                        <div class="form-card">
                            <div class="form-card-title">Account Details</div>
                            <p>Create your account credentials. Make sure to use a strong password.</p>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Address</label>
                                    <div class="input-group">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" name="email" id="email" placeholder="Enter your email address" class="form-control input-with-icon">
                                    </div>
                                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="input-group">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" name="password" id="password" placeholder="Create a password (min. 8 characters)" class="form-control input-with-icon">
                                        <span class="password-toggle" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="password-strength">
                                        <div class="password-strength-meter" id="passwordStrengthMeter"></div>
                                    </div>
                                    <div class="password-strength-text" id="passwordStrengthText"></div>
                                    <div class="error-message" id="passwordError">Password must be at least 8 characters long</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="passconfirm">Confirm Password</label>
                                    <div class="input-group">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" name="passconfirm" id="passconfirm" placeholder="Re-enter your password" class="form-control input-with-icon">
                                        <span class="password-toggle" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="error-message" id="passconfirmError">Passwords do not match</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-outline" id="backToStep1">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="nextToStep3">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Academic Information -->
                    <div class="form-section" id="section3">
                        <div class="form-card">
                            <div class="form-card-title">Academic Information</div>
                            <p>Provide details about your academic status and enrollment preferences.</p>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="lrn">Learner's Reference Number (LRN)</label>
                                    <div class="input-group">
                                        <i class="fas fa-id-card input-icon"></i>
                                        <input type="text" name="lrn" id="lrn" placeholder="Enter your 12-digit LRN" class="form-control input-with-icon">
                                    </div>
                                    <div class="form-text">Your 12-digit unique identifier issued by DepEd</div>
                                    <div class="error-message" id="lrnError">Please enter a valid 12-digit LRN</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Enrollment Status</label>
                                    <div class="radio-group">
                                        <div class="radio-item">
                                            <input type="radio" name="yesnt" id="yes" value="Enrolled" class="radio-input">
                                            <label for="yes" class="radio-label">
                                                <i class="fas fa-user-check"></i> Already Enrolled
                                            </label>
                                        </div>
                                        <div class="radio-item">
                                            <input type="radio" name="yesnt" id="no" value="Enrolling" class="radio-input">
                                            <label for="no" class="radio-label">
                                                <i class="fas fa-user-plus"></i> New Enrollee
                                            </label>
                                        </div>
                                    </div>
                                    <div class="error-message" id="yesntError">Please select your enrollment status</div>
                                </div>
                            </div>
                        </div>

                        <div id="extraForm">
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label class="form-label" id="enrolling">Enrolling For</label>
                                        <label class="form-label" id="enrolled" style="display: none;">Enrolled For</label>
                                        <div class="radio-group">
                                            <div class="radio-item">
                                                <input type="radio" name="level" id="highschool" value="Highschool" class="radio-input">
                                                <label for="highschool" class="radio-label">
                                                    <i class="fas fa-school"></i> Junior High School
                                                </label>
                                            </div>
                                            <div class="radio-item">
                                                <input type="radio" name="level" id="senior" value="Senior Highschool" class="radio-input">
                                                <label for="senior" class="radio-label">
                                                    <i class="fas fa-graduation-cap"></i> Senior High School
                                                </label>
                                            </div>
                                        </div>
                                        <div class="error-message" id="levelError">Please select your education level</div>
                                    </div>
                                </div>
                            </div>

                            <div id="gradeLevel">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label class="form-label" for="hs">Grade Level</label>
                                            <select name="gradeLevel" id="hs" class="form-control">
                                                <option value="">Select grade level</option>
                                                <option value="Grade 7">Grade 7</option>
                                                <option value="Grade 8">Grade 8</option>
                                                <option value="Grade 9">Grade 9</option>
                                                <option value="Grade 10">Grade 10</option>
                                            </select>
                                            <div class="error-message" id="hsError">Please select your grade level</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label class="form-label" for="shs">Grade Level</label>
                                            <select name="gradeLevel" id="shs" class="form-control">
                                                <option value="">Select grade level</option>
                                                <option value="Grade 11">Grade 11</option>
                                                <option value="Grade 12">Grade 12</option>
                                            </select>
                                            <div class="error-message" id="shsError">Please select your grade level</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label class="form-label" for="strand">Strand</label>
                                            <select name="strand" id="strand" class="form-control">
                                                <option value="">Select strand</option>
                                                <option value="STEM">STEM - Science, Technology, Engineering, and Mathematics</option>
                                                <option value="HUMMS">HUMMS - Humanities and Social Sciences</option>
                                                <option value="ABM">ABM - Accountancy, Business, and Management</option>
                                                <option value="Cookery">TVL - Cookery</option>
                                                <option value="ICT Programming">TVL - ICT Programming</option>
                                                <option value="ICT Animation">TVL - ICT Animation</option>
                                                <option value="Caregiving">TVL - Caregiving</option>
                                                <option value="EPAS">TVL - EPAS</option>
                                                <option value="Home Economics">TVL - Home Economics</option>
                                                <option value="Fashion Design">TVL - Fashion Design</option>
                                            </select>
                                            <div class="error-message" id="strandError">Please select your strand</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-outline" id="backToStep2">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="nextToStep4">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Review & Submit -->
                    <div class="form-section" id="section4">
                        <div class="form-card">
                            <div class="form-card-title">Review Your Information</div>
                            <p>Please review all the information you've provided before submitting your registration.</p>
                        </div>

                        <div class="form-summary" id="formSummary">
                            <!-- This will be filled dynamically by JavaScript -->
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="termsCheck" class="checkbox-input">
                                    <label for="termsCheck" class="checkbox-label">
                                        I confirm that all the information provided is accurate and complete
                                    </label>
                                </div>
                            </div>
                            <div class="error-message" id="termsError">You must confirm that your information is accurate</div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-outline" id="backToStep3">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="submit" class="btn btn-primary" name="submit" id="submitBtn">
                                <i class="fas fa-check-circle"></i> Complete Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "index.php" ?>
    <?php include "includes/footer.php" ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const form = document.getElementById('registrationForm');
            const sections = document.querySelectorAll('.form-section');
            const progressBar = document.getElementById('progressBar');
            const steps = document.querySelectorAll('.step');
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Navigation buttons
            const nextToStep2 = document.getElementById('nextToStep2');
            const nextToStep3 = document.getElementById('nextToStep3');
            const nextToStep4 = document.getElementById('nextToStep4');
            const backToStep1 = document.getElementById('backToStep1');
            const backToStep2 = document.getElementById('backToStep2');
            const backToStep3 = document.getElementById('backToStep3');
            const submitBtn = document.getElementById('submitBtn');

            // Current step tracker
            let currentStep = 1;

            // Debug function to check if elements exist
            function debugElement(element, name) {
                if (!element) {
                    console.error(`Element ${name} not found!`);
                    return false;
                }
                return true;
            }

            // Check if all required elements exist
            if (!debugElement(form, 'registrationForm') ||
                !debugElement(progressBar, 'progressBar') ||
                !debugElement(nextToStep2, 'nextToStep2') ||
                !debugElement(nextToStep3, 'nextToStep3') ||
                !debugElement(nextToStep4, 'nextToStep4') ||
                !debugElement(backToStep1, 'backToStep1') ||
                !debugElement(backToStep2, 'backToStep2') ||
                !debugElement(backToStep3, 'backToStep3') ||
                !debugElement(submitBtn, 'submitBtn')) {
                console.error('Some required elements are missing!');
            }

            // Update progress bar
            function updateProgressBar() {
                const progressPercentage = ((currentStep - 1) / (steps.length - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;
            }

            // Show specific step
            function showStep(stepNumber) {
                console.log(`Showing step ${stepNumber}`);
                sections.forEach((section, index) => {
                    section.classList.remove('active');
                    if (index === stepNumber - 1) {
                        section.classList.add('active');
                    }
                });

                steps.forEach((step, index) => {
                    step.classList.remove('active', 'completed');
                    if (index + 1 === stepNumber) {
                        step.classList.add('active');
                    } else if (index + 1 < stepNumber) {
                        step.classList.add('completed');
                    }
                });

                currentStep = stepNumber;
                updateProgressBar();
            }

            // Validate form fields
            function validateFields(fieldsToValidate) {
                let isValid = true;
                console.log(`Validating fields: ${fieldsToValidate.join(', ')}`);

                fieldsToValidate.forEach(field => {
                    const input = document.getElementById(field);

                    if (!input) {
                        console.error(`Field ${field} not found!`);
                        return;
                    }

                    const errorElement = document.getElementById(`${field}Error`);
                    if (!errorElement) {
                        console.error(`Error element for ${field} not found!`);
                        return;
                    }

                    const formGroup = input.closest('.form-group');
                    if (!formGroup) {
                        console.error(`Form group for ${field} not found!`);
                        return;
                    }

                    // Skip validation for middle name as it's optional
                    if (field === 'mname') return;

                    // Special validation for radio buttons
                    if (field === 'gender' || field === 'yesnt' || field === 'level') {
                        const radioButtons = document.querySelectorAll(`input[name="${field}"]`);
                        let radioSelected = false;

                        radioButtons.forEach(radio => {
                            if (radio.checked) {
                                radioSelected = true;
                            }
                        });

                        if (!radioSelected) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // Special validation for select elements
                    if (field === 'hs' || field === 'shs' || field === 'strand') {
                        // Only validate if the element is visible
                        if (input.style.display === 'block') {
                            if (!input.value) {
                                isValid = false;
                                formGroup.classList.add('has-error');
                                errorElement.style.display = 'block';
                            } else {
                                formGroup.classList.remove('has-error');
                                errorElement.style.display = 'none';
                            }
                        }
                        return;
                    }

                    // Special validation for terms checkbox
                    if (field === 'termsCheck') {
                        if (!input.checked) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // Email validation
                    if (field === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!input.value || !emailRegex.test(input.value)) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // Password validation
                    if (field === 'password') {
                        if (!input.value || input.value.length < 8) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // Password confirmation validation
                    if (field === 'passconfirm') {
                        const password = document.getElementById('password').value;
                        if (!input.value || input.value !== password) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // LRN validation
                    if (field === 'lrn') {
                        // Make LRN validation less strict for testing
                        if (!input.value) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // Contact number validation
                    if (field === 'contact') {
                        // Make contact validation less strict for testing
                        if (!input.value) {
                            isValid = false;
                            formGroup.classList.add('has-error');
                            errorElement.style.display = 'block';
                        } else {
                            formGroup.classList.remove('has-error');
                            errorElement.style.display = 'none';
                        }
                        return;
                    }

                    // General validation for other fields
                    if (!input.value) {
                        isValid = false;
                        formGroup.classList.add('has-error');
                        errorElement.style.display = 'block';
                    } else {
                        formGroup.classList.remove('has-error');
                        errorElement.style.display = 'none';
                    }
                });

                console.log(`Validation result: ${isValid ? 'Valid' : 'Invalid'}`);
                return isValid;
            }

            // Password strength meter
            const passwordInput = document.getElementById('password');
            const passwordStrengthMeter = document.getElementById('passwordStrengthMeter');
            const passwordStrengthText = document.getElementById('passwordStrengthText');

            if (passwordInput && passwordStrengthMeter && passwordStrengthText) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let strengthClass = '';
                    let strengthText = '';

                    if (password.length >= 8) strength += 1;
                    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
                    if (password.match(/\d/)) strength += 1;
                    if (password.match(/[^a-zA-Z\d]/)) strength += 1;

                    switch (strength) {
                        case 0:
                            strengthClass = '';
                            strengthText = '';
                            break;
                        case 1:
                            strengthClass = 'weak';
                            strengthText = 'Weak';
                            break;
                        case 2:
                            strengthClass = 'medium';
                            strengthText = 'Medium';
                            break;
                        case 3:
                            strengthClass = 'strong';
                            strengthText = 'Strong';
                            break;
                        case 4:
                            strengthClass = 'very-strong';
                            strengthText = 'Very Strong';
                            break;
                    }

                    passwordStrengthMeter.className = 'password-strength-meter ' + strengthClass;
                    passwordStrengthText.textContent = strengthText;
                    passwordStrengthText.className = 'password-strength-text ' + strengthClass;
                });
            }

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('passconfirm');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            // Function to show/hide elements based on the selected option
            function toggleDisplay(elementsToShow, elementsToHide) {
                elementsToShow.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.style.display = "block";
                    } else {
                        console.error(`Element ${id} not found!`);
                    }
                });
                elementsToHide.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.style.display = "none";
                    } else {
                        console.error(`Element ${id} not found!`);
                    }
                });
            }

            // Function to set names for elements
            function setNames(nameMappings) {
                for (const [id, name] of Object.entries(nameMappings)) {
                    const element = document.getElementById(id);
                    if (element) {
                        element.name = name;
                    } else {
                        console.error(`Element ${id} not found!`);
                    }
                }
            }

            // Function to update hidden fields
            function updateHiddenField(fieldId, value) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.value = value;
                } else {
                    console.error(`Hidden field ${fieldId} not found!`);
                }
            }

            // Event listener for the "senior" radio button
            const seniorRadio = document.getElementById("senior");
            if (seniorRadio) {
                seniorRadio.addEventListener("click", function() {
                    toggleDisplay(["shs", "gradeLevel", "strand"], ["hs"]);
                    setNames({
                        "hs": "gradeLevel1",
                        "shs": "gradeLevel",
                        "strand": "strand",
                        "hiddenStrand": ""
                    });
                    const hsElement = document.getElementById("hs");
                    if (hsElement) hsElement.value = "";
                    updateHiddenField("levelHidden", "Senior Highschool");
                });
            }

            // Event listener for the "highschool" radio button
            const highschoolRadio = document.getElementById("highschool");
            if (highschoolRadio) {
                highschoolRadio.addEventListener("click", function() {
                    toggleDisplay(["hs", "gradeLevel"], ["shs", "strand"]);
                    setNames({
                        "hs": "gradeLevel",
                        "shs": "gradeLevel1",
                        "strand": "noStrand",
                        "hiddenStrand": "strand"
                    });
                    const shsElement = document.getElementById("shs");
                    const strandElement = document.getElementById("strand");
                    if (shsElement) shsElement.value = "";
                    if (strandElement) strandElement.value = "";
                    updateHiddenField("levelHidden", "Highschool");
                });
            }

            // Event listener for the "no" radio button
            const noRadio = document.getElementById("no");
            if (noRadio) {
                noRadio.addEventListener("click", function() {
                    toggleDisplay(["extraForm", "enrolling"], ["shs", "strand", "hs", "enrolled"]);
                    if (highschoolRadio) highschoolRadio.checked = false;
                    if (seniorRadio) seniorRadio.checked = false;
                    const strandElement = document.getElementById("strand");
                    if (strandElement) strandElement.value = "";
                    updateHiddenField("yesntHidden", "Enrolling");
                });
            }

            // Event listener for the "yes" radio button
            const yesRadio = document.getElementById("yes");
            if (yesRadio) {
                yesRadio.addEventListener("click", function() {
                    toggleDisplay(["extraForm", "enrolled"], ["shs", "strand", "hs", "enrolling"]);
                    if (highschoolRadio) highschoolRadio.checked = false;
                    if (seniorRadio) seniorRadio.checked = false;
                    const strandElement = document.getElementById("strand");
                    if (strandElement) strandElement.value = "";
                    updateHiddenField("yesntHidden", "Enrolled");
                });
            }

            // Event listeners for gender selection
            const maleRadio = document.getElementById("male");
            if (maleRadio) {
                maleRadio.addEventListener("click", function() {
                    updateHiddenField("genderHidden", "Male");
                });
            }

            const femaleRadio = document.getElementById("female");
            if (femaleRadio) {
                femaleRadio.addEventListener("click", function() {
                    updateHiddenField("genderHidden", "Female");
                });
            }

            // Generate summary for review step
            function generateSummary() {
                const formSummary = document.getElementById('formSummary');
                if (!formSummary) {
                    console.error('Form summary element not found!');
                    return;
                }

                formSummary.innerHTML = '';

                // Personal Information
                const personalInfo = document.createElement('div');
                const fname = document.getElementById('fname') ? document.getElementById('fname').value : '';
                const mname = document.getElementById('mname') ? document.getElementById('mname').value : '';
                const lname = document.getElementById('lname') ? document.getElementById('lname').value : '';
                const age = document.getElementById('age') ? document.getElementById('age').value : '';
                const birth = document.getElementById('birth') ? document.getElementById('birth').value : '';
                const contact = document.getElementById('contact') ? document.getElementById('contact').value : '';
                const gender = document.querySelector('input[name="gender"]:checked') ? document.querySelector('input[name="gender"]:checked').value : '';

                personalInfo.innerHTML = `
                <h4 class="mb-3">Personal Information</h4>
                <div class="summary-item">
                    <div class="summary-label">Full Name:</div>
                    <div class="summary-value">${fname} ${mname} ${lname}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Age:</div>
                    <div class="summary-value">${age}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Date of Birth:</div>
                    <div class="summary-value">${birth}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Gender:</div>
                    <div class="summary-value">${gender}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Contact Number:</div>
                    <div class="summary-value">${contact}</div>
                </div>
            `;
                formSummary.appendChild(personalInfo);

                // Account Information
                const accountInfo = document.createElement('div');
                const email = document.getElementById('email') ? document.getElementById('email').value : '';

                accountInfo.innerHTML = `
                <h4 class="mb-3 mt-4">Account Information</h4>
                <div class="summary-item">
                    <div class="summary-label">Email Address:</div>
                    <div class="summary-value">${email}</div>
                </div>
            `;
                formSummary.appendChild(accountInfo);

                // Academic Information
                const academicInfo = document.createElement('div');
                const lrn = document.getElementById('lrn') ? document.getElementById('lrn').value : '';
                const yesnt = document.querySelector('input[name="yesnt"]:checked') ? document.querySelector('input[name="yesnt"]:checked').value : '';

                academicInfo.innerHTML = `
                <h4 class="mb-3 mt-4">Academic Information</h4>
                <div class="summary-item">
                    <div class="summary-label">LRN:</div>
                    <div class="summary-value">${lrn}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Enrollment Status:</div>
                    <div class="summary-value">${yesnt}</div>
                </div>
            `;

                // Add education level if selected
                const levelChecked = document.querySelector('input[name="level"]:checked');
                if (levelChecked) {
                    academicInfo.innerHTML += `
                    <div class="summary-item">
                        <div class="summary-label">Education Level:</div>
                        <div class="summary-value">${levelChecked.value}</div>
                    </div>
                `;

                    // Add grade level and strand if applicable
                    if (document.getElementById('highschool') && document.getElementById('highschool').checked &&
                        document.getElementById('hs') && document.getElementById('hs').value) {
                        academicInfo.innerHTML += `
                        <div class="summary-item">
                            <div class="summary-label">Grade Level:</div>
                            <div class="summary-value">${document.getElementById('hs').value}</div>
                        </div>
                    `;
                    } else if (document.getElementById('senior') && document.getElementById('senior').checked) {
                        if (document.getElementById('shs') && document.getElementById('shs').value) {
                            academicInfo.innerHTML += `
                            <div class="summary-item">
                                <div class="summary-label">Grade Level:</div>
                                <div class="summary-value">${document.getElementById('shs').value}</div>
                            </div>
                        `;
                        }

                        if (document.getElementById('strand') && document.getElementById('strand').value) {
                            academicInfo.innerHTML += `
                            <div class="summary-item">
                                <div class="summary-label">Strand:</div>
                                <div class="summary-value">${document.getElementById('strand').value}</div>
                            </div>
                        `;
                        }
                    }
                }

                formSummary.appendChild(academicInfo);
            }

            // Navigation event listeners
            if (nextToStep2) {
                nextToStep2.addEventListener('click', function(e) {
                    console.log('Next to Step 2 button clicked');
                    // For testing, make validation less strict
                    if (validateFields(['fname', 'lname'])) {
                        showStep(2);
                        window.scrollTo(0, 0);
                    }
                });
            }

            if (nextToStep3) {
                nextToStep3.addEventListener('click', function(e) {
                    console.log('Next to Step 3 button clicked');
                    // For testing, make validation less strict
                    if (validateFields(['email'])) {
                        showStep(3);
                        window.scrollTo(0, 0);
                    }
                });
            }

            if (nextToStep4) {
                nextToStep4.addEventListener('click', function(e) {
                    console.log('Next to Step 4 button clicked');
                    // For testing, make validation less strict
                    generateSummary();
                    showStep(4);
                    window.scrollTo(0, 0);
                });
            }

            if (backToStep1) {
                backToStep1.addEventListener('click', function(e) {
                    console.log('Back to Step 1 button clicked');
                    showStep(1);
                    window.scrollTo(0, 0);
                });
            }

            if (backToStep2) {
                backToStep2.addEventListener('click', function(e) {
                    console.log('Back to Step 2 button clicked');
                    showStep(2);
                    window.scrollTo(0, 0);
                });
            }

            if (backToStep3) {
                backToStep3.addEventListener('click', function(e) {
                    console.log('Back to Step 3 button clicked');
                    showStep(3);
                    window.scrollTo(0, 0);
                });
            }

            // Form submission
            if (form) {
                form.addEventListener('submit', function(event) {
                    if (!validateFields(['termsCheck'])) {
                        event.preventDefault();
                        return;
                    }

                    // Show loading overlay
                    if (loadingOverlay) {
                        loadingOverlay.classList.add('show');
                    }
                });
            }

            // Initialize the form
            updateProgressBar();
            console.log('Form initialization complete');
        });
    </script>
</body>

</html>