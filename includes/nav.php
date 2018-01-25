<?php
  include_once('../connect/helper.php');

  if(!$isAdmin) {
    $isAdmin = false;
  }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/">
    <?php include('rabbit.php'); ?>
    Courses
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/access">Access</a>
      </li>
    </ul>

    <?php
    // are they logged in?
    if($_SESSION['loggedin'] === 1){
      // are they an admin?
      if($_SESSION['is_admin'] === 1) {
        ?>
        <a class="btn btn-outline-primary mr-10" href="/admin">Admin Dashboard</a>
        <?php
      }
      ?>
      <a class="btn btn-outline-dark" href="/logout">
        Logout
      </a>
      <?php
    } else {
      ?>
      <a class="btn btn-outline-secondary" href="/admin">
        Admin Access
      </a>
      <?php
    }
    ?>
  </div>
</nav>

<div id="message-area">
  <?php
  // do we have messages to show?
  if(!empty($_SESSION['msg'])) {
    include('alert.php');
    unset($_SESSION['msg']);
    unset($_SESSION['msg-type']);
  }
  ?>
</div><!-- /#message-area -->
