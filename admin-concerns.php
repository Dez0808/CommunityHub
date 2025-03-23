<?php
session_start();
include 'Demo_DBConnection.php'; // Using your database connection

// Check if user is logged in as admin
// You'll need to modify this based on your authentication system
$is_admin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $is_admin = true;
} else {
    // Redirect non-admin users
    header("Location: Demo_Index.php");
    exit;
}

// Update concern status if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $concern_id = $_POST['concern_id'];
    $new_status = $_POST['new_status'];
    $admin_notes = htmlspecialchars($_POST['admin_notes']);

    $stmt = $conn->prepare("UPDATE concerns SET status = ?, admin_notes = ?, last_updated = NOW() WHERE id = ?");
    $stmt->bind_param("ssi", $new_status, $admin_notes, $concern_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Concern status updated successfully.";
    } else {
        $_SESSION['error_message'] = "Error updating concern status: " . $stmt->error;
    }

    $stmt->close();

    // Redirect to refresh the page
    header("Location: admin-concerns.php");
    exit;
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Check if the concerns table exists
$check_table = "SHOW TABLES LIKE 'concerns'";
$table_exists = mysqli_query($conn, $check_table);

// Build the query with filters
$query = "SELECT * FROM concerns WHERE 1=1";

if (!empty($status_filter)) {
    $query .= " AND status = '" . $conn->real_escape_string($status_filter) . "'";
}

if (!empty($category_filter)) {
    $query .= " AND category = '" . $conn->real_escape_string($category_filter) . "'";
}

if (!empty($search)) {
    $query .= " AND (name LIKE '%" . $conn->real_escape_string($search) . "%' OR 
                     email LIKE '%" . $conn->real_escape_string($search) . "%' OR 
                     subject LIKE '%" . $conn->real_escape_string($search) . "%' OR 
                     message LIKE '%" . $conn->real_escape_string($search) . "%')";
}

