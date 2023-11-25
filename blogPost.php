<?php 
ob_start();
session_start();

include_once "db.php";
include_once "config.php";

$db = new Database();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT `id` FROM `users` WHERE `email` = '$email'";
    $result = $db->select($query);
    
    $row = mysqli_fetch_array($result);
    $id = $row['id'];
    
    if(isset($_POST['submit'])){
        $user_id = $id;

        $title = $_POST['title'];
        $image = $_POST['image'];
        $description = $_POST['description'];
        $description = str_replace("'", "‘", $description);
        $description = str_replace('"', '“', $description);
        $description = str_replace('\'', '', $description);
        $sql = "INSERT INTO `blogs` (`user_id`, `heading`, `image`, `content`) VALUES ('$user_id', '$title', '$image', '$description')";
        $db->insert($sql);
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
  <div class="card col-md-9 m-auto mt-5">
        <div class="card-header">
            <h2>Blog Post</h2>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control mb-3" placeholder="Enter your title" required>
                <label for="image">Image</label>
                <input type="text" name="image" id="image" class="form-control mb-3" placeholder="Paste the link of the image" required>
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control mb-3" placeholder="Enter your description" required></textarea>
                <button type="submit" name="submit" class="btn btn-primary">Post</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>