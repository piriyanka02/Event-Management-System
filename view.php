<?php
// view.php
include 'config.php';

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_condition = '';
if(!empty($search)) {
    $search_condition = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR event LIKE '%$search%'";
}

// Get total records for pagination
$total_sql = "SELECT COUNT(*) as total FROM event_bookings $search_condition";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Adjust page if out of bounds
if ($total_pages > 0 && $page > $total_pages) {
    $page = $total_pages;
}

// Get data with pagination
$sql = "SELECT * FROM event_bookings $search_condition ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Bookings - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.html">Event Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-3"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="events.html">Events</a></li>
                <li class="nav-item mx-3"><a class="nav-link active" href="view.php">View Bookings</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="about.html">About</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="contact.html">Contact</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="Register.html">Register/Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">All Event Bookings</h2>
    
    <!-- Results Count -->
    <p class="text-center text-muted mb-3">
        Showing <?php echo ($total_rows > 0) ? (($page - 1) * $limit + 1) : 0; ?> - 
        <?php echo min($page * $limit, $total_rows); ?> of <?php echo $total_rows; ?> bookings
    </p>
    
    <!-- Search Form -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="page" value="1">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Name, Email or Event" 
                   value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
            <button class="btn btn-primary" type="submit">Search</button>
            <?php if(!empty($search)): ?>
                <a href="view.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </div>
    </form>
    
    <!-- Bookings Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Event</th>
                    <th>Tickets</th>
                    <th>Message</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['event']}</td>
                            <td>{$row['tickets']}</td>
                            <td>" . (empty($row['message']) ? 'No message' : substr($row['message'], 0, 50) . '...') . "</td>
                            <td>" . date('M j, Y', strtotime($row['created_at'])) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center py-4'>
                        <i class='bi bi-inbox display-4 text-muted d-block mb-2'></i>
                        <p class='text-muted'>" . 
                        (!empty($search) ? 
                         'No bookings match your search' : 
                         'No bookings found') . 
                        "</p>
                    </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Previous Button -->
            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">
                    &laquo; Previous
                </a>
            </li>
            
            <!-- Page Numbers -->
            <?php 
            // Show limited page numbers for better UI
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $start_page + 4);
            
            for($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <!-- Next Button -->
            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">
                    Next &raquo;
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<footer class="text-center">
    Developed by Piriyanka | 2025
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>