$query .= " ORDER BY date_submitted DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <title>Admin - Manage Concerns</title>
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
            --red: #e74c3c;
            --orange: #f39c12;
            --green: #2ecc71;
            --blue: #3498db;
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

        /* Main Content */
        .admin-main {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        /* Alert Messages */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            border-left: 4px solid var(--green);
            color: var(--green);
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid var(--red);
            color: var(--red);
        }

        /* Filter Section */
        .filter-section {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            margin-bottom: 30px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .filter-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .filter-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .filter-btn {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            height: 42px; /* Fixed height for alignment */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-btn:hover {
            background-color: var(--primary-dark);
        }

        .filter-btn.reset {
            background-color: var(--text-light);
        }

        .filter-btn.reset:hover {
            background-color: var(--text-color);
        }

        /* Concerns Table */
        .concerns-table {
            width: 100%;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--primary-dark);
            color: white;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            font-weight: 600;
        }

        tbody tr:hover {
            background-color: var(--gray-light);
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-open {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--blue);
        }

        .status-in-progress {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--orange);
        }

        .status-resolved {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--green);
        }

        .status-closed {
            background-color: rgba(149, 165, 166, 0.1);
            color: var(--text-light);
        }

        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border: none;
            cursor: pointer;
            transition: var(--transition);
            margin-right: 5px;
        }

        .action-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .action-btn.view {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--blue);
        }

        .action-btn.view:hover {
            background-color: var(--blue);
            color: white;
        }

        /* Modal Styling */
        .modal-overlay {
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

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: var(--white);
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 700px;
            padding: 30px;
            position: relative;
            transform: scale(0.8);
            transition: all 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--primary-color);
        }

        .modal-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        .concern-details {
            margin-bottom: 20px; /* Reduced gap */
        }

        .concern-detail {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--text-color);
            background-color: var(--gray-light);
            padding: 10px;
            border-radius: 8px;
        }

        .concern-message {
            background-color: var(--gray-light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px; /* Reduced gap */
            white-space: pre-line;
            word-wrap: break-word;
        }

        /* Form in Modal */
        .modal-form-group {
            margin-bottom: 20px;
        }

        .modal-form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .modal-form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }

        .modal-form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        textarea.modal-form-control {
            min-height: 120px;
            resize: vertical;
        }

        .modal-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .modal-btn:hover {
            background-color: var(--primary-dark);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--white);
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }

        .page-link:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .page-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .filter-form {
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }
            
            .filter-buttons {
                width: 100%;
                margin-left: 0;
            }

            .filter-btn {
                flex: 1;
            }

            th,
            td {
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 2rem;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }

        .no-concerns {
            text-align: center;
            padding: 50px 20px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }

        .no-concerns i {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 20px;
        }

        .no-concerns h3 {
            font-size: 1.5rem;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .no-concerns p {
            color: var(--text-light);
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <h1 class="page-title">Manage Student Concerns</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="filter-section">
            <form class="filter-form" method="GET" action="admin-concerns.php">
                <div class="filter-group">
                    <label for="status" class="filter-label">Status</label>
                    <select id="status" name="status" class="filter-control">
                        <option value="">All Statuses</option>
                        <option value="Open" <?php echo $status_filter == 'Open' ? 'selected' : ''; ?>>Open</option>
                        <option value="In Progress" <?php echo $status_filter == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Resolved" <?php echo $status_filter == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="Closed" <?php echo $status_filter == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="category" class="filter-label">Category</label>
                    <select id="category" name="category" class="filter-control">
                        <option value="">All Categories</option>
                        <option value="Academic" <?php echo $category_filter == 'Academic' ? 'selected' : ''; ?>>Academic</option>
                        <option value="Facilities" <?php echo $category_filter == 'Facilities' ? 'selected' : ''; ?>>Facilities</option>
                        <option value="Staff" <?php echo $category_filter == 'Staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="Student Affairs" <?php echo $category_filter == 'Student Affairs' ? 'selected' : ''; ?>>Student Affairs</option>
                        <option value="Other" <?php echo $category_filter == 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="search" class="filter-label">Search</label>
                    <input type="text" id="search" name="search" class="filter-control" placeholder="Search by name, email, subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="filter-btn">Apply Filters</button>
                    <a href="admin-concerns.php" class="filter-btn reset">Reset</a>
                </div>
            </form>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <!-- Concerns Table -->
            <div class="concerns-table">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Subject/Message</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($row['name']); ?><br>
                                        <small><?php echo htmlspecialchars($row['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td>
                                        <?php if (!empty($row['subject'])): ?>
                                            <strong><?php echo htmlspecialchars($row['subject']); ?></strong><br>
                                        <?php endif; ?>
                                        <small><?php echo substr(htmlspecialchars($row['message']), 0, 50) . (strlen($row['message']) > 50 ? '...' : ''); ?></small>
                                    </td>
                                    <td><?php echo date('M d, Y g:i A', strtotime($row['date_submitted'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="action-btn view" 
                                            data-id="<?php echo $row['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                            data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                            data-subject="<?php echo htmlspecialchars($row['subject']); ?>"
                                            data-message="<?php echo htmlspecialchars($row['message']); ?>"
                                            data-status="<?php echo htmlspecialchars($row['status']); ?>"
                                            data-date="<?php echo date('M d, Y g:i A', strtotime($row['date_submitted'])); ?>"
                                            data-notes="<?php echo htmlspecialchars($row['admin_notes'] ?? ''); ?>"
                                            title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <!-- No concerns found -->
            <div class="no-concerns">
                <i class="fas fa-inbox"></i>
                <h3>No Concerns Found</h3>
                <p>There are no concerns matching your criteria. Check back later or adjust your filters.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- View Concern Modal -->
    <div id="viewConcernModal" class="modal-overlay">
        <div class="modal-content">
            <span id="closeModal" class="modal-close"><i class="fas fa-times"></i></span>

            <h2 class="modal-title">Concern Details</h2>

            <div id="concernDetails" class="concern-details">
                <!-- This will be populated with JavaScript -->
            </div>

            <form id="updateStatusForm" method="POST" action="admin-concerns.php">
                <input type="hidden" id="concern_id" name="concern_id" value="">

                <div class="modal-form-group">
                    <label for="new_status" class="modal-form-label">Update Status</label>
                    <select id="new_status" name="new_status" class="modal-form-control" required>
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label for="admin_notes" class="modal-form-label">Admin Notes</label>
                    <textarea id="admin_notes" name="admin_notes" class="modal-form-control" placeholder="Add notes about this concern..."></textarea>
                </div>

                <div style="text-align: center;">
                    <button type="submit" name="update_status" class="modal-btn">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const viewConcernModal = document.getElementById('viewConcernModal');
            const closeModalBtn = document.getElementById('closeModal');

            closeModalBtn.addEventListener('click', function() {
                viewConcernModal.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close modal when clicking outside
            viewConcernModal.addEventListener('click', function(e) {
                if (e.target === viewConcernModal) {
                    viewConcernModal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // Add event listeners to all view buttons
            document.querySelectorAll('.action-btn.view').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const category = this.getAttribute('data-category');
                    const subject = this.getAttribute('data-subject');
                    const message = this.getAttribute('data-message');
                    const status = this.getAttribute('data-status');
                    const date = this.getAttribute('data-date');
                    const adminNotes = this.getAttribute('data-notes');
                    
                    viewConcern(id, name, email, category, subject, message, status, date, adminNotes);
                });
            });

            // Alert auto-dismiss
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });

        // Function to view concern details
        function viewConcern(id, name, email, category, subject, message, status, date, adminNotes) {
            const modal = document.getElementById('viewConcernModal');
            const concernDetails = document.getElementById('concernDetails');
            const concernIdInput = document.getElementById('concern_id');
            const statusSelect = document.getElementById('new_status');
            const adminNotesTextarea = document.getElementById('admin_notes');

            // Populate the concern details
            concernDetails.innerHTML = `
                <div class="concern-detail">
                    <div class="detail-label">Submitted By</div>
                    <div class="detail-value">${name} (${email})</div>
                </div>
                <div class="concern-detail">
                    <div class="detail-label">Category</div>
                    <div class="detail-value">${category}</div>
                </div>
                <div class="concern-detail">
                    <div class="detail-label">Subject</div>
                    <div class="detail-value">${subject || 'N/A'}</div>
                </div>
                <div class="concern-detail">
                    <div class="detail-label">Date Submitted</div>
                    <div class="detail-value">${date}</div>
                </div>
                <div class="concern-detail">
                    <div class="detail-label">Current Status</div>
                    <div class="detail-value">
                        <span class="status-badge status-${status.toLowerCase().replace(' ', '-')}">${status}</span>
                    </div>
                </div>
                <div class="concern-detail">
                    <div class="detail-label">Message</div>
                    <div class="concern-message">${message}</div>
                </div>
            `;

            // Set the concern ID in the form
            concernIdInput.value = id;

            // Set the current status in the select dropdown
            statusSelect.value = status;

            // Set any existing admin notes
            adminNotesTextarea.value = adminNotes;

            // Show the modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    </script>
</body>

</html>

