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

    // Fetch GymServices
    $query = "SELECT  * FROM GymServices";
    try {
        $stmt = $pdo->query($query);
        $gymServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }

    // Fetch ServiceTypes
    $query2 = "SELECT * FROM ServiceTypes";
    try {
        $stmt = $pdo->query($query2);
        $uniqueServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
    }

    // Fetch accepted_users
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
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Gym Services - Admin</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/services.css?v=<?php echo time(); ?>">
        
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

        <!-- Displaying Gym Services for Admin -->
        <section class="admin-services">
            <h3>Manage Gym Services</h3>
            <table class="admin-services">    
                <tr class="table-header">
                    <th>ServiceID</th>
                    <th>Service</th>
                    <th>Gymnast</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>AvailableSlots</th>
                    <th>Available Actions</th>
                </tr>
                <?php
                    foreach ($gymServices as $service){
                ?>
                <tr>
                    <td><?php echo $service["ServiceID"]?></td>
                    <td><?php echo $service["Service"]?></td>
                    <td><?php echo $service["Gymnast"]?></td>
                    <td><?php echo $service["Date"]?></td>
                    <td><?php echo $service["Time"]?></td>
                    <td><?php echo $service["AvailableSlots"]?></td>
                
                    <!-- Edit and Delete buttons connect to updateservice.php to do their work -->
                    <form action="updateservice.php" method="post">
                        <td>
                            <button type="submit" name="edit" value="edit" class="btn">Edit</button>
                            <button type="submit" name="delete" value="delete" class="btn">Remove</button>
                        </td>
                        <input type="hidden" name="id" value="<?php echo $service['ServiceID']; ?>"/>
                    </form>
                </tr>
                <?php
                    }
                ?>
            </table>
        </section>
         
        <!-- Form for Adding New Gym Service for Admin -->
        <section class="add-service">
            <h3>Add Gym Service</h3>
            <form method="post" action="adminservices.php">
                <div class="form-group">
                    <label for="service">Service:</label>
                    <select id="service" name="service" class="form-control" required>
                        <?php foreach ($uniqueServices as $service): ?>
                            <option value="<?php echo $service['Type']; ?>"><?php echo $service['Type']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="gymnast">Gymnast:</label>
                    <select id="gymnast" name="gymnast" class="form-control" required>
                        <?php foreach ($gymnasts as $gymnast): ?>
                            <option value="<?php echo $gymnast['name']; ?>"><?php echo $gymnast['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="available_slots">Available Slots:</label>
                    <input type="number" id="available_slots" name="available_slots" class="form-control" required min="0">
                </div>
                <button type="submit" name="add-service" value="add-service"  class="btn btn-primary">Add Service</button>
            </form>
        </section>



        <!-- Displaying Gym Services Types for Admin -->
        <section class="admin-services">
            <h3>Manage Gym Services Types</h3>
            <table class="admin-services">    
                <tr class="table-header">
                    <th>ServiceID</th>
                    <th>Service Type</th>
                </tr>
                <?php
                    foreach ($uniqueServices as $types){
                ?>
                <tr>
                    <td><?php echo $types["ID"]?></td>
                    <td><?php echo $types["Type"]?></td>
                </tr>
                <?php
                    }
                ?>
            </table>
        </section>
        
        <!-- Form for Adding New Gym Service Type for Admin -->
        <section class="add-type">
            <h3>Create a Service Type</h3>
            <form method="post" action="adminservices.php">
                <div class="form-group">
                    <label for="type">Add Service:</label>
                    <input type="text" name="type" class="form-control" required>
                </div>
                <button type="submit" name="add-type" value="add-type" class="btn btn-primary">Add Type</button>
            </form>
        </section>

        <!-- Form for Deleting Gym Service Type for Admin -->
        <section class="delete-type">
            <h3>Delete a Service Type</h3>
            <form method="post" action="adminservices.php">
                <div class="form-group">
                    <label for="type">Delete Service:</label>
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
                </div>
                <button type="submit" name="service_del" value="service_del" class="btn btn-primary">Delete Service Type</button>
            </form>
        </section>
                    

        <?php
            
            // Create Service if the form is submitted
            if (isset($_POST['add-service'])) {
                // Get form data
                $service = $_POST['service'];
                $gymnast = $_POST['gymnast'];
                $date = $_POST['date'];
                $time = $_POST['time'];
                $available_slots = $_POST['available_slots'];

                // Preparation of query
                $query = "INSERT INTO GymServices (Service, Gymnast, Date, Time, AvailableSlots) 
                        VALUES (?, ?, ?, ?, ?)";

                try {
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$service, $gymnast, $date, $time, $available_slots]);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }
                echo "<script>window.location = window.location.href;</script>";
                exit();
            }

            // Create of Service Type if the form is submitted
            if (isset($_POST['add-type'])) {
                // Get form data
                $type = $_POST['type'];

                // Preparation of query
                $query = "INSERT INTO servicetypes (Type) 
                        VALUES (?)";

                try {
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$type]);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }
                echo "<script>window.location = window.location.href;</script>";
                exit();
            }
            
            // Delete a Service Type if the form is submitted
            if(isset($_POST['service_del'])){
                // Get Service Types that are in use
                $test = "SELECT DISTINCT Service FROM GymServices";

                try {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }

                // Get form data
                $type = $_POST['service'];

                // Checking if Service Type is in use
                $smth = false;
                foreach ($result as $row){
                    if ($row["Service"] == $type){
                        $smth = true;
                        break;
                    }
                }
                if ($smth==false){
                    $query = "DELETE 
                    FROM servicetypes
                    WHERE Type = '$type';";
                    try {
                        $stmt = $pdo->query($query);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Error executing query: " . $e->getMessage());
                    }
                    echo "<script>window.location = window.location.href;</script>";
                    exit();
                }elseif($smth==true){
                    ?>
                    <script>
                        alert("Delete is not possible");
                    </script>
                    <?php
                }
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