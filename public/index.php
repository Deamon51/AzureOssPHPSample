<?php
session_start();
require_once 'dbconnect.php';

if (isset($_SESSION['userSession'])!="") {
 header("Location: home.php");
 exit;
}

if (isset($_POST['btn-login'])) {
 
 $email = strip_tags($_POST['email']);
 $password = strip_tags($_POST['password']);
 
 $email = $DBcon->real_escape_string($email);
 $password = $DBcon->real_escape_string($password);
 
 $query = $DBcon->query("SELECT username, email, password FROM users WHERE email='$email'");
 $row=$query->fetch_array();
 
 $count = $query->num_rows;
 
 if (password_verify($password, $row['password']) && $count==1) {
  $_SESSION['userSession'] = $row['username'];
  header("Location: home.php");
 } else {
  $msg = "Invalid email or Password !";
 }
 $DBcon->close();
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" type="image/ico" href="favicon.ico">
<title>Login - OSS Sample</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="assets/style.css" type="text/css"  />
</head>
<body>

<div class="container">
  <div class="signInBlock">  
      <form method="post" >
        <h2 class="signInTitle">Sign In.</h2>  
          <?php
          if(isset($msg)){echo $msg;}
          ?>
        <div class="signInInput"> 
          <input type="email" placeholder="Email address" name="email" required/>
          <input type="password" placeholder="Password" name="password" required/>
        </div>
          
        <div class="signInSubmit">
              <button type="submit" name="btn-login" class="btn-submit">
              &nbsp; Sign In
              </button> 
              </br>
              <a class="registerLink" href="register.php">Sign UP Here</a>      
          </div>  
        </form>

  </div>
</div>
</body>
</html>