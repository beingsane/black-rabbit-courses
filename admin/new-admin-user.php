<?php
include_once('../connect/helper.php');

// first is there
if(isThereAnAdminUser()){
  // go to add new admin
	header('Location: /admin/');
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
    <form>
      <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>
      <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
      </div>
      <div class="form-check">
      <input type="checkbox" class="form-check-input" id="exampleCheck1">
      <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

<?php
include_once('../includes/foot.php');
?>
