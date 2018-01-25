<div class="container">
  <div class="alert alert-<?php echo $_SESSION['msg-type']; ?> alert-dismissible fade show" role="alert">
  	<?php echo $_SESSION['msg']; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</div>
