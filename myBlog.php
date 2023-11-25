<?php 
ob_start();
include "db.php";
include "config.php";

session_start();
$db = new Database();

if (isset($_SESSION['email'])) {
  $email=$_SESSION['email'];
  $query = "SELECT `id` FROM `users` WHERE `email` = '$email'";
  $result = $db->select($query);
  
  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $id = $row['id'];
  }
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

<body>
<nav class="navbar navbar-expand-lg py-4 bg-success">
      <div class="container-fluid">
        <a class="navbar-brand fs-3" href="#">Blogs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/blog-post/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?logout=true">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<section>
    <div class="col-md-12 bg-success text-center py-3">
        <h1 style="font-size:60px;">My Blogs</h1>
    </div>
</section>
<section>
    <div class="container mt-4 flex-column">
    <?php
$query = "SELECT * FROM `blogs` where `user_id` = '$id'";
$blogs = $db->select($query);
if(!$blogs){
  echo "<h1 class='text-center'>No blogs yet</h1>";
}
else{
while ($blog = $blogs->fetch_assoc()) {
// rest of your code
?>
        <div class="col-md-8 bg-info card mb-5 p-0">
          <div class="card-header bg-info d-flex justify-content-between flex-row">
            <p class="h3">
              <?php echo $blog['heading']; ?>
            </p>
            <?php
            $id = $blog['id'];
            ?>
          </div>
          <img class="col-md-11 m-auto mt-4" src="<?php echo $blog['image']; ?>" alt="" width="100%">

          <p class='p-2'>
            <?php echo $blog['content']; ?>
          </p>


          <div class="dropdown col-md-12">
            <button class="btn text-end border w-100 bg-info dropdown-toggle" type="button" id="dropdownMenuButton1"
              data-bs-toggle="dropdown" aria-expanded="false">
              Comment
            </button>
            <div class="dropdown-menu bg-info col-md-12">
              <div class="col-md-10 bg-info-subtle card p-4 my-3 border-success m-auto">
                <h3 class="text-center">Comments</h3>
                <?php
                // Show all comment0
                $query = "SELECT * FROM `comments` WHERE `blog_id` = '$id'";
                $comments = $db->select($query);
                if ($comments) {
                  while ($comment = $comments->fetch_assoc()) {
                    echo "<div> <p class='mb-0 border-bottom'>" . $comment['comment'] . "</p>";


                    ?>



                    <div class="reply dropdown d-flex flex-row-reverse col-md-12">
                      <button class="btn  p-0 mt-0 dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false"><img src="assets/images/reply-fill.svg"
                          alt=""></button>



                      <div class="dropdown-menu">
                        <div class="col-md-12">
                          <form action="index.php" method="POST">
                            <div class="input-group">
                              <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                              <input type="text" name="reply" class='form-control col-md-11'>
                              <button type="submit" name="reply_submit" class="btn btn-primary"><img
                                  src="assets/images/send.svg" alt=""></button>
                            </div>
                          </form>
                          <?php
                          if (isset($_POST['reply_submit'])) {
                            $reply = $_POST['reply'];
                            $comment_id = $_POST['comment_id'];
                            $query = "SELECT * FROM `replies` WHERE `comment_id` = '$comment_id' AND `reply` = '$reply'";
                            $existingReply = $db->select($query);
                            if ($existingReply) {
                              $error_message = "Duplicate reply";
                            } else {
                              $query = "INSERT INTO `replies` (`comment_id`, `reply`) VALUES ('$comment_id', '$reply')";
                              $db->insert($query);

                            }
                          }


                          ?>
                        </div>
                      </div>
                    </div>
                    <?php
                    // Show all replies
                    $comment_id = $comment['id'];
                    $query = "SELECT * FROM `replies` WHERE `comment_id` = '$comment_id'";
                    $replies = $db->select($query);
                    if ($replies) {
                      while ($reply = $replies->fetch_assoc()) {
                        echo "<p class='text-end'>" . $reply['reply'] . "</p>";
                      }
                    }

                    ?>
                    <?php
                    echo "</div>";
                  }
                } else {
                  echo "<p>No comments yet</p>";
                }


                ?>
              </div>



              <div class="col-md-12 m-auto">
                <form method="post" action="index.php">
                  <input type="hidden" name="id" value="<?php echo $blog['id']; ?>">
                  <div class="form-group">
                    <div class="input-group">
                      <input class="form-control col-md-8" name="comment" rows="4" cols="50">
                      <button type="submit" class="btn btn-primary" name="comment_submit"><img
                          src="assets/images/send.svg" alt=""></button>
                    </div>
                  </div>
                </form>
                <?php
                if (isset($_POST['comment_submit'])) {
                  $comment = $_POST['comment'];
                  if (empty($comment)) {
                    $error_message = "Comment field is empty";
                  }
                  $blog_id = $_POST['id'];

                  $query = "SELECT * FROM `comments` WHERE `blog_id` = '$blog_id' AND `comment` = '$comment'";
                  $existingComment = $db->select($query);

                  if (!$existingComment) {
                    $query = "INSERT INTO `comments` (`blog_id`, `comment`) VALUES ('$blog_id', '$comment')";
                    $db->insert($query);
                    echo "<script>window.location.reload();</script>"; // Reload the page after comment submission
                  } else {
                    $error_message = "Duplicate comment";
                  }
                }
                ?>
              </div>
            </div>

          </div>
        </div>
      <?php }} ?>

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