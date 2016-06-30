<?php
$servername = "localhost";
$username = "testuser";
$password = "daj123";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);
		if($conn->connect_error){
			//die("Connection failed:" . $conn->connect_error);
			echo json_encode('ERROR');
		}
		$result = mysqli_query($conn,"SELECT * FROM noise");
		
		$to_encode = array();
		while($row = mysqli_fetch_array($result)){		
			$to_encode[] = $row['noiseDB'];
		}
		
		mysqli_close($conn);
		echo json_encode($to_encode);
		//echo $_GET['callback'] . '('.json_encode($to_encode).')';
		 
?>