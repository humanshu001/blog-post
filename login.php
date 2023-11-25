<?php

include_once "db.php";
include_once "config.php";

$db = new Database();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $db->select($query);
    if ($result) {
        session_start();
        $_SESSION['email'] = $email;
        header('location:index.php');
    } else {
        echo "<script>alert('Email or password is incorrect.')</script>";
    }
}
?>




<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="card col-md-6 m-auto mt-5">
        <div class="card-header">
            <h2>Log in Here</h2>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Enter your email"
                    required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control mb-3"
                    placeholder="Enter your password" required>
                <p>Don't have Account?<a href="register.php">Sign up</a></p>
                <button type="submit" name="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>