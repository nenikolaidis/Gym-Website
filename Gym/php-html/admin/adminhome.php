<?php
    session_start();
    // Check if the user is logged in and is an admin, if not redirect to the login page
    if (!isset($_SESSION["username"]) || $_SESSION["roles"] !== "Admin") {
        header("Location: http://localhost/Gym/php-html/main/index.php");
        exit();
    }
?>

<!DOCTYPE html>         
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
    
</head>
<body>
    <header>
        <h2 class="logo">Train Gym Admin</h2>
        <nav class="navigation">
            <a href="adminhome.php">Home</a>
            <a href="admissions.php"> Manage Admissions</a>
            <a href="usersmanagement.php">Manage Users</a>
            <a href="adminservices.php">Manage Gym Services</a>
            <a href="adminhistory.php">See Bookings</a>
            <a href="adminnews.php">Manage Announcements</a>
            <!-- <a href="#"><?php /*echo $_SESSION["username"];*/?></a> -->
            <a href="../includes/logout.inc.php" class="btn btn-light">Logout</a>
        </nav>
    </header>
    
    <section class="main">
        <div class="main">
            <h1>Welcome to Train Gym Admin Dashboard</h1>
            <p>Monitor and control all aspects of Train Gym's operations and user data from this centralized admin panel.</p>
            <p>Explore the menu options above to manage memberships, track attendance, handle billing, and more.</p>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Train Gym. All rights reserved.</p>
    </footer>

    <script>
        function gotoLink(element) {
            var link = element.value;
            window.location.href = link;
        }
    </script>
</body>
</html>