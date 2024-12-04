<?php
session_start();
session_destroy();
header('Location: ../indexmain.php');
exit();
?>
