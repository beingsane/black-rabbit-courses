<?php
include_once('../connect/helper.php');

// first is there
if(!isThereAnAdminUser()){
  // go to add new admin
	header('Location: /admin/new-admin-user.php');
  exit();
}

// are we not logged in?
if($_SESSION['loggedin'] !== 1){
  // go to admin login
	header('Location: /admin/login.php');
  exit();
}

// always check session on these restricted pages
checksession();

$title = 'Admin Dashboard';

include_once('../includes/head.php');
include_once('../includes/nav.php');
?>

<div class="container spacer-top">
  <div class="jumbotron">
    <h1 class="display-4">Welcome to Dashboard!</h1>
  </div>
</div>

<?php
include_once('../includes/foot.php');
?>
