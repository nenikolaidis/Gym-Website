<?php
    session_start();
    //Check if the user is logged in, if not redirect to the login page
    if (!isset($_SESSION["username"])) {
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

    $current_user = $_SESSION["username"];
    // Fetch Gym Bookings
    $queryGymBookings = "SELECT * FROM GymBookings WHERE username = '$current_user'";
    try {
        $stmtGymBookings = $pdo->query($queryGymBookings);
        $gymBookings = $stmtGymBookings->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing Gym Bookings query: " . $e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking History</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/history.css?v=<?php echo time(); ?>">
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

        <!-- Displaying User Gym Bookings as a Table -->
        <section class="user-bookings">
            <h3>History</h3>
            <?php if (count($gymBookings) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Gymnast</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Booking Date</th>
                            <th>Booking Time</th>
                            <th>Available Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <?php
                            // Loop through the gym bookings and display them in a table
                            foreach ($gymBookings as $booking) {
                        ?>
                            <td><?php echo $booking["Service"]?></td>
                            <td><?php echo $booking["Gymnast"]?></td>
                            <td><?php echo $booking["Name"]?></td>
                            <td><?php echo $booking["Surname"]?></td>
                            <td><?php echo $booking["BookingDate"]?></td>
                            <td><?php echo $booking["BookingTime"]?></td>

                            <form action="userhistory.php" method="post">
                                <input type="hidden" name="serviceid" value="<?php echo $booking['ServiceID']; ?>"/>
                                <input type="hidden" name="date" value="<?php echo $booking['BookingDate']; ?>"/>
                                <input type="hidden" name="time" value="<?php echo $booking['BookingTime']; ?>"/>
                                <input type="hidden" name="id" value="<?php echo $booking['BookingID']; ?>"/>
                                <input type="hidden" name="username" value="<?php echo $booking['Username']; ?>"/>
                                <?php
                                    // Check if the booking can be deleted (2 hours before start)
                                    $currentTime = time();
                                    $bookingDateTime = strtotime($booking["BookingDate"] . " " . $booking["BookingTime"]);
                                    $timeDifference = $bookingDateTime - $currentTime;
                                    if ($timeDifference >= 7200) {  // 2 hours in seconds
                                ?>
                                <td><button type="submit" name="delete" value="delete" class="btn">Cancel</button></td>

                                <?php
                                }else{
                                    ?>
                                    <td><button type="submit" class="btn" disabled>Cannot Cancel</button></td>
                                <?php
                                    }
                                ?>
                            </form>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No bookings available.</p>
            <?php endif; ?>
        </section>
        <?php
            // Delete Booking if the form is submitted
            if(isset($_POST['delete'])){
                // Get Data
                $booking_id = $_POST['id'];
                $booking_serviceid = $_POST['serviceid'];
                $booking_date = $_POST['date'];
                $booking_time = $_POST['time'];
                $booking_username = $_POST['username'];

                $queryAcceptedUsers = "SELECT * FROM accepted_users WHERE username = '$booking_username'";

                try {
                    $stmtAcceptedUsers = $pdo->query($queryAcceptedUsers);
                    $accepted_users = $stmtAcceptedUsers->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing Accepted Users query: " . $e->getMessage());
                }

                // Check cancelation_count
                if($accepted_users["cancelation_count"]<1){

                    $query = "DELETE 
                        FROM GymBookings
                        WHERE BookingID = '$booking_id';";
        

                    try {
                        $stmt = $pdo->query($query);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Error executing query: " . $e->getMessage());
                    }

                        $query_time = "UPDATE `accepted_users` SET 
                        `cancelation_count`= cancelation_count + 1,
                        `cancelled_at` = CURRENT_TIMESTAMP() 
                        WHERE username = ?";

                        try {
                            $stmt = $pdo->prepare($query_time);
                            $stmt->execute([$booking_username]);
                        } catch (PDOException $e) {
                            die("Error executing query: " . $e->getMessage());
                        }

                        // Update available slots after delete booking
                        $queryUpdateSlots = "UPDATE GymServices SET AvailableSlots = AvailableSlots + 1 WHERE ServiceID = ?;";
                        try {
                            $stmtUpdateSlots = $pdo->prepare($queryUpdateSlots);
                            $stmtUpdateSlots->execute([$booking_serviceid]);
                        } catch (PDOException $e) {
                            die("Error updating available slots: " . $e->getMessage());
                        }
                        echo "<script>window.location = window.location.href;</script>";
                        exit();

                }elseif($accepted_users["cancelation_count"]<2){
                    $query = "DELETE 
                        FROM GymBookings
                        WHERE BookingID = '$booking_id';";
        

                    try {
                        $stmt = $pdo->query($query);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Error executing query: " . $e->getMessage());
                    }

                        $query_time = "UPDATE `accepted_users` SET 
                        `cancelation_count`= cancelation_count + 1
                        WHERE username = ?";

                        try {
                            $stmt = $pdo->prepare($query_time);
                            $stmt->execute([$booking_username]);
                        } catch (PDOException $e) {
                            die("Error executing query: " . $e->getMessage());
                        }
                        
                        // Update available slots after delete booking
                        $queryUpdateSlots = "UPDATE GymServices SET AvailableSlots = AvailableSlots + 1 WHERE ServiceID = ?;";
                        try {
                            $stmtUpdateSlots = $pdo->prepare($queryUpdateSlots);
                            $stmtUpdateSlots->execute([$booking_serviceid]);
                        } catch (PDOException $e) {
                            die("Error updating available slots: " . $e->getMessage());
                        }
                        echo "<script>window.location = window.location.href;</script>";
                        exit();
                }        
            }
        ?>
        <footer class="bg-dark text-white">
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