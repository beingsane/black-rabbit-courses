<?php
include_once('../connect/helper.php');

// if post
if(isset($_POST) && !empty($_POST)) {
  addAdminUser($_POST);
  exit();
}

// first is there
if(isThereAnAdminUser()){
  // go to add new admin
	header('Location: /admin/');
  exit();
}

$title = 'Create Admin';

include_once('../includes/head.php');

$isAdmin = true;
$noAdmin = true;
include_once('../includes/nav.php');
?>

<div class="container spacer-top">
  <div class="jumbotron">
    <h1>Add Admin User</h1>
    <form action="" method="post">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="name" placeholder="Name">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="username" placeholder="Username">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="email" placeholder="E-mail">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder="Password">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Add User</button>
    </form>
  </div>
</div>

<?php
include_once('../includes/foot.php');
?>
