<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Tren Gym - Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Include your custom CSS -->
    <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <h2 class="logo">Train Gym</h2>
        <nav class="navigation">
            <?php
                if (isset($_SESSION["username"]) && $_SESSION["roles"] == "Admin") {
            ?>
            <a href="../admin/adminhome.php">Admin Home</a>
            <?php
                }
            ?>  
            <a href="../user/open.php">Home</a>
            <a href="../user/userservices.php">Gym Services</a>
            <a href="../user/usernews.php">News & Announcements</a>
            <?php
                if (isset($_SESSION["username"])) {
            ?>
            <a href="../user/userhistory.php">Booking History</a>
            <a href="#"><?php echo $_SESSION["username"];?></a>
            <a href="../includes/logout.inc.php" class="btn btn-light">Logout</a>
            <?php
                } else {
            ?>
                <div class = "login">
                <button onclick="gotoLink(this)" value="http://localhost/Gym/php-html/main/index.php" class="btn btn-light">Login / Register</button>
                </div>            
                <?php
                }
            ?>        
        </nav>
    </header>
    
    <section class="main">
        <div class="main">
            <h1>Congratulations, <?php echo $_SESSION["username"]; ?>!</h1>
            <p>Your action was successful. Thank you for choosing Train Gym.</p>
            <p>Continue your fitness journey and explore more of our services.</p>
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
