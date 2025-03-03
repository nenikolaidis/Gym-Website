<?php
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

//Check connection
if ($conn->connect_error) {
    die("". $conn->connect_error);
}
echo"Connected succesfully";

//Create database
$sql = "CREATE DATABASE project_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created succesfully";
} else {
    echo"Error creating database". $conn->error;
}

//sql to create table
$sql = "CREATE TABLE Gymnasts (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    'Password' VARCHAR(80) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Gymnasts created succesfully";
} else {
    echo"Error creating table". $conn->error;
}

$sql = "CREATE TABLE Programs_Types(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Types VARCHAR(30)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Programs_Types created succesfully";
} else {
    echo"Error creating table". $conn->error;
}

//Using foreign keys gto connect with the gymnasts table
$sql = "CREATE TABLE Group_Programs(
    Program_date DATE,
    Program_time TIME,
    Program_gymnasts VARCHAR(30),
    Capacity INT(6),
    Foreign Key(gymnasts_id) REFERENCES Gymnasts(id)
    ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Group_Programs created succesfully";
} else {
    echo"Error creating table". $conn->error;
}
/*
PENDING USERS TABLE

CREATE TABLE pending_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    country VARCHAR(30) NOT NULL,
    city VARCHAR(30) NOT NULL,
    address VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    username VARCHAR(30) NOT NULL,
    pass_word VARCHAR(80) NOT NULL
)

ACCEPTED USERS TABLE

CREATE TABLE accepted_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    country VARCHAR(30) NOT NULL,
    city VARCHAR(30) NOT NULL,
    address VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    username VARCHAR(30) NOT NULL,
    pass_word VARCHAR(80) NOT NULL,
    roles VARCHAR(80) NOT NULL
)
*/ 
?>