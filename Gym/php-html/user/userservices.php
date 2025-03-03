<?php
session_start();

require_once("../classes/dbh.connection.php");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=project_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
    
// Fetch Gym Services
$queryGymServices = "SELECT * FROM GymServices";
try {
    $stmtGymServices = $pdo->query($queryGymServices);
    $gymServices = $stmtGymServices->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error executing Gym Services query: " . $e->getMessage());
}

// Fetch Gym Services
$queryServicesTypes = "SELECT * FROM servicetypes";
try {
    $stmtServicesTypes = $pdo->query($queryServicesTypes);
    $ServicesTypes = $stmtServicesTypes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error executing Gym Services query: " . $e->getMessage());
}

// Fetch Gym Bookings
$queryGymBookings = "SELECT * FROM GymBookings";
try {
    $stmtGymBookings = $pdo->query($queryGymBookings);
    $gymBookings = $stmtGymBookings->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error executing Gym Bookings query: " . $e->getMessage());
}

$query = "SELECT Type FROM ServiceTypes";
    try {
        $stmt = $pdo->query($query);
        $uniqueServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }

$result = $gymServices;
//This php request handles the search request
if (isset($_POST['search'])) {
    $service = $_POST['service'];
    $date = $_POST['date'];

    $query = "SELECT * FROM GymServices WHERE 
    Service = '$service' AND
    Date = '$date' AND
    AvailableSlots > 0";

    try {
        $stmt = $pdo->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }
    }


    if (isset($_SESSION["username"])) {
    
    $current_user = $_SESSION["username"];
    $queryAcceptedUsers = "SELECT * FROM accepted_users WHERE username = '$current_user'";

    try {
        $stmtAcceptedUsers = $pdo->query($queryAcceptedUsers);
        $accepted_users = $stmtAcceptedUsers->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing Accepted Users query: " . $e->getMessage());
    }

    $givenTimestamp = strtotime($accepted_users["cancelled_at"]);
    $currentTimestamp = time();

    $weekDifference = round(($currentTimestamp - $givenTimestamp) / (604800)); // Calculate difference in weeks

    if ($weekDifference > 1) {
        // Reset the cancelled_at timestamp
        $resetTimestamp = date("Y-m-d H:i:s", strtotime("0000-00-00 00:00:00"));
        $resetCount = 0;
        // Perform update query to reset cancelled_at
        $updateQuery = "UPDATE accepted_users SET cancelled_at = ? , cancelation_count = ? WHERE username = ?";
        try {
            $stmtUpdate = $pdo->prepare($updateQuery);
            $stmtUpdate->execute([$resetTimestamp, $resetCount, $current_user]);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
    } 
    }
    // Get current date and time
    $currentDate = date("Y-m-d");
    $currentTime = time("H:i:s");

    // Construct DELETE query to delete services passed today's date and time
    $deleteQuery = "DELETE FROM GymServices WHERE Date < ? OR (Date = ? AND Time < ?)";
    try {
        $stmtDelete = $pdo->prepare($deleteQuery);
        $stmtDelete->execute([$currentDate, $currentDate, $currentTime]);
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
    <title>Gym Services</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/services.css?v=<?php echo time(); ?>">
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
    
    <!-- Displaying Gym Services as a Table -->
    <?php
    if (isset($_SESSION["username"])) {
    ?>
    <section class="user-services">
        <form action="userservices.php" method="post"> 
            <h4>Search</h4>
                <div class="form-group">
                    <label for="service">Select Service:</label>    
                        <select name="service" id="service" class="form-control" required>
                            <option>Choose Option</option>
                                <?php
                                    foreach ($uniqueServices as $types){
                                ?>
                                    <option value="<?php echo $types["Type"]?>"><?php echo $types["Type"]?></option>
                                <?php
                                    }
                                ?>
                        </select>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                </div>
            <button type="submit" name="search" value="search" class="btn">Search</button>
        </form>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Gymnast</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the gym services and display them in a table
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row["Service"] . "</td>";
                    echo "<td>" . $row["Gymnast"] . "</td>";
                    echo "<td>" . $row["Date"] . "</td>";
                    echo "<td>" . $row["Time"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php

        $current_user = $_SESSION["username"];
        // Fetch Gym Bookings
        $query_user = "SELECT * FROM accepted_users WHERE username = '$current_user'";
        try {
            $stmt = $pdo->query($query_user);
            $counter = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
            ?>
            <section class="booking-form">
                <h3>Book Gym Service</h3>
                <form method="post" action="userservices.php">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" name="firstName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" name="lastName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Select Time:</label>
                        <select name="time" id="time" class="form-control" required>
                            <?php
                            foreach ($result as $row) {
                            ?>
                                <option value="<?php echo $row["Time"]?>"><?php echo $row["Time"]?></option>
                            <?php
                            }
                            ?>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="service" value="<?php echo $service; ?>"/>
                        <input type="hidden" name="date" value="<?php echo $date; ?>"/>
                    </div>

                    <?php
                        // Fetch Gym Bookings
                        $queryGymBookings = "SELECT * FROM GymBookings WHERE username = '$current_user'";
                        try {
                            $stmtGymBookings = $pdo->query($queryGymBookings);
                            $gymBookings = $stmtGymBookings->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            die("Error executing Gym Bookings query: " . $e->getMessage());
                        }
                    // Check if gymBookings is not empty
                    if (!empty($gymBookings)) {
                        if($gymBookings[0]["BookingTime"] != $row["Time"]){
                        ?>
                        <input type="hidden" name="id" value="<?php echo $row['ServiceID']; ?>"/>
                        <button type="submit" name="book_now" value="book_now" class="btn">Book Now</button>

                        <?php
                            } elseif($counter>=2) {
                        ?>
                            <p>You are not able to book, because you have canceled 2 times this</p>
                        <?php
                            } else{
                            ?>
                                <p>You already have a booking for this service.</p>
                            <?php
                            }
                    } else {
                    ?>
                        <input type="hidden" name="id" value="<?php echo $row['ServiceID']; ?>"/>
                        <button type="submit" name="book_now" value="book_now" class="btn">Book Now</button>
                    <?php
                        }
                    ?>
                </form>
            </section>
    </section>

    <?php
    } else {
    ?>
    <section class="nonuser-services">
        <h3>Gym Services</h3>
        <table class="nonuser-services-table">
            <tr class="table-header">
                <td>Number</td>
                <td>Service</td>
            </tr>
            <?php
            foreach ($ServicesTypes as $service) {
                echo "<tr>";
                echo "<td>" . $service["ID"] . "</td>";
                echo "<td>" . $service["Type"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </section>
    <?php 
    }
    ?>  

<?php
        if (isset($_SESSION["username"])) {
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
<?php
if (isset($_POST['book_now'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $serviceid = $_POST['id'];
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];


    // Fetch user information based on the current session's username
    $queryUserInfo = "SELECT * FROM accepted_users WHERE username = ?";
    try {
        $stmtUserInfo = $pdo->prepare($queryUserInfo);
        $stmtUserInfo->execute([$_SESSION['username']]);
        $userInfo = $stmtUserInfo->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing User Info query: " . $e->getMessage());
    }

    // Validate first name and last name against retrieved user information
    if (strcasecmp($userInfo['name'], $firstName) !== 0 || strcasecmp($userInfo['surname'], $lastName) !== 0) {
        // Display an error message and prompt the user to retry
        echo "Error: The provided first name and last name do not match the user information. Please retry.";
        exit();
    }

    // Fetch gymnast information based on the selected service
    $queryGymnastInfo = "SELECT Gymnast FROM GymServices WHERE Service = ? AND Date = ? AND Time = ?";
    try {
        $stmtGymnastInfo = $pdo->prepare($queryGymnastInfo);
        $stmtGymnastInfo->execute([$service, $date, $time]);
        $gymnastInfo = $stmtGymnastInfo->fetch(PDO::FETCH_ASSOC);

        // Use the fetched gymnast information if available
        $gymnast = $gymnastInfo ? $gymnastInfo['Gymnast'] : "$firstName $lastName";
    } catch (PDOException $e) {
        die("Error executing Gymnast Info query: " . $e->getMessage());
    }

    // Your database query to insert booking information using prepared statement
    $query = "INSERT INTO GymBookings (ServiceID, Service, Gymnast, Username, Name, Surname, BookingDate, BookingTime) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $username = $_SESSION["username"];

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$serviceid, $service, $gymnast, $username, $firstName, $lastName, $date, $time]);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }

    // Update available slots after booking
    $queryUpdateSlots = "UPDATE GymServices SET AvailableSlots = AvailableSlots - 1 WHERE Service = ? AND Date = ? AND Time = ?";
    try {
        $stmtUpdateSlots = $pdo->prepare($queryUpdateSlots);
        $stmtUpdateSlots->execute([$service, $date, $time]);
    } catch (PDOException $e) {
        die("Error updating available slots: " . $e->getMessage());
    }
    
    exit();
    }
?>