<?php
include_once('connect/helper.php');

// check if they are logged in first
if($_SESSION['loggedin'] == 1){
  endsession();
  exit();
}

// if not, why are they even here?
$_SESSION['msg'] = 'Why were you even trying to get here?';
$_SESSION['msg-type'] = 'dark';
header('Location: /index.php');
exit();
?>
