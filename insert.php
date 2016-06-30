<?php
$servername = "localhost";
$username = "testuser";
$password = "daj123";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
die("Connection failed:" . $conn->connect_error);
}
else{
$noiseDB = $_GET['noiseDB'];
$sqlQuery = "INSERT INTO `noise`(`noiseDB`) VALUES ($noiseDB)";
if($conn->query($sqlQuery) === FALSE){
die("Cannot add to database" . $conn->error);
}
else
echo "Succesfuly added!";
}
$conn->close();
?>

