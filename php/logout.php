<?php
session_start();
session_unset(); 
session_destroy(); 

header("Location: /002 - Turma_2o_Semestre_2024/SA_Senai/index.php"); 
exit(); 
?>