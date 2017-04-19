<?php
session_start();
if (isset($_SESSION['userSession'])!="") {
 header("Location: home.php");
}
require_once 'dbconnect.php';

if(isset($_POST['btn-signup'])) {
 
 $uname = strip_tags($_POST['username']);
 $email = strip_tags($_POST['email']);
 $upass = strip_tags($_POST['password']);
 
 $uname = $DBcon->real_escape_string($uname);
 $email = $DBcon->real_escape_string($email);
 $upass = $DBcon->real_escape_string($upass);
 
 $hashed_password = password_hash($upass, PASSWORD_DEFAULT);
 
 $check_email = $DBcon->query("SELECT email FROM users WHERE email='$email'");
 $count=$check_email->num_rows;
 
 if ($count==0) {
  
  $query = "INSERT INTO users(username,email,password) VALUES('$uname','$email','$hashed_password')";

  if ($DBcon->query($query)) {
   $msg = "successfully registered !";
  }else {
   $msg = "error while registering !";
  }
  
 } else {
  
  
  $msg = "sorry email already taken !";
   
 }
 
 $DBcon->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="icon" type="image/ico" href="favicon.ico">
<title>Registration - OSS Sample</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="assets/style.css" type="text/css"  />
</head>
<body>

<div class="container">
  <div class="signUpBlock">      
    <form method="post">  
        <h2 class="signInTitle">Sign Up</h2>
        <?php
            if (isset($msg)) {echo $msg;}
        ?>   
        <div class="signUpInput">
        <input type="text" placeholder="Username" name="username" required/></br>
        <input type="email" placeholder="Email address" name="email" required/></br>
        <input type="password" placeholder="Password" name="password" required/></br>
        </div>

        
        <div class="signInSubmit">
            <button type="submit" name="btn-signup">
            &nbsp; Create Account
            </button></br> 
            <a class="registerLink" href="index.php">Log In Here</a>
        </div>  
      </form>
    </div>
</div>
</body>
</html>