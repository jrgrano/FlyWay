<?php

session_unset();     
session_destroy();

header('Location: index.php');

setcookie(session_name(), '', time() - 3600, '/');
 ?>