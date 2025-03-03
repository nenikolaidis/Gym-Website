<?php
    session_start();
    // Check if the user is logged in and is an admin, if not redirect to the login page
    if (!isset($_SESSION["username"]) || $_SESSION["roles"] !== "Admin") {
        header("Location: http://localhost/Gym/php-html/main/index.php");
        exit();
    }

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=project_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Get Data from adminservices if the form is submitted
    if(isset($_POST['edit'])){
        $service_id = $_POST["id"];
        $query = "SELECT * FROM GymServices WHERE ServiceID = $service_id";
        try {
            $stmt = $pdo->query($query);
            $service = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
    }

    // Delete announcements if the form is submitted
    if(isset($_POST['delete'])){
        // Get form data
        $id = $_POST['id'];

        // Preparation of query
        $query2 = "DELETE 
                FROM GymServices
                WHERE ServiceID = '$id';";
                try {
                    $stmt = $pdo->query($query2);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }

        header("location: adminservices.php");
    } 

    // Update announcements if the form is submitted
    if(isset($_POST['update'])){
        // Get form data
        $id = $_POST["id"];
        $services = $_POST["service"]; //named differently because we have the global variable as well
        $gymnast = $_POST["gymnast"];
        $date = $_POST["date"];
        $time = $_POST["time"];
        $availableSlots = $_POST["availableSlots"];

        // Preparation of query
        $query = "UPDATE `GymServices` SET 
        `Service`= ?,
        `Gymnast`= ?,
        `Date`= ?,
        `Time`= ?,
        `AvailableSlots`= ?
        WHERE ServiceID = ?";

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute([$services,$gymnast,$date,$time,$availableSlots,$id]);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }

        header("location: adminservices.php");
    }

    $query3 = "SELECT DISTINCT name FROM accepted_users WHERE roles = 'Gymnast'";
    try {
        $stmt = $pdo->query($query3);
        $gymnasts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <title>Update Service</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/update.css?v=<?php echo time(); ?>">
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

        <!-- Form for Updating Service -->
        <div class="wrapper">
            <div class="form-box login">
                <h2>Update Service</h2>
                <form action="updateservice.php" method="post">
                    <div class="login-form">
                        <div class="input-box">
                            <label for="service">Service:</label>
                            <input type="text" id="service" name="service" value="<?php echo $service[0]["Service"]?>">
                        </div>
                        <select name="gymnast" id="gymnast" class="form-control">
                            <option value="<?php echo $service[0]["Gymnast"]?>"><?php echo $service[0]["Gymnast"]?></option>
                                <?php
                                    foreach ($gymnasts as $row){
                                ?>
                                    <option value="<?php echo $row["name"]?>"><?php echo $row["name"]?></option>
                                <?php
                                    }
                                ?>
                        </select>
                        <div class="input-box">
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" value="<?php echo $service[0]["Date"]?>">
                        </div>
                        <div class="input-box">
                            <label for="time">Time:</label>
                            <input type="time" id="time" name="time" value="<?php echo $service[0]["Time"]?>">
                        </div>
                        <div class="input-box">
                            <label for="availableSlots">Available Slots:</label>
                            <input type="number" id="availableSlots" name="availableSlots" value="<?php echo $service[0]["AvailableSlots"]?>">
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $service[0]['ServiceID']; ?>"/>
                    <button type="submit" name="update" class="btn">Update</button> 
                </form> 
            </div>
        </div>
    </body>
</html>