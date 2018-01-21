<?php
$title = 'Welcome to Black Rabbit Courses';
include('../includes/head.php');

$isAdmin = true;
include('../includes/nav.php');
?>
<div class="container spacer-top">
  <div class="jumbotron">
    <h1 class="display-4">Welcome to Black Rabbit Courses!</h1>
    <p class="lead">A place to host and take courses.</p>
    <hr class="my-4">
    <p class="lead">
      <a class="btn btn-primary btn-lg" href="access" role="button">Enter Access Token</a>
    </p>
  </div>
</div>
<?php
include('../includes/foot.php');
?>
