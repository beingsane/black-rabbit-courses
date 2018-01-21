<?php
  if(!$isAdmin) {
    $isAdmin = false;
  }

  if(!$noAdmin) {
    $noAdmin = false;
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
    <?php
    if(!$isAdmin) {
      ?>
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/access">Access</a>
        </li>
      </ul>
      <a class="btn btn-outline-secondary" href="/admin">
        Admin Access
      </a>
      <?php
    } elseif(!$noAdmin) {
      ?>
      <a class="btn btn-outline-secondary" href="/logout">
        Logout
      </a>
      <?php
    }
    ?>
  </div>
</nav>
