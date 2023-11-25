<?php
include_once "db.php";
include_once "config.php";

$db = new Database();

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password == $confirm_password){
        $sql = "INSERT INTO `users` (`username`, `email`, `password`) VALUES ('$username', '$email', '$password')";
        $result = $db->insert($sql);
        if ($result) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Registration failed";
    }
    }else{
        echo "Password and Confirm Password don't match";
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
  <div class="card col-md-6 m-auto mt-5">
        <div class="card-header">
            <h2>Register Here</h2>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <label for="username">Name</label>
                <input type="text" name="username" id="username" class="form-control mb-3" placeholder="Enter your name" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Enter your email" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control mb-3" placeholder="Enter your password" required>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control mb-3" placeholder="Confirm your password" required>
                <p>Already have Account?<a href="login.php">Sign in</a></p>
                <button type="submit" name="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>