<?php
ob_start();
session_start();

include_once "../config.php";
include_once "../db.php";


$db = new Database();

if (!isset($_SESSION['email'])) {
  header('location: adminLogin.php');
  exit;
}


if (isset($_GET['logout'])) {
    session_destroy();
    header('location: adminLogin.php');
    exit;
  }
  

  ?>
<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <style>
    .reply .dropdown-toggle::after {
      display: none;
    }
    </style>
  <title>Blogs</title>
</head>
<nav class="navbar navbar-expand-lg py-4 bg-warning">
      <div class="container-fluid">
        <a class="navbar-brand fs-3" href="#">Blogs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?logout=true">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<h1 class='text-center'>Welcome Back to Admin Panel!</h1>
<section>
    <div class="container">
    <h3>All Users Data</h3>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th colspan="4" class="text-center">All Users</th>
            </tr>
        </thead>
        <tr>
            <th>#</th>
            <th>User Name</th>
            <th>Email</th>
            <th>No. of Blogs</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $result = $db->select($sql);
        if ($result) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                $sql1 = "SELECT * FROM blogs WHERE user_id = {$row['id']}";
                $result1 = $db->select($sql1);
                $noOfBlogs = $result1 ? $result1->num_rows : 0;
                echo "<tr>
                    <td>{$counter}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$noOfBlogs}</td>
                    </tr>";
                $counter++;
            }
        }
        ?>
    </table>
    </div>
    <div class="container">
    <h3>All Blogs Details</h3>
    <table class="table table-bordered">
    <thead class="table-light">
            <tr>
                <th colspan="4" class="text-center">All Blogs</th>
            </tr>
        </thead>
        <tr>
            <th>#</th>
            <th>Blog Title</th>
            <th>Author of Blog</th>
            <th>No of Comments</th>
        </tr>
        <?php
        $sql = "SELECT * FROM blogs";
        $result = $db->select($sql);
        if ($result) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                $sql1 = "SELECT * FROM comments WHERE blog_id = {$row['id']}";
                $result1 = $db->select($sql1);
                $sql2 = "SELECT * FROM users WHERE id = {$row['user_id']}";
                $result2 = $db->select($sql2);
                $author = $result2 ? $result2->fetch_assoc()['username'] : "No User";
                $noOfComments = $result1 ? $result1->num_rows : 0;
                echo "<tr>
                    <td>{$counter}</td>
                    <td>{$row['heading']}</td>
                    <td>{$author}</td>
                    <td>{$noOfComments}</td>
                    </tr>";
                $counter++;
            }
        }
        ?>
    </table>

    
    </div>
</section>
  </body>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('.dropdown-menu').click(function (e) {
      e.stopPropagation();
    });
  });
</script>

</html>