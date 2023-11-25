<?php
ob_start();
session_start();

include_once "config.php";
include_once "db.php";
$author = "Anonymous" . rand();

$db = new Database();

if (!isset($_SESSION['email'])) {
  header('location: login.php');
  exit;
}

if (isset($_GET['logout'])) {
  session_destroy();
  header('location: login.php');
  exit;
}

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $query = "SELECT `username` FROM `users` WHERE `email` = '$email'";
  $result = $db->select($query);

  $name = $result->fetch_assoc()['username'];
  
$query = "SELECT * FROM `blogs`";
$blogs = $db->select($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  
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
              <a class="nav-link active" aria-current="page" href="">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blogPost.php">Post Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="myBlog.php">My Blogs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?logout=true">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  <section>
    
        <div class="col-md-12 py-2 bg-warning">
          <h1 class="text-center">Welcome, <?php echo $name; ?></h1>
        </div>
</section>
  <section>
    <div class="container w-100" style='background-image: url("assets/images/slide1.jpg"); height:40%;'>

    </div>
  </section>
  <section>
    <div class="container mt-4 flex-column">
      <?php while ($blog = $blogs->fetch_assoc()) { ?>
        <div class="col-md-10 m-auto card bg-info mb-5 p-0">
          <div class="card-header d-flex align-items-center justify-content-between flex-row">
            <p class="h3">
              <?php echo $blog['heading']; ?>
            </p>
            <p>Author: <?php echo $author ?></p>
            <?php
            $id = $blog['id'];
            ?>
          </div>
          <img src="<?php echo $blog['image']; ?>" alt="" width="100%">

          <p class='p-2'>
            <?php echo $blog['content']; ?>
          </p>


          <div class="dropdown bg-info col-md-12">
            <button class="btn text-end border w-100 dropdown-toggle" type="button" id="dropdownMenuButton1"
              data-bs-toggle="dropdown" aria-expanded="false">
              Comment
            </button>
            <div class="dropdown-menu bg-info w-100">
              <div class="col-md-11 card bg-info-subtle p-4 my-3 border-success m-auto">
                <h3 class="text-center">Comments</h3>
                <?php
                // Show all comments
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



                      <div class="dropdown-menu col-md-8">
                        <div class="col-md-11 m-auto">
                          <form action="index.php" method="POST">
                            <div class="input-group">
                              <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                              <input type="text" name="reply" class='form-control'>
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



              <div class="col-md-12">
                <form method="post" action="index.php">
                  <input type="hidden" name="id" value="<?php echo $blog['id']; ?>">
                  <div class="form-group">
                    <div class="input-group">
                      <input class="form-control" name="comment" rows="4" cols="50">
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
      <?php  } ?>

    </div>
  </section>

  <section>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</html>

<?php } ?>