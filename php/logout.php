<?php
session_start();
session_unset(); 
session_destroy(); 

header("Location: /GitHub/SA_Senai/login.php"); 
exit(); 
?>