<?php

    //Connecting with the signup-contr class
    include "../classes/dbh.connection.php";
    include "../classes/signup.classes.php";
    

    //Getting the data from the signup form
    if (isset($_POST['submit'])) {
        // Retrieve user input from the form
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $country = $_POST['country'];
        if (isset($_POST['state']) && $_POST['state']!==''){
            $state = $_POST['state'];
        }else{
            $state= "default";
        }
        if (isset($_POST['city'])&& $_POST['city']!==''){
            $city = $_POST['city'];
        }else{
            $city = "default";
        }    
        $address = $_POST['address'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Create an instance of the Signup class
        $signup = new Signup();
    
        // Call the createUser method to insert the user into the database
        $signup->createUser($name, $surname, $country, $state, $city, $address, $email, $username, $password);
    } else {
        // Redirect to the sign-up page if the form is not submitted
        header("Location: ../main/success.php");
        exit();
    }
    ?>