<?php
$database_connect = mysqli_connect("localhost", "root", "", "boardman");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die();
}