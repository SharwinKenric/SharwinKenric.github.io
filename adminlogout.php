<?php
session_start();
unset($_SESSION['admin_username']);
header("Location: admin.php");
exit();
?>