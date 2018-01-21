<?php
include_once('../connect/helper.php');

// first is there
if(!isThereAnAdminUser()){
  // go to add new admin
	header('Location: /admin/new-admin-user.php');
}

$title = 'Welcome to Black Rabbit Courses';

include_once('../includes/head.php');

$isAdmin = true;
include_once('../includes/nav.php');
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
