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

    // Get Data from adminnews if the form is submitted
    if(isset($_POST['edit'])){
        $announcements_id = $_POST["id"];
        $query = "SELECT * FROM announcements WHERE id = $announcements_id";
        try {
            $stmt = $pdo->query($query);
            $announcement = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                FROM announcements
                WHERE id = '$id';";
                try {
                    $stmt = $pdo->query($query2);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }

        header("location: adminnews.php");
    } 

    // Update announcements if the form is submitted
    if(isset($_POST['update'])){
        // Get form data
        $id = $_POST["id"];
        $title = $_POST["title"];
        $date = $_POST["date"];
        $content = $_POST["content"];
        $type = $_POST["type"];

        // Preparation of query
        $query3 = "UPDATE `announcements` SET 
        `Title`= ?,
        `Date`= ?,
        `Content`= ?,
        `Type`= ?
        WHERE id = ?";

        try {
            $stmt = $pdo->prepare($query3);
            $stmt->execute([$title,$date,$content,$type,$id]);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
        header("location: adminnews.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update News</title>
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

        <!-- Form for Updating Announcements/Offers -->
        <div class="wrapper">
            <div class="form-box login">
                <h2>Update Announcement</h2>
                <form action="updatenews.php" method="post">
                    <div class="login-form">
                        <div class="input-box">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" value="<?php echo $announcement[0]["Title"]?>">
                        </div>
                        <div class="input-box">
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" value="<?php echo $announcement[0]["Date"]?>"> 
                        </div>
                        <div class="input-box">
                            <label for="content">Content:</label>
                            <input type="content" id="content" name="content" value="<?php echo $announcement[0]["Content"]?>"> 
                        </div>
                        <select name="type">
                            <option value="<?php echo $announcement[0]["Type"]?>"><?php echo $announcement[0]["Type"]?></option>
                            <option value="Offer">Offer</option>
                            <option value="Announcement">Announcement</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $announcement[0]['id']; ?>"/>
                    <button type="submit" name="update" value="update" class="btn">Update</button></td>
                </form>
            </div>
        </div>
    </body>
</html>