<?php
    session_start();
    require_once("../classes/dbh.connection.php");

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=project_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
    
    // Fetch Announcements
    $queryAnnouncements = "SELECT * FROM announcements";
    try {
        $stmtAnnouncements = $pdo->query($queryAnnouncements);
        $announcements = $stmtAnnouncements->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>News & Announcements</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/news.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <header class="bg-primary text-white">
            <h2 class="logo">Train Gym</h2>
            <nav class="navigation">
                <a href="open.php">Home</a>
                <a href="userservices.php">Gym Services</a>
                <a href="usernews.php">News & Announcements</a>
                <?php
                if (isset($_SESSION["username"])) {
                    ?>
                    <a href="userhistory.php">Booking History</a>
                    <a href="#" class="text-white"><?php echo $_SESSION["username"]; ?></a>
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
        
        <!-- Displaying User Announcements as a Table -->
        <section class="announcements">
            <h3>Announcements</h3>
            <?php if (!empty($announcements)): ?>
                <div class="announcement-container">
                    <?php foreach ($announcements as $announcement): ?>
                        <?php if ($announcement["Type"] == "Offer" && isset($_SESSION['username'])):  //if user is logged in?>
                            <h4><?php echo $announcement["Title"] . " | " . $announcement["Type"] ?></h4>
                            <p class="date"><?php echo $announcement["Date"] ?></p>
                            <p class="content"><?php echo $announcement["Content"] ?></p>
                            <hr>
                        <?php elseif ($announcement["Type"] != "Offer"): //if user isn't logged in?>
                            <h4><?php echo $announcement["Title"] . " | " . $announcement["Type"] ?></h4>
                            <p class="date"><?php echo $announcement["Date"] ?></p>
                            <p class="content"><?php echo $announcement["Content"] ?></p>
                            <hr>
                        <?php endif; ?>
                        
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No announcements available at the moment.</p>
            <?php endif; ?>
        </section>
        <?php
            if (isset($_SESSION["username"])) {
        ?>
            <footer class="bg-dark text-white">
                <p>&copy; <?php echo date("Y"); ?> Train Gym. All rights reserved.</p>
                <style>
                footer {
                    position: relative !important;
                    }
                </style>
            </footer>
        <?php
            } else {
        ?>
            <footer class="bg-dark text-white">
                <p>&copy; <?php echo date("Y"); ?> Train Gym. All rights reserved.</p>
                <style>
                footer {
                    position: absolute !important;
                    }
                </style>
            </footer>
        <?php
            }
        ?>
        <script>
            function gotoLink(element) {
                var link = element.value;
                window.location.href = link;
            }
        </script>
    </body>
</html>