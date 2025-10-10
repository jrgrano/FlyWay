<?php

session_unset();     
session_destroy();

header('Location: pag_login_cadastro.php');

setcookie(session_name(), '', time() - 3600, '/');
 ?>