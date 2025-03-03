<?php
    session_start();
    // Check if the user is logged in and is an admin, if not redirect to the login page
    if (!isset($_SESSION["username"]) || $_SESSION["roles"] !== "Admin") {
        header("Location: http://localhost/Gym/php-html/main/index.php");
        exit();
    }
    //Connect with data base
    require_once("../classes/dbh.connection.php");

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=project_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Fetch Gym Bookings
    $queryBookings = "SELECT * FROM GymBookings";
    try {
        $stmtBookings = $pdo->query($queryBookings);
        $gymBookings = $stmtBookings->fetchAll(PDO::FETCH_ASSOC);
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
        <title>Booking History - Admin</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/history.css?v=<?php echo time(); ?>">
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

        <!-- Displaying Gym Bookings as a Table -->
        <section class="bookings">
            <h3>Bookings</h3>
            <?php
            if (empty($gymBookings)) {
                ?>
            <p>No bookings available at the moment.</p>
            <?php
            } else {
            ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Service ID</th>
                            <th>Service</th>
                            <th>Gymnast</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <?php
                        // Loop through the gym bookings and display them in a table
                        foreach ($gymBookings as $booking) {
                        ?>
                            <td><?php echo $booking["BookingID"]?></td>
                            <td><?php echo $booking["ServiceID"]?></td>
                            <td><?php echo $booking["Service"]?></td>
                            <td><?php echo $booking["Gymnast"]?></td>
                            <td><?php echo $booking["Username"]?></td>
                            <td><?php echo $booking["Name"]?></td>
                            <td><?php echo $booking["Surname"]?></td>
                            <td><?php echo $booking["BookingDate"]?></td>
                            <td><?php echo $booking["BookingTime"]?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </section>
        
        <script>
            function gotoLink(element) {
                var link = element.value;
                window.location.href = link;
            }
        </script>
    </body>
</html>
