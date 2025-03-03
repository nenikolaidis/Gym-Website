<?php
    session_start();
    // Check if the user is logged in and is an admin, if not redirect to the login page
    if (!isset($_SESSION["username"]) || $_SESSION["roles"] !== "Admin") {
        header("Location: http://localhost/Gym/php-html/main/index.php");
        exit();
    }

    // Connect with data base
    require_once("../classes/dbh.connection.php");
    
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=project_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Fetch pending_users
    $query = "SELECT * FROM pending_users";
    try {
        $stmt = $pdo->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Admissions - Admin</title>
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

    <!-- Displaying Pending Users as a Table -->
    <section class="pending-users">
        <h3>Users For Admission</h3>
        <?php if (count($result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Available Actions</th>
                    </tr>
                </thead>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo $row["name"]?></td>
                        <td><?php echo $row["surname"]?></td>
                        <td><?php echo $row["country"]?></td>
                        <td><?php echo $row["state"]?></td>
                        <td><?php echo $row["city"]?></td>
                        <td><?php echo $row["address"]?></td>
                        <td><?php echo $row["email"]?></td>
                        <td><?php echo $row["username"]?></td>
                        <form action="admissions.php" method="post">
                            <td>
                                <select name="roles">
                                    <option value="Gymnast">Gymnast</option>
                                    <option value="User">User</option>
                                </select>
                            </td>
                            <td class ="actions">
                                <button type="submit" name="approve" value="approve" class="btn">Approve</button>
                                <button type="submit" name="delete" value="delete" class="btn">Remove</button>
                            </td>
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>"/>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No users for admission.</p>
        <?php endif; ?>
    </section>
        <?php
            // Approve pending user if the form is submitted
            if(isset($_POST['approve'])) {
                // Get form data
                $id = $_POST['id'];
                $roles = $_POST['roles'];

                // Preparation of query
                $query = "SELECT * FROM pending_users WHERE id = '$id';";
                try {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }
                $name = $result[0]["name"];
                $surname = $result[0]["surname"];
                $country = $result[0]["country"];
                $state = $result[0]["state"];
                $city = $result[0]["city"];
                $address = $result[0]["address"];
                $email = $result[0]["email"];
                $username = $result[0]["username"];
                $password = $result[0]["pass_word"];

                // Preparation of query
                $query = "INSERT INTO `accepted_users`
                ( `name`, `surname`, `country`, `state`, `city`, `address`, `email`, `username`, `pass_word`, `roles`)
                    VALUES ('$name','$surname','$country', '$state','$city','$address','$email','$username','$password','$roles')";
                try {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }

                // Preparation of query
                $query = "DELETE 
                FROM pending_users
                WHERE id = '$id';";
                try {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }
                echo "<script>window.location = window.location.href;</script>";
                exit();
            }

            // Delete pending user if the form is submitted
            if(isset($_POST['delete'])) {
                // Get form data
                $id = $_POST['id'];

                // Preparation of query
                $query = "DELETE 
                FROM pending_users
                WHERE id = '$id';";
                try {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }
                echo "<script>window.location = window.location.href;</script>";
                exit();
            }
        ?>
    </body>
</html>