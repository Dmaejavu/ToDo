<?php

$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "todo"; 

$connection = new mysqli($serverName, $username, $password, $dbName);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
} else {
}

?>