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
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Announcements - Admin</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/default.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../../css/news.css?v=<?php echo time(); ?>">
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

        <!-- Displaying Announcements as a Table -->
        <section class="admin-announcements">
            <h3>Announcements</h3>
            <?php
            if (empty($announcements)) {
                ?>
                <p>No announcements available at the moment.</p>
            <?php
            } else {
                ?>
               <table>
                <tr class="table-header">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Content</th>
                    <th>Type</th>
                    <th>Available Actions</th>
                </tr>
                <tr>
                <?php
                    foreach ($announcements as $row){
                ?>
                    <td><?php echo $row["id"]?></td>
                    <td><?php echo $row["Title"]?></td>
                    <td><?php echo $row["Date"]?></td>
                    <td><?php echo $row["Content"]?></td>
                    <td><?php echo $row["Type"]?></td>
                    
                    <!-- Edit and Delete buttons connect to updatenews.php to do their work -->
                    <form action="updatenews.php" method="post">
                        <td>
                            <button type="submit" name="edit" value="edit" class="btn">Edit</button>
                            <button type="submit" name="delete" value="delete" class="btn">Delete</button>
                        </td>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>"/>
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

        <!-- Form for Adding New Announcements/Offers -->
        <section class="announcements-form">
            <h3>Add New Announcement</h3>
            <form action="adminnews.php" method="post">
                <label for="title">Title:</label>
                <input type="text" name="title" required>

                <label for="date">Date:</label>
                <input type="date" name="date" required>

                <label for="content">Content:</label>
                <textarea name="content" required></textarea>

                <label for="type">Type:</label>
                <select name="type" required>
                    <option value="Offer">Offer</option>
                    <option value="Announcement">Announcement</option>
                </select>

                <button type="submit" name="create" value="create" class="btn btn-primary">Post</button>
            </form>



            <?php
                //Create announcements/offers if the form is submitted
                if (isset($_POST['create'])) {
                    // Get form data
                    $title = $_POST['title'];
                    $date = $_POST['date'];
                    $content = $_POST['content'];
                    $type = $_POST['type'];

                    // Preparation of query
                    $query = "INSERT INTO announcements (Title, Date, Content, Type) VALUES (?, ?, ?, ?)";

                    try {
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$title, $date, $content, $type]);
                    } catch (PDOException $e) {
                        die("Error executing query: " . $e->getMessage());
                    }

                    echo "<script>window.location = window.location.href;</script>";
                    exit();
                }
                
                //Delete announcements/offers if the form is submitted
                if(isset($_POST['delete'])){
                    // Get form data
                    $id = $_POST['id'];
                    
                    // Preparation of query
                    $query = "DELETE 
                    FROM announcements
                    WHERE id = '$id';";
                    try {
                        $stmt = $pdo->query($query);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        die("Error executing query: " . $e->getMessage());
                    }
                    header("Location:adminnews.php");
                    exit();
                }

            ?>
        </section>

    </body>
</html>