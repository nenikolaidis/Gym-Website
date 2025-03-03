<?php
    include "../classes/dbh.connection.php";
    include "../classes/login.classes.php";
    
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Create an instance of the Login class
        $loginInstance = new Login();
    
        // Call the public method loginUser(), which in turn calls the protected method getUser()
        $loginInstance->loginUser($username, $password);
    }
    ?>