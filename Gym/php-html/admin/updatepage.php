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
    
    // Get Data from umanagement if the form is submitted
    if(isset($_POST['edit'])){
        $user_id = $_POST['id'];
        $query = "SELECT * FROM accepted_users WHERE id = $user_id";
        try {
            $stmt = $pdo->query($query);
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
    }

    // Delete user if the form is submitted
    if(isset($_POST['delete'])){
        // Get form data
        $id = $_POST['id'];
        $name = $_POST['name'];
        $role = $_POST['roles'];

        if($role == "Gymnast"){
            // Preparation of query
            $deleteQuery = "DELETE FROM GymServices WHERE Gymnast =?";
            try {
                $stmtDelete = $pdo->prepare($deleteQuery);
                $stmtDelete->execute([$name]);
            } catch (PDOException $e) {
                die("Error executing query: " . $e->getMessage());
            }
        }

        // Preparation of query
        $query = "DELETE 
                FROM accepted_users
                WHERE id = '$id';";
                try {
                    $stmt = $pdo->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Error executing query: " . $e->getMessage());
                }

        header("location: usersmanagement.php");
    } 

    // Update user if the form is submitted
    if(isset($_POST['update'])){
        // Get form data
        $id = $_POST["id"];
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $country = $_POST["country"];
        $state = $_POST["state"];
        $city = $_POST["city"];
        $address = $_POST["address"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $roles = $_POST["roles"];
        $cancelation_count = $_POST["cancelation_count"];

        // Preparation of query
        $query = "UPDATE `accepted_users` SET 
        `name`= ?,
        `surname`= ?,
        `country`= ?,
        `state`= ?,
        `city`= ?,
        `address`= ?,
        `email`= ?,
        `username`= ?,
        `pass_word`= ?,
        `roles`= ?,
        `cancelation_count`= ?
        WHERE id = ?";

        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute([$name,$surname,$country,$state,$city,$address,$email,$username,$password,$roles,$cancelation_count,$id]);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }

        header("location: usersmanagement.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update User</title>
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

        <!-- Form for Updating User -->
        <div class="wrapper">
            <div class="form-box login">
                <h2>Update User</h2>
                <form action="updatepage.php" method="post">
                    <div class="login-form">
                        <div class="input-box">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo $user[0]["name"]?>">
                        </div>
                        <div class="input-box">
                            <label for="surname">Surname:</label>
                            <input type="text" id="surname" name="surname" value="<?php echo $user[0]["surname"]?>"> 
                        </div>

                        <div class="select_option">
                                <select class="form-select country" name="country" aria-label="Default select example" onchange="loadStates()">
                                    <option value="<?php echo $user[0]["country"]?>" selected><?php echo $user[0]["country"]?></option>
                                </select>
                                <select class="form-select state" name="state" aria-label="Default select example" onchange="loadCities()">
                                    <option value="<?php echo $user[0]["state"]?>" selected><?php echo $user[0]["state"]?>"</option>
                                </select>
                                <select class="form-select city" name="city" aria-label="Default select example">
                                    <option value="<?php echo $user[0]["city"]?>" selected><?php echo $user[0]["city"]?>"</option>
                                </select>
                        </div>

                        <div class="input-box">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" value="<?php echo $user[0]["address"]?>">
                        </div>

                        <div class="input-box">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo $user[0]["email"]?>">
                        </div>
                        <div class="input-box">
                            <label for="username">Username:</label>
                            <input type="username" id="username" name="username" value="<?php echo $user[0]["username"]?>">
                        </div>
                        <div class="input-box">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" value="<?php echo $user[0]["pass_word"]?>">
                        </div>
                        <label for="roles">Role:</label>
                        <select name="roles">
                            <option value="<?php echo $user[0]["roles"]?>"><?php echo $user[0]["roles"]?></option>
                            <option value="Gymnast">Gymnast</option>
                            <option value="User">User</option>
                        </select>
                        <div class="input-box">
                            <label for="cancelation_count">Cancelation Count:</label>
                            <input type="number" id="cancelation_count" name="cancelation_count" value="<?php echo $user[0]["cancelation_count"]?>">
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $user[0]['id']; ?>"/>
                    <button type="submit" name="update" class="btn">Update</button> 
                </form> 
            </div>
        </div>
        
        <script>
            var config = {
                cUrl: 'https://api.countrystatecity.in/v1/countries',
                ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
            }


            var countrySelect = document.querySelector('.country'),
                stateSelect = document.querySelector('.state'),
                citySelect = document.querySelector('.city')


            function loadCountries() {

                let apiEndPoint = config.cUrl

                fetch(apiEndPoint, {headers: {"X-CSCAPI-KEY": config.ckey}})
                .then(Response => Response.json())
                .then(data => {
                    // console.log(data);

                    data.forEach(country => {
                        const option = document.createElement('option')
                        option.value = country.iso2
                        option.textContent = country.name 
                        countrySelect.appendChild(option)
                    })
                })
                .catch(error => console.error('Error loading countries:', error))

                stateSelect.disabled = true
                citySelect.disabled = true
                stateSelect.style.pointerEvents = 'none'
                citySelect.style.pointerEvents = 'none'
            }

            function loadStates() {
                stateSelect.disabled = false
                citySelect.disabled = true
                stateSelect.style.pointerEvents = 'auto'
                citySelect.style.pointerEvents = 'none'

                const selectedCountryCode = countrySelect.value
                // console.log(selectedCountryCode);
                stateSelect.innerHTML = '<option value="">Select State</option>' // for clearing the existing states
                citySelect.innerHTML = '<option value="">Select City</option>' // Clear existing city options

                fetch(`${config.cUrl}/${selectedCountryCode}/states`, {headers: {"X-CSCAPI-KEY": config.ckey}})
                .then(response => response.json())
                .then(data => {
                    // console.log(data);

                    data.forEach(state => {
                        const option = document.createElement('option')
                        option.value = state.iso2
                        option.textContent = state.name 
                        stateSelect.appendChild(option)
                    })
                })
                .catch(error => console.error('Error loading countries:', error))
            }

            function loadCities() {
                citySelect.disabled = false
                citySelect.style.pointerEvents = 'auto'

                const selectedCountryCode = countrySelect.value
                const selectedStateCode = stateSelect.value
                // console.log(selectedCountryCode, selectedStateCode);

                citySelect.innerHTML = '<option value="">Select City</option>' // Clear existing city options

                fetch(`${config.cUrl}/${selectedCountryCode}/states/${selectedStateCode}/cities`, {headers: {"X-CSCAPI-KEY": config.ckey}})
                .then(response => response.json())
                .then(data => {
                    // console.log(data);

                    data.forEach(city => {
                        const option = document.createElement('option')
                        option.value = city.iso2
                        option.textContent = city.name 
                        citySelect.appendChild(option)
                    })
                })
            }

            window.onload = loadCountries
        </script>
    </body>
</html>
