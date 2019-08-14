<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$email = isset($_POST["loginName"]) ? $_POST["loginName"] : false;
	$pass = isset($_POST["loginPassw"]) ? $_POST["loginPassw"] : false;	
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Morne Roos">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form enctype='multipart/form-data' method='POST'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload'
									multiple='multiple' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input type='hidden' value='$email' name='email'/>
                                    <input type='hidden' value='$pass' name='pass'/>
								</div>
						  	</form>";
				$user_id = $row['user_id'];
                    
                    if(isset($_FILES['picToUpload']))
                    {
                        $uploadFile = $_FILES['picToUpload'];
                        $numFiles = count($uploadFile["name"]);
                        
                        $target_dir = 'gallery/';
                        
                        for($j = 0; $j < $numFiles; $j++)
                        {
                         
                            if(($uploadFile['type'][$j] == 'image/jpeg' || $uploadFile['type'][$j] == 'image/jpg') && ($uploadFile['size'][$j] <= 1048576))
                            {
                                //echo $user_id;
                                
                                $target_file = $target_dir.$uploadFile['name'][$j];
                                move_uploaded_file($uploadFile["tmp_name"][$j], $target_file);
                                $filename = $uploadFile['name'][$j];
                                $query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$user_id', '$filename');";
                                mysqli_query($mysqli, $query);
                            }else
                            {
                                echo    '<div class="alert alert-danger mt-3" role="alert">
                                          Image too large or is not in JPEG/JPG format!
                                       </div>';
                            }
                            
                        }
                    
                    }
                    
                    $query = "SELECT * FROM tbgallery WHERE user_id = '$user_id'";
                    $jmage_result = $mysqli->query($query);
                        
                        echo '<div class="row imageGallery">'; 
                                    while($jmgrow = mysqli_fetch_array($jmage_result))
                                    {
                                        echo '<div class="col-3" style="background-image: url(gallery/'.$jmgrow['filename'].')">
                                        </div><br/>';
                                    }
                        echo '</div>';
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>
