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

    // Fetch accepted_users
    $queryAcceptedUsers = "SELECT * FROM accepted_users";
    try {
        $stmtAcceptedUsers = $pdo->query($queryAcceptedUsers);
        $acceptedUsers = $stmtAcceptedUsers->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing Accepted Users query: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Management - Admin</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/umanagement.css?v=<?php echo time(); ?>">
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

        <!-- Displaying Accepted Users as a Table -->
        <section class="accepted-users">
            <h3>Accepted Users</h3>
            <?php
            if (empty($acceptedUsers)) {
                ?>
                <p>No accepted users available at the moment.</p>
                <?php
            } else {
            ?>
            <table class="table">
                <thead>
                    <tr class="table-header">
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Roles</th>
                        <th>Cancellation Count</th>
                        <th>Available Actions</th>
                    </tr>
                </thead>  
                <?php
                foreach ($acceptedUsers as $user) { 
                    ?>
                <tr>
                    <td><?php echo $user["id"]?></td>
                    <td><?php echo $user["name"]?></td>
                    <td><?php echo $user["surname"]?></td>
                    <td><?php echo $user["country"]?></td>
                    <td><?php echo $user["state"]?></td>
                    <td><?php echo $user["city"]?></td>
                    <td><?php echo $user["address"]?></td>
                    <td><?php echo $user["email"]?></td>
                    <td><?php echo $user["username"]?></td>
                    <td><?php echo $user["roles"]?></td>
                    <td><?php echo $user["cancelation_count"]?></td>
                    <form action="updatepage.php" method="post">
                    <td>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>"/>
                        <input type="hidden" name="name" value="<?php echo $user['name']; ?>"/>
                        <input type="hidden" name="roles" value="<?php echo $user['roles']; ?>"/>
                        <button type="submit" name="edit" value="edit" class="btn">Edit</button>
                        <?php
                        // Fetch Gym Bookings
                        $queryGymBookings = "SELECT * FROM GymBookings WHERE Gymnast = '$user[name]'";
                        try {
                            $stmtGymBookings = $pdo->query($queryGymBookings);
                            $gymBookings = $stmtGymBookings->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            die("Error executing Gym Bookings query: " . $e->getMessage());
                        }

                        // Check If you can delete a user
                        if ($user["roles"] == "Gymnast" && !empty($gymBookings)) {
                            ?>
                            <button type="submit" class="btn" disabled>Cannot Delete</button>
                        <?php
                        } elseif ($user["roles"] == "Admin") {
                            ?>
                            <button type="submit" class="btn" disabled>Cannot Delete</button>
                        <?php
                        } else {
                            ?>
                            <button type="submit" name="delete" value="delete" class="btn">Delete</button>
                        <?php
                        }
                        ?>
                    </td>
                    </form>
                </tr>
                <?php
                    }
                ?> 
            </table>
            <?php
            }
            ?>
        </section>
    </body>
</html>
