<?php
include 'config.php'; // database connection

if(!isset($_GET['id'])){
    echo "Event not found!";
    exit;
}

$event_id = intval($_GET['id']); // sanitize
$sql = "SELECT * FROM events WHERE id = $event_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    echo "Event not found!";
    exit;
}

$event = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Event - Event Management System</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- External CSS -->
  <link rel="stylesheet" href="css/style.css">
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
          <li class="nav-item mx-3"><a class="nav-link active" href="events.html">Events</a></li>
          <li class="nav-item mx-3"><a class="nav-link" href="about.html">About</a></li>
          <li class="nav-item mx-3"><a class="nav-link" href="contact.html">Contact</a></li>
          <li class="nav-item mx-3"><a class="nav-link" href="Register.html">Register/Login</a></li>
        </ul>
      </div>
    </div>
  </nav>
<div class="container mt-5">
    <h1><?= htmlspecialchars($event['title']); ?></h1>
    <p><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
    <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']); ?></p>
    <p><?= nl2br(htmlspecialchars($event['description'])); ?></p>
    <a href="book.html?event=<?= urlencode($event['title']); ?>" class="btn btn-primary">Book Now</a>
    <a href="index.html" class="btn btn-secondary">Back to Events</a>
</div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
