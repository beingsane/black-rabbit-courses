<?php
include_once('../connect/helper.php');

// if post
if(isset($_POST) && !empty($_POST)) {
  // addAdminUser($_POST);
  exit();
}

$title = 'Admin Login';

include_once('../includes/head.php');

$isAdmin = true;
$noAdmin = true;
include_once('../includes/nav.php');
?>

<div class="container spacer-top">
  <div class="jumbotron">
    <h1>Admin Login</h1>
    <form action="" method="post">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="username" placeholder="Username">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder="Password">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

<?php
include_once('../includes/foot.php');
?>
