<?php
session_start();
session_unset();
session_destroy();
header("Location: /assets/php/login.php");
exit();
?